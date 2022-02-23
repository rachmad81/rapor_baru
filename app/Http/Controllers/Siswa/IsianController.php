<?php

namespace App\Http\Controllers\Siswa;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use App\Http\Controllers\Controller;

use DB,Session;

class IsianController extends Controller
{
	function main(){
		$nik = Session::get('nik');
		$npsn = Session::get('npsn');
		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');
		
		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("nik='$nik' AND npsn='$npsn' and status_siswa='Aktif'")->first();
		$kelas = (!empty($siswa)) ? $siswa->kelas : '';
		$rombel = (!empty($siswa)) ? $siswa->rombel : '';

		// $mengajar = DB::connection($conn)->table($nama_schema.'.nilai as n')
		// ->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		// ->selectRaw("*,m.nama as nama_mapel")
		// ->whereRaw("id_siswa='$siswa->id_siswa' and m.is_aktif=true")->orderBy('m.kategori','ASC','m.urutan','ASC')->get();

		$mengajar = DB::connection($conn)->table($nama_schema.'.mengajar as m')->join('public.rapor_mapel as rm','rm.mapel_id','m.mapel_id')
		->selectRaw("*,rm.nama as nama_mapel")
		->whereRaw("kelas='$siswa->kelas' AND rombel='$siswa->rombel' AND npsn='$siswa->npsn'")->get();

		$data = [
			'main_menu'=>'nilai',
			'sub_menu'=>'',
			'mapel'=>$mengajar,
		];

		return view('siswa.isian.index',$data);
	}

	function mengajar(Request $request){
		$jenjang = Session::get('jenjang');
		if($jenjang=='SD'){
			$return = $this->data_sd($request);
		}else{
			$return = $this->data_smp($request);
		}

		return $return;
	}

	function data_sd(Request $request){
		$nik = Session::get('nik');
		$npsn = Session::get('npsn');
		$id_mapel = $request->id_mengajar;
		Session::put('id_mapel',$id_mapel);
		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("nik='$nik' AND npsn='$npsn' and status_siswa='Aktif'")->first();
		$mengajar = DB::connection($conn)->table($nama_schema.'.nilai as n')
		->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		->selectRaw("*,m.nama as nama_mapel")
		->whereRaw("id_siswa='$siswa->id_siswa' AND m.mapel_id='$id_mapel'")->first();

		if(empty($mengajar)){
			$data_insert = [
				'mapel_id'=>$id_mapel,
				'kelas'=>$siswa->kelas,
				'rombel'=>$siswa->rombel,
				'npsn'=>$npsn,
				'id_siswa'=>$siswa->id_siswa,
			];
			$simpan = DB::connection($conn)->table($nama_schema.'.nilai')->insert($data_insert);
		}

		$mengajar = DB::connection($conn)->table($nama_schema.'.nilai as n')
		->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		->selectRaw("*,m.nama as nama_mapel")
		->whereRaw("id_siswa='$siswa->id_siswa' AND m.mapel_id='$id_mapel'")->first();

		$kd3 = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("mapel_id='$id_mapel' AND no_ki='3' AND kelas='$mengajar->kelas'")->orderBy('kd_id','ASC')->get();
		$kd4 = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("mapel_id='$id_mapel' AND no_ki='4' AND kelas='$mengajar->kelas'")->orderBy('kd_id','ASC')->get();

		$data = [
			'main_menu'=>'nilai',
			'sub_menu'=>'',
			'mengajar'=>$mengajar,
			'kd3'=>$kd3,
			'kd4'=>$kd4,
			'semester'=>'I (Satu)',
		];

		return view('siswa.isian.form',$data);
	}

	function simpansd(Request $request){
		$namenya = $request->namenya;
		$nilainya = $request->nilai;
		$npsn = Session::get('npsn');
		$id_mapel = Session::get('id_mapel');

		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND nik='".Session::get('nik')."' AND status_siswa='Aktif' AND alumni is not true")->orderBy('nama','ASC')->first();		
		$nilai = DB::connection($conn)->table($nama_schema.'.nilai')->whereRaw("id_siswa='$siswa->id_siswa' AND npsn='$npsn' AND mapel_id='$id_mapel'")->first();
		
		$data_insert = [
			'mapel_id'=>$id_mapel,
			'npsn'=>$npsn,
			'id_siswa'=>$siswa->id_siswa,
		];

		for ($i=0; $i < count($nilainya); $i++) { 
			$data_insert = array_merge($data_insert,[
				$namenya.'_'.($i+1) => $nilainya[$i],
			]);
		}

		if(!empty($nilai)){
			$simpan = DB::connection($conn)->table($nama_schema.'.nilai')->whereRaw("id_siswa='$siswa->id_siswa' AND npsn='$npsn' AND mapel_id='$id_mapel'")->update($data_insert);
		}else{
			$data_insert = array_merge($data_insert,[
				'kelas'=>$siswa->kelas,
				'rombel'=>$siswa->rombel,
			]);
			$simpan = DB::connection($conn)->table($nama_schema.'.nilai')->insert($data_insert);
		}

		if($simpan){
			$return = ['code'=>'200','message'=>'Berhasil disimpan','title'=>'Success','type'=>'success'];
		}else{
			$return = ['code'=>'250','message'=>'Gagal disimpan','title'=>'Whooops','type'=>'error'];
		}

		return $return;
	}

