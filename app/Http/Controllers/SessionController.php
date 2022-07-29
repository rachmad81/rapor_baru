<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use Session,Redirect,DB;

class SessionController extends Controller
{
	function set_tahun_ajaranOld(Request $request){
		$nama_schema = $request->ta;
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$npsn = Session::get('npsn');
		$kelas_rombel_now = DB::connection($conn)->table('public.siswa')->selectRaw("kelas,rombel")->whereRaw("npsn='$npsn' AND alumni is not true and status_siswa='Aktif'")->groupByRaw("kelas,rombel");
		$kelas_rombel = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->selectRaw("kelas,rombel")->whereRaw("npsn='$npsn'")->union($kelas_rombel_now)->groupBy('kelas','rombel')->orderByRaw("kelas ASC,rombel ASC")->get();
		return $kelas_rombel;
	}

	function set_tahun_ajaran(Request $request){
		$id = $request->ta;
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$ta = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->where('id_tahun_ajaran',$id)->first();
		$now = date('Y-m-d');
		$npsn = Session::get('npsn');

		$code = '200';
		if($now>=date('Y-m-d',strtotime($ta->tgl_setting_awal)) && $now<=date('Y-m-d',strtotime($ta->tgl_setting_akhir))){
			$kelas = DB::connection($conn)->table('public.siswa')->selectRaw("npsn,kelas,rombel")->whereRaw("npsn='$npsn' AND status_siswa='Aktif' AND alumni is not true")->groupByRaw("npsn,kelas,rombel")->orderByRaw("kelas ASC,rombel ASC")->get();
		}else{
			if(date('Y-m-d',strtotime($ta->tgl_setting_awal)) > $now){
				$kelas = ['message'=>'Tahun ajaran belum di buka'];
				$code = '250';
			}else{
				$kelas = DB::connection($conn)->table('public.siswa')->selectRaw("npsn,kelas,rombel")->whereRaw("npsn='$npsn' AND status_siswa='Aktif' AND alumni is not true")->groupByRaw("npsn,kelas,rombel")->orderByRaw("kelas ASC,rombel ASC")->get();
			}
		}

		$result = [
			'code'=>$code,
			'kelas'=>$kelas,
		];

		return $result;
	}

	function get_rombel(Request $request){
		$id = $request->ta;
		$semester = $request->semester;
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);
		$npsn = Session::get('npsn');

		$rb = DB::connection($conn)->table('public.rombongan_belajar')->whereRaw("npsn='$npsn'AND tahun_ajaran_id='$id' AND semester='$semester'")->orderByRaw("kelas asc,rombel asc")->get();

		$code = '200';

		$result = [
			'code'=>$code,
			'kelas'=>$rb,
		];

		return $result;
	}

	function get_mapel_by_kategori(Request $request){
		$mapel = Get_data::get_rapor_mapel_by_kategori($request);

		return $mapel;
	}
}
