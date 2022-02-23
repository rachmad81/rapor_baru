<?php

namespace App\Http\Controllers\Siswa;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Convert;
use App\Http\Libraries\Hitung_sikap;

use App\Http\Controllers\Controller;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use DB,Session;

class RaporController extends Controller
{
	function main(){
		$jenjang = Session::get('jenjang');

		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.siswa as s')->join('public.sekolah as sek','sek.npsn','s.npsn')->selectRaw("*,sek.nama as nama_sekolah,sek.alamat as alamat_sekolah")->whereRaw("s.nik='".Session::get('nik')."' AND s.status_siswa='Aktif' AND (s.alumni is not true OR s.alumni is null)")->first();

		$kelas = $siswa->kelas;

		$tahun_ajaran = [];

		if($jenjang=='SD'){
			if($kelas=='1'){
				$isi = [
					'urut'=>'1',
					'nama_db'=>env('CURRENT_DB_SD','production'),
					'nama'=>'2021/2022 Ganjil',
				];

				array_push($tahun_ajaran, $isi);
				$isi = [
					'urut'=>'1',
					'nama_db'=>'rapor_sd_2021_ganjil',
					'nama'=>'2021/2022 Ganjil',
				];

				array_push($tahun_ajaran, $isi);
			}else{
				$isi = [
					'urut'=>'1',
					'nama_db'=>env('CURRENT_DB_SD','production'),
					'nama'=>'2021/2022 Genap',
				];

				array_push($tahun_ajaran, $isi);
				$isi = [
					'urut'=>'1',
					'nama_db'=>'rapor_sd_2021_ganjil',
					'nama'=>'2021/2022 Ganjil',
				];

				array_push($tahun_ajaran, $isi);
				$urut = 1;
				for ($i=1; $i < $kelas; $i++) { 
					$tahun = 2021-$i;
					$isi = [
						'urut'=>($urut+2),
						'nama_db'=>'rapor_sd_'.$tahun.'_genap',
						'nama'=>$tahun.'/'.($tahun+1).' Genap',
					];
					array_push($tahun_ajaran, $isi);

					$isi = [
						'urut'=>($urut+1),
						'nama_db'=>'rapor_sd_'.$tahun.'_ganjil',
						'nama'=>$tahun.'/'.($tahun+1).' Ganjil',
					];
					array_push($tahun_ajaran, $isi);

					$urut+=2;
				}
			}
		}else{		
			if($kelas=='7'){
				$isi = [
					'urut'=>'1',
					'nama_db'=>env('CURRENT_DB_SMP','production'),
					'nama'=>'2021/2022 Genap',
				];

				array_push($tahun_ajaran, $isi);
				$isi = [
					'urut'=>'1',
					'nama_db'=>'rapor_smp_2021_ganjil',
					'nama'=>'2021/2022 Ganjil',
				];

				array_push($tahun_ajaran, $isi);
			}else{
				$isi = [
					'urut'=>'1',
					'nama_db'=>env('CURRENT_DB_SMP','production'),
					'nama'=>'2021/2022 Genap',
				];

				array_push($tahun_ajaran, $isi);
				$isi = [
					'urut'=>'1',
					'nama_db'=>'rapor_smp_2021_ganjil',
					'nama'=>'2021/2022 Ganjil',
				];

				array_push($tahun_ajaran, $isi);
				$urut = 1;
				$kelas = $kelas-6;
				for ($i=1; $i < $kelas; $i++) { 
					$tahun = 2021-$i;
					$isi = [
						'urut'=>($urut+2),
						'nama_db'=>'rapor_smp_'.$tahun.'_genap',
						'nama'=>$tahun.'/'.($tahun+1).' Genap',
					];
					array_push($tahun_ajaran, $isi);

					$isi = [
						'urut'=>($urut+1),
						'nama_db'=>'rapor_smp_'.$tahun.'_ganjil',
						'nama'=>$tahun.'/'.($tahun+1).' Ganjil',
					];
					array_push($tahun_ajaran, $isi);

					$urut+=2;
				}
			}
		}

		$data = [
			'main_menu'=>'rapor',
			'sub_menu'=>'',
			'siswa'=>$siswa,
			'tahun_ajaran'=>$tahun_ajaran,
		];

		return view('siswa.rapor.index',$data);
	}