	// ==========================================
	
	// KHUSUS SMP (SEKOLAH MENENGAH PERTAMA)

	// ==========================================
	function data_smp(Request $request){
		$nik = Session::get('nik');
		$npsn = Session::get('npsn');
		$id_mapel = $request->id_mengajar;
		Session::put('id_mapel',$id_mapel);
		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("nik='$nik' AND npsn='$npsn' and status_siswa='Aktif'")->first();
		$mengajar = DB::connection($conn)->table($nama_schema.'.nilai as n')
		->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		->selectRaw("*,m.nama as nama_mapel")
		->whereRaw("id_siswa='$siswa->id_siswa' AND m.mapel_id='$id_mapel'")->first();

		if(empty($mengajar)){
			$data_insert = [
				'mapel_id'=>$id_mapel,
				'kelas'=>$siswa->kelas,
				'rombel'=>$siswa->rombel,
				'npsn'=>$npsn,
				'id_siswa'=>$siswa->id_siswa,
			];
			$simpan = DB::connection($conn)->table($nama_schema.'.nilai')->insert($data_insert);
		}

		$mengajar = DB::connection($conn)->table($nama_schema.'.nilai as n')
		->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		->selectRaw("*,m.nama as nama_mapel")
		->whereRaw("id_siswa='$siswa->id_siswa' AND m.mapel_id='$id_mapel'")->first();

		$kd3 = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("mapel_id='$mengajar->mapel_id' AND no_ki='3'")->orderBy('kd_id','ASC')->get();
		$kd4 = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("mapel_id='$mengajar->mapel_id' AND no_ki='4'")->orderBy('kd_id','ASC')->get();

		$data = [
			'main_menu'=>'nilai',
			'sub_menu'=>'',
			'mengajar'=>$mengajar,
			'kd3'=>$kd3,
			'kd4'=>$kd4,
			'semester'=>'I (Satu)',
		];

		return view('siswa.isian.smp.form',$data);
	}

	function get_pages(Request $request){
		$i = $request->i;
		$content = 'Pilih Tab';

		switch ($i) {
			case '1':
			$content = view('siswa.isian.smp.pages.satu')->render();
			break;
			case '2':
			$content = view('siswa.isian.smp.pages.dua')->render();
			break;
			case '3':
			$content = view('siswa.isian.smp.pages.tiga')->render();
			break;
			case '4':
			$content = view('siswa.isian.smp.pages.empat')->render();
			break;
			
			default:
			$content = 'Pilih Tab';
			break;
		}

		return ['content'=>$content];
	}

	// PAGE 1
	function pages1(Request $request){
		$i = $request->id;
		$content = 'Pilih Tab';

		switch ($i) {
			case '1':
			$content = $this->show11();
			break;
			case '2':
			$content = $this->show12();
			break;
			case '3':
			$content = $this->show13();
			break;

			default:
			$content = 'Pilih Tab';
			break;
		}

		return ['content'=>$content];
	}

	function show11(){
		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND nik='".Session::get('nik')."' AND status_siswa='Aktif' AND alumni is not true")->orderBy('nama','ASC')->first();

		$id_mapel = Session::get('id_mapel');
		$mengajar = DB::connection($conn)->table($nama_schema.'.nilai as n')
		->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		->selectRaw("*,m.nama as nama_mapel")
		->whereRaw("id_siswa='$siswa->id_siswa' AND m.mapel_id='$id_mapel'")->first();
		$kelas = $mengajar->kelas;
		$rombel = $mengajar->rombel;

		$kd = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("mapel_id='$mengajar->mapel_id' and kelas='$kelas' AND no_ki='3'")->orderBy('kd_id','ASC')->get();

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
			'mengajar'=>$mengajar,
		];

		$content = view('siswa.isian.smp.pages.satu.satu',$data)->render();

