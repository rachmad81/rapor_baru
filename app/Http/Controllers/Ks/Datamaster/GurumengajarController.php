<?php

namespace App\Http\Controllers\Ks\Datamaster;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Get_data;

use DB,Session,Datatables;


class GurumengajarController extends Controller
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
			'main_menu'=>'guru_mengajar',
			'sub_menu'=>'',
			'tahun_ajaran'=>$tahun_ajaran,
		];

		return view('ks.data_master.guru_mengajar.index',$data);
	}

	function form(Request $request){
		$id = $request->id;
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);
		$tahun_ajaran = DB::connection($conn)->table('public.tahun_ajaran')->orderBy('nama_tahun_ajaran')->get();
		$kategori = Get_data::get_kategori_rapor_mapel();
		$guru = Get_data::get_guru();

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$mengajar = DB::connection($conn)->table($this->schema.'.mengajar as m')
		->leftjoin('public.pegawai as peg',function($join){
			$join->on('m.peg_id','=','peg.peg_id')->on('m.nik_pengajar','=','peg.nik');
		})
		->leftjoin('public.rombongan_belajar as r',function($join){
			$join->on('m.rombel_id','=','r.id_rombongan_belajar');
		})
		->leftjoin('public.rapor_mapel as rm',function($join){
			$join->on('m.mapel_id','=','rm.mapel_id');
		})
		->selectRaw("m.*,peg.nik,r.tahun_ajaran_id")
		->where('id_mengajar',$id)
		->first();

		if($id=='0'){
			$title = 'Tambah';
		}else{
			$title = 'Edit';
			Session::put('tahun_ajaran',$mengajar->tahun_ajaran_id);
		}


		$data = [
			'title'=>$title,
			'tahun_ajaran'=>$tahun_ajaran,
			'kategori'=>$kategori,
			'guru'=>$guru,
			'mengajar'=>$mengajar,
		];

		$content = view('ks.data_master.guru_mengajar.form',$data)->render();

		return ['content'=>$content];
	}

	function simpan(Request $request){
		$guru = $request->guru; // "218787"
		$kategori = $request->kategori; // "AGAMA ISLAM"
		$mapel_id = $request->mapel_id; // "51"
		$rombel = $request->rombel; // "2"
		$tahun_ajaran = $request->tahun_ajaran; // "1"

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$npsn = Session::get('npsn');

		$pegawai = DB::connection($conn)->table('public.pegawai')->whereRaw("peg_id='$guru'")->first();
		$nik = (!empty($pegawai)) ? $pegawai->nik : '';
		$peg_id = (!empty($pegawai)) ? $pegawai->peg_id : '';

		$data = [
			'mapel_id'=>$mapel_id,
			'rombel_id'=>$rombel,
			'nik_pengajar'=>$nik,
			'peg_id'=>$peg_id,
			'created_at'=>date('Y-m-d H:i:s'),
			'updated_at'=>date('Y-m-d H:i:s'),
		];

		$simpan = DB::connection($conn)->table($this->schema.'.mengajar')->insert($data);

		if($simpan){
			Session::flash('title','Success');
			Session::flash('message','Berhasil disimpan');
			Session::flash('type','success');
			return ['code'=>'200','title'=>'Success','message'=>'Berhasil disimpan','type'=>'success'];
		}else{
			return ['code'=>'250','title'=>'Whooops','message'=>'Gagal disimpan','type'=>'error'];
		}
	}

	function get_data(Request $request){
		$id = $request->tahun_ajaran;
		$semester = $request->semester;
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');

		if($id==''){
			$mengajar = [];
		}else{
			$mengajar = DB::connection($conn)->table('public.rombongan_belajar as rb')
			->join($this->schema.'.mengajar as m','m.rombel_id','rb.id_rombongan_belajar')
			->join('public.rapor_mapel as rm','rm.mapel_id','m.mapel_id')
			->leftjoin('public.pegawai as p',function($join){
				return $join->on('p.nik','=','m.nik_pengajar')->on('p.peg_id','=','m.peg_id');
			})
			->selectRaw("p.nama as nama_guru,rm.nama as nama_mapel,CONCAT(rb.kelas,'.',rb.rombel) as kelas_rombel,rb.tahun_ajaran_id,m.id_mengajar")
			->whereRaw("rb.npsn='$npsn' AND rb.tahun_ajaran_id='$id' AND semester='$semester'")
			->get();

			if($mengajar->count()!=0){
				foreach($mengajar as $m){
					$aksi = '';
					$m->aksi = $aksi;
				}
			}
		}

		return ['data'=>$mengajar];
	}

	function hapus(Request $request){
		$kelas = $request->kelas; //"6"
		$mapel_id = $request->mapel_id; //"51"
		$nip = $request->nip; //"6446756658300002"
		$rombel = $request->rombel; //"C"
		$nama_schema = $request->tahun_ajaran;
		$npsn = Session::get('npsn');

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		
		$mengajar = DB::connection($conn)->table($nama_schema.'.mengajar')->whereRaw("npsn='$npsn' and kelas='$kelas' and rombel='$rombel' and nip='$nip' and mapel_id='$mapel_id'")->first();

		if(!empty($mengajar)){
			$hapus = DB::connection($conn)->table($nama_schema.'.mengajar')->whereRaw("npsn='$npsn' and kelas='$kelas' and rombel='$rombel' and nip='$nip' and mapel_id='$mapel_id'")->delete();
			if($hapus){
				Session::flash('title','Success');
				Session::flash('message','Berhasil dihapus');
				Session::flash('type','success');
				$return = ['code'=>'200','message'=>'Berhasil dihapus','title'=>'Success','type'=>'success'];
			}else{
				$return = ['code'=>'250','message'=>'Gagal dihapus','title'=>'Whooops','type'=>'error'];
			}
		}else{
			$return = ['code'=>'404','message'=>'data not found','title'=>'Whooops','type'=>'warning'];
		}

		return $return;
	}
}
