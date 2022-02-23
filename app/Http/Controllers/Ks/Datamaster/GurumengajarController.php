<?php

namespace App\Http\Controllers\Ks\Datamaster;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Get_data;

use DB,Session,Datatables;

class GurumengajarController extends Controller
{
	function main(){
		$tahun_ajaran = Setkoneksi::tahun_ajaran();

		$data = [
			'main_menu'=>'guru_mengajar',
			'sub_menu'=>'',
			'tahun_ajaran'=>$tahun_ajaran,
		];

		return view('ks.data_master.guru_mengajar.index',$data);
	}

	function form(Request $request){
		$id = $request->id;
		$tahun_ajaran = Setkoneksi::tahun_ajaran();
		$kategori = Get_data::get_kategori_rapor_mapel();
		$guru = Get_data::get_guru();

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$mengajar = DB::connection($conn)->table('rapor_dummy.mengajar as m')
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
		$guru = $request->guru;
		$kategori = $request->kategori;
		$mapel_id = $request->mapel_id;
		$kelasrombel = explode('|||',$request->rombel);
		$kelas = $kelasrombel[0];
		$rombel = $kelasrombel[1];
		$nama_schema = $request->tahun_ajaran;

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$npsn = Session::get('npsn');

		$pegawai = DB::connection($conn)->table('public.pegawai')->whereRaw("peg_id='$guru'")->first();
		$user_rapor = (!empty($pegawai)) ? $pegawai->user_rapor : '';
		$nama = (!empty($pegawai)) ? $pegawai->nama : '';

		$data = [
			'nip'=>$user_rapor,
			'mapel_id'=>$mapel_id,
			'npsn'=>$npsn,
			'kelas'=>$kelas,
			'rombel'=>$rombel,
			'nama'=>$nama,
			'kkm'=>0,
			'kurikulum'=>'',
		];

		$simpan = DB::connection($conn)->table($nama_schema.'.mengajar')->insert($data);

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
		$nama_schema = $request->tahun_ajaran;
		
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$npsn = Session::get('npsn');

		if($nama_schema==''){
			$mengajar = [];
		}else{
			$mengajar = DB::connection($conn)->table($nama_schema.'.mengajar as m')
			->leftjoin('public.pegawai as peg',function($join){
				$join->on('m.nip','=','peg.user_rapor')->on('m.npsn','=','peg.npsn');
			})
			->leftjoin('public.rapor_mapel as rm',function($join){
				$join->on('m.mapel_id','=','rm.mapel_id');
			})
			->selectRaw("m.*,peg.nama as nama_guru,rm.nama as nama_mapel,CONCAT(m.kelas,' ',m.rombel) as kelas_rombel")
			->whereRaw("m.npsn='$npsn'")->get();

			if($mengajar->count()!=0){
				foreach($mengajar as $m){
					$m->aksi = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="hapus_data(\''.$m->nip.'\',\''.$m->mapel_id.'\',\''.$m->kelas.'\',\''.$m->rombel.'\')"><i class="fa fa-trash"></i> Hapus</a>';
					
					// $m->aksi = '<div class="btn-group">'.
					// '<button type="button" class="btn btn-default">Action</button>'.
					// '<button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="true">'.
					// '<span class="sr-only">Toggle Dropdown</span>'.
					// '</button>'.
					// '<div class="dropdown-menu" role="menu" style="position: absolute; transform: translate3d(68px, 38px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-start">'.
					// '<a class="dropdown-item" href="javascript:void(0)" onclick="form(\''.$m->id_mengajar.'\')"><i class="fa fa-edit"></i> Edit</a>'.
					// '<a class="dropdown-item" href="javascript:void(0)" onclick="hapus_data(\''.$m->id_mengajar.'\')"><i class="fa fa-trash"></i> Hapus</a>'.
					// //'<div class="dropdown-divider"></div>'.
					// //'<a class="dropdown-item" href="#">Separated link</a>'.
					// '</div>'.
					// '</div>';
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
