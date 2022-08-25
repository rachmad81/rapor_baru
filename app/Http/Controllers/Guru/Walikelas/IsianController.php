<?php

namespace App\Http\Controllers\Guru\Walikelas;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;
use App\Http\Libraries\Convert;

use App\Http\Controllers\Controller;

use DB,Session,Redirect,Excel;

class IsianController extends Controller
{
	protected $schema;

	public function __construct() 
	{
		$this->schema = env('CURRENT_SCHEMA','production');
	}

	function main(Request $request){
		$id_rombel = $request->id_rombel;
		$mapel_id = $request->mapel_id;
		$jenjang = Session::get('jenjang');
		$request->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($request);

		$rombongan_belajar = DB::connection($conn)->table('public.rombongan_belajar as rb')->where('rb.id_rombongan_belajar',$id_rombel)->first();
		$tahun_ajaran = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->where('id_tahun_ajaran',$rombongan_belajar->tahun_ajaran_id)->first();
		$rombongan_belajar->nama_tahun_ajaran = $tahun_ajaran->nama_tahun_ajaran;

		if(!empty($rombongan_belajar)){
			Session::put('kelas_wk',$rombongan_belajar->kelas);
			Session::put('rombel_wk',$rombongan_belajar->rombel);
			Session::put('ta_wk',$tahun_ajaran->nama_tahun_ajaran);
			$semester = ($rombongan_belajar->semester==1) ? 'Semester Ganjil' : 'Semester Genap';
			Session::put('semester_wk',$semester);
		}else{
			Session::put('kelas_wk','0xx');
			Session::put('rombel_wk','0xx');
			Session::put('ta_wk','0xx');
			Session::put('semester_wk','0xx');
			$semester = '';
		}
		$mengajar = DB::connection($conn)->table($this->schema.'.mengajar as m')
		->join('public.rapor_mapel as ma','ma.mapel_id','m.mapel_id')
		->selectRaw("*,ma.nama as nama_mapel")
		->whereRaw("m.rombel_id='$id_rombel' AND m.mapel_id='$mapel_id'")->first();

		Session::put('id_rombel',$id_rombel);
		Session::put('mapel_id',$mapel_id);

		if(!empty($mengajar)){
			$data = [
				'main_menu'=>'walikelas',
				'sub_menu'=>'',
				'mengajar'=>$mengajar,
				'rombongan_belajar'=>$rombongan_belajar,
			];

			return view('guru.walikelas.isian.index',$data);
		}else{
			Session::flash('title','Whooops');
			Session::flash('message','Tidak memiliki akses');
			Session::flash('type','warning');
			return Redirect::route('dashboard_guru');
		}

	}

	function pages(Request $request){
		$i = $request->i;
		$jenjang = Session::get('jenjang');
		$content = 'Pilih Tab';

		switch ($i) {
			case '1':
			$content = $this->show1();
			break;
			case '2':
			if($jenjang=='SD'){
				$content = view('guru.walikelas.isian.pages.dua')->render();
			}else{
				$content = view('guru.walikelas.isian.pages.smp.dua')->render();
			}
			break;
			case '3':
			if($jenjang=='SD'){
				$content = view('guru.walikelas.isian.pages.tiga')->render();
			}else{
				$content = view('guru.walikelas.isian.pages.smp.tiga')->render();
			}
			break;
			case '4':
			$content = view('guru.walikelas.isian.pages.empat')->render();
			break;
			case '5':
			$content = $this->show5();
			break;
			case '6':
			$content = view('guru.walikelas.isian.pages.enam')->render();
			break;
			
			default:
			$content = 'Pilih Tab';
			break;
		}
		return ['content'=>$content];
	}

	// PAGE 1
	function show1(){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id_wk');
		$nama_schema = Session::get('nama_schema');
		$npsn = Session::get('npsn');

		$mengajar = DB::connection($conn)->table($nama_schema.'.mengajar as m')
		->leftjoin('public.rapor_mapel as rm','rm.mapel_id','m.mapel_id')
		->selectRaw("m.*,rm.nama as nama_mapel")
		->whereRaw("kelas='$kelas' AND rombel='$rombel' AND npsn='$npsn' AND m.mapel_id='$mapel_id'")->first();

		$data = [
			'mengajar'=>$mengajar,
		];

		$content = view('guru.walikelas.isian.pages.satu',$data)->render();

		return $content;
	}

	function simpan_kkm(Request $request){
		$kkm = $request->kkm;

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id_wk');
		$nama_schema = Session::get('nama_schema');
		$npsn = Session::get('npsn');

		$mengajar = DB::connection($conn)->table($nama_schema.'.mengajar as m')->whereRaw("kelas='$kelas' AND rombel='$rombel' AND npsn='$npsn' AND m.mapel_id='$mapel_id'")->first();
		if(!empty($mengajar)){
			$update = DB::connection($conn)->table($nama_schema.'.mengajar as m')->whereRaw("kelas='$kelas' AND rombel='$rombel' AND npsn='$npsn' AND m.mapel_id='$mapel_id'")->update(['kkm'=>$kkm]);
			if($update){
				$return = ['code'=>'200','title'=>'Success','message'=>'Berhasil disimpan','type'=>'success'];
			}else{
				$return = ['code'=>'250','title'=>'Whooops','message'=>'Ggaal disimpan','type'=>'error'];
			}
		}else{
			$return = ['code'=>'250','title'=>'Whooops','message'=>'Mapel tidak diajarkan oleh '.Session::get('nama'),'type'=>'error'];
		}

		return $return;
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
			$content = $this->show22();
			break;
			case '3':
			$content = $this->show23();
			break;

			default:
			$content = 'Pilih Tab';
			break;
		}

