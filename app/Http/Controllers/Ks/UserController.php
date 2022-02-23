<?php

namespace App\Http\Controllers\Ks;

use App\Http\Controllers\Controller;

use Session;

class UserController extends Controller
{
	function main(){
		$data = [
			'main_menu'=>'user_ks',
			'sub_menu'=>'',
		];

		return view('ks.user.index',$data);
	}
}
