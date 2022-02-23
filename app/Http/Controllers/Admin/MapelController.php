<?php

namespace App\Http\Controllers\Admin;

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

		return view('admin.mapel.index',$data);
	}

	function form(Request $request){
		$id = $request->id;

		$title = ($id=='0') ? 'Tambah' : 'Edit';

		$data = [
			'title'=>$title,
		];

		$content = view('admin.mapel.form',$data)->render();

		return ['content'=>$content];
	}

	function simpan(Request $request){
		return $request->all();
	}

	function get_data(Request $request){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$mapel = DB::connection($conn)->table('public.rapor_mapel')->orderByRaw("kategori ASC,nama ASC")->get();

		return ['data'=>$mapel];
	}
}
