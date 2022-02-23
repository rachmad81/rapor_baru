<?php

namespace App\Http\Controllers\Ks\Rapor;

use App\Http\Controllers\Controller;

use Session;

class KunciController extends Controller
{
	function main(){
		$data = [
			'main_menu'=>'kunci_rapor',
			'sub_menu'=>'',
		];

		return view('ks.rapor.kunci.index',$data);
	}
}
