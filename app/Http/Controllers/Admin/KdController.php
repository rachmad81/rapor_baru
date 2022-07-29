<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use App\Http\Controllers\Controller;

use DB,Session;

class KdController extends Controller
{	
	protected $schema;

	public function __construct() 
	{
		$this->schema = env('CURRENT_SCHEMA','production');
	}

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
		// dd($request->all());

		if($jenjang=='SD'){
			$nama_schema = env('CURRENT_DB_SD', 'production');
			$kolom_kategori = 'kategori_baru';
		}else{
			$nama_schema = env('CURRENT_DB_SMP', 'production');
			$kolom_kategori = 'kategori';
		}

		$kelas = $request->kelas;
		$mapel = DB::connection($conn)->table('public.rapor_mapel')->whereRaw("is_aktif is true")->orderByRaw("kategori asc,nama asc")->get();
		if($mapel->count()!=0){
			foreach($mapel as $v){
				$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='".$v->mapel_id."'")->count();
				$v->kd = $kd;
			}
		}
		$col_mapel = collect($mapel);

		$data = [
			'kelas'=>$kelas,
			'mapel'=>$mapel,
			'kolom_kategori'=>$kolom_kategori,
		];

		$content = view('admin.kd.data_mapel',$data)->render();

		return ['content'=>$content];
	}

	function setting(Request $request){
		$id = $request->id;
		$kelas = $request->kelas;
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$tahun_ajaran = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->orderBy('nama_tahun_ajaran','ASC')->get();

		$title = ($id=='0') ? 'Tambah' : 'Edit';

		$data = [
			'title'=>$title,
			'mapel_id'=>$id,
			'kelas'=>$kelas,
			'tahun_ajaran'=>$tahun_ajaran,
		];

		$content = view('admin.kd.setting',$data)->render();

		return ['content'=>$content];
	}

	function simpan(Request $request){
		$kd = $request->kd; // "3"
		$kelas = $request->kelas; // "1"
		$mapel = $request->mapel; // "52"
		$semester = $request->semester; // "1"
		$tahun_ajaran = $request->tahun_ajaran; // "1"
		$uraian = $request->uraian; // "Anjirlah"

		$coni = new Request;
		$jenjang = Session::get('jenjang');
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$data = [
			'mapel_id'=>$mapel,
			'isi'=>$uraian,
			'no_ki'=>$kd,
			'kelas'=>$kelas,
			'tahun_ajaran_id'=>$tahun_ajaran,
			'semester'=>$semester,
		];

		$simpan = DB::connection($conn)->table($this->schema.'.kd')->insert($data);
		if($simpan){
			return ['code'=>'200'];
		}else{
			return ['code'=>'250'];
		}
	}

	function get_kd(Request $request){
		// return $request->all();
		if(count($request->all())==0){
			return '';
		}else{
			$id = $request->id;
			$kelas = $request->kelas;
			$tahun_ajaran = $request->tahun_ajaran;
			$semester = $request->semester;

			if($tahun_ajaran==''){
				return ['content'=>''];
			}else{
				$coni = new Request;
				$jenjang = Session::get('jenjang');
				$coni->jenjang = $jenjang;
				$conn = Setkoneksi::set_koneksi($coni);

				$ki3 = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$id' AND tahun_ajaran_id='$tahun_ajaran' AND semester='$semester' AND no_ki='3'")->orderBy('id_kd','ASC')->get();
				$ki4 = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$id' AND tahun_ajaran_id='$tahun_ajaran' AND semester='$semester' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

				$data = [
					'ki3'=>$ki3,
					'ki4'=>$ki4,
					'kelas'=>$kelas,
				];

				$content = view('admin.kd.data',$data)->render();

				return ['content'=>$content];
			}
		}
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
		$id = $request->id; // "27"
		$kelas = $request->kelas; // "1"
		$mapel = $request->mapel; // "52"
		$no_kd = $request->no_kd; // "3"
		$semester = $request->semester; // "1"
		$tahun_ajaran = $request->tahun_ajaran; // "1"
		$uraian = $request->uraian; // "Anjirlah"

		$coni = new Request;
		$jenjang = Session::get('jenjang');
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$data = [
			'mapel_id'=>$mapel,
			'isi'=>$uraian,
			'no_ki'=>$no_kd,
			'kelas'=>$kelas,
			'tahun_ajaran_id'=>$tahun_ajaran,
			'semester'=>$semester,
		];

		$update = DB::connection($conn)->table($this->schema.'.kd')->where('id_kd',$id)->update($data);
		if($update){
			return ['code'=>'200','title'=>'Success','message'=>'Berhasil diupdate','type'=>'success'];
		}else{
			return ['code'=>'250','title'=>'Whooops','message'=>'Gagal diupdate','type'=>'error'];
		}
	}
}
