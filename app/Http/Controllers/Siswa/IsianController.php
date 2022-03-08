<?php

namespace App\Http\Controllers\Siswa;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use App\Http\Controllers\Controller;

use DB,Session;

class IsianController extends Controller
{
	protected $schema;

	public function __construct() 
	{
		$this->schema = env('CURRENT_SCHEMA','production');
	}

	function main(){
		$nik = Session::get('nik');
		$npsn = Session::get('npsn');
		$jenjang = Session::get('jenjang');
		
		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("nik='$nik' AND npsn='$npsn' and status_siswa='Aktif'")->first();
		$kelas = (!empty($siswa)) ? $siswa->kelas : '';
		$rombel = (!empty($siswa)) ? $siswa->rombel : '';

		$rombel = DB::connection($conn)->table('public.anggota_rombel as ar')->join('public.rombongan_belajar as rb','rb.id_rombongan_belajar','ar.rombongan_belajar_id')
		->whereRaw("ar.siswa_id='$siswa->id_siswa' AND rb.kelas='$kelas' AND rombel='$rombel' AND npsn='$npsn'")->first();

		$id_rombel = '';
		$id_anggota_rombel = '';
		$semester = '';
		if(!empty($rombel)){
			$id_rombel = $rombel->id_rombongan_belajar;
			$id_anggota_rombel = $rombel->id_anggota_rombel;
			$semester = ($rombel->semester==1) ? 'I Ganjil' : 'II Genap';
		}
		Session::put('id_rombel',$id_rombel);
		Session::put('id_anggota_rombel',$id_anggota_rombel);
		Session::put('kelas',$kelas);
		Session::put('rombel',$rombel);
		Session::put('semester',$semester);

		// $mengajar = DB::connection($conn)->table($this->schema.'.nilai as n')
		// ->join('public.rapor_mapel as m','n.mapel_id','m.mapel_id')
		// ->selectRaw("*,m.nama as nama_mapel")
		// ->whereRaw("id_siswa='$siswa->id_siswa' and m.is_aktif=true")->orderBy('m.kategori','ASC','m.urutan','ASC')->get();

		$mengajar = DB::connection($conn)->table($this->schema.'.mengajar as m')->join('public.rapor_mapel as rm','rm.mapel_id','m.mapel_id')
		->selectRaw("*,rm.nama as nama_mapel")
		->whereRaw("rombel_id='$id_rombel'")->get();

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
		$id_rombel = Session::get('id_rombel');
		$jenjang = Session::get('jenjang');
		$id_anggota_rombel = Session::get('id_anggota_rombel');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$kelas = Session::get('kelas');

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("nik='$nik' AND npsn='$npsn' and status_siswa='Aktif'")->first();

		$mengajar = DB::connection($conn)->table($this->schema.'.mengajar as m')->join('public.rapor_mapel as rm','rm.mapel_id','m.mapel_id')
		->selectRaw("rm.nama as nama_mapel")
		->whereRaw("rm.mapel_id='$id_mapel'")->first();

		$nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as nm')->whereRaw("mapel_id='$id_mapel' AND anggota_rombel_id='$id_anggota_rombel'")->first();
		if(empty($nilai)){
			$insert = [
				'mapel_id'=>$id_mapel,
				'anggota_rombel_id'=>$id_anggota_rombel,
			];

			$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->insert($insert);
		}

		$kd3 = DB::connection($conn)->table($this->schema.'.kd')
		->whereRaw("mapel_id='$id_mapel' AND kelas='$kelas' AND no_ki='3'")->orderBy('id_kd','ASC')->get();

		$kd4 = DB::connection($conn)->table($this->schema.'.kd')
		->whereRaw("mapel_id='$id_mapel' AND kelas='$kelas' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$data = [
			'main_menu'=>'nilai',
			'sub_menu'=>'',
			'mengajar'=>$mengajar,
			'nilai_mapel'=>$nilai,
			'semester'=>Session::get('semester'),
			'kd3'=>$kd3,
			'kd4'=>$kd4,
			'conn'=>$conn,
			'schema'=>$this->schema,
		];

		return view('siswa.isian.form',$data);
	}
	// ==========================================
	
	// KHUSUS SMP (SEKOLAH MENENGAH PERTAMA)

