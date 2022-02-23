<?php

namespace App\Http\Controllers\Ks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Libraries\Setkoneksi;

use Session,DB;

class ResetController extends Controller
{
	function main(){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$npsn = Session::get('npsn');

		$data = [
			'main_menu'=>'reset_password',
			'sub_menu'=>'',
			'pegawai'=>DB::connection($conn)->table('public.pegawai')->whereRaw("npsn='$npsn' AND keterangan='Aktif' AND jabatan!='2'")->get(),
		];

		return view('ks.reset.main',$data);
	}
	function reset(Request $request){
		$guru = $request->guru;
		$semua = (isset($request->semua)) ? $request->semua : '';

		$npsn = Session::get('npsn');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		if($semua!=''){
			$simpan = DB::connection($conn)->table('public.pegawai')->whereRaw("npsn='$npsn'")->update(['passwds'=>md5('123456')]);
		}else{
			$simpan = DB::connection($conn)->table('public.pegawai')->whereRaw("npsn='$npsn' AND user_rapor='$guru'")->update(['passwds'=>md5('123456')]);
		}

		if($simpan){
			$return = ['code'=>'200','message'=>'Berhasil direset'];
		}else{
			$return = ['code'=>'250','message'=>'Gagal direset'];
		}

		return $return;
	}
}