	function data(Request $request){
		$jenjang = Session::get('jenjang');
		if($jenjang=='SD'){
			$content = $this->data_sd($request);
		}else{
			$content = $this->data_smp($request);
		}

		return ['content'=>$content];
	}

	function data_sd(Request $request){
		$jenjang = Session::get('jenjang');
		$nama_schema = $request->ta;
		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);
		$siswa = DB::connection($conn)->table('public.siswa as s')->join('public.sekolah as sek','sek.npsn','s.npsn')
		->leftjoin('public.kecamatan as kec','kec.kecamatan_kode','sek.kec_id')
		->leftjoin('public.kelurahan as kel','kel.kelurahan_kode','sek.desa')
		->leftjoin('public.agama as a','a.aga_id','s.aga_id')
		->leftjoin('public.wali_murid as wm',function($join){
			return $join->on('wm.id_siswa','=','s.id_siswa')->on('wm.npsn','=','s.npsn');
		})
		->leftjoin('public.pekerjaan as pa','pa.kode','wm.pekerjaan_ayah')
		->leftjoin('public.pekerjaan as pi','pi.kode','wm.pekerjaan_ibu')
		->leftjoin('public.pekerjaan as pw','pw.kode','wm.pekerjaan_wali')
		->selectRaw("s.nama as nama_siswa,s.tgl_lahir,s.nisn,s.nis,s.tempat_lahir,s.kelas,s.rombel,sek.kepala,sek.email as email_sekolah,sek.website as website_sekolah,s.id_siswa,sek.nama as nama_sekolah,sek.alamat as alamat_sekolah,kec.kecamatan_dispenduk,kel.kelurahan_dispenduk,s.kelamin,s.asal_sekolah,a.aga_nama,s.status_anak,s.anakke,s.alamat_ortu,s.telpon,s.alamat as alamat_siswa,wm.nama_ayah as ayah,wm.nama_ibu as ibu,wm.nama_wali,wm.pekerjaan_wali,pa.nama as pekerjaan_ayah,pi.nama as pekerjaan_ibu,pw.nama as pekerjaan_wali,wm.alamat_rumah,wm.rt,wm.rw,sek.kkm,sek.nss")
		->where('s.nik',Session::get('nik'))->first();
		$siswa_id = $siswa->id_siswa;

		$kelas = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->whereRaw("id_siswa='$siswa_id'")->orderBy('mapel_id','ASC')->first();
		$sikap = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->whereRaw("id_siswa='$siswa_id' AND kelas='$kelas->kelas' AND rombel='$kelas->rombel' AND mapel_id='1'")->orderBy('mapel_id','ASC')->first();
		