	// ==========================================
	function data_smp(Request $request){
		$nik = Session::get('nik');
		$npsn = Session::get('npsn');
		$id_mapel = $request->id_mengajar;
		Session::put('id_mapel',$id_mapel);
		$id_rombel = Session::get('id_rombel');
		$jenjang = Session::get('jenjang');
		$id_anggota_rombel = Session::get('id_anggota_rombel');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("nik='$nik' AND npsn='$npsn' and status_siswa='Aktif'")->first();

		$mengajar = DB::connection($conn)->table($this->schema.'.mengajar as m')->join('public.rapor_mapel as rm','rm.mapel_id','m.mapel_id')
		->selectRaw("rm.nama as nama_mapel")
		->whereRaw("rm.mapel_id='$id_mapel'")->first();

		$nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as nm')->whereRaw("mapel_id='$id_mapel' AND anggota_rombel_id='$id_anggota_rombel'")->first();
		if(empty($nilai)){
			$insert = [
				'mapel_id'=>$id_mapel,
				'anggota_rombel_id'=>$id_anggota_rombel,
			];

			$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->insert($insert);
		}

		$data = [
			'main_menu'=>'nilai',
			'sub_menu'=>'',
			'mengajar'=>$mengajar,
			'semester'=>Session::get('semester'),
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
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		
		$id_mapel = Session::get('id_mapel');
		$id_anggota_rombel = Session::get('id_anggota_rombel');
		
		$nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as nm')->whereRaw("mapel_id='$id_mapel' AND anggota_rombel_id='$id_anggota_rombel'")->first();

		$data = [
			'mengajar'=>$nilai,
		];

		$content = view('siswa.isian.smp.pages.satu.satu',$data)->render();

		return $content;
	}

	function simpan11(Request $request){
		$uas = $request->uas;
		$uts = $request->uts;
		$npsn = Session::get('npsn');
		$id_mapel = Session::get('id_mapel');
		$id_anggota_rombel = Session::get('id_anggota_rombel');

		$jenjang = Session::get('jenjang');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		
		$nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as nm')->whereRaw("mapel_id='$id_mapel' AND anggota_rombel_id='$id_anggota_rombel'")->first();

		$data_insert = [
			'pts'=>$uts,
			'pas'=>$uas,
		];

		$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel as nm')->whereRaw("mapel_id='$id_mapel' AND anggota_rombel_id='$id_anggota_rombel'")->update($data_insert);


		if($simpan){
			$return = ['code'=>'200','message'=>'Berhasil disimpan','title'=>'Success','type'=>'success'];
		}else{
			$return = ['code'=>'250','message'=>'Gagal disimpan','title'=>'Whooops','type'=>'error'];
		}

		return $return;
	}

	function show12(){
		$jenjang = Session::get('jenjang');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas');

		$id_mapel = Session::get('id_mapel');
		$id_anggota_rombel = Session::get('id_anggota_rombel');

		$kd = DB::connection($conn)->table($this->schema.'.kd')
		->whereRaw("mapel_id='$id_mapel' AND kelas='$kelas' AND no_ki='3'")->orderBy('id_kd','ASC')->get();

		$nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as nm')->whereRaw("mapel_id='$id_mapel' AND anggota_rombel_id='$id_anggota_rombel'")->first();
		
		$data = [
			'kd'=>$kd,
			'schema'=>$this->schema,
			'conn'=>$conn,
			'mengajar'=>$nilai,
		];

		$content = view('siswa.isian.smp.pages.satu.dua',$data)->render();

		return $content;
	}

	function show13(){
		$jenjang = Session::get('jenjang');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas');

		$id_mapel = Session::get('id_mapel');
		$id_anggota_rombel = Session::get('id_anggota_rombel');

		$kd = DB::connection($conn)->table($this->schema.'.kd')
		->whereRaw("mapel_id='$id_mapel' AND kelas='$kelas' AND no_ki='3'")->orderBy('id_kd','ASC')->get();

		$nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as nm')->whereRaw("mapel_id='$id_mapel' AND anggota_rombel_id='$id_anggota_rombel'")->first();
		
		$data = [
			'kd'=>$kd,
			'schema'=>$this->schema,
			'conn'=>$conn,
			'mengajar'=>$nilai,
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
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas');

		$id_mapel = Session::get('id_mapel');
		$id_anggota_rombel = Session::get('id_anggota_rombel');

		$kd = DB::connection($conn)->table($this->schema.'.kd')
		->whereRaw("mapel_id='$id_mapel' AND kelas='$kelas' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as nm')->whereRaw("mapel_id='$id_mapel' AND anggota_rombel_id='$id_anggota_rombel'")->first();
		
		$data = [
			'kd'=>$kd,
			'schema'=>$this->schema,
			'conn'=>$conn,
			'mengajar'=>$nilai,
		];

		$content = view('siswa.isian.smp.pages.dua.satu',$data)->render();

		return $content;
	}

	function show22(){
		$jenjang = Session::get('jenjang');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas');

		$id_mapel = Session::get('id_mapel');
		$id_anggota_rombel = Session::get('id_anggota_rombel');

		$kd = DB::connection($conn)->table($this->schema.'.kd')
		->whereRaw("mapel_id='$id_mapel' AND kelas='$kelas' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as nm')->whereRaw("mapel_id='$id_mapel' AND anggota_rombel_id='$id_anggota_rombel'")->first();
		
		$data = [
			'kd'=>$kd,
			'schema'=>$this->schema,
			'conn'=>$conn,
			'mengajar'=>$nilai,
		];

		$content = view('siswa.isian.smp.pages.dua.dua',$data)->render();

		return $content;
	}

	function show23(){
		$jenjang = Session::get('jenjang');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas');

		$id_mapel = Session::get('id_mapel');
		$id_anggota_rombel = Session::get('id_anggota_rombel');

		$kd = DB::connection($conn)->table($this->schema.'.kd')
		->whereRaw("mapel_id='$id_mapel' AND kelas='$kelas' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as nm')->whereRaw("mapel_id='$id_mapel' AND anggota_rombel_id='$id_anggota_rombel'")->first();
		
		$data = [
			'kd'=>$kd,
			'schema'=>$this->schema,
			'conn'=>$conn,
			'mengajar'=>$nilai,
		];

		$content = view('siswa.isian.smp.pages.dua.tiga',$data)->render();

		return $content;
	}

	function show24(){
		$jenjang = Session::get('jenjang');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas');

		$id_mapel = Session::get('id_mapel');
		$id_anggota_rombel = Session::get('id_anggota_rombel');

		$kd = DB::connection($conn)->table($this->schema.'.kd')
		->whereRaw("mapel_id='$id_mapel' AND kelas='$kelas' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as nm')->whereRaw("mapel_id='$id_mapel' AND anggota_rombel_id='$id_anggota_rombel'")->first();
		
		$data = [
			'kd'=>$kd,
			'schema'=>$this->schema,
			'conn'=>$conn,
			'mengajar'=>$nilai,
		];

		$content = view('siswa.isian.smp.pages.dua.empat',$data)->render();

		return $content;
	}

	function simpankd(Request $request){
		$namenya = $request->namenya;
		$nilainya = $request->nilai;
		$id_kd = $request->id_kd;
		$kolom = $request->kolom;
		$npsn = Session::get('npsn');
		$id_mapel = Session::get('id_mapel');
		$id_anggota_rombel = Session::get('id_anggota_rombel');

		$jenjang = Session::get('jenjang');
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as nm')->whereRaw("mapel_id='$id_mapel' AND anggota_rombel_id='$id_anggota_rombel'")->first();


		for ($i=0; $i < count($nilainya); $i++) { 
			$data_insert = [
				'kd_id'=>$id_kd[$i],
				'nilai_mapel_id'=>$nilai->id_nilai_mapel,
				$kolom=>$nilainya[$i],
			];

			$cek = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->whereRaw("nilai_mapel_id='$nilai->id_nilai_mapel' AND kd_id='$id_kd[$i]'")->first();
			if(!empty($cek)){
				$simpan = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->whereRaw("nilai_mapel_id='$nilai->id_nilai_mapel' AND kd_id='$id_kd[$i]'")->update($data_insert);
			}else{
				$simpan = DB::connection($conn)->insert($data_insert);
			}
		}

		if($simpan){
			$return = ['code'=>'200','message'=>'Berhasil disimpan','title'=>'Success','type'=>'success'];
		}else{
			$return = ['code'=>'250','message'=>'Gagal disimpan','title'=>'Whooops','type'=>'error'];
		}

		return $return;
	}
}