		return $content;
	}

	function simpan11(Request $request){
		$uas = $request->uas;
		$uts = $request->uts;
		$npsn = Session::get('npsn');
		$id_mapel = Session::get('id_mapel');

		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND nik='".Session::get('nik')."' AND status_siswa='Aktif' AND alumni is not true")->orderBy('nama','ASC')->first();		
		$nilai = DB::connection($conn)->table($nama_schema.'.nilai')->whereRaw("id_siswa='$siswa->id_siswa' AND npsn='$npsn' AND mapel_id='$id_mapel'")->first();
		
		$data_insert = [
			'uts'=>$uts,
			'uas'=>$uas,
			'mapel_id'=>$id_mapel,
			'npsn'=>$npsn,
			'id_siswa'=>$siswa->id_siswa,
		];

		if(!empty($nilai)){
			$simpan = DB::connection($conn)->table($nama_schema.'.nilai')->whereRaw("id_siswa='$siswa->id_siswa' AND npsn='$npsn' AND mapel_id='$id_mapel'")->update($data_insert);
		}else{
			$data_insert = array_merge($data_insert,[
				'kelas'=>$siswa->kelas,
				'rombel'=>$siswa->rombel,
			]);
			$simpan = DB::connection($conn)->table($nama_schema.'.nilai')->insert($data_insert);
		}

		if($simpan){
			$return = ['code'=>'200','message'=>'Berhasil disimpan','title'=>'Success','type'=>'success'];
		}else{
			$return = ['code'=>'250','message'=>'Gagal disimpan','title'=>'Whooops','type'=>'error'];
		}

		return $return;
	}

	function show12(){
		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND nik='".Session::get('nik')."' AND status_siswa='Aktif' AND alumni is not true")->orderBy('nama','ASC')->first();

		$id_mapel = Session::get('id_mapel');
		$mengajar = DB::connection($conn)->table($nama_schema.'.nilai as n')
		->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		->selectRaw("*,m.nama as nama_mapel")
		->whereRaw("id_siswa='$siswa->id_siswa' AND m.mapel_id='$id_mapel'")->first();
		$kelas = $mengajar->kelas;
		$rombel = $mengajar->rombel;

		$kd = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("mapel_id='$mengajar->mapel_id' and kelas='$kelas' AND no_ki='3'")->orderBy('kd_id','ASC')->get();

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
			'mengajar'=>$mengajar,
		];

		$content = view('siswa.isian.smp.pages.satu.dua',$data)->render();

		return $content;
	}

	function show13(){
		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND nik='".Session::get('nik')."' AND status_siswa='Aktif' AND alumni is not true")->orderBy('nama','ASC')->first();

		$id_mapel = Session::get('id_mapel');
		$mengajar = DB::connection($conn)->table($nama_schema.'.nilai as n')
		->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		->selectRaw("*,m.nama as nama_mapel")
		->whereRaw("id_siswa='$siswa->id_siswa' AND m.mapel_id='$id_mapel'")->first();
		$kelas = $mengajar->kelas;
		$rombel = $mengajar->rombel;

		$kd = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("mapel_id='$mengajar->mapel_id' and kelas='$kelas' AND no_ki='3'")->orderBy('kd_id','ASC')->get();

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
			'mengajar'=>$mengajar,
		];

		$content = view('siswa.isian.smp.pages.satu.tiga',$data)->render();

		return $content;
	}

	// PAGE 2
	function pages2(Request $request){
		$i = $request->id;
		$content = 'Pilih Tab';

		switch ($i) {
			case '1':
			$content = $this->show21();
			break;
			case '2':
			$content = $this->show22();;
			break;
			case '3':
			$content = $this->show23();;
			break;
			case '4':
			$content = $this->show24();;
			break;
			
			default:
			$content = 'Pilih Tab';
			break;
		}

		return ['content'=>$content];
	}

	function show21(){
		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND nik='".Session::get('nik')."' AND status_siswa='Aktif' AND alumni is not true")->orderBy('nama','ASC')->first();

		$id_mapel = Session::get('id_mapel');
		$mengajar = DB::connection($conn)->table($nama_schema.'.nilai as n')
		->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		->selectRaw("*,m.nama as nama_mapel")
		->whereRaw("id_siswa='$siswa->id_siswa' AND m.mapel_id='$id_mapel'")->first();
		$kelas = $mengajar->kelas;
		$rombel = $mengajar->rombel;

		$kd = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("mapel_id='$mengajar->mapel_id' and kelas='$kelas' AND no_ki='4'")->orderBy('kd_id','ASC')->get();

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
			'mengajar'=>$mengajar,
		];

		$content = view('siswa.isian.smp.pages.dua.satu',$data)->render();

		return $content;
	}

	function show22(){
		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND nik='".Session::get('nik')."' AND status_siswa='Aktif' AND alumni is not true")->orderBy('nama','ASC')->first();

		$id_mapel = Session::get('id_mapel');
		$mengajar = DB::connection($conn)->table($nama_schema.'.nilai as n')
		->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		->selectRaw("*,m.nama as nama_mapel")
		->whereRaw("id_siswa='$siswa->id_siswa' AND m.mapel_id='$id_mapel'")->first();
		$kelas = $mengajar->kelas;
		$rombel = $mengajar->rombel;

		$kd = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("mapel_id='$mengajar->mapel_id' and kelas='$kelas' AND no_ki='4'")->orderBy('kd_id','ASC')->get();

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
			'mengajar'=>$mengajar,
		];

		$content = view('siswa.isian.smp.pages.dua.dua',$data)->render();

		return $content;
	}

	function show23(){
		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND nik='".Session::get('nik')."' AND status_siswa='Aktif' AND alumni is not true")->orderBy('nama','ASC')->first();

		$id_mapel = Session::get('id_mapel');
		$mengajar = DB::connection($conn)->table($nama_schema.'.nilai as n')
		->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		->selectRaw("*,m.nama as nama_mapel")
		->whereRaw("id_siswa='$siswa->id_siswa' AND m.mapel_id='$id_mapel'")->first();
		$kelas = $mengajar->kelas;
		$rombel = $mengajar->rombel;

		$kd = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("mapel_id='$mengajar->mapel_id' and kelas='$kelas' AND no_ki='4'")->orderBy('kd_id','ASC')->get();

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
			'mengajar'=>$mengajar,
		];

		$content = view('siswa.isian.smp.pages.dua.tiga',$data)->render();

		return $content;
	}

	function show24(){
		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND nik='".Session::get('nik')."' AND status_siswa='Aktif' AND alumni is not true")->orderBy('nama','ASC')->first();

		$id_mapel = Session::get('id_mapel');
		$mengajar = DB::connection($conn)->table($nama_schema.'.nilai as n')
		->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		->selectRaw("*,m.nama as nama_mapel")
		->whereRaw("id_siswa='$siswa->id_siswa' AND m.mapel_id='$id_mapel'")->first();
		$kelas = $mengajar->kelas;
		$rombel = $mengajar->rombel;

		$kd = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("mapel_id='$mengajar->mapel_id' and kelas='$kelas' AND no_ki='4'")->orderBy('kd_id','ASC')->get();

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
			'mengajar'=>$mengajar,
		];

		$content = view('siswa.isian.smp.pages.dua.empat',$data)->render();

		return $content;
	}

	function simpankd(Request $request){
		$namenya = $request->namenya;
		$nilainya = $request->nilai;
		$npsn = Session::get('npsn');
		$id_mapel = Session::get('id_mapel');

		$jenjang = Session::get('jenjang');
		$nama_schema = ($jenjang=='SD') ? env('CURRENT_DB_SD','production') : env('CURRENT_DB_SMP','production');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND nik='".Session::get('nik')."' AND status_siswa='Aktif' AND alumni is not true")->orderBy('nama','ASC')->first();		
		$nilai = DB::connection($conn)->table($nama_schema.'.nilai')->whereRaw("id_siswa='$siswa->id_siswa' AND npsn='$npsn' AND mapel_id='$id_mapel'")->first();
		
		$data_insert = [
			'mapel_id'=>$id_mapel,
			'npsn'=>$npsn,
			'id_siswa'=>$siswa->id_siswa,
		];

		for ($i=0; $i < count($nilainya); $i++) { 
			$data_insert = array_merge($data_insert,[
				$namenya.'_'.($i+1) => $nilainya[$i],
			]);
		}

		if(!empty($nilai)){
			$simpan = DB::connection($conn)->table($nama_schema.'.nilai')->whereRaw("id_siswa='$siswa->id_siswa' AND npsn='$npsn' AND mapel_id='$id_mapel'")->update($data_insert);
		}else{
			$data_insert = array_merge($data_insert,[
				'kelas'=>$siswa->kelas,
				'rombel'=>$siswa->rombel,
			]);
			$simpan = DB::connection($conn)->table($nama_schema.'.nilai')->insert($data_insert);
		}

		if($simpan){
			$return = ['code'=>'200','message'=>'Berhasil disimpan','title'=>'Success','type'=>'success'];
		}else{
			$return = ['code'=>'250','message'=>'Gagal disimpan','title'=>'Whooops','type'=>'error'];
		}

		return $return;
	}
}
