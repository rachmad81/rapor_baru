<?php

namespace App\Http\Controllers\Ks\Rapor;

use App\Http\Controllers\Controller;

use Session;

class BukaController extends Controller
{
	function main(){
		$data = [
			'main_menu'=>'buka_rapor',
			'sub_menu'=>'',
		];

		return view('ks.rapor.buka.index',$data);
	}
}
