<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;

use Session,Redirect,DB;

class PasswordController extends Controller
{
	function main(){
		return view('password.index');
	}

	function reset_password(Request $request){
		Session::put('ubah_password',false);
		return true;
		// return $request->all();
	}
}
