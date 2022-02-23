<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use App\Http\Controllers\Controller;

use DB,Session;

class KdController extends Controller
{
	function main(Request $request){
		$coni = new Request;
		$jenjang = Session::get('jenjang');
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		if($jenjang=='SD'){
			$nama_schema = env('CURREND_DB_SD', 'production');
		}else{
			$nama_schema = env('CURREND_DB_SMP', 'production');
		}

		$kelas = $request->kelas;

		$data = [
			'main_menu'=>'kd-'.$kelas,
			'sub_menu'=>'',
			'kelas'=>$kelas,
		];

		return view('admin.kd.index',$data);
	}

	function get_mapel(Request $request){
		$coni = new Request;
		$jenjang = Session::get('jenjang');
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		if($jenjang=='SD'){
			$nama_schema = env('CURREND_DB_SD', 'production');
		}else{
			$nama_schema = env('CURREND_DB_SMP', 'production');
		}

		$kelas = $request->kelas;
		$mapel = DB::connection($conn)->table('public.rapor_mapel')->whereRaw("is_aktif is true")->orderByRaw("kategori asc,nama asc")->get();
		if($mapel->count()!=0){
			foreach($mapel as $v){
				$kd = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("kelas='$kelas' AND mapel_id='".$v->mapel_id."'")->count();
				$v->kd = $kd;
			}
		}

		$data = [
			'kelas'=>$kelas,
			'mapel'=>$mapel,
		];

		$content = view('admin.kd.data_mapel',$data)->render();

		return ['content'=>$content];
	}

	function setting(Request $request){
		$id = $request->id;
		$kelas = $request->kelas;

		$title = ($id=='0') ? 'Tambah' : 'Edit';

		$data = [
			'title'=>$title,
			'mapel_id'=>$id,
			'kelas'=>$kelas,
		];

		$content = view('admin.kd.setting',$data)->render();

		return ['content'=>$content];
	}

	function simpan(Request $request){
		$kd = $request->kd; // "3"
		$mapel = $request->mapel; // "0"
		$kelas = $request->kelas; // "0"
		$uraian = $request->uraian; // "asdaf"
		$tempat = ($kd=='3') ? 'tempat_3' : 'tempat_4';

		$coni = new Request;
		$jenjang = Session::get('jenjang');
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		if($jenjang=='SD'){
			$nama_schema = env('CURREND_DB_SD', 'production');
		}else{
			$nama_schema = env('CURREND_DB_SMP', 'production');
		}

		$data = [
			'kelas'=>$kelas,
			'mapel_id'=>$mapel,
			'kd_id'=>$kd,
			'kd_isi'=>$uraian,
			'no_ki'=>$kd,
		];

		$where = $data;
		unset($where['kd_id']);
		unset($where['kd_isi']);

		$last_kd_id = DB::connection($conn)->table($nama_schema.'.master_kd')->where($where)->orderBy('kd_id','DESC')->first();
		if(!empty($last_kd_id)){
			$kd_id = $last_kd_id->kd_id+1;
		}else{
			$kd_id = 1;
		}
		$data['kd_id'] = $kd_id;

		$simpan = DB::connection($conn)->table($nama_schema.'.master_kd')->insert($data);
		if($simpan){
			return ['code'=>'200'];
		}else{
			return ['code'=>'250'];
		}
	}

	function get_kd(Request $request){
		$id = $request->id;
		$kelas = $request->kelas;

		$coni = new Request;
		$jenjang = Session::get('jenjang');
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		if($jenjang=='SD'){
			$nama_schema = env('CURREND_DB_SD', 'production');
		}else{
			$nama_schema = env('CURREND_DB_SMP', 'production');
		}

		$ki3 = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("kelas='$kelas' AND mapel_id='$id' AND no_ki='3'")->orderBy('kd_id','ASC')->get();
		$ki4 = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("kelas='$kelas' AND mapel_id='$id' AND no_ki='4'")->orderBy('kd_id','ASC')->get();

		$data = [
			'ki3'=>$ki3,
			'ki4'=>$ki4,
			'kelas'=>$kelas,
		];

		$content = view('admin.kd.data',$data)->render();

		return ['content'=>$content];
	}

	function hapus(Request $request){
		$id = $request->id;
		$kelas = $request->kelas;
		$mapel = $request->mapel;
		$no_kd = $request->no_kd;

		$coni = new Request;
		$jenjang = Session::get('jenjang');
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		if($jenjang=='SD'){
			$nama_schema = env('CURREND_DB_SD', 'production');
		}else{
			$nama_schema = env('CURREND_DB_SMP', 'production');
		}

		$where = [
			'kelas'=>$kelas,
			'mapel_id'=>$mapel,
			'no_ki'=>$no_kd,
			'kd_id'=>$id,
		];

		$hapus = DB::connection($conn)->table($nama_schema.'.master_kd')->where($where)->delete();
		if($hapus){
			return ['code'=>'200','title'=>'Success','message'=>'Berhasil dihapus','type'=>'success'];
		}else{
			return ['code'=>'250','title'=>'Whooops','message'=>'Gagal dihapus','type'=>'error'];
		}
	}

	function update(Request $request){
		$id = $request->id;
		$mapel = $request->mapel; // "0"
		$uraian = $request->uraian; // "asdaf"
		$kelas = $request->kelas;
		$no_kd = $request->no_kd;

		$coni = new Request;
		$jenjang = Session::get('jenjang');
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		if($jenjang=='SD'){
			$nama_schema = env('CURREND_DB_SD', 'production');
		}else{
			$nama_schema = env('CURREND_DB_SMP', 'production');
		}

		$data = [
			'kelas'=>$kelas,
			'mapel_id'=>$mapel,
			'kd_id'=>$id,
			'kd_isi'=>$uraian,
			'no_ki'=>$no_kd,
		];

		$where = $data;
		unset($where['kd_isi']);

		$update = DB::connection($conn)->table($nama_schema.'.master_kd')->where($where)->update($data);
		if($update){
			return ['code'=>'200','title'=>'Success','message'=>'Berhasil diupdate','type'=>'success'];
		}else{
			return ['code'=>'250','title'=>'Whooops','message'=>'Gagal diupdate','type'=>'error'];
		}
	}
}
