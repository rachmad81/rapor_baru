<?php

namespace App\Http\Controllers\Guru\Walikelas;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use App\Http\Controllers\Controller;

use DB,Session;

class WalikelasController extends Controller
{
	function main(){
		$tahun_ajaran = Setkoneksi::tahun_ajaran();

		$data = [
			'main_menu'=>'walikelas',
			'sub_menu'=>'',
			'tahun_ajaran'=>$tahun_ajaran,
		];

		return view('guru.walikelas.index',$data);
	}

	function get_rombel(Request $request){
		$ta = $request->ta;
		$npsn = Session::get('npsn');
		$user_rapor = Session::get('user_rapor');
		$nik = Session::get('nik');
		Session::put('nama_schema',$ta);

		$nama_schema = $ta;

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$walikelas = DB::connection($conn)->table($nama_schema.'.walikelas')->whereRaw("npsn='$npsn' AND nip='$user_rapor'")->get();

		$mengajar = DB::connection($conn)->table($nama_schema.'.mengajar as m')
		->leftjoin('public.rapor_mapel as ma','ma.mapel_id','=','m.mapel_id')
		->leftjoin('public.rombongan_belajar as rb',function($join){
			return $join->on('rb.kelas','=','m.kelas')->on('rb.rombel','=','m.rombel')->on('rb.npsn','=','m.npsn');
		})
		->selectRaw("m.mapel_id,m.kelas,m.rombel,ma.nama as nama_mapel")
		->whereRaw("m.nip='$user_rapor' AND m.npsn='$npsn'")->get();

		$data = [
			'walikelas'=>$walikelas,
			'mengajar'=>$mengajar,
		];

		$content = view('guru.walikelas.data_sekolah',$data)->render();

		return ['content'=>$content];
	}
}