		$nilaia = DB::connection($conn)->table($nama_schema.'.nilai_akhir as na')
		->leftjoin($nama_schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id')->on('m.npsn','=','na.npsn')->on('m.kelas','=','na.kelas')->on('m.rombel','=','na.rombel');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.user_rapor','=','m.nip')->on('peg.npsn','=','m.npsn');
		})
		->selectRaw("na.id_siswa,na.npsn,na.nama,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4,m.nama as guru_mengajar,na.mapel")->whereRaw("na.id_siswa='$siswa_id' AND na.kategori IN ('KELOMPOK A','WAJIB') AND deskripsi_ki1 is null")->orderBy('na.urutan','ASC')->get();
		
		$nilaib = DB::connection($conn)->table($nama_schema.'.nilai_akhir as na')
		->leftjoin($nama_schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id')->on('m.npsn','=','na.npsn')->on('m.kelas','=','na.kelas')->on('m.rombel','=','na.rombel');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.user_rapor','=','m.nip')->on('peg.npsn','=','m.npsn');
		})
		->selectRaw("na.id_siswa,na.npsn,na.nama,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4,m.nama as guru_mengajar,na.mapel")->whereRaw("na.id_siswa='$siswa_id' AND na.kategori IN ('KELOMPOK B','MUATAN LOKAL') AND deskripsi_ki1 is null")->orderBy('na.urutan','ASC')->get();

		$nilai_agama = DB::connection($conn)->table($nama_schema.'.nilai_akhir as na')
		->leftjoin($nama_schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id')->on('m.npsn','=','na.npsn')->on('m.kelas','=','na.kelas')->on('m.rombel','=','na.rombel');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.user_rapor','=','m.nip')->on('peg.npsn','=','m.npsn');
		})
		->selectRaw("na.id_siswa,na.npsn,na.nama,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4,m.nama as guru_mengajar,na.mapel")->whereRaw("na.id_siswa='$siswa_id' AND na.kategori IN ('AGAMA ISLAM') AND deskripsi_ki1 is null")->orderBy('na.urutan','ASC')->get();
		
		$walikelas = DB::connection($conn)->table($nama_schema.'.walikelas as wk')
		->leftjoin('public.pegawai as p',function($join){
			return $join->on('wk.nip','=','p.user_rapor')->on('wk.npsn','=','p.npsn');
		})
		->leftjoin('public.gelar_akademik as gd',function($join){
			return $join->on('p.gelar','=',DB::raw('CAST(gd.gelar_akademik_id as varchar)'));
		})
		->leftjoin('public.gelar_akademik as gb',function($join){
			return $join->on('p.gelar2','=',DB::raw('CAST(gb.gelar_akademik_id as varchar)'));
		})
		->selectRaw("p.nama as nama_wk,gd.kode as gelar_depan,gb.kode as gelar_belakang,CONCAT('NIP. ',p.nip) as nip")
		->whereRaw("wk.npsn='".Session::get('npsn')."' AND wk.kelas='$kelas->kelas' AND wk.rombel='$kelas->rombel'")->first();
		$ekskul = DB::connection($conn)->table($nama_schema.'.ekskul_absen')->whereRaw("npsn='".Session::get('npsn')."' AND id_siswa='$siswa_id' AND kelas='$kelas->kelas' AND rombel='$kelas->rombel'")->first();

		$prestasi = DB::connection($conn)->table('public.prestasi_siswa')->whereRaw("id_siswa='$siswa_id' AND npsn='".Session::get('npsn')."'")->get();

		$ks = DB::connection($conn)->table('public.pegawai as p')
		->leftjoin('public.gelar_akademik as gd',function($join){
			return $join->on('p.gelar','=',DB::raw('CAST(gd.gelar_akademik_id as varchar)'));
		})
		->leftjoin('public.gelar_akademik as gb',function($join){
			return $join->on('p.gelar2','=',DB::raw('CAST(gb.gelar_akademik_id as varchar)'));
		})
		->selectRaw("p.nama as nama_ks,gd.kode as gelar_depan,gb.kode as gelar_belakang,CONCAT('NIP. ',p.nip) as nip")
		->whereRaw("(npsn='".Session::get('npsn')."' or sekolah_plt='".Session::get('npsn')."') AND jabatan='2' AND keterangan='Aktif'")->first();

		if($jenjang=='SD'){
			$tahun_ajaran = substr($nama_schema,9,4);
			$semester = substr($nama_schema,14);
		}else{
			$tahun_ajaran = substr($nama_schema,10,4);
			$semester = substr($nama_schema,15);
		}

		$rapor_semester = DB::connection($conn)->table('public.rapor_semester')->whereRaw("semester='".$tahun_ajaran."_".$semester."'")->first();

		$qrcode = QrCode::size(50)->generate($request->url());

		switch ($nama_schema) {
			// case 'rapor_sd_2017_ganjil':
			// $pagesnya = '2017_ganjil';
			// break;
			// case 'rapor_sd_2017_genap':
			// $pagesnya = '2017_genap';
			// break;
			// case 'rapor_sd_2018_ganjil':
			// $pagesnya = '2018_ganjil';
			// break;
			// case 'rapor_sd_2018_genap':
			// $pagesnya = '2018_genap';
			// break;
			// case 'rapor_sd_2019_ganjil':
			// $pagesnya = '2019_ganjil';
			// break;
			// case 'rapor_sd_2019_genap':
			// $pagesnya = '2019_genap';
			// break;
			// case 'rapor_sd_2020_ganjil':
			// $pagesnya = '2020_ganjil';
			// break;
			// case 'rapor_sd_2020_genap':
			// $pagesnya = '2020_genap';
			// break;
			// case 'rapor_sd_2021_ganjil':
			// $pagesnya = '2021_ganjil';
			// break;
			
			default:
			$pagesnya = 'pages';
			break;
		}

		$kenaikan = "Berdasarkan pencapaian kompetensi pada semester ke-1 dan ke-2, peserta didik *) <br>Tidak Naik dan tetap di kelas ".$kelas->kelas.' ('.Convert::terbilang($kelas->kelas).')';
		if(isset($ekskul->kenaikan_kelas) && $ekskul->kenaikan_kelas==true){
			$kenaikan = "Berdasarkan pencapaian kompetensi pada semester ke-1 dan ke-2, peserta didik *) <br>Naik ke kelas ".($kelas->kelas+1).' ('.Convert::terbilang(($kelas->kelas+1)).')';
		}

		$data = [
			'siswa'=>$siswa,
			'sikap'=>$sikap,
			'kelas'=>$kelas,
			'semester'=>($semester=='genap') ? 'II (Dua)' : 'I (Satu)',
			'tahun_ajaran'=>$tahun_ajaran.'/'.($tahun_ajaran+1),
			'walikelas'=>$walikelas,
			'nilaia'=>$nilaia,
			'nilaib'=>$nilaib,
			'nilai_agama'=>$nilai_agama,
			'ekskul'=>$ekskul,
			'qrcode'=>$qrcode,
			'pagesnya'=>$pagesnya,
			'kenaikan'=>$kenaikan,
			'prestasi'=>$prestasi,
			'rapor_semester'=>$rapor_semester,
			'ks'=>$ks,
			'foto'=>Session::get('foto'),
		];

		$content = view('siswa.rapor.data',$data)->render();

		return $content;
	}

	function data_smp(Request $request){
		$jenjang = Session::get('jenjang');
		$nama_schema = $request->ta;
		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);
		$siswa = DB::connection($conn)->table('public.siswa as s')->join('public.sekolah as sek','sek.npsn','s.npsn')
		->leftjoin('public.kecamatan as kec','kec.kecamatan_kode','sek.kec_id')
		->leftjoin('public.kelurahan as kel','kel.kelurahan_kode','sek.desa')
		->leftjoin('public.agama as a','a.aga_id','s.aga_id')
		->leftjoin('public.wali_murid as wm',function($join){
			return $join->on('wm.id_siswa','=','s.id_siswa')->on('wm.npsn','=','s.npsn');
		})
		->leftjoin('public.pekerjaan as pa','pa.kode','wm.pekerjaan_ayah')
		->leftjoin('public.pekerjaan as pi','pi.kode','wm.pekerjaan_ibu')
		->leftjoin('public.pekerjaan as pw','pw.kode','wm.pekerjaan_wali')
		->selectRaw("s.nama as nama_siswa,s.tgl_lahir,s.nisn,s.nis,s.tempat_lahir,s.kelas,s.rombel,sek.kepala,sek.email as email_sekolah,sek.website as website_sekolah,s.id_siswa,sek.nama as nama_sekolah,sek.alamat as alamat_sekolah,kec.kecamatan_dispenduk,kel.kelurahan_dispenduk,s.kelamin,s.asal_sekolah,a.aga_nama,s.status_anak,s.anakke,s.alamat_ortu,s.telpon,s.alamat as alamat_siswa,wm.nama_ayah as ayah,wm.nama_ibu as ibu,wm.nama_wali,wm.pekerjaan_wali,pa.nama as pekerjaan_ayah,pi.nama as pekerjaan_ibu,pw.nama as pekerjaan_wali,wm.alamat_rumah,wm.rt,wm.rw,sek.kkm,sek.nss")
		->where('s.nik',Session::get('nik'))->first();
		$siswa_id = $siswa->id_siswa;

		$kelas = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->whereRaw("id_siswa='$siswa_id'")->orderBy('mapel_id','ASC')->first();

		if(!empty($kelas)){		
			$sikap = Hitung_sikap::nilai_sikap($siswa_id,$kelas->kelas,$kelas->rombel,$nama_schema,Session::get('npsn'));

			$nilaia = DB::connection($conn)->table($nama_schema.'.nilai_akhir as na')
			->leftjoin($nama_schema.'.mengajar as m',function($join){
				return $join->on('m.mapel_id','=','na.mapel_id')->on('m.npsn','=','na.npsn')->on('m.kelas','=','na.kelas')->on('m.rombel','=','na.rombel');
			})
			->leftjoin('public.pegawai as peg',function($join){
				return $join->on('peg.user_rapor','=','m.nip')->on('peg.npsn','=','m.npsn');
			})
			->selectRaw("*")->whereRaw("na.id_siswa='$siswa_id' AND na.kategori IN ('KELOMPOK A','WAJIB') AND m.mapel_id!='1'")->orderBy('na.urutan','ASC')->get();

			$nilaib = DB::connection($conn)->table($nama_schema.'.nilai_akhir as na')
			->leftjoin($nama_schema.'.mengajar as m',function($join){
				return $join->on('m.mapel_id','=','na.mapel_id')->on('m.npsn','=','na.npsn')->on('m.kelas','=','na.kelas')->on('m.rombel','=','na.rombel');
			})
			->leftjoin('public.pegawai as peg',function($join){
				return $join->on('peg.user_rapor','=','m.nip')->on('peg.npsn','=','m.npsn');
			})
			->selectRaw("*")->whereRaw("na.id_siswa='$siswa_id' AND na.kategori IN ('KELOMPOK B','MUATAN LOKAL') AND m.mapel_id!='1'")->orderBy('na.urutan','ASC')->get();

			$nilai_agama = DB::connection($conn)->table($nama_schema.'.nilai_akhir as na')
			->leftjoin($nama_schema.'.mengajar as m',function($join){
				return $join->on('m.mapel_id','=','na.mapel_id')->on('m.npsn','=','na.npsn')->on('m.kelas','=','na.kelas')->on('m.rombel','=','na.rombel');
			})
			->leftjoin('public.pegawai as peg',function($join){
				return $join->on('peg.user_rapor','=','m.nip')->on('peg.npsn','=','m.npsn');
			})
			->selectRaw("*")->whereRaw("na.id_siswa='$siswa_id' AND na.kategori IN ('AGAMA ISLAM') AND m.mapel_id!='1'")->orderBy('na.urutan','ASC')->get();

			$walikelas = DB::connection($conn)->table($nama_schema.'.walikelas as wk')
			->leftjoin('public.pegawai as p',function($join){
				return $join->on('wk.nip','=','p.user_rapor')->on('wk.npsn','=','p.npsn');
			})
			->leftjoin('public.gelar_akademik as gd',function($join){
				return $join->on('p.gelar','=',DB::raw('CAST(gd.gelar_akademik_id as varchar)'));
			})
			->leftjoin('public.gelar_akademik as gb',function($join){
				return $join->on('p.gelar2','=',DB::raw('CAST(gb.gelar_akademik_id as varchar)'));
			})
			->selectRaw("p.nama as nama_wk,gd.kode as gelar_depan,gb.kode as gelar_belakang,CONCAT('NIP. ',p.nip) as nip")
			->whereRaw("wk.npsn='".Session::get('npsn')."' AND wk.kelas='$kelas->kelas' AND wk.rombel='$kelas->rombel'")->first();
			$ekskul = DB::connection($conn)->table($nama_schema.'.ekskul_absen')->whereRaw("npsn='".Session::get('npsn')."' AND id_siswa='$siswa_id' AND kelas='$kelas->kelas' AND rombel='$kelas->rombel'")->first();

			$prestasi = DB::connection($conn)->table('public.prestasi_siswa')->whereRaw("id_siswa='$siswa_id' AND npsn='".Session::get('npsn')."'")->get();

			$ks = DB::connection($conn)->table('public.pegawai as p')
			->leftjoin('public.gelar_akademik as gd',function($join){
				return $join->on('p.gelar','=',DB::raw('CAST(gd.gelar_akademik_id as varchar)'));
			})
			->leftjoin('public.gelar_akademik as gb',function($join){
				return $join->on('p.gelar2','=',DB::raw('CAST(gb.gelar_akademik_id as varchar)'));
			})
			->selectRaw("p.nama as nama_ks,gd.kode as gelar_depan,gb.kode as gelar_belakang,CONCAT('NIP. ',p.nip) as nip")
			->whereRaw("(npsn='".Session::get('npsn')."' or sekolah_plt='".Session::get('npsn')."') AND jabatan='2' AND keterangan='Aktif'")->first();

			if($jenjang=='SD'){
				$tahun_ajaran = substr($nama_schema,9,4);
				$semester = substr($nama_schema,14);
			}else{
				$tahun_ajaran = substr($nama_schema,10,4);
				$semester = substr($nama_schema,15);
			}

			$rapor_semester = DB::connection($conn)->table('public.rapor_semester')->whereRaw("semester='".$tahun_ajaran."_".$semester."'")->first();

			$qrcode = QrCode::size(50)->generate($request->url());

			switch ($nama_schema) {
			// case 'rapor_sd_2017_ganjil':
			// $pagesnya = '2017_ganjil';
			// break;
			// case 'rapor_sd_2017_genap':
			// $pagesnya = '2017_genap';
			// break;
			// case 'rapor_sd_2018_ganjil':
			// $pagesnya = '2018_ganjil';
			// break;
			// case 'rapor_sd_2018_genap':
			// $pagesnya = '2018_genap';
			// break;
			// case 'rapor_sd_2019_ganjil':
			// $pagesnya = '2019_ganjil';
			// break;
			// case 'rapor_sd_2019_genap':
			// $pagesnya = '2019_genap';
			// break;
			// case 'rapor_sd_2020_ganjil':
			// $pagesnya = '2020_ganjil';
			// break;
			// case 'rapor_sd_2020_genap':
			// $pagesnya = '2020_genap';
			// break;
			// case 'rapor_sd_2021_ganjil':
			// $pagesnya = '2021_ganjil';
			// break;

				default:
				$pagesnya = 'pages_smp';
				break;
			}

			$kenaikan = "Berdasarkan pencapaian kompetensi pada semester ke-1 dan ke-2, peserta didik *) <br>Tidak Naik dan tetap di kelas ".$kelas->kelas.' ('.Convert::terbilang($kelas->kelas).')';
			if(isset($ekskul->kenaikan_kelas) && $ekskul->kenaikan_kelas==true){
				$kenaikan = "Berdasarkan pencapaian kompetensi pada semester ke-1 dan ke-2, peserta didik *) <br>Naik ke kelas ".($kelas->kelas+1).' ('.Convert::terbilang(($kelas->kelas+1)).')';
			}

			$data = [
				'siswa'=>$siswa,
				'sikap'=>$sikap,
				'kelas'=>$kelas,
				'semester'=>($semester=='genap') ? 'II (Dua)' : 'I (Satu)',
				'tahun_ajaran'=>$tahun_ajaran.'/'.($tahun_ajaran+1),
				'walikelas'=>$walikelas,
				'nilaia'=>$nilaia,
				'nilaib'=>$nilaib,
				'nilai_agama'=>$nilai_agama,
				'ekskul'=>$ekskul,
				'qrcode'=>$qrcode,
				'pagesnya'=>$pagesnya,
				'kenaikan'=>$kenaikan,
				'prestasi'=>$prestasi,
				'rapor_semester'=>$rapor_semester,
				'ks'=>$ks,
				'foto'=>Session::get('foto'),
			];

			$content = view('siswa.rapor.data',$data)->render();
		}else{
			$content = 'Maaf data belum ada';	
		}

		return $content;
	}
}
