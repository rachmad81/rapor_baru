<?php

namespace App\Http\Controllers\Guru\Mapel;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;

use App\Http\Controllers\Controller;

use DB,Session;

class MapelController extends Controller
{
	function main(){

		$data = [
			'main_menu'=>'mapel',
			'sub_menu'=>'',
		];

		return view('guru.data_master.mapel.index',$data);
	}

	function form(Request $request){
		$id = $request->id;

		$title = ($id=='0') ? 'Tambah' : 'Edit';

		$data = [
			'title'=>$title,
		];

		$content = view('guru.data_master.mapel.form',$data)->render();

		return ['content'=>$content];
	}

	function simpan(Request $request){
		return $request->all();
	}
}
