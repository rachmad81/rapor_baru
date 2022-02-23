<?php

namespace App\Http\Controllers\Guru\Walikelas;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use App\Http\Controllers\Controller;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use DB,Session;

class BukuindukController extends Controller
{
	function main(Request $request){
		$id_siswa = $request->id_siswa;
		$cetak = (isset($request->cetak)) ? $request->cetak : '';

		$jenjang = Session::get('jenjang');
		$request->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($request);

		$siswa = DB::connection($conn)->table('public.siswa as s')
		->leftjoin('public.agama as a','a.aga_id','s.aga_id')
		->leftjoin('public.country_code as c','c.code_iso2','s.kewarganegaraan')
		->leftjoin('public.wali_murid as wm',function($join){
			return $join->on('wm.id_siswa','=','s.id_siswa')->on('wm.npsn','=','s.npsn');
		})
		->leftjoin('public.pendidikan as p_ayah',function($join){
			return $join->on('wm.pendidikan_ayah','=','p_ayah.kode');
		})
		->leftjoin('public.pendidikan as p_ibu',function($join){
			return $join->on('wm.pendidikan_ibu','=','p_ibu.kode');
		})
		->leftjoin('public.pekerjaan as p1_ayah',function($join){
			return $join->on('wm.pekerjaan_ayah','=','p1_ayah.kode');
		})
		->leftjoin('public.pekerjaan as p1_ibu',function($join){
			return $join->on('wm.pekerjaan_ibu','=','p1_ibu.kode');
		})
		->selectRaw("s.*,wm.*,a.aga_nama,c.country_name,p_ayah.nama as c_pendidikan_ayah,p_ibu.nama as c_pendidikan_ibu,p1_ayah.nama as c_pekerjaan_ayah,p1_ibu.nama as c_pekerjaan_ibu")
		->whereRaw("s.id_siswa='$id_siswa'")->first();

		$bukuinduk = DB::connection($conn)->table('public.data_bukuinduk')->whereRaw("id_siswa='".$siswa->id_siswa."' AND npsn='".$siswa->npsn."'")->first();

		$sekolah = DB::connection($conn)->table('public.sekolah as s')
		->leftjoin('public.kecamatan as kec','kec.kecamatan_kode','s.kec_id')
		->leftjoin('public.kelurahan as kel','kel.kelurahan_kode','s.desa')
		->selectRaw("*")
		->where('s.npsn',$siswa->npsn)->first();

		$qrcode = QrCode::size(80)->generate($request->url());

		$data = [
			'siswa'=>$siswa,
			'bukuinduk'=>$bukuinduk,
			'cetak'=>$cetak,
			'sekolah'=>$sekolah,
			'qrcode'=>$qrcode,
		];

		return view('guru.walikelas.bukuinduk.index',$data);
	}

	function simpan(Request $request){
		$id_siswa = $request->id_siswa;
		$npsn = $request->npsn;

		$jenjang = Session::get('jenjang');
		$request->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($request);

		$data = $request->all();

		$bukuinduk = DB::connection($conn)->table('public.data_bukuinduk')->whereRaw("id_siswa='$id_siswa' AND npsn='$npsn'")->first();
		if(!empty($bukuinduk)){
			$simpan = DB::connection($conn)->table('public.data_bukuinduk')->whereRaw("id_siswa='$id_siswa' AND npsn='$npsn'")->update($data);
		}else{
			$simpan = DB::connection($conn)->table('public.data_bukuinduk')->insert($data);
		}

		if($simpan){
			$return = ['code'=>'200','message'=>'Success'];
		}else{
			$return = ['code'=>'250','message'=>'Failed'];
		}

		return $return;
	}
}
