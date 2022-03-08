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
	protected $schema;

	public function __construct() 
	{
		$this->schema = env('CURRENT_SCHEMA','production');
	}

	function main(){
		$jenjang = Session::get('jenjang');

		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.siswa as s')->join('public.sekolah as sek','sek.npsn','s.npsn')->selectRaw("*,sek.nama as nama_sekolah,sek.alamat as alamat_sekolah")->whereRaw("s.nik='".Session::get('nik')."' AND s.status_siswa='Aktif' AND (s.alumni is not true OR s.alumni is null)")->first();

		$kelas = $siswa->kelas;

		$tahun_ajaran = [];

		$tahun_ajaran = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.rombongan_belajar as rb','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.tahun_ajaran as ta','ta.id_tahun_ajaran','rb.tahun_ajaran_id')
		->selectRaw("ta.nama_tahun_ajaran,rb.semester,rb.kelas,rb.rombel,ta.id_tahun_ajaran,ar.id_anggota_rombel,CASE WHEN (rb.semester='1') THEN 'Ganjil' ELSE 'Genap' END as nama_semester")
		->whereRaw("siswa_id='$siswa->id_siswa'")
		->orderByRaw("ta.nama_tahun_ajaran ASC,rb.semester ASC")->get();

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
			$content = $this->data_sd($request);
		}

		return ['content'=>$content];
	}

	function data_sd(Request $request){
		$id_anggota_rombel = $request->ta;

		$npsn = Session::get('npsn');
		$jenjang = Session::get('jenjang');
		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->join('public.sekolah as sek','sek.npsn','s.npsn')
		->leftjoin('public.kecamatan as kec','kec.kecamatan_kode','sek.kec_id')
		->leftjoin('public.kelurahan as kel','kel.kelurahan_kode','sek.desa')
		->leftjoin('public.agama as a','a.aga_id','s.aga_id')
		->leftjoin('public.wali_murid as wm',function($join){
			return $join->on('wm.id_siswa','=','s.id_siswa')->on('wm.npsn','=','s.npsn');
		})
		->leftjoin('public.pekerjaan as pa','pa.kode','wm.pekerjaan_ayah')
		->leftjoin('public.pekerjaan as pi','pi.kode','wm.pekerjaan_ibu')
		->leftjoin('public.pekerjaan as pw','pw.kode','wm.pekerjaan_wali')
		->leftjoin('public.rombongan_belajar as rb','rb.id_rombongan_belajar','ar.rombongan_belajar_id')
		->leftjoin('public.tahun_ajaran as ta','ta.id_tahun_ajaran','rb.tahun_ajaran_id')
		->selectRaw("s.foto,s.nama as nama_siswa,s.tgl_lahir,s.nisn,s.nis,s.tempat_lahir,rb.kelas,rb.rombel,sek.kepala,sek.email as email_sekolah,sek.website as website_sekolah,s.id_siswa,sek.nama as nama_sekolah,sek.alamat as alamat_sekolah,kec.kecamatan_dispenduk,kel.kelurahan_dispenduk,s.kelamin,s.asal_sekolah,a.aga_nama,s.status_anak,s.anakke,s.alamat_ortu,s.telpon,s.alamat as alamat_siswa,wm.nama_ayah as ayah,wm.nama_ibu as ibu,wm.nama_wali,wm.pekerjaan_wali,pa.nama as pekerjaan_ayah,pi.nama as pekerjaan_ibu,pw.nama as pekerjaan_wali,wm.alamat_rumah,wm.rt,wm.rw,sek.kkm,sek.nss,ta.tgl_setting_awal,ta.tgl_setting_akhir,ar.id_anggota_rombel")
		->whereRaw("ar.id_anggota_rombel='$id_anggota_rombel'")->first();
		$id_siswa = $siswa->id_siswa;

		$tahun_ajaran = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.rombongan_belajar as rb','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.tahun_ajaran as ta','ta.id_tahun_ajaran','rb.tahun_ajaran_id')
		->selectRaw("ta.nama_tahun_ajaran,rb.semester,rb.id_rombongan_belajar,rb.kelas,rb.rombel,ta.id_tahun_ajaran,ar.id_anggota_rombel,CASE WHEN (rb.semester='1') THEN 'Ganjil' ELSE 'Genap' END as nama_semester")
		->whereRaw("ar.id_anggota_rombel='$id_anggota_rombel'")
		->orderByRaw("ta.nama_tahun_ajaran ASC,rb.semester ASC")->first();

		$id_rombel = $tahun_ajaran->id_rombongan_belajar;
		$semester = $tahun_ajaran->nama_semester;
		$tahun_ajaran = $tahun_ajaran->nama_tahun_ajaran;

		$ks = DB::connection($conn)->table('public.pegawai as p')
		->leftjoin('public.gelar_akademik as gd',function($join){
			return $join->on('p.gelar','=',DB::raw('CAST(gd.gelar_akademik_id as varchar)'));
		})
		->leftjoin('public.gelar_akademik as gb',function($join){
			return $join->on('p.gelar2','=',DB::raw('CAST(gb.gelar_akademik_id as varchar)'));
		})
		->selectRaw("p.nama as nama_ks,gd.kode as gelar_depan,gb.kode as gelar_belakang,CONCAT('NIP. ',p.nip) as nip")
		->whereRaw("(npsn='".Session::get('npsn')."' or sekolah_plt='".Session::get('npsn')."') AND jabatan='2' AND keterangan='Aktif'")->first();

		$qrcode = QrCode::size(50)->generate($request->url());

		$sikap = DB::connection($conn)->table($this->schema.'.nilai_perilaku')
		->whereRaw("anggota_rombel_id='$siswa->id_anggota_rombel' AND npsn='$npsn'")->first();
		
		$nilaia = DB::connection($conn)->table($this->schema.'.nilai_mapel as na')
		->leftjoin($this->schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id');
		})
		->leftjoin('public.rapor_mapel as ma',function($join){
			return $join->on('ma.mapel_id','=','na.mapel_id');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.nik','=','m.nik_pengajar')->on('peg.peg_id','=','m.peg_id');
		})
		->selectRaw("ma.nama as mapel,peg.nama as guru_mengajar,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4,75 as kkm")
		->whereRaw("na.anggota_rombel_id='$siswa->id_anggota_rombel' AND ma.kategori IN ('KELOMPOK A','WAJIB','A. MATA PELAJARAN')")
		->groupByRaw("ma.nama,peg.nama,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4")
		->orderBy('ma.nama','ASC')->get();

		$nilaib = DB::connection($conn)->table($this->schema.'.nilai_mapel as na')
		->leftjoin($this->schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id');
		})
		->leftjoin('public.rapor_mapel as ma',function($join){
			return $join->on('ma.mapel_id','=','na.mapel_id');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.nik','=','m.nik_pengajar')->on('peg.peg_id','=','m.peg_id');
		})
		->selectRaw("ma.nama as mapel,peg.nama as guru_mengajar,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4,75 as kkm")
		->whereRaw("na.anggota_rombel_id='$siswa->id_anggota_rombel' AND ma.kategori IN ('KELOMPOK B','MUATAN LOKAL')")
		->groupByRaw("ma.nama,peg.nama,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4")
		->orderBy('ma.nama','ASC')->get();

		$nilai_agama = DB::connection($conn)->table($this->schema.'.nilai_mapel as na')
		->leftjoin($this->schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id');
		})
		->leftjoin('public.rapor_mapel as ma',function($join){
			return $join->on('ma.mapel_id','=','na.mapel_id');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.nik','=','m.nik_pengajar')->on('peg.peg_id','=','m.peg_id');
		})
		->selectRaw("ma.nama as mapel,peg.nama as guru_mengajar,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4,75 as kkm")
		->whereRaw("na.anggota_rombel_id='$siswa->id_anggota_rombel' AND ma.kategori IN ('AGAMA ISLAM')")
		->groupByRaw("ma.nama,peg.nama,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4")
		->orderBy('ma.nama','ASC')->get();

		$prestasi = DB::connection($conn)->table('public.prestasi_siswa')->whereRaw("id_siswa='$siswa->id_siswa' AND npsn='".Session::get('npsn')."'")->get();
		
		$walikelas = DB::connection($conn)->table('public.rombongan_belajar as wk')
		->leftjoin('public.pegawai as p',function($join){
			return $join->on('wk.wali_kelas_peg_id','=',DB::raw("CAST(p.peg_id as varchar)"))->on('wk.nik_wk','=','p.nik');
		})
		->leftjoin('public.gelar_akademik as gd',function($join){
			return $join->on('p.gelar','=',DB::raw('CAST(gd.gelar_akademik_id as varchar)'));
		})
		->leftjoin('public.gelar_akademik as gb',function($join){
			return $join->on('p.gelar2','=',DB::raw('CAST(gb.gelar_akademik_id as varchar)'));
		})
		->selectRaw("p.nama as nama_wk,gd.kode as gelar_depan,gb.kode as gelar_belakang,CONCAT('NIP. ',p.nip) as nip")
		->whereRaw("wk.npsn='".Session::get('npsn')."' AND wk.id_rombongan_belajar='$id_rombel'")->first();

		//$ekskul = DB::connection($conn)->table($nama_schema.'.ekskul_absen')->whereRaw("npsn='".Session::get('npsn')."' AND id_siswa='$siswa_id' AND kelas='$kelas->kelas' AND rombel='$kelas->rombel'")->first();

		// $kenaikan = "Berdasarkan pencapaian kompetensi pada semester ke-1 dan ke-2, peserta didik *) <br>Tidak Naik dan tetap di kelas ".$kelas->kelas.' ('.Convert::terbilang($kelas->kelas).')';
		// if(isset($ekskul->kenaikan_kelas) && $ekskul->kenaikan_kelas==true){
		// 	$kenaikan = "Berdasarkan pencapaian kompetensi pada semester ke-1 dan ke-2, peserta didik *) <br>Naik ke kelas ".($kelas->kelas+1).' ('.Convert::terbilang(($kelas->kelas+1)).')';
		// }

		$data = [
			'qrcode'=>$qrcode,
			'siswa'=>$siswa,
			'ks'=>$ks,
			'semester'=>$semester,
			'tahun_ajaran'=>$tahun_ajaran,
			'pagesnya'=>($jenjang=='SD') ? 'pages' : 'pages_smp',
			'foto'=>'https://profilsekolah.dispendik.surabaya.go.id/profilsekolahlama/foto/siswa/'.$siswa->foto,
			'sikap'=>$sikap,
			'nilaia'=>$nilaia,
			'nilaib'=>$nilaib,
			'nilai_agama'=>$nilai_agama,
			'prestasi'=>$prestasi,
			'walikelas'=>$walikelas,
			'sisipan'=>'',
			'kenaikan'=>'$kenaikan',
		];
		$content = view('siswa.rapor.data',$data)->render();
		
		return $content;
	}
}
