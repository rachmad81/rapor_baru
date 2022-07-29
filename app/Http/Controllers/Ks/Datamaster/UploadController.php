<?php

namespace App\Http\Controllers\Ks\Datamaster;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;

use App\Http\Controllers\Controller;

use DB,Session,Excel;

class UploadController extends Controller
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
		$tahun_ajaran = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->orderBy('nama_tahun_ajaran')->get();

		$data = [
			'main_menu'=>'upload',
			'sub_menu'=>'',
			'tahun_ajaran'=>$tahun_ajaran,
		];

		return view('ks.data_master.upload.index',$data);
	}

	function mapel(Request $request){
		$id_rombel = $request->kelas;
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$mengajar = DB::connection($conn)->table($this->schema.'.mengajar as m')
		->join('public.rapor_mapel as r','m.mapel_id','r.mapel_id')
		->selectRaw("r.nama,r.mapel_id")
		->whereRaw("rombel_id='$id_rombel'")->get();

		return $mengajar;
	}

	function template(Request $request){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$id_rombel = $request->id_rombel;
		$mapel_id = $request->mapel;

		$semester = Session::get('semester_wk');
		$npsn = Session::get('npsn');

		$rombongan_belajar = DB::connection($conn)->table('public.rombongan_belajar as rb')->join('public.pegawai as p',function($join){
			return $join->on('rb.wali_kelas_peg_id','=',DB::raw("CAST(p.peg_id as varchar)"))->on('rb.nik_wk','=','p.no_ktp');
		})->where('rb.id_rombongan_belajar',$id_rombel)->first();

		$tahun_ajaran = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->where('id_tahun_ajaran',$rombongan_belajar->tahun_ajaran_id)->first();
		$rombongan_belajar->nama_tahun_ajaran = $tahun_ajaran->nama_tahun_ajaran;

		$kelas = (!empty($rombongan_belajar)) ? $rombongan_belajar->kelas : '';
		$rombel = (!empty($rombongan_belajar)) ? $rombongan_belajar->rombel : '';

		$sekolah = DB::connection($conn)->table('public.sekolah')->whereRaw("npsn='$npsn'")->first();
		
		if($mapel_id=='spiritual'){
			$mengajar = new Request;
			$mengajar->nama_mapel = $mapel_id;
			$mengajar->nama_pengajar = '';

			$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
			->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
			->join('public.siswa as s',function($join){
				return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
			})
			->leftjoin($this->schema.'.nilai_perilaku as ns','ns.anggota_rombel_id','ar.id_anggota_rombel')
			->selectRaw("rb.*,s.nama,s.id_siswa,ar.id_anggota_rombel,ns.*")
			->whereRaw("ar.rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();
		}else{
			$mengajar = DB::connection($conn)->table($this->schema.'.mengajar as m')
			->join('public.rapor_mapel as ma','ma.mapel_id','m.mapel_id')
			->join('public.pegawai as p',function($join){
				return $join->on('m.peg_id','=','p.peg_id')->on('m.nik_pengajar','=','p.no_ktp');
			})
			->selectRaw("*,ma.nama as nama_mapel,p.nama as nama_pengajar")
			->whereRaw("m.rombel_id='$id_rombel' AND m.mapel_id='$mapel_id'")->first();
			
			$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
			->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
			->join('public.siswa as s',function($join){
				return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
			})
			->leftjoin($this->schema.'.nilai_mapel as ns',function($join) use($mapel_id){
				return $join->on('ns.anggota_rombel_id','=','ar.id_anggota_rombel')->where('ns.mapel_id','=',$mapel_id);
			})
			->selectRaw("rb.*,s.nama,s.id_siswa,ar.id_anggota_rombel,ns.*")
			->whereRaw("ar.rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();
		}


		$data = [
			'nama_file'=>'Nilai_mapel_'.$mapel_id.'_'.$kelas.$rombel,
			'semester'=>$semester,
			'rombongan_belajar'=>$rombongan_belajar,
			'sekolah'=>$sekolah,
			'mengajar'=>$mengajar,
			'siswa'=>$siswa,
			'mapel_id'=>$mapel_id,
		];

		return view('ks.data_master.upload.template',$data);
	}

	function upload(Request $request){
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$npsn = Session::get('npsn');

		$request->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($request);
		
		$file = $request->file('file_excel');

		$collection = Excel::toArray(new Request, $file);

		$sheet = $collection[0];
		$jumlah_baris = count($sheet);

		for ($i=0; $i < $jumlah_baris; $i++) { 
			if($i<10){
				continue;
			}

			$baris = $sheet[$i];
			$kolom = $baris;

			if($kolom[6]=='spiritual'){
				$cari = [
					'npsn'=>$npsn,
					'anggota_rombel_id'=>$kolom[3],
				];
				
				$data_simpan = [
					'anggota_rombel_id'=>$kolom[3],
					'npsn'=>$npsn,
					'predikat_ki1'=>$kolom[7],
					'deskripsi_ki1'=>$kolom[8],
					'predikat_ki2'=>$kolom[9],
					'deskripsi_ki2'=>$kolom[10],
					'catatan_siswa'=>$kolom[11],
					'sakit'=>$kolom[12],
					'izin'=>$kolom[13],
					'tanpa_keterangan'=>$kolom[14],
					'tinggi_badan'=>$kolom[15],
					'berat_badan'=>$kolom[16],
					'pendengaran'=>$kolom[17],
					'penglihatan'=>$kolom[18],
					'gizi'=>$kolom[19],
					'lainnya'=>$kolom[20],
					'tgl_perhitungan'=>date('Y-m-d H:i:s'),
					'updated_at'=>date('Y-m-d H:i:s'),
				];
				
				$cek = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->where($cari)->first();
				if(!empty($cek)){
					$simpan = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->where($cari)->update($data_simpan);
				}else{
					$data_simpan = array_merge($data_simpan,[
						'created_at'=>$date('Y-m-d H:i:s'),
					]);
					
					$simpan = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->insert($data_simpan);
				}				
			}else{
				$cari = [
					'anggota_rombel_id'=>$kolom[3],
					'mapel_id'=>$kolom[6],
				];
				
				$data_simpan = [
					'mapel_id'=>$kolom[6],
					'anggota_rombel_id'=>$kolom[3],
					'nilai_ki3'=>$kolom[7],
					'predikat_ki3'=>$kolom[8],
					'deskripsi_ki3'=>$kolom[9],
					'nilai_ki4'=>$kolom[10],
					'predikat_ki4'=>$kolom[11],
					'deskripsi_ki4'=>$kolom[12],
					'updated_at'=>date('Y-m-d H:i:s'),
				];

				$cek = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($cari)->first();
				if(!empty($cek)){
					$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($cari)->update($data_simpan);
				}else{
					$data_simpan = array_merge($data_simpan,['created_at'=>date('Y-m-d H:i:s')]);
					$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->insert($data_simpan);
				}
			}
		}

		return ['message'=>'Berhasil diunggah','status'=>'Success','type'=>'success'];
	}
}
