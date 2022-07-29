<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use App\Http\Controllers\Controller;

use DB,Session;

class TaController extends Controller
{
	protected $schema;

    public function __construct() 
    {
        $this->schema = env('CURRENT_SCHEMA','production');
    }
    
	function main(){

		$data = [
			'main_menu'=>'tahun-ajaran',
			'sub_menu'=>'',
		];

		return view('admin.tahun_ajaran.index',$data);
	}

	function form(Request $request){
		$id = $request->id;

		$title = ($id=='0') ? 'Tambah' : 'Edit';

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$ta = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->where('id_tahun_ajaran',$id)->first();

		$data = [
			'title'=>$title,
			'ta'=>$ta,
		];

		$content = view('admin.tahun_ajaran.form',$data)->render();

		return ['content'=>$content];
	}

	function simpan(Request $request){
		$data_input = $request->all();
		$id = $request->id_tahun_ajaran;

		if($id=='0'){
			$data_input = array_merge($data_input,['created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')]);
			unset($data_input['id_tahun_ajaran']);
		}else{
			$data_input = array_merge($data_input,['updated_at'=>date('Y-m-d H:i:s')]);
		}
		$data_input['tgl_setting_awal'] = $request->tgl_setting_awal.' 00:00:00';
		$data_input['tgl_setting_akhir'] = $request->tgl_setting_akhir.' 23:59:59';

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$get = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->where('id_tahun_ajaran',$id)->first();
		if(!empty($get)){
			$simpan = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->where('id_tahun_ajaran',$id)->update($data_input);
		}else{
			$simpan = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->insert($data_input);
		}

		if($simpan){
			$result = ['code'=>'200'];
		}else{
			$result = ['code'=>'250'];
		}

		return $result;
	}

	function get_data(Request $request){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$tahun_ajaran = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->orderByRaw("nama_tahun_ajaran ASC")->get();
		if($tahun_ajaran->count()!=0){
			foreach($tahun_ajaran as $ta){
				$ta->awal = date('d-m-Y',strtotime($ta->tgl_setting_awal));
				$ta->akhir = date('d-m-Y',strtotime($ta->tgl_setting_akhir));
				$aksi = '<a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="form_data(\''.$ta->id_tahun_ajaran.'\')">Edit</a>';
				$aksi .= ' <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="hapus(\''.$ta->id_tahun_ajaran.'\')">Hapus</a>';
				$ta->aksi = $aksi;
			}
		}

		return ['data'=>$tahun_ajaran];
	}

	function hapus(Request $request){
		$id = $request->id;

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$hapus = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->where('id_tahun_ajaran',$id)->delete();

		if($hapus){
			$result = ['code'=>'200'];
		}else{
			$result = ['code'=>'250'];
		}

		return $result;
	}
}
