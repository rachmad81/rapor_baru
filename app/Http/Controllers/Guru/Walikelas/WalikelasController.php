<?php

namespace App\Http\Controllers\Guru\Walikelas;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use App\Http\Controllers\Controller;
use Excel;

use DB,Session;

class WalikelasController extends Controller
{
	protected $schema;

	public function __construct() 
	{
		$this->schema = env('CURRENT_SCHEMA','production');
	}

	function main(){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$tahun_ajaran = DB::connection($conn)->table('public.tahun_ajaran')->orderBy('nama_tahun_ajaran')->get();

		$data = [
			'main_menu'=>'walikelas',
			'sub_menu'=>'',
			'tahun_ajaran'=>$tahun_ajaran,
		];

		return view('guru.walikelas.index',$data);
	}

	function get_rombel(Request $request){
		$ta = $request->ta;
		$semester = $request->semester;
		$npsn = Session::get('npsn');
		$user_rapor = Session::get('user_rapor');
		$nik = Session::get('nik');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		if($nik==''){
			$pegawai = DB::connection($conn)->table('public.pegawai')->whereRaw("user_rapor='$user_rapor' AND nik is null AND npsn='$npsn'")->first();
		}else{
			$pegawai = DB::connection($conn)->table('public.pegawai')->whereRaw("user_rapor='$user_rapor' AND nik='$nik' AND npsn='$npsn'")->first();
		}

		if(!empty($pegawai)){
			if($nik==''){
				$walikelas = DB::connection($conn)->table('public.rombongan_belajar')->whereRaw("npsn='$npsn' AND nik_wk is null AND wali_kelas_peg_id='$pegawai->peg_id' AND tahun_ajaran_id='$ta' AND semester='$semester'")->orderByRaw("kelas ASC,rombel ASC")->get();
				$mengajar = DB::connection($conn)->table($this->schema.'.mengajar as m')
				->join('public.rombongan_belajar as rb','rb.id_rombongan_belajar','m.rombel_id')
				->join('public.rapor_mapel as ma','ma.mapel_id','m.mapel_id')
				->selectRaw("*,ma.nama as nama_mapel")
				->whereRaw("rb.npsn='$npsn' AND m.nik_pengajar is null AND m.peg_id='$pegawai->peg_id' AND rb.tahun_ajaran_id='$ta' AND rb.semester='$semester'")
				->orderByRaw("rb.kelas ASC,rb.rombel ASC,ma.nama ASC")
				->get();
			}else{
				$walikelas = DB::connection($conn)->table('public.rombongan_belajar')->whereRaw("npsn='$npsn' AND nik_wk='$pegawai->nik' AND wali_kelas_peg_id='$pegawai->peg_id' AND tahun_ajaran_id='$ta' AND semester='$semester'")->orderByRaw("kelas ASC,rombel ASC")->get();
				$mengajar = DB::connection($conn)->table($this->schema.'.mengajar as m')
				->join('public.rombongan_belajar as rb','rb.id_rombongan_belajar','m.rombel_id')
				->join('public.rapor_mapel as ma','ma.mapel_id','m.mapel_id')
				->selectRaw("*,ma.nama as nama_mapel")
				->whereRaw("rb.npsn='$npsn' AND m.nik_pengajar='$pegawai->nik' AND m.peg_id='$pegawai->peg_id' AND rb.tahun_ajaran_id='$ta' AND rb.semester='$semester'")
				->orderByRaw("rb.kelas ASC,rb.rombel ASC,ma.nama ASC")
				->get();
			}


			$data = [
				'walikelas'=>$walikelas,
				'mengajar'=>$mengajar,
			];

			$content = view('guru.walikelas.data_sekolah',$data)->render();

		}else{
			$content = '<h1>Data tidak ditemukan</h1>';
		}
		return ['content'=>$content];
	}
}
