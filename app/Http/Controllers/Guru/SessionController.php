<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use DB,Session;

class SessionController extends Controller
{
	function set_npsn(Request $request){
		$npsn = $request->npsn;
		$nama_sekolah = $request->nama;
		$jabatan = $request->jabatan;

		Session::put('npsn',$npsn);
		Session::put('nama_sekolah',$nama_sekolah);
		Session::put('jabatan',$jabatan);

		$return = true;

		return $return;
	}
}
