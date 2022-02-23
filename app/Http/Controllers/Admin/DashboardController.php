<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Session;

class DashboardController extends Controller
{
	function main(){
		$data = [
			'main_menu'=>'dashboard',
			'sub_menu'=>'',
		];

		return view('admin.dashboard.admin',$data);
	}
}
