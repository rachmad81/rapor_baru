<?php

namespace App\Http\Controllers\Ks\Datamaster;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use App\Http\Controllers\Controller;

use DB,Session;

class WalikelasController extends Controller
{
	function main(){
		$tahun_ajaran = Setkoneksi::tahun_ajaran();

		$data = [
			'main_menu'=>'walikelas',
			'sub_menu'=>'',
			'tahun_ajaran'=>$tahun_ajaran,
		];

		return view('ks.data_master.walikelas.index',$data);
	}

	function form(Request $request){
		$id = $request->id;
		$tahun_ajaran = Setkoneksi::tahun_ajaran();
		$guru = Get_data::get_guru();

		$jenjang = Session::get('jenjang');

		if($jenjang=='SD'){
			$kelas = ['1','2','3','4','5','6'];
		}else{
			$kelas = ['7','8','9'];
		}

		$title = ($id=='0') ? 'Tambah' : 'Edit';

		$data = [
			'title'=>$title,
			'tahun_ajaran'=>$tahun_ajaran,
			'guru'=>$guru,
			'kelas'=>$kelas,
		];

		$content = view('ks.data_master.walikelas.form',$data)->render();

		return ['content'=>$content];
	}

	function simpan(Request $request){
		$peg_id = $request->guru; // "128173"
		$kelasrombel = explode('|||',$request->kelas);
		$kelas = $kelasrombel[0]; // "1"
		$rombel = $kelasrombel[1]; // "C"
		$tahun_ajaran = $request->tahun_ajaran; // "1"
		$kurikulum = $request->kurikulum; // "ktsp"

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		
		$pegawai = DB::connection($conn)->table('public.pegawai')->whereRaw("peg_id='$peg_id'")->first();
		$user_rapor = (!empty($pegawai)) ? $pegawai->user_rapor : '';
		$nama = (!empty($pegawai)) ? $pegawai->nama : '';

		$data = [
			'nip'=>$user_rapor,
			'npsn'=>Session::get('npsn'),
			'kelas'=>$kelas,
			'rombel'=>$rombel,
			'nama'=>$nama,
			'kurikulum'=>$kurikulum,
		];

		$simpan = DB::connection($conn)->table($tahun_ajaran.'.walikelas')->insert($data);
		if($simpan){
			Session::flash('title','Success');
			Session::flash('message','Berhasil disimpan');
			Session::flash('type','success');
			return ['title'=>'Success','message'=>'Berhasil disimpan','type'=>'success','code'=>'200'];
		}else{
			return ['title'=>'Whooops','message'=>'Gagal disimpan','type'=>'error','code'=>'250'];
		}
	}

	function get_data(Request $request){
		$nama_schema = $request->tahun_ajaran;
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$npsn = Session::get('npsn');

		if($request->tahun_ajaran==''){
			$wali_kelas = [];
		}else{
			$wali_kelas = DB::connection($conn)->table($nama_schema.'.walikelas')->whereRaw("npsn='$npsn'")->get();


			if(count($wali_kelas)!=0){
				foreach($wali_kelas as $w){
					$w->aksi = '<a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="hapus(\''.$w->nip.'\',\''.$w->kelas.'\',\''.$w->rombel.'\')"><i class="fa fa-trash"></i> Hapus</a>';
					// $w->aksi = '';
				}
			}
		}

		return ['data'=>$wali_kelas];
	}

	function hapus(Request $request){
		$nip = $request->nip;
		$kelas = $request->kelas;
		$rombel = $request->rombel;
		$nama_schema = $request->tahun_ajaran;

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$npsn = Session::get('npsn');

		$hapus = DB::connection($conn)->table($nama_schema.'.walikelas')->whereRaw("nip='$nip' AND npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel'")->delete();
		if($hapus){
			Session::flash('title','Success');
			Session::flash('message','Berhasil dihapus');
			Session::flash('type','success');
			return ['title'=>'Success','message'=>'Berhasil dihapus','type'=>'success','code'=>'200'];
		}else{
			return ['title'=>'Whooops','message'=>'Gagal dihapus','type'=>'error','code'=>'250'];
		}
	}
}
