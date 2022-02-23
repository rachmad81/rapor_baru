<?php

namespace App\Http\Controllers\Ks\Datamaster;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Get_data;
use App\Http\Libraries\Setkoneksi;

use Illuminate\Http\Request;
use Session,DB;

class GuruController extends Controller
{
	function main(){
		$data = [
			'main_menu'=>'master_guru',
			'sub_menu'=>'',
		];

		return view('ks.data_master.guru.index',$data);
	}

	function get_data(){
		$guru = Get_data::get_guru();

		if($guru->count()!=0){
			foreach($guru as $v){
				$v->aksi = '<div class="btn-group">'.
				'<button type="button" class="btn btn-default">Action</button>'.
				'<button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="true">'.
				'<span class="sr-only">Toggle Dropdown</span>'.
				'</button>'.
				'<div class="dropdown-menu" role="menu" style="position: absolute; transform: translate3d(68px, 38px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-start">'.
				'<a class="dropdown-item" href="javascript:void(0)" onclick="reset_password(\''.$v->user_rapor.'\')"><i class="fa fa-lock"></i> Reset Password</a>'.
				'<a class="dropdown-item" href="javascript:void(0)" onclick="form(\''.$v->user_rapor.'\')"><i class="fa fa-user"></i> Ubah User</a>'.
				'</div>'.
				'</div>';
			}
		}

		return ['data'=>$guru];
	}

	function get_form(Request $request){
		$peg_id = $request->id;
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$guru = DB::connection($conn)->table('public.pegawai')->where('user_rapor',$peg_id)->first();
		$data = [
			'title'=>'Ubah user rapor',
			'guru'=>$guru,
		];
		$content = view('ks.data_master.guru.form',$data)->render();

		return ['content'=>$content];
	}

	function simpan(Request $request){
		$user_baru = $request->user_baru; // "1234565446"
		$user_lama = $request->user_lama; // "2146754657300003"
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$guru = DB::connection($conn)->table('public.pegawai')->where('user_rapor',$user_lama)->first();
		$return = ['code'=>'250','message'=>'Tidak ditemukan'];

		if(!empty($guru)){
			$simpan = DB::connection($conn)->table('public.pegawai')->where('user_rapor',$user_lama)->update(['user_rapor'=>$user_baru]);
			if($simpan){
				$return = ['code'=>'200','message'=>'Berhasil diubah'];
			}else{
				$return = ['code'=>'250','message'=>'Gagal diubah'];
			}
		}

		return $return;
	}
}
