<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use Session,Redirect,DB;

class SessionController extends Controller
{
	function set_tahun_ajaran(Request $request){
		$nama_schema = $request->ta;
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$npsn = Session::get('npsn');
		$kelas_rombel_now = DB::connection($conn)->table('public.siswa')->selectRaw("kelas,rombel")->whereRaw("npsn='$npsn' AND alumni is not true and status_siswa='Aktif'")->groupByRaw("kelas,rombel");
		$kelas_rombel = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->selectRaw("kelas,rombel")->whereRaw("npsn='$npsn'")->union($kelas_rombel_now)->groupBy('kelas','rombel')->orderByRaw("kelas ASC,rombel ASC")->get();
		return $kelas_rombel;
	}

	function get_mapel_by_kategori(Request $request){
		$mapel = Get_data::get_rapor_mapel_by_kategori($request);

		return $mapel;
	}
}
