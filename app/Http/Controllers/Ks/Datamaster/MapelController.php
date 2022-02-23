<?php

namespace App\Http\Controllers\Ks\Datamaster;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use App\Http\Controllers\Controller;

use DB,Session;

class MapelController extends Controller
{
	function main(){

		$data = [
			'main_menu'=>'mapel',
			'sub_menu'=>'',
		];

		return view('ks.data_master.mapel.index',$data);
	}

	function get_data(){
		$mapel = Get_data::get_mapel();

		return ['data'=>$mapel];
	}
}
