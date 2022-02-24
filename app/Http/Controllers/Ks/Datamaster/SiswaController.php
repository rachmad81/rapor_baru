<?php

namespace App\Http\Controllers\Ks\Datamaster;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Get_data;
use App\Http\Libraries\Setkoneksi;
use Illuminate\Http\Request;

use Session,DB,Redirect;

class SiswaController extends Controller
{
	function main(){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$tahun_ajaran = DB::connection($conn)->table('public.tahun_ajaran')->orderBy('nama_tahun_ajaran')->get();

		$data = [
			'main_menu'=>'master_siswa',
			'sub_menu'=>'',
			'tahun_ajaran'=>$tahun_ajaran,
		];

		return view('ks.data_master.siswa.index',$data);
	}

	function get_data(Request $request){
		$id = $request->tahun_ajaran;
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);
		$npsn = Session::get('npsn');

		$kelasrombel = explode('|||',$request->kelas);
		$kelas = (count($kelasrombel)>1) ? $kelasrombel[0] : '';
		$rombel = (count($kelasrombel)>1) ? $kelasrombel[1] : '';

		if($id!=null){
			$ta = DB::connection($conn)->table('public.tahun_ajaran')->where('id_tahun_ajaran',$id)->first();
			$now = date('Y-m-d');
			if($now>=date('Y-m-d',strtotime($ta->tgl_setting_awal)) && $now<=date('Y-m-d',strtotime($ta->tgl_setting_akhir))){
				$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND status_siswa='Aktif' AND alumni is not true")->get();
			}else{
				$siswa = [];
			}
		}else{
			$siswa = [];
		}

		return ['data'=>$siswa];
	}
}
