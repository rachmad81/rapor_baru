<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;

use Session;

class DashboardController extends Controller
{
	function main(){
		$data = [
			'main_menu'=>'dashboard',
			'sub_menu'=>'',
		];

		return view('guru.dashboard.index',$data);
	}
}