		return ['content'=>$content];
	}

	function show21(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='3'")->orderBy('id_kd','ASC')->get();

		$kolom = 'nph';

		if($siswa->count()!=0){
			if($jenjang=='SD'){
				foreach($siswa as $s){
					$arr_nilai = [];
					if($kd->count()!=0){
						foreach($kd as $k){
							$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
							->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
							->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
							$arr_nilai[$k->id_kd] = (!empty($get_nilai)) ? $get_nilai->$kolom : '';
						}
					}

					$s->nilai = $arr_nilai;
				}
			}else{
				foreach($siswa as $s){
					$arr_nilai = [];
					$get_nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as np')
					->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();

					$s->uts = (!empty($get_nilai)) ? $get_nilai->pts : '';
					$s->uas = (!empty($get_nilai)) ? $get_nilai->pas : '';
				}
			}
		}

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		if($jenjang=='SD'){
			$content = view('guru.walikelas.isian.pages.dua.satu',$data)->render();
		}else{
			$content = view('guru.walikelas.isian.pages.smp.dua.satu',$data)->render();
		}

		return $content;
	}

	function show22(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);


		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='3'")->orderBy('id_kd','ASC')->get();

		if($jenjang=='SD'){
			$kolom = 'npts';
		}else{
			$kolom = 'nph';
		}

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$arr_nilai = [];
				if($kd->count()!=0){
					foreach($kd as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						$arr_nilai[$k->id_kd] = (!empty($get_nilai)) ? $get_nilai->$kolom : '';
					}
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		if($jenjang=='SD'){
			$content = view('guru.walikelas.isian.pages.dua.dua',$data)->render();
		}else{
			$content = view('guru.walikelas.isian.pages.smp.dua.dua',$data)->render();
		}

		return $content;
	}

	function show23(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='3'")->orderBy('id_kd','ASC')->get();

		if($jenjang=='SD'){
			$kolom = 'npas';
		}else{
			$kolom = 'npts';
		}

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$arr_nilai = [];
				if($kd->count()!=0){
					foreach($kd as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						$arr_nilai[$k->id_kd] = (!empty($get_nilai)) ? $get_nilai->$kolom : '';
					}
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		if($jenjang=='SD'){
			$content = view('guru.walikelas.isian.pages.dua.tiga',$data)->render();
		}else{
			$content = view('guru.walikelas.isian.pages.smp.dua.tiga',$data)->render();
		}

		return $content;
	}

	// PAGE 3
	function pages3(Request $request){
		$i = $request->id;
		$content = 'Pilih Tab';

		switch ($i) {
			case '1':
			$content = $this->show31();
			break;
			case '2':
			$content = $this->show32();
			break;
			case '3':
			$content = $this->show33();
			break;
			case '4':
			$content = $this->show34();
			break;

			default:
			$content = 'Pilih Tab';
			break;
		}

		return ['content'=>$content];
	}

	function show31(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$kolom = 'keterampilan';

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$arr_nilai = [];
				if($kd->count()!=0){
					foreach($kd as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						$arr_nilai[$k->id_kd] = (!empty($get_nilai)) ? $get_nilai->$kolom : '';
					}
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		if($jenjang=='SD'){
			$content = view('guru.walikelas.isian.pages.tiga.satu',$data)->render();
		}else{
			$content = view('guru.walikelas.isian.pages.smp.tiga.satu',$data)->render();
		}

		return $content;
	}

	function show32(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$kolom = 'portofolio';

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$arr_nilai = [];
				if($kd->count()!=0){
					foreach($kd as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						$arr_nilai[$k->id_kd] = (!empty($get_nilai)) ? $get_nilai->$kolom : '';
					}
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		if($jenjang=='SD'){
			$content = view('guru.walikelas.isian.pages.tiga.dua',$data)->render();
		}else{
			$content = view('guru.walikelas.isian.pages.smp.tiga.dua',$data)->render();
		}

		return $content;
	}

	function show33(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$kolom = 'proyek';

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$arr_nilai = [];
				if($kd->count()!=0){
					foreach($kd as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						$arr_nilai[$k->id_kd] = (!empty($get_nilai)) ? $get_nilai->$kolom : '';
					}
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		if($jenjang=='SD'){
			$content = view('guru.walikelas.isian.pages.tiga.tiga',$data)->render();
		}else{
			$content = view('guru.walikelas.isian.pages.smp.tiga.tiga',$data)->render();
		}

		return $content;
	}

	function show34(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$kolom = 'produk';

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$arr_nilai = [];
				if($kd->count()!=0){
					foreach($kd as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						$arr_nilai[$k->id_kd] = (!empty($get_nilai)) ? $get_nilai->$kolom : '';
					}
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		if($jenjang=='SD'){
			$content = view('guru.walikelas.isian.pages.tiga.empat',$data)->render();
		}else{
			$content = view('guru.walikelas.isian.pages.smp.tiga.empat',$data)->render();
		}

		return $content;
	}

	// PAGE 5
	function show5(){
		$jenjang = Session::get('jenjang');
		if($jenjang=='SD'){
			$content = $this->show5SD();
		}else{
			$content = $this->show5SMP();
		}

		return $content;
	}

	function show5SD(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama','ASC')->get();

		$kd3 = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='3'")->orderBy('id_kd','ASC')->get();
		$kd4 = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();
		$kkm = 75;

		$temp = round(((100 - $kkm)/3),0);
		$c	  = $kkm + ($temp-1);
		$b	  = $c + ($temp);
		$a    = 100 - $temp;

		$adanilai=false;
		$kd_pengetahuan_tertinggi	= 0;
		$nilai_pengetahuan_tertinggi= 0;
		$kd_pengetahuan_terendah	= 0;
		$nilai_pengetahuan_terendah	= 100;
		$kd_pengetahuan_terendah2	= 0;
		$kd_pengetahuan_terendah3	= 0;

		$tampil = [];
		$id=1;

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$huruf_ki3 = '';
				$nilai_ki3 = 0;
				$nr_ki4 = 0;
				$catatan3 = '';
				$catatan4 = '';
				$arr_nilai = [];
				$pembagi_kd=4;

				// KI-3
				if($kd3->count()!=0){
					$np_pembagi = 0;
					$nilai_kd = 0;
					$np_jumlah = 0;
					foreach($kd3 as $i=>$k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->selectRaw("*,COALESCE(nph,0) as nph,COALESCE(npts,0) as npts,COALESCE(npas,0) as npas")
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						if(!empty($get_nilai)){
							if($get_nilai->nph>0 && $get_nilai->npas>0){
								$adanilai=true;
							}
							$np_pembagi++;

							if($adanilai==true){
								if($pembagi_kd>0){
									$nilai_kd = ((2*$get_nilai->nph)+($get_nilai->npts)+($get_nilai->npas))/$pembagi_kd;
								}else{
									$nilai_kd = 0;
								}
							}else{
								$nilai_kd = 0;
							}

							$np_jumlah+=$nilai_kd;

							if($nilai_kd>$nilai_pengetahuan_tertinggi){
								$nilai_pengetahuan_tertinggi=$nilai_kd;
								$kd_pengetahuan_tertinggi=$i;
							}
							if($nilai_kd<$nilai_pengetahuan_terendah){
								$nilai_pengetahuan_terendah=$nilai_kd;
								$kd_pengetahuan_terendah=$i;
							}
						}
					}

					if($np_pembagi>0){
						$np = $np_jumlah/$np_pembagi;
					}else{
						$np = 0;
					}

					if($np>0){
						$nilai_ki3 = $np;

						$huruf_ki3=Convert::angka2hurufsma($nilai_ki3,$kkm);
						$catatan3=Convert::catatan_ki3($mapel_id,$kelas,$huruf_ki3,$kd_pengetahuan_terendah,$kd_pengetahuan_terendah2,$kd_pengetahuan_terendah3,$kd_pengetahuan_tertinggi);
					}
				}

				// KI-4
				$praktek_pembagi=0;
				$praktek_jumlah=0;
				$nr_pembagi=0;
				$nr_jumlah=0;

				$kd_ketrampilan_tertinggi	= 0;
				$nilai_ketrampilan_tertinggi= 0;
				$kd_ketrampilan_terendah	= 0;
				$nilai_ketrampilan_terendah	= 100;
				$kd_ketrampilan_terendah2	= 0;
				$kd_ketrampilan_terendah3	= 0;
				$pembagi_kd=0;
				$adanilai=false;

				if($kd4->count()!=0){
					foreach($kd4 as $i=>$k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->selectRaw("*,COALESCE(keterampilan,0) as keterampilan")
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						
						if(!empty($get_nilai)){
							if($get_nilai->keterampilan>0){
								$praktek_pembagi++;
								$praktek_jumlah+=$get_nilai->keterampilan;
							}

							if($praktek_pembagi>0){ 
								$praktek=$praktek_jumlah/$praktek_pembagi; 
							}else{
								$praktek=0;
							}

							$nilai_ki4=0;
							$nilai_ki4=$praktek;

							$nr_ki4=round(($nilai_ki4),2);
							$huruf_nr_ki4=Convert::angka2hurufsma($nr_ki4,$kkm);
							
							if($get_nilai->keterampilan>0){
								$nr_pembagi++;
								$nr_jumlah+=$get_nilai->keterampilan;
								$pembagi_kd++;
								$adanilai=true;
							}
						}


						if($pembagi_kd>0 ){ 
							$nilai_kd=($get_nilai->keterampilan)/$pembagi_kd;
						}else{
							$nilai_kd=0;
						}

						if($adanilai){
							if($nilai_kd>$nilai_ketrampilan_tertinggi){
								$nilai_ketrampilan_tertinggi=$nilai_kd;
								$kd_ketrampilan_tertinggi=$i;
							}
							if($nilai_kd<$nilai_ketrampilan_terendah){
								$nilai_ketrampilan_terendah=$nilai_kd;									
								$kd_ketrampilan_terendah3=$kd_ketrampilan_terendah2;
								$kd_ketrampilan_terendah2=$kd_ketrampilan_terendah;
								$kd_ketrampilan_terendah=$i;
							}
						}	
					}

					if($nr_pembagi>0){
						$nr=$nr_jumlah/$nr_pembagi; 
						$catatan4=Convert::catatan_ki4($mapel_id,$kelas,$huruf_nr_ki4,$kd_ketrampilan_terendah,$kd_ketrampilan_terendah2,$kd_ketrampilan_terendah3,$kd_ketrampilan_tertinggi);
					}else{
						$nr=0;
					} 
				}

				if($nilai_ki3 != 0){
					$show_hurufk1 = number_format($nilai_ki3,0)." (".$huruf_ki3.")";
				}else{
					$show_hurufk1 = "&nbsp<br />&nbsp";
				}
				if($nr_ki4 != 0){
					$show_hurufk2 = number_format($nr_ki4,0)." (".$huruf_nr_ki4.")";
				}else{
					$show_hurufk2 = "&nbsp<br />&nbsp";
				}

				$baris = [
					'id'=>$id,
					'nama'=>$s->nama,
					'hurufk1'=>$show_hurufk1,
					'catatan1'=>$catatan3,
					'hurufk2'=>$show_hurufk2,
					'catatan2'=>$catatan4,
				];

				array_push($tampil,$baris);

				if($nilai_ki3> 0 OR $nr_ki4 > 0){
					$namasiswa	= str_replace("'", "&apos;", $s->nama);
					$dta_insert = [
						'nilai_ki3'=>$nilai_ki3,
						'predikat_ki3'=>$huruf_ki3,
						'deskripsi_ki3'=>$catatan3,
						'nilai_ki4'=>$nr_ki4,
						'predikat_ki4'=>$huruf_nr_ki4,
						'deskripsi_ki4'=>$catatan4,
					];

					$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel as np')
					->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
					if(!empty($get_nilai_mapel)){
						$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel as np')
						->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->update($dta_insert);
					}
					$id++;
				}
			}
		}

		$data = [
			'tampil'=>$tampil,
			'nama_schema'=>$nama_schema,
			'kkm'=>$kkm,
			'temp'=>$temp,
			'c'=>$c,
			'b'=>$b,
			'a'=>$a,
		];

		$content = view('guru.walikelas.isian.pages.lima',$data)->render();

		return $content;
	}

	function show5SMP(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama','ASC')->get();

		$kd3 = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='3'")->orderBy('id_kd','ASC')->get();
		$kd4 = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();
		$kkm = 75;

		$temp = round(((100 - $kkm)/3),0);
		$c	  = $kkm + ($temp-1);
		$b	  = $c + ($temp);
		$a    = 100 - $temp;

		$adanilai=false;
		$kd_pengetahuan_tertinggi	= 1;
		$nilai_pengetahuan_tertinggi= 0;
		$kd_pengetahuan_terendah	= 1;
		$nilai_pengetahuan_terendah	= 100;
		$kd_pengetahuan_terendah2	= 0;
		$kd_pengetahuan_terendah3	= 0;

		$tampil = [];
		$id=1;

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$huruf_ki3 = '';
				$nilai_ki3 = 0;
				$catatan3 = '';
				$nilai_ki4 = 0;
				$huruf_ki4 = '';
				$catatan4 = '';

				$get_nilai_uts = DB::connection($conn)->table($this->schema.'.nilai_mapel as np')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();

				$nilai_ki3 = (!empty($get_nilai_uts)) ? ($get_nilai_uts->pts+$get_nilai_uts->pas) : 0;

				// KI-3
				if($kd3->count()!=0){
					$pembagi = [0,0];
					$aspek = ['nph','npts'];
					$nilai_kd = [0,0];
					$kd_terendah = '';
					$kd_tertinggi = '';
					$tinggi = 0;
					$rendah = 100;

					foreach($kd3 as $i=>$k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->selectRaw("*,COALESCE(nph,0) as nph,COALESCE(npts,0) as npts,COALESCE(npas,0) as npas")
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						
						if(!empty($get_nilai)){
							for ($j=0; $j < count($aspek); $j++) { 
								$kolom = $aspek[$j];
								if($get_nilai->$kolom!=0){
									$nilai_kd[$j] += $get_nilai->$kolom;
									$pembagi[$j]++;

									if($tinggi<$get_nilai->$kolom){
										$kd_tertinggi = $i;
										$tinggi = $get_nilai->$kolom;
									}

									if($rendah>$get_nilai->$kolom){
										$kd_terendah = $i;
										$rendah = $get_nilai->$kolom;
									}
								}
							}
						}
					}

					for ($i=0; $i < count($aspek); $i++) { 						
						if($pembagi[$i]!=0){
							$nilai_ki3 += $nilai_kd[$i]/$pembagi[$i];
						}
					}

					$nilai_ki3 = $nilai_ki3/4;

					$huruf_ki3=Convert::angka2hurufsma($nilai_ki3,$kkm);
					$catatan3=Convert::catatan_ki3($mapel_id,$kelas,$huruf_ki3,$kd_terendah,'','',$kd_tertinggi);
				}


				// KI-4
				if($kd4->count()!=0){
					$pembagi = [0,0,0,0];
					$aspek = ['keterampilan','portofolio','produk','proyek'];
					$nilai_kd = [0,0,0,0];
					$kd_terendah = '';
					$kd_tertinggi = '';
					$tinggi = 0;
					$rendah = 100;

					foreach($kd4 as $i=>$k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->selectRaw("*,COALESCE(keterampilan,0) as keterampilan,COALESCE(portofolio,0) as portofolio,COALESCE(produk,0) as produk,COALESCE(proyek,0) as proyek")
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						
						if(!empty($get_nilai)){
							for ($j=0; $j < count($aspek); $j++) { 
								$kolom = $aspek[$j];
								if($get_nilai->$kolom!=0){
									$nilai_kd[$j] += $get_nilai->$kolom;
									$pembagi[$j]++;

									if($tinggi<$get_nilai->$kolom){
										$kd_tertinggi = $i;
										$tinggi = $get_nilai->$kolom;
									}

									if($rendah>$get_nilai->$kolom){
										$kd_terendah = $i;
										$rendah = $get_nilai->$kolom;
									}
								}
							}
						}
					}

					for ($i=0; $i < count($aspek); $i++) { 						
						if($pembagi[$i]!=0){
							$nilai_ki4 += $nilai_kd[$i]/$pembagi[$i];
						}
					}

					$nilai_ki4 = $nilai_ki4/4;

					$huruf_ki4=Convert::angka2hurufsma($nilai_ki4,$kkm);
					$catatan4=Convert::catatan_ki4($mapel_id,$kelas,$huruf_ki4,$kd_terendah,'','',$kd_terendah);
				}

				if($nilai_ki3 != 0){
					$show_hurufk1 = number_format($nilai_ki3,0)." (".$huruf_ki3.")";
				}else{
					$show_hurufk1 = "&nbsp<br />&nbsp";
					$catatan3 = '';
				}

				if($nilai_ki4 != 0){
					$show_hurufk2 = number_format($nilai_ki4,0)." (".$huruf_ki4.")";
				}else{
					$show_hurufk2 = "&nbsp<br />&nbsp";
					$catatan4 = '';
				}

				$baris = [
					'id'=>$id,
					'nama'=>$s->nama,
					'hurufk1'=>$show_hurufk1,
					'catatan1'=>$catatan3,
					'hurufk2'=>$show_hurufk2,
					'catatan2'=>$catatan4,
				];

				array_push($tampil,$baris);

				if($nilai_ki3> 0 OR $nilai_ki4 > 0){
					$namasiswa	= str_replace("'", "&apos;", $s->nama);
					$dta_insert = [
						'nilai_ki3'=>$nilai_ki3,
						'predikat_ki3'=>$huruf_ki3,
						'deskripsi_ki3'=>$catatan3,
						'nilai_ki4'=>$nilai_ki4,
						'predikat_ki4'=>$huruf_ki4,
						'deskripsi_ki4'=>$catatan4,
					];

					$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel as np')
					->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
					if(!empty($get_nilai_mapel)){
						$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel as np')
						->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->update($dta_insert);
					}
					$id++;
				}
			}
		}

		$data = [
			'tampil'=>$tampil,
			'nama_schema'=>$nama_schema,
			'kkm'=>$kkm,
			'temp'=>$temp,
			'c'=>$c,
			'b'=>$b,
			'a'=>$a,
		];

		$content = view('guru.walikelas.isian.pages.lima',$data)->render();

		return $content;
	}

	// PAGE 6
	function pages6(Request $request){
		$i = $request->id;
		$content = 'Pilih Tab';

		switch ($i) {
			case '1':
			$content = $this->show61();
			break;
			case '2':
			$content = view('guru.walikelas.isian.pages.enam.dua')->render();
			break;

			default:
			$content = 'Pilih Tab';
			break;
		}

		return ['content'=>$content];
	}

	function show61(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		foreach($siswa as $s){
			$arr_nilai = [];
			$get_nilai = DB::connection($conn)->table($this->schema.'.nilai_mapel as np')
			->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();

			$s->usek = (!empty($get_nilai)) ? $get_nilai->usek : '';
		}

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian.pages.enam.satu',$data)->render();

		return $content;
	}

	function simpan_uts(Request $request){
		$id_siswa = $request->id_siswa;
		$uas = $request->uas;
		$uts = $request->uts;
		$mapel_id = Session::get('mapel_id_wk');
		$nama_schema = Session::get('nama_schema');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$id_rombel = Session::get('id_rombel');
		$mapel_id = Session::get('mapel_id');
		$npsn = Session::get('npsn');
		$kategori = $request->kategori;
		$usek = $request->usek;

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("id_siswa='$id_siswa' AND rombongan_belajar_id='$id_rombel'")->first();
		$id_anggota = '';
		if(!empty($anggota)){
			$id_anggota = $anggota->id_anggota_rombel;
		}

		$data_nilai_mapel = [
			'anggota_rombel_id'=>$id_anggota,
			'mapel_id'=>$mapel_id,
		];

		$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($data_nilai_mapel)->first();

		if($kategori=='usek'){
			$data_insert = array_merge($data_nilai_mapel,['usek'=>$usek]);
		}else{
			$data_insert = array_merge($data_nilai_mapel,['pts'=>$uts,'pas'=>$uas]);
		}

		if(!empty($get_nilai_mapel)){
			$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($data_nilai_mapel)->update($data_insert);
		}else{
			$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->insert($data_insert);
		}

		if($simpan){
			$return = ['code'=>'200','title'=>'Success','type'=>'success','message'=>'Berhasil disimpan'];
		}else{
			$return = ['code'=>'250','title'=>'Whooops','type'=>'error','message'=>'Gagal disimpan'];
		}

		return $return;
	}

	function simpan_nilai(Request $request){
		$id_siswa = $request->id_siswa;
		$kategori = $request->kategori;
		$data_nilai = $request->nilai;
		$id_kd = $request->id_kd;
		$no_ki = $request->no_ki;
		$mapel_id = Session::get('mapel_id_wk');
		$nama_schema = Session::get('nama_schema');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$id_rombel = Session::get('id_rombel');
		$mapel_id = Session::get('mapel_id');
		$npsn = Session::get('npsn');

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("id_siswa='$id_siswa' AND rombongan_belajar_id='$id_rombel'")->first();
		$id_anggota = '';
		if(!empty($anggota)){
			$id_anggota = $anggota->id_anggota_rombel;
		}

		$data_nilai_mapel = [
			'anggota_rombel_id'=>$id_anggota,
			'mapel_id'=>$mapel_id,
		];

		if($id_anggota!=''){
			$id_nilai_mapel='';
			while($id_nilai_mapel=='') {
				$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($data_nilai_mapel)->first();
				if(!empty($get_nilai_mapel)){
					$id_nilai_mapel = $get_nilai_mapel->id_nilai_mapel;
				}else{
					$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->insert($data_nilai_mapel);
					$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($data_nilai_mapel)->first();
					$id_nilai_mapel = $get_nilai_mapel->id_nilai_mapel;
				}
			};
		}

		if($id_nilai_mapel!=''){
			for ($i=0; $i < count($data_nilai); $i++) {
				$data = [
					'nilai_mapel_id'=>$id_nilai_mapel,
					'kd_id'=>$id_kd[$i],
				];

				$where = $data;

				$data = array_merge($data,[
					$kategori=>$data_nilai[$i],
				]);

				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->where($where)->first();
				if(!empty($get_nilai)){
					$simpan = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->where($where)->update($data);
				}else{
					$simpan = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->insert($data);
				}
			}

			if($simpan){
				$return = ['code'=>'200','title'=>'Success','message'=>'Berhasil disimpan','type'=>'success'];
			}else{
				$return = ['code'=>'250','title'=>'Whooops','message'=>'Gagal disimpan','type'=>'error'];
			}
		}else{
			$return = ['code'=>'250','title'=>'Whooops','message'=>'Gagal disimpan','type'=>'error'];			
		}

		return $return;
	}

	// template nilai excel
	function template(Request $request){
		$jenjang = Session::get('jenjang');
		
		if($jenjang=='SD'){
			$hasil = $this->templateSD($request);
		}else{
			$hasil = $this->templateSMP($request);
		}

		return $hasil;
	}

	function templateSD(Request $request){
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');
		$semester = Session::get('semester_wk');
		$id_rombel = Session::get('id_rombel');
		$jenjang = Session::get('jenjang');
		$npsn = Session::get('npsn');
		
		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$rombongan_belajar = DB::connection($conn)->table('public.rombongan_belajar as rb')->join('public.tahun_ajaran as ta','ta.id_tahun_ajaran','rb.tahun_ajaran_id')->join('public.pegawai as p',function($join){
			return $join->on('rb.wali_kelas_peg_id','=',DB::raw("CAST(p.peg_id as varchar)"))->on('rb.nik_wk','=','p.no_ktp');
		})->where('rb.id_rombongan_belajar',$id_rombel)->first();

		$sekolah = DB::connection($conn)->table('public.sekolah')->whereRaw("npsn='$npsn'")->first();
		
		$mengajar = DB::connection($conn)->table($this->schema.'.mengajar as m')
		->join('public.rapor_mapel as ma','ma.mapel_id','m.mapel_id')
		->join('public.pegawai as p',function($join){
			return $join->on('m.peg_id','=','p.peg_id')->on('m.nik_pengajar','=','p.no_ktp');
		})
		->selectRaw("*,ma.nama as nama_mapel,p.nama as nama_pengajar")
		->whereRaw("m.rombel_id='$id_rombel' AND m.mapel_id='$mapel_id'")->first();

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$kd3 = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='3'")->orderBy('id_kd','ASC')->get();
		$kd4 = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$arr_nilai3 = [];
				$arr_nilai4 = [];
				if($kd3->count()!=0){
					foreach($kd3 as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						
						$arr_nilai3[$k->id_kd] = $get_nilai;
					}
				}
				if($kd4->count()!=0){
					foreach($kd4 as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						
						$arr_nilai4[$k->id_kd] = $get_nilai;
					}
				}
				$s->nilai_kd3 = $arr_nilai3;
				$s->nilai_kd4 = $arr_nilai4;
			}
		}

		$data = [
			'nama_file'=>'Nilai_mapel_'.$mapel_id.'_'.$kelas.$rombel,
			'semester'=>$semester,
			'rombongan_belajar'=>$rombongan_belajar,
			'sekolah'=>$sekolah,
			'mengajar'=>$mengajar,
			'siswa'=>$siswa,
			'kd3'=>$kd3,
			'kd4'=>$kd4,
		];

		if($jenjang=='SD'){
			return view('guru.walikelas.isian.pages.template',$data);
		}else{
			return view('guru.walikelas.isian.pages.smp.template',$data);
		}
	}

	function templateSMP(Request $request){
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');
		$semester = Session::get('semester_wk');
		$id_rombel = Session::get('id_rombel');
		$jenjang = Session::get('jenjang');
		$npsn = Session::get('npsn');
		
		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$rombongan_belajar = DB::connection($conn)->table('public.rombongan_belajar as rb')->join('public.pegawai as p',function($join){
			return $join->on('rb.wali_kelas_peg_id','=',DB::raw("CAST(p.peg_id as varchar)"))->on('rb.nik_wk','=','p.no_ktp');
		})->where('rb.id_rombongan_belajar',$id_rombel)->first();
		$tahun_ajaran = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->where('id_tahun_ajaran',$rombongan_belajar->tahun_ajaran_id)->first();
		$rombongan_belajar->nama_tahun_ajaran = $tahun_ajaran->nama_tahun_ajaran;

		$sekolah = DB::connection($conn)->table('public.sekolah')->whereRaw("npsn='$npsn'")->first();
		
		$mengajar = DB::connection($conn)->table($this->schema.'.mengajar as m')
		->join('public.rapor_mapel as ma','ma.mapel_id','m.mapel_id')
		->join('public.pegawai as p',function($join){
			return $join->on('m.peg_id','=','p.peg_id')->on('m.nik_pengajar','=','p.no_ktp');
		})
		->selectRaw("*,ma.nama as nama_mapel,p.nama as nama_pengajar")
		->whereRaw("m.rombel_id='$id_rombel' AND m.mapel_id='$mapel_id'")->first();

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$kd3 = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='3'")->orderBy('id_kd','ASC')->get();
		$kd4 = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$arr_nilai3 = [];
				$arr_nilai4 = [];
				if($kd3->count()!=0){
					foreach($kd3 as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						
						$arr_nilai3[$k->id_kd] = $get_nilai;
					}
				}
				if($kd4->count()!=0){
					foreach($kd4 as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						
						$arr_nilai4[$k->id_kd] = $get_nilai;
					}
				}

				$get_uts = DB::connection($conn)->table($this->schema.'.nilai_mapel as np')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();

				$s->nilai_kd3 = $arr_nilai3;
				$s->nilai_kd4 = $arr_nilai4;
				$s->uts = (!empty($get_uts)) ? $get_uts->pts : '';
				$s->uas = (!empty($get_uts)) ? $get_uts->pas : '';
			}
		}

		$data = [
			'nama_file'=>'Nilai_mapel_'.$mapel_id.'_'.$kelas.$rombel,
			'semester'=>$semester,
			'rombongan_belajar'=>$rombongan_belajar,
			'sekolah'=>$sekolah,
			'mengajar'=>$mengajar,
			'siswa'=>$siswa,
			'kd3'=>$kd3,
			'kd4'=>$kd4,
		];

		if($jenjang=='SD'){
			return view('guru.walikelas.isian.pages.template',$data);
		}else{
			return view('guru.walikelas.isian.pages.smp.template',$data);
		}
	}

	function upload_excel(Request $request){
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$request->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($request);
		
		$file = $request->file('file_excel');

		$collection = Excel::toArray(new Request, $file);

		$sheet = $collection[0];
		$jumlah_baris = count($sheet);
		if($jenjang == 'SD'){
			$kolom_kd = $sheet[11];
			$kolom_kd_pakai = [];
			for ($i=0; $i < count($kolom_kd); $i++) { 
				if($i<7){
					continue;
				}

				if($kolom_kd[$i]==''){
					$kolom_kd[$i] = $kolom_kd[$i-1];
				}
				array_push($kolom_kd_pakai, $kolom_kd[$i]);
			}
		}else{
			$kolom_kd = $sheet[11];
			$kolom_kd_pakai = [];
			for ($i=0; $i < count($kolom_kd); $i++) { 
				if($i<9){
					continue;
				}

				if($kolom_kd[$i]==''){
					$kolom_kd[$i] = $kolom_kd[$i-1];
				}
				array_push($kolom_kd_pakai, $kolom_kd[$i]);
			}
		}

		for ($i=0; $i < $jumlah_baris; $i++) { 
			if($i<13){
				continue;
			}

			$baris = $sheet[$i];

			if($jenjang=='SD'){
				$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("id_siswa='$baris[6]' AND rombongan_belajar_id='$id_rombel'")->first();
				$id_anggota = '';
				if(!empty($anggota)){
					$id_anggota = $anggota->id_anggota_rombel;
				}

				$where_nilai_mapel = [
					'mapel_id'=>$baris[2],
					'anggota_rombel_id'=>$anggota->id_anggota_rombel,
				];

				$insert_nilai_mapel = array_merge($where_nilai_mapel);

				$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($where_nilai_mapel)->first();
				if(!empty($get_nilai_mapel)){
					$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($where_nilai_mapel)->update($insert_nilai_mapel);
				}else{
					$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->insert($insert_nilai_mapel);
				}

				$id_nilai_mapel='';
				while($id_nilai_mapel=='') {
					$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($where_nilai_mapel)->first();
					if(!empty($get_nilai_mapel)){
						$id_nilai_mapel = $get_nilai_mapel->id_nilai_mapel;
					}else{
						$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->insert($insert_nilai_mapel);
						$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($where_nilai_mapel)->first();
						$id_nilai_mapel = $get_nilai_mapel->id_nilai_mapel;
					}
				};

				for ($j=0; $j < count($kolom_kd_pakai); $j++) {
					switch($sheet[12][($j+7)]){
						default:
						$kolom_db = strtolower($sheet[12][($j+7)]);
						break;
					}
					$insert_detail_nilai = [
						'nilai_mapel_id'=>$id_nilai_mapel,
						'kd_id'=>$kolom_kd_pakai[$j],
						$kolom_db=>$baris[($j+7)]
					];
					$where_detail_nilai = [
						'nilai_mapel_id'=>$id_nilai_mapel,
						'kd_id'=>$kolom_kd_pakai[$j],
					];

					$cek_detail = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->where($where_detail_nilai)->first();
					if(!empty($cek_detail)){
						$simpan = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->where($where_detail_nilai)->update($insert_detail_nilai);
					}else{
						$simpan = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->insert($insert_detail_nilai);
					}
				}
			}else{
				$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("id_siswa='$baris[6]' AND rombongan_belajar_id='$id_rombel'")->first();
				$id_anggota = '';
				if(!empty($anggota)){
					$id_anggota = $anggota->id_anggota_rombel;
				}

				$where_nilai_mapel = [
					'mapel_id'=>$baris[2],
					'anggota_rombel_id'=>$anggota->id_anggota_rombel,
				];

				$insert_nilai_mapel = [
					'pts'=>$baris[7],
					'pas'=>$baris[8],
				];

				$insert_nilai_mapel = array_merge($insert_nilai_mapel,$where_nilai_mapel);

				$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($where_nilai_mapel)->first();
				if(!empty($get_nilai_mapel)){
					$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($where_nilai_mapel)->update($insert_nilai_mapel);
				}else{
					$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->insert($insert_nilai_mapel);
				}

				$id_nilai_mapel='';
				while($id_nilai_mapel=='') {
					$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($where_nilai_mapel)->first();
					if(!empty($get_nilai_mapel)){
						$id_nilai_mapel = $get_nilai_mapel->id_nilai_mapel;
					}else{
						$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->insert($insert_nilai_mapel);
						$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($where_nilai_mapel)->first();
						$id_nilai_mapel = $get_nilai_mapel->id_nilai_mapel;
					}
				};

				for ($j=0; $j < count($kolom_kd_pakai); $j++) {
					switch($sheet[12][($j+9)]){
						case 'Ulangan Harian':
						$kolom_db = 'nph';
						break;

						case 'Tugas':
						$kolom_db = 'npts';
						break;

						case 'Praktek':
						$kolom_db = 'keterampilan';
						break;

						default:
						$kolom_db = strtolower($sheet[12][($j+9)]);
						break;
					}
					$insert_detail_nilai = [
						'nilai_mapel_id'=>$id_nilai_mapel,
						'kd_id'=>$kolom_kd_pakai[$j],
						$kolom_db=>$baris[($j+9)]
					];
					$where_detail_nilai = [
						'nilai_mapel_id'=>$id_nilai_mapel,
						'kd_id'=>$kolom_kd_pakai[$j],
					];

					$cek_detail = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->where($where_detail_nilai)->first();
					if(!empty($cek_detail)){
						$simpan = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->where($where_detail_nilai)->update($insert_detail_nilai);
					}else{
						$simpan = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->insert($insert_detail_nilai);
					}
				}
			}
		}

		return ['message'=>'Berhasil diunggah','status'=>'Success','type'=>'success'];
	}
}
