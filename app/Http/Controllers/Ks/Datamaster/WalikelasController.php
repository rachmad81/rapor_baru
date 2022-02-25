<?php

namespace App\Http\Controllers\Ks\Datamaster;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use App\Http\Controllers\Controller;

use DB,Session;

class WalikelasController extends Controller
{
	protected $schema;

    public function __construct() 
    {
        $this->schema = env('CURRENT_SCHEMA','production');
    }

	function main(){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$tahun_ajaran = DB::connection($conn)->table('public.tahun_ajaran')->orderBy('nama_tahun_ajaran')->get();

		$data = [
			'main_menu'=>'walikelas',
			'sub_menu'=>'',
			'tahun_ajaran'=>$tahun_ajaran,
		];

		return view('ks.data_master.walikelas.index',$data);
	}

	function form(Request $request){
		$id = $request->id;
		$jenjang = Session::get('jenjang');

		$request->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($request);

		$tahun_ajaran = DB::connection($conn)->table('public.tahun_ajaran')->orderBy('nama_tahun_ajaran')->get();
		$guru = Get_data::get_guru();


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
		$semester = $request->semester;

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$npsn = Session::get('npsn');
		
		$pegawai = DB::connection($conn)->table('public.pegawai')->whereRaw("peg_id='$peg_id' and npsn='$npsn'")->first();
		$nik = (!empty($pegawai)) ? $pegawai->nik : '';
		$nama = (!empty($pegawai)) ? $pegawai->nama : '';

		$data = [
			'wali_kelas_peg_id'=>$pegawai->peg_id,
			'tahun_ajaran_id'=>$tahun_ajaran,
			'npsn'=>$npsn,
			'nik_wk'=>$nik,
			'kelas'=>$kelas,
			'rombel'=>$rombel,
			'semester'=>$semester,
		];

		$simpan = DB::connection($conn)->table('public.rombongan_belajar')->insert($data);
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
		$id = $request->tahun_ajaran;
		$semester = $request->semester;
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$npsn = Session::get('npsn');

		if($request->tahun_ajaran==''){
			$wali_kelas = [];
		}else{
			$wali_kelas = DB::connection($conn)->table('public.rombongan_belajar as wk')
			->join('public.pegawai as p',function($join){
				return $join->on(DB::raw("CAST(p.peg_id as varchar)"),'=','wk.wali_kelas_peg_id');
			})->whereRaw("wk.npsn='$npsn' AND wk.tahun_ajaran_id='$id' AND semester='$semester' AND CASE WHEN (wk.nik_wk is null) THEN p.nik is null ELSE wk.nik_wk=p.nik END")->get();

			if($wali_kelas->count()!=0){
				foreach($wali_kelas as $wk){
					$aksi = '';
					$wk->aksi = $aksi;
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
