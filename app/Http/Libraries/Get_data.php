<?php
namespace App\Http\Libraries;

use Illuminate\Http\Request;

use DB,Session;

class Get_data
{
	public static function get_tahun_ajaran(Request $request=null){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$tahun_ajaran = DB::connection($conn)->table('public.tahun_ajaran')->orderBy('id_tahun_ajaran','DESC')->get();

		return $tahun_ajaran;
	}

	public static function get_kategori_rapor_mapel(Request $request=null){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$kategori = DB::connection($conn)->table('public.rapor_mapel')->selectRaw("kategori")->groupBy('kategori')->orderByRaw("kategori ASC")->get();

		return $kategori;
	}

	public static function get_rapor_mapel_by_kategori(Request $request=null){
		$kategori = $request->kategori;

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$mapel = DB::connection($conn)->table('public.rapor_mapel')->whereRaw("kategori='$kategori'")->orderByRaw("kategori ASC,nama ASC")->get();

		return $mapel;
	}

	public static function get_guru(Request $request=null){
		$npsn = Session::get('npsn');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$guru = DB::connection($conn)->table('public.pegawai as peg')
		->leftjoin('public.pangkat_golongan as g',function($join){
			$join->on(DB::raw("cast(g.pangkat_golongan_id as varchar)"),"=","peg.gol_id");
		})
		->selectRaw("peg.*,CAST(g.nama as varchar(10)) as nama_golongan")->whereRaw("peg.keterangan='Aktif' AND peg.npsn='$npsn'")->orderByRaw("peg.nama ASC")->get();

		return $guru;
	}

	public static function get_kelas_rombel(Request $request=null){
		$tahun_ajaran = Session::get('tahun_ajaran');
		$npsn = Session::get('npsn');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		if($tahun_ajaran==''){
			$kelas = [];
		}else{
			$kelas = DB::connection($conn)->table('public.rombongan_belajar')->whereRaw("tahun_ajaran_id='$tahun_ajaran' AND npsn='$npsn'")->orderByRaw("kelas ASC,rombel ASC")->get();
		}

		return $kelas;
	}

	public static function get_mapel(Request $request=null){
		$npsn = Session::get('npsn');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$mapel = DB::connection($conn)->table('public.rapor_mapel')->orderByRaw("kategori ASC,nama ASC")->get();
		if($mapel->count()!=0){
			foreach($mapel as $m){
				$kd = DB::connection($conn)->table('rapor_dummy.kd')->whereRaw("mapel_id='".$m->mapel_id."'")->count();
				$m->kd = $kd;
			}
		}

		return $mapel;
	}

	public static function get_walikelas(Request $request=null){
		$tahun_ajaran = $request->tahun_ajaran;
		$npsn = Session::get('npsn');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		if($tahun_ajaran==''){
			$kelas = [];
		}else{
			$kelas = DB::connection($conn)->table('public.rombongan_belajar as r')
			->leftjoin('public.pegawai as p',function($join){
				$join->on(DB::raw("CAST(p.peg_id as VARCHAR(10))"),'=','r.wali_kelas_peg_id');
			})
			->selectRaw("r.*,p.nama as nama_guru")
			->whereRaw("r.tahun_ajaran_id='$tahun_ajaran' AND r.npsn='$npsn'")->orderByRaw("r.kelas ASC,r.rombel ASC")->get();
		}


		return $kelas;
	}

	public static function get_siswa(Request $request=null){
		$tahun_ajaran = $request->tahun_ajaran;
		$kelas = $request->kelas;
		$npsn = Session::get('npsn');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$rombel = DB::connection($conn)->table('public.rombongan_belajar')->where('id_rombongan_belajar',$kelas)->first();

		if(!empty($rombel)){
			$siswa = DB::connection($conn)->table('public.siswa as r')
			->whereRaw("r.kelas='$rombel->kelas' AND r.rombel='$rombel->rombel' AND r.npsn='$npsn'")->orderByRaw("r.nama ASC")->get();
		}else{
			$siswa = [];
		}


		return $siswa;
	}

	public static function get_mengajar_by_id(Request $request=null){
		$id_mengajar = $request->mengajar_id;

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$mengajar = DB::connection($conn)->table('rapor_dummy.mengajar as m')
		->leftjoin('public.rapor_mapel as ma','ma.mapel_id','=','m.mapel_id')
		->leftjoin('public.rombongan_belajar as rb','rb.id_rombongan_belajar','=','m.rombel_id')
		->selectRaw("*,ma.nama as nama_mapel")
		->where('id_mengajar',$id_mengajar)->first();

		return $mengajar;
	}

	public static function get_rombel_by_id(Request $request=null){
		$id_rombel = $request->rombel_id;

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$rombel = DB::connection($conn)->table('public.rombongan_belajar as m')
		->where('id_rombongan_belajar',$id_rombel)->first();

		return $rombel;
	}
}
?>