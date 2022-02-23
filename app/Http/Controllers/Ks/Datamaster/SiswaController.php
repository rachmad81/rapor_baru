<?php

namespace App\Http\Controllers\Ks\Datamaster;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Get_data;
use App\Http\Libraries\Setkoneksi;
use Illuminate\Http\Request;

use Session,DB,Redirect;

class SiswaController extends Controller
{
	function main(){
		$tahun_ajaran = Setkoneksi::tahun_ajaran();

		$data = [
			'main_menu'=>'master_siswa',
			'sub_menu'=>'',
			'tahun_ajaran'=>$tahun_ajaran,
		];

		return view('ks.data_master.siswa.index',$data);
	}

	function get_data(Request $request){
		$nama_schema = $request->tahun_ajaran;
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		if($request->kelas==''){
			$siswa = [];
		}else{
			$kelasrombel = explode('|||',$request->kelas);
			$kelas = $kelasrombel[0];
			$rombel = $kelasrombel[1];
			$npsn = Session::get('npsn');

			$siswa = DB::connection($conn)->table($nama_schema.'.nilai_akhir as n')->selectRaw("n.npsn,n.id_siswa,n.nama,n.kelas,n.rombel,s.nik,s.nis,s.tgl_lahir,s.kelamin,s.alamat_domisili")
			->leftjoin('public.siswa as s','n.id_siswa','s.id_siswa')->whereRaw("n.kelas='$kelas' AND n.rombel='$rombel' AND n.npsn='$npsn'")->groupByRaw("n.npsn,n.id_siswa,n.nama,n.kelas,n.rombel,s.nik,s.nis,s.tgl_lahir,s.kelamin,s.alamat_domisili")->get();
		}

		return ['data'=>$siswa];
	}
}
