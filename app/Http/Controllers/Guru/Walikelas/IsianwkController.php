<?php

namespace App\Http\Controllers\Guru\Walikelas;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;
use App\Http\Libraries\Convert;
use App\Http\Libraries\Hitung_sikap;

use App\Http\Controllers\Controller;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use DB,Session;

class IsianwkController extends Controller
{
	protected $schema;

	public function __construct() 
	{
		$this->schema = env('CURRENT_SCHEMA','production');
	}

	function main(Request $request){
		$id_rombel = $request->id_rombel;
		$jenjang = Session::get('jenjang');
		$request->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($request);

		$rombongan_belajar = DB::connection($conn)->table('public.rombongan_belajar as rb')->where('rb.id_rombongan_belajar',$id_rombel)->first();
		$tahun_ajaran = DB::connection('pgsql_sd')->table('public.tahun_ajaran')->where('id_tahun_ajaran',$rombongan_belajar->tahun_ajaran_id)->first();
		$rombongan_belajar->nama_tahun_ajaran = $tahun_ajaran->nama_tahun_ajaran;

		if(!empty($rombongan_belajar)){
			Session::put('kelas_wk',$rombongan_belajar->kelas);
			Session::put('rombel_wk',$rombongan_belajar->rombel);
			Session::put('ta_wk',$tahun_ajaran->nama_tahun_ajaran);
			Session::put('rombel_semester',$rombongan_belajar->semester);
			$semester = ($rombongan_belajar->semester==1) ? 'Semester Ganjil' : 'Semester Genap';
			Session::put('semester_wk',$semester);
		}else{
			Session::put('kelas_wk','0xx');
			Session::put('rombel_wk','0xx');
			Session::put('ta_wk','0xx');
			Session::put('rombel_semester','0xx');
			Session::put('semester_wk','0xx');
			$semester = '';
		}
		Session::put('id_rombel',$id_rombel);


		$data = [
			'main_menu'=>'walikelas',
			'sub_menu'=>'',
			'semester'=>$semester,
		];

		return view('guru.walikelas.isian_wk.index',$data);
	}	

	function pages(Request $request){
		$i = $request->i;
		$content = 'Pilih Tab';

		switch ($i) {
			case '1':
			$content = $this->show1();
			break;
			case '2':
			$content = view('guru.walikelas.isian_wk.pages.dua')->render();
			break;
			case '3':
			$content = view('guru.walikelas.isian_wk.pages.tiga')->render();
			break;
			case '4':
			$content = $this->show4();
			break;
			case '5':
			$content = $this->show5();
			break;
			case '6':
			$content = $this->show6();
			break;
			case '7':
			$content = $this->show7();
			break;
			case '8':
			$content = $this->show8();
			break;
			case '9':
			$content = $this->show9();
			break;
			case 'generate':
			$content = $this->generate_anggota();
			break;
			
			default:
			$content = 'Pilih Tab';
			break;
		}

		return ['content'=>$content];
	}

	// PAGE 1
	function show1(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian_wk.pages.satu',$data)->render();

		return $content;
	}

	// PAGE 2
	function pages2(Request $request){
		$i = $request->i;
		$content = 'Pilih Tab';

		switch ($i) {
			case '1':
			$content = $this->show21();
			break;
			case '2':
			$content = $this->show22();
			break;
			case '3':
			$content = $this->show23();
			break;
			case '4':
			$content = $this->show24();
			break;
			
			default:
			$content = 'Pilih Tab';
			break;
		}

		return ['content'=>$content];
	}

	function show21(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku as dp')
				->join($this->schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel'")->get();
				$arr_nilai = [];
				$uraian = ['xxxxx'];
				$nilai = ['xxxxx'];

				if($get_nilai->count()!=0){
					foreach($get_nilai as $v){
						array_push($uraian, $v->bulan);
						array_push($nilai, $v->ibadah);
					}

					array_push($arr_nilai, ['uraian'=>$uraian,'nilai'=>$nilai]);
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian_wk.pages.dua.satu',$data)->render();

		return $content;
	}

	function show22(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku as dp')
				->join($this->schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel'")->get();
				$arr_nilai = [];
				$uraian = ['xxxxx'];
				$nilai = ['xxxxx'];

				if($get_nilai->count()!=0){
					foreach($get_nilai as $v){
						array_push($uraian, $v->bulan);
						array_push($nilai, $v->syukur);
					}

					array_push($arr_nilai, ['uraian'=>$uraian,'nilai'=>$nilai]);
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian_wk.pages.dua.dua',$data)->render();

		return $content;
	}

	function show23(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku as dp')
				->join($this->schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel'")->get();
				$arr_nilai = [];
				$uraian = ['xxxxx'];
				$nilai = ['xxxxx'];

				if($get_nilai->count()!=0){
					foreach($get_nilai as $v){
						array_push($uraian, $v->bulan);
						array_push($nilai, $v->berdoa);
					}

					array_push($arr_nilai, ['uraian'=>$uraian,'nilai'=>$nilai]);
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian_wk.pages.dua.tiga',$data)->render();

		return $content;
	}

	function show24(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku as dp')
				->join($this->schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel'")->get();
				$arr_nilai = [];
				$uraian = ['xxxxx'];
				$nilai = ['xxxxx'];

				if($get_nilai->count()!=0){
					foreach($get_nilai as $v){
						array_push($uraian, $v->bulan);
						array_push($nilai, $v->toleransi);
					}

					array_push($arr_nilai, ['uraian'=>$uraian,'nilai'=>$nilai]);
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian_wk.pages.dua.empat',$data)->render();

		return $content;
	}

	// PAGE 3
	function pages3(Request $request){
		$i = $request->i;
		$content = 'Pilih Tab';

		switch ($i) {
			case '1':
			$content = $this->show31();
			break;
			case '2':
			$content = $this->show32();
			break;
			case '3':
			$content = $this->show33();
			break;
			case '4':
			$content = $this->show34();
			break;
			case '5':
			$content = $this->show35();
			break;
			case '6':
			$content = $this->show36();
			break;
			case '7':
			$content = $this->show37();
			break;
			
			default:
			$content = 'Pilih Tab';
			break;
		}

		return ['content'=>$content];
	}

	function show31(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku as dp')
				->join($this->schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel'")->get();
				$arr_nilai = [];
				$uraian = ['xxxxx'];
				$nilai = ['xxxxx'];

				if($get_nilai->count()!=0){
					foreach($get_nilai as $v){
						array_push($uraian, $v->bulan);
						array_push($nilai, $v->jujur);
					}

					array_push($arr_nilai, ['uraian'=>$uraian,'nilai'=>$nilai]);
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian_wk.pages.tiga.satu',$data)->render();

		return $content;
	}

	function show32(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku as dp')
				->join($this->schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel'")->get();
				$arr_nilai = [];
				$uraian = ['xxxxx'];
				$nilai = ['xxxxx'];

				if($get_nilai->count()!=0){
					foreach($get_nilai as $v){
						array_push($uraian, $v->bulan);
						array_push($nilai, $v->disiplin);
					}

					array_push($arr_nilai, ['uraian'=>$uraian,'nilai'=>$nilai]);
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian_wk.pages.tiga.dua',$data)->render();

		return $content;
	}

	function show33(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku as dp')
				->join($this->schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel'")->get();
				$arr_nilai = [];
				$uraian = ['xxxxx'];
				$nilai = ['xxxxx'];

				if($get_nilai->count()!=0){
					foreach($get_nilai as $v){
						array_push($uraian, $v->bulan);
						array_push($nilai, $v->tanggung_jawab);
					}

					array_push($arr_nilai, ['uraian'=>$uraian,'nilai'=>$nilai]);
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian_wk.pages.tiga.tiga',$data)->render();

		return $content;
	}

	function show34(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku as dp')
				->join($this->schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel'")->get();
				$arr_nilai = [];
				$uraian = ['xxxxx'];
				$nilai = ['xxxxx'];

				if($get_nilai->count()!=0){
					foreach($get_nilai as $v){
						array_push($uraian, $v->bulan);
						array_push($nilai, $v->sopan_santun);
					}

					array_push($arr_nilai, ['uraian'=>$uraian,'nilai'=>$nilai]);
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian_wk.pages.tiga.empat',$data)->render();

		return $content;
	}

	function show35(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku as dp')
				->join($this->schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel'")->get();
				$arr_nilai = [];
				$uraian = ['xxxxx'];
				$nilai = ['xxxxx'];

				if($get_nilai->count()!=0){
					foreach($get_nilai as $v){
						array_push($uraian, $v->bulan);
						array_push($nilai, $v->peduli);
					}

					array_push($arr_nilai, ['uraian'=>$uraian,'nilai'=>$nilai]);
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian_wk.pages.tiga.lima',$data)->render();

		return $content;
	}

	function show36(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku as dp')
				->join($this->schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel'")->get();
				$arr_nilai = [];
				$uraian = ['xxxxx'];
				$nilai = ['xxxxx'];

				if($get_nilai->count()!=0){
					foreach($get_nilai as $v){
						array_push($uraian, $v->bulan);
						array_push($nilai, $v->percaya_diri);
					}

					array_push($arr_nilai, ['uraian'=>$uraian,'nilai'=>$nilai]);
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian_wk.pages.tiga.enam',$data)->render();

		return $content;
	}

	function show37(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku as dp')
				->join($this->schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
				->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel'")->get();
				$arr_nilai = [];
				$uraian = ['xxxxx'];
				$nilai = ['xxxxx'];

				if($get_nilai->count()!=0){
					foreach($get_nilai as $v){
						array_push($uraian, $v->bulan);
						array_push($nilai, $v->kerjasama);
					}

					array_push($arr_nilai, ['uraian'=>$uraian,'nilai'=>$nilai]);
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian_wk.pages.tiga.tujuh',$data)->render();

		return $content;
	}

	// PAGE 4
	function show4(){
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		if($jenjang=='SD'){
			$tampil = $this->page4smp();
		}else{
			$tampil = $this->page4smp();
		}

		$data = [
			'tampil'=>$tampil,
			'nama_schema'=>$nama_schema,
		];

		$content = view('guru.walikelas.isian_wk.pages.empat',$data)->render();

		return $content;
	}

	function page4smp(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$id_rombel = Session::get('id_rombel');
		$jenjang = Session::get('jenjang');
		$jumkd		= 6;

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$get_siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->selectRaw("s.nama,s.id_siswa as idsiswa")
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$tampil = [];

		$id=1;
		foreach($get_siswa as $k=>$v){
			$sikap = Hitung_sikap::nilai_sikap($v->idsiswa);

			$baris = [
				'id'=>$id,
				'nama'=>$v->nama,
				'hurufk1'=>(!empty($sikap)) ? $sikap['hurufk1'] : '',
				'catatan1'=>(!empty($sikap)) ? $sikap['catatan1'] : '',
				'hurufk2'=>(!empty($sikap)) ? $sikap['hurufk2'] : '',
				'catatan2'=>(!empty($sikap)) ? $sikap['catatan2'] : '',
			];

			array_push($tampil,$baris);
			$id++;
		}

		return $tampil;
	}

	// PAGE 5
	function show5(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$ekskul = DB::connection($conn)->table('public.rapor_master_ekskul')->orderBy('nama_ekskul','asc')->get();

		$data = [
			'siswa'=>$siswa,
			'ekskul'=>$ekskul,
			'nama_schema'=>$nama_schema,
		];

		$content = view('guru.walikelas.isian_wk.pages.lima',$data)->render();

		return $content;
	}

	// PAGE 6
	function show6(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("id_siswa='$s->id_siswa' AND rombongan_belajar_id='$id_rombel'")->first();
				if(!empty($anggota)){
					$nilai = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->selectRaw("*,COALESCE(sakit,0) as sakit,COALESCE(izin,0) as izin,COALESCE(tanpa_keterangan,0) as tanpa_keterangan")->whereRaw("anggota_rombel_id='$anggota->id_anggota_rombel' AND npsn='$npsn'")->first();
					if(!empty($nilai)){
						$s->izin = $nilai->izin;
						$s->sakit = $nilai->sakit;
						$s->tanpa_keterangan = $nilai->tanpa_keterangan;
					}else{
						$s->izin = '0';
						$s->sakit = '0';
						$s->tanpa_keterangan = '0';
					}
				}else{
					$s->izin = '0';
					$s->sakit = '0';
					$s->tanpa_keterangan = '0';
				}
			}
		}

		$data = [
			'siswa'=>$siswa,
			'nama_schema'=>$nama_schema,
		];

		$content = view('guru.walikelas.isian_wk.pages.enam',$data)->render();

		return $content;
	}

	// PAGE 7
	function show7(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("id_siswa='$s->id_siswa' AND rombongan_belajar_id='$id_rombel'")->first();
				if(!empty($anggota)){
					$nilai = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->selectRaw("*,COALESCE(sakit,0) as sakit,COALESCE(izin,0) as izin,COALESCE(tanpa_keterangan,0) as tanpa_keterangan")->whereRaw("anggota_rombel_id='$anggota->id_anggota_rombel' AND npsn='$npsn'")->first();
					if(!empty($nilai)){
						$s->catatan = $nilai->catatan_siswa;
					}else{
						$s->catatan = '';
					}
				}else{
					$s->catatan = '';
				}
			}
		}

		$data = [
			'siswa'=>$siswa,
			'nama_schema'=>$nama_schema,
		];

		$content = view('guru.walikelas.isian_wk.pages.tujuh',$data)->render();

		return $content;
	}

	// PAGE 8
	function show8(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$data = [
			'siswa'=>$siswa,
			'nama_schema'=>$nama_schema,
		];

		$content = view('guru.walikelas.isian_wk.pages.delapan',$data)->render();

		return $content;
	}

	// PAGE 9
	function show9(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama')->get();

		$data = [
			'siswa'=>$siswa,
			'nama_schema'=>$nama_schema,
		];

		$content = view('guru.walikelas.isian_wk.pages.sembilan',$data)->render();

		return $content;
	}

	function simpan_nilai(Request $request){
		$id_siswa = $request->id_siswa;
		$kolom = explode('_',$request->kolom);
		$data_nilai = $request->nilai;
		$npsn = Session::get('npsn');
		$id_rombel = Session::get('id_rombel');
		$no_ki = $request->no_ki;

		$request->jenjang = Session::get('jenjang');

		$conn = Setkoneksi::set_koneksi($request);

		$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("id_siswa='$id_siswa' AND rombongan_belajar_id='$id_rombel'")->first();
		$id_anggota = '';
		if(!empty($anggota)){
			$id_anggota = $anggota->id_anggota_rombel;
		}

		$data_nilai_perilaku = [
			'anggota_rombel_id'=>$id_anggota,
			'npsn'=>$npsn,
		];

		if($id_anggota!=''){
			$id_nilai_perilaku='';
			while($id_nilai_perilaku=='') {
				$get_nilai_perilaku = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->where($data_nilai_perilaku)->first();
				if(!empty($get_nilai_perilaku)){
					$id_nilai_perilaku = $get_nilai_perilaku->id_nilai_perilaku;
				}else{
					$simpan = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->insert($data_nilai_perilaku);
					$get_nilai_perilaku = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->where($data_nilai_perilaku)->first();
					$id_nilai_perilaku = $get_nilai_perilaku->id_nilai_perilaku;
				}
			};
		}


		if($id_nilai_perilaku!=''){			
			$data_detail = [
				'nilai_perilaku_id'=>$id_nilai_perilaku,
				'bulan'=>$kolom[1],
			];

			$where = $data_detail;

			$cari_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku')->where($where)->first();
			if(!empty($cari_nilai)){
				$nilai = new Request;
				$nilai->ibadah = ($kolom[0]=='ibadah') ? $data_nilai : $cari_nilai->ibadah;
				$nilai->syukur = ($kolom[0]=='syukur') ? $data_nilai : $cari_nilai->syukur;
				$nilai->berdoa = ($kolom[0]=='berdoa') ? $data_nilai : $cari_nilai->berdoa;
				$nilai->toleransi = ($kolom[0]=='toleransi') ? $data_nilai : $cari_nilai->toleransi;
				$nilai->jujur = ($kolom[0]=='jujur') ? $data_nilai : $cari_nilai->jujur;
				$nilai->disiplin = ($kolom[0]=='disiplin') ? $data_nilai : $cari_nilai->disiplin;
				$nilai->tanggung_jawab = ($kolom[0]=='tanggung_jawab') ? $data_nilai : $cari_nilai->tanggung_jawab;
				$nilai->sopan_santun = ($kolom[0]=='sopan_santun') ? $data_nilai : $cari_nilai->sopan_santun;
				$nilai->peduli = ($kolom[0]=='peduli') ? $data_nilai : $cari_nilai->peduli;
				$nilai->percaya_diri = ($kolom[0]=='percaya_diri') ? $data_nilai : $cari_nilai->percaya_diri;
				$nilai->kerjasama = ($kolom[0]=='kerjasama') ? $data_nilai : $cari_nilai->kerjasama;

				$data_detail = array_merge($data_detail,[
					'ibadah'=>$nilai->ibadah,
					'syukur'=>$nilai->syukur,
					'berdoa'=>$nilai->berdoa,
					'toleransi'=>$nilai->toleransi,
					'jujur'=>$nilai->jujur,
					'disiplin'=>$nilai->disiplin,
					'tanggung_jawab'=>$nilai->tanggung_jawab,
					'sopan_santun'=>$nilai->sopan_santun,
					'peduli'=>$nilai->peduli,
					'percaya_diri'=>$nilai->percaya_diri,
					'kerjasama'=>$nilai->kerjasama,
					'created_at'=>date('Y-m-d H:i:s'),
					'updated_at'=>date('Y-m-d H:i:s'),
				]);

				$update = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku')->where($where)->update($data_detail);
			}else{
				$nilai = new Request;
				$nilai->ibadah = ($kolom[0]=='ibadah') ? $data_nilai : '3';
				$nilai->syukur = ($kolom[0]=='syukur') ? $data_nilai : '3';
				$nilai->berdoa = ($kolom[0]=='berdoa') ? $data_nilai : '3';
				$nilai->toleransi = ($kolom[0]=='toleransi') ? $data_nilai : '3';
				$nilai->jujur = ($kolom[0]=='jujur') ? $data_nilai : '3';
				$nilai->disiplin = ($kolom[0]=='disiplin') ? $data_nilai : '3';
				$nilai->tanggung_jawab = ($kolom[0]=='tanggung_jawab') ? $data_nilai : '3';
				$nilai->sopan_santun = ($kolom[0]=='sopan_santun') ? $data_nilai : '3';
				$nilai->peduli = ($kolom[0]=='peduli') ? $data_nilai : '3';
				$nilai->percaya_diri = ($kolom[0]=='percaya_diri') ? $data_nilai : '3';
				$nilai->kerjasama = ($kolom[0]=='kerjasama') ? $data_nilai : '3';

				$data_detail = array_merge($data_detail,[
					'ibadah'=>$nilai->ibadah,
					'syukur'=>$nilai->syukur,
					'berdoa'=>$nilai->berdoa,
					'toleransi'=>$nilai->toleransi,
					'jujur'=>$nilai->jujur,
					'disiplin'=>$nilai->disiplin,
					'tanggung_jawab'=>$nilai->tanggung_jawab,
					'sopan_santun'=>$nilai->sopan_santun,
					'peduli'=>$nilai->peduli,
					'percaya_diri'=>$nilai->percaya_diri,
					'kerjasama'=>$nilai->kerjasama,
					'created_at'=>date('Y-m-d H:i:s'),
					'updated_at'=>date('Y-m-d H:i:s'),
				]);
				$update = DB::connection($conn)->table($this->schema.'.detail_nilai_perilaku')->insert($data_detail);
			}

			if($update){
				$return = ['code'=>'200','title'=>'Success','message'=>'Berhasil disimpan','type'=>'success'];
			}else{
				$return = ['code'=>'250','title'=>'Whooops','message'=>'Gagal disimpan','type'=>'error'];
			}
		}else{
			$return = ['code'=>'250','title'=>'Whooops','message'=>'ID nilai Perilaku gagal digenerate','type'=>'error'];
		}

		return $return;
	}

	function cetak_rapor(Request $request){
		$schema = $request->schema;
		$id_siswa = $request->id_siswa;
		$jenjang = Session::get('jenjang');
		if($jenjang=='SD'){
			$content = $this->data_smp($request);
		}else{
			$content = $this->data_smp($request);
		}

		return $content;
	}

	function data_smp(Request $request){
		$jenjang = Session::get('jenjang');
		$id_siswa = $request->id_siswa;
		$nama_schema = $request->schema;
		$id_rombel = Session::get('id_rombel');
		$semester = substr(Session::get('semester_wk'),9);
		$tahun_ajaran = Session::get('ta_wk');
		$npsn = Session::get('npsn');
		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->join('public.sekolah as sek','sek.npsn','s.npsn')
		->leftjoin('public.kecamatan as kec','kec.kecamatan_kode','sek.kec_id')
		->leftjoin('public.kelurahan as kel','kel.kelurahan_kode','sek.desa')
		->leftjoin('public.agama as a','a.aga_id','s.aga_id')
		->leftjoin('public.wali_murid as wm',function($join){
			return $join->on('wm.id_siswa','=','s.id_siswa')->on('wm.npsn','=','s.npsn');
		})
		->leftjoin('public.pekerjaan as pa','pa.kode','wm.pekerjaan_ayah')
		->leftjoin('public.pekerjaan as pi','pi.kode','wm.pekerjaan_ibu')
		->leftjoin('public.pekerjaan as pw','pw.kode','wm.pekerjaan_wali')
		->leftjoin('public.tahun_ajaran as ta','ta.id_tahun_ajaran','rb.tahun_ajaran_id')
		->selectRaw("s.foto,s.nama as nama_siswa,s.tgl_lahir,s.nisn,s.nis,s.tempat_lahir,rb.kelas,rb.rombel,sek.kepala,sek.email as email_sekolah,sek.website as website_sekolah,s.id_siswa,sek.nama as nama_sekolah,sek.alamat as alamat_sekolah,kec.kecamatan_dispenduk,kel.kelurahan_dispenduk,s.kelamin,s.asal_sekolah,a.aga_nama,s.status_anak,s.anakke,s.alamat_ortu,s.telpon,s.alamat as alamat_siswa,wm.nama_ayah as ayah,wm.nama_ibu as ibu,wm.nama_wali,wm.pekerjaan_wali,pa.nama as pekerjaan_ayah,pi.nama as pekerjaan_ibu,pw.nama as pekerjaan_wali,wm.alamat_rumah,wm.rt,wm.rw,sek.kkm,sek.nss,ta.tgl_setting_awal,ta.tgl_setting_akhir,ar.id_anggota_rombel")
		->whereRaw("rb.id_rombongan_belajar='$id_rombel' AND s.npsn='$npsn' AND s.id_siswa='$id_siswa'")->first();

		$ks = DB::connection($conn)->table('public.pegawai as p')
		->leftjoin('public.gelar_akademik as gd',function($join){
			return $join->on('p.gelar','=',DB::raw('CAST(gd.gelar_akademik_id as varchar)'));
		})
		->leftjoin('public.gelar_akademik as gb',function($join){
			return $join->on('p.gelar2','=',DB::raw('CAST(gb.gelar_akademik_id as varchar)'));
		})
		->selectRaw("p.nama as nama_ks,gd.kode as gelar_depan,gb.kode as gelar_belakang,CONCAT('NIP. ',p.nip) as nip")
		->whereRaw("(npsn='".Session::get('npsn')."' or sekolah_plt='".Session::get('npsn')."') AND jabatan='2' AND keterangan='Aktif'")->first();

		$qrcode = QrCode::size(50)->generate($request->url());

		$sikap = DB::connection($conn)->table($this->schema.'.nilai_perilaku')
		->selectRaw("*,COALESCE(sakit,0) as sakit,COALESCE(izin,0) as izin, COALESCE(tanpa_keterangan,0) as tanpa_keterangan")
		->whereRaw("anggota_rombel_id='$siswa->id_anggota_rombel' AND npsn='$npsn'")->first();
		
		$nilaia = DB::connection($conn)->table($this->schema.'.nilai_mapel as na')
		->leftjoin($this->schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id');
		})
		->leftjoin('public.rapor_mapel as ma',function($join){
			return $join->on('ma.mapel_id','=','na.mapel_id');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.nik','=','m.nik_pengajar')->on('peg.peg_id','=','m.peg_id');
		})
		->selectRaw("ma.nama as mapel,peg.nama as guru_mengajar,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4,75 as kkm")
		->whereRaw("na.anggota_rombel_id='$siswa->id_anggota_rombel' AND ma.kategori IN ('KELOMPOK A','WAJIB','A. MATA PELAJARAN')")
		->groupByRaw("ma.nama,peg.nama,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4")
		->orderBy('ma.nama','ASC')->get();

		$nilaib = DB::connection($conn)->table($this->schema.'.nilai_mapel as na')
		->leftjoin($this->schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id');
		})
		->leftjoin('public.rapor_mapel as ma',function($join){
			return $join->on('ma.mapel_id','=','na.mapel_id');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.nik','=','m.nik_pengajar')->on('peg.peg_id','=','m.peg_id');
		})
		->selectRaw("ma.nama as mapel,peg.nama as guru_mengajar,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4,75 as kkm")
		->whereRaw("na.anggota_rombel_id='$siswa->id_anggota_rombel' AND ma.kategori IN ('KELOMPOK B','MUATAN LOKAL')")
		->groupByRaw("ma.nama,peg.nama,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4")
		->orderBy('ma.nama','ASC')->get();

		$nilai_agama = DB::connection($conn)->table($this->schema.'.nilai_mapel as na')
		->leftjoin($this->schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id');
		})
		->leftjoin('public.rapor_mapel as ma',function($join){
			return $join->on('ma.mapel_id','=','na.mapel_id');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.nik','=','m.nik_pengajar')->on('peg.peg_id','=','m.peg_id');
		})
		->selectRaw("ma.nama as mapel,peg.nama as guru_mengajar,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4,75 as kkm")
		->whereRaw("na.anggota_rombel_id='$siswa->id_anggota_rombel' AND ma.kategori IN ('AGAMA ISLAM')")
		->groupByRaw("ma.nama,peg.nama,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4")
		->orderBy('ma.nama','ASC')->get();

		$prestasi = DB::connection($conn)->table('public.prestasi_siswa')->whereRaw("id_siswa='$siswa->id_siswa' AND npsn='".Session::get('npsn')."'")->get();
		
		$walikelas = DB::connection($conn)->table('public.rombongan_belajar as wk')
		->leftjoin('public.pegawai as p',function($join){
			return $join->on('wk.wali_kelas_peg_id','=',DB::raw("CAST(p.peg_id as varchar)"))->on('wk.nik_wk','=','p.nik');
		})
		->leftjoin('public.gelar_akademik as gd',function($join){
			return $join->on('p.gelar','=',DB::raw('CAST(gd.gelar_akademik_id as varchar)'));
		})
		->leftjoin('public.gelar_akademik as gb',function($join){
			return $join->on('p.gelar2','=',DB::raw('CAST(gb.gelar_akademik_id as varchar)'));
		})
		->selectRaw("p.nama as nama_wk,gd.kode as gelar_depan,gb.kode as gelar_belakang,CONCAT('NIP. ',p.nip) as nip")
		->whereRaw("wk.npsn='".Session::get('npsn')."' AND wk.id_rombongan_belajar='$id_rombel'")->first();

		//$ekskul = DB::connection($conn)->table($nama_schema.'.ekskul_absen')->whereRaw("npsn='".Session::get('npsn')."' AND id_siswa='$siswa_id' AND kelas='$kelas->kelas' AND rombel='$kelas->rombel'")->first();

		$kenaikan = 'Tidak naik kelas';
		if(!empty($sikap)){
			if(is_null($sikap->kenaikan_kelas) || $sikap->kenaikan_kelas==true){
				$kenaikan = 'Naik ke kelas '.($kelas+1);
			}
		}

		// $kesehatan1 = new Request;
		// $kesehatan2 = new Request;
		
		$smt1 = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join($this->schema.'.nilai_perilaku as np','np.anggota_rombel_id','ar.id_anggota_rombel')
		->whereRaw("rb.kelas='$kelas' AND rb.rombel='$rombel' AND rb.semester='1' AND rb.npsn='$npsn' AND ar.id_siswa='$id_siswa'")->first();

		$smt2 = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join($this->schema.'.nilai_perilaku as np','np.anggota_rombel_id','ar.id_anggota_rombel')
		->whereRaw("rb.kelas='$kelas' AND rb.rombel='$rombel' AND rb.semester='2' AND rb.npsn='$npsn' AND ar.id_siswa='$id_siswa'")->first();

		// $kesehatan1->tinggi = (!empty($smt1)) ? $smt1->tinggi_badan : '';
		// $kesehatan1->berat = (!empty($smt1)) ? $smt1->berat_badan : '';
		// $kesehatan1->dengar = (!empty($smt1)) ? $smt1->pendengaran : '';
		// $kesehatan1->lihat = (!empty($smt1)) ? $smt1->penglihatan : '';
		// $kesehatan1->gigi = (!empty($smt1)) ? $smt1->gizi : '';
		// $kesehatan1->lain = (!empty($smt1)) ? $smt1->lainnya : '';

		// $kesehatan2->tinggi = (!empty($smt2)) ? $smt2->tinggi_badan : '';
		// $kesehatan2->berat = (!empty($smt2)) ? $smt2->berat_badan : '';
		// $kesehatan2->dengar = (!empty($smt2)) ? $smt2->pendengaran : '';
		// $kesehatan2->lihat = (!empty($smt2)) ? $smt2->penglihatan : '';
		// $kesehatan2->gigi = (!empty($smt2)) ? $smt2->gizi : '';
		// $kesehatan2->lain = (!empty($smt2)) ? $smt2->lainnya : '';

		$data = [
			'qrcode'=>$qrcode,
			'siswa'=>$siswa,
			'ks'=>$ks,
			'semester'=>$semester,
			'tahun_ajaran'=>$tahun_ajaran,
			'pagesnya'=>($jenjang=='SD') ? 'pages' : 'pages_smp',
			'foto'=>'https://profilsekolah.dispendik.surabaya.go.id/profilsekolahlama/foto/siswa/'.$siswa->foto,
			'sikap'=>$sikap,
			'nilaia'=>$nilaia,
			'nilaib'=>$nilaib,
			'nilai_agama'=>$nilai_agama,
			'prestasi'=>$prestasi,
			'walikelas'=>$walikelas,
			'sisipan'=>$request->sisipan,
			'kenaikan'=>$kenaikan,
			'kesehatan1'=>$smt1,
			'kesehatan2'=>$smt2,
		];

		$content = view('siswa.rapor.data',$data)->render();
		
		return $content;
	}

	function simpan_ekskul(Request $request){
		return $request->all();
		$ekskul = $request->ekskul; // ["Ngaji", "pramuka", null]
		$id_siswa = $request->id_siswa; // "35451514"
		$nilai = $request->nilai; // ["Baik", "Baik", null]
		$schema = $request->schema; // "rapor_sd_2021_ganjil"
		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$get_ekskul = DB::connection($conn)->table($schema.'.ekskul_absen')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND id_siswa='$id_siswa'")->first();

		$data_insert = [];

		for ($i=0; $i < count($ekskul); $i++) { 
			$data_insert = array_merge($data_insert,[
				'ekskul_'.($i+1) => $ekskul[$i],
				'nilai_ekskul_'.($i+1) => $nilai[$i],
			]);
		}

		if(!empty($get_ekskul)){
			$simpan = DB::connection($conn)->table($schema.'.ekskul_absen')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND id_siswa='$id_siswa'")->update($data_insert);
		}else{
			$data_insert = array_merge($data_insert,[
				'id_siswa'=>$id_siswa,
				'kelas'=>$kelas,
				'rombel'=>$rombel,
				'npsn'=>$npsn,
			]);
			$simpan = DB::connection($conn)->table($schema.'.ekskul_absen')->insert($data_insert);
		}

		if($simpan){
			$return = ['code'=>'200','title'=>'Success','message'=>'Berhasil disimpan','type'=>'success'];
		}else{
			$return = ['code'=>'250','title'=>'Whooops','message'=>'Gagal disimpan','type'=>'error'];
		}

		return $return;
	}

	function simpan_absen(Request $request){
		$absen = $request->absen; // ["Ngaji", "pramuka", null]
		$id_siswa = $request->id_siswa; // "35451514"
		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$id_rombel = Session::get('id_rombel');

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("id_siswa='$id_siswa' AND rombongan_belajar_id='$id_rombel'")->first();
		if(!empty($anggota)){
			$get_ekskul = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->whereRaw("npsn='$npsn' AND anggota_rombel_id='$anggota->id_anggota_rombel'")->first();

			$data_insert = [
				'sakit'=>$absen[0],
				'izin'=>$absen[1],
				'tanpa_keterangan'=>$absen[2],
			];

			if(!empty($get_ekskul)){
				$simpan = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->whereRaw("npsn='$npsn' AND anggota_rombel_id='$anggota->id_anggota_rombel'")->update($data_insert);
			}else{
				$data_insert = array_merge($data_insert,[
					'anggota_rombel_id'=>$anggota->id_anggota_rombel,
					'npsn'=>$npsn,
				]);
				$simpan = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->insert($data_insert);
			}

			if($simpan){
				$return = ['code'=>'200','title'=>'Success','message'=>'Berhasil disimpan','type'=>'success'];
			}else{
				$return = ['code'=>'250','title'=>'Whooops','message'=>'Gagal disimpan','type'=>'error'];
			}
		}else{
			$return = ['code'=>'250','title'=>'Whooops','message'=>'Siswa tidak termasuk dalam anggota rombel','type'=>'error'];			
		}

		return $return;
	}

	function simpan_catatan(Request $request){
		$catatan = $request->catatan; // ["Ngaji", "pramuka", null]
		$id_siswa = $request->id_siswa; // "35451514"
		$schema = $request->schema; // "rapor_sd_2021_ganjil"
		$kolom = $request->kolom; // "rapor_sd_2021_ganjil"
		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$id_rombel = Session::get('id_rombel');

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$data_insert = [
			$kolom=>$catatan,
		];

		$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("id_siswa='$id_siswa' AND rombongan_belajar_id='$id_rombel'")->first();
		if(!empty($anggota)){
			$get_ekskul = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->whereRaw("npsn='$npsn' AND anggota_rombel_id='$anggota->id_anggota_rombel'")->first();

			if(!empty($get_ekskul)){
				$simpan = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->whereRaw("npsn='$npsn' AND anggota_rombel_id='$anggota->id_anggota_rombel'")->update($data_insert);
			}else{
				$data_insert = array_merge($data_insert,[
					'anggota_rombel_id'=>$anggota->id_anggota_rombel,
					'npsn'=>$npsn,
				]);
				$simpan = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->insert($data_insert);
			}

			if($simpan){
				$return = ['code'=>'200','title'=>'Success','message'=>'Berhasil disimpan','type'=>'success'];
			}else{
				$return = ['code'=>'250','title'=>'Whooops','message'=>'Gagal disimpan','type'=>'error'];
			}
		}else{
			$return = ['code'=>'250','title'=>'Whooops','message'=>'Siswa tidak termasuk dalam anggota rombel','type'=>'error'];			
		}

		return $return;
	}

	function modal_kesehatan(Request $request){
		$coni = new Request;
		$id_siswa = $request->id_siswa;
		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$id_rombel = Session::get('id_rombel');
		$schema = $request->schema;

		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->whereRaw("ar.id_siswa='$id_siswa' AND ar.rombongan_belajar_id='$id_rombel'")->first();

		if(!empty($siswa)){
			$nilai = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->whereRaw("npsn='$npsn' AND anggota_rombel_id='$siswa->id_anggota_rombel'")->first();
		}else{
			$nilai = '';
		}

		$data = [
			'siswa'=>$siswa,
			'nama_schema'=>$schema,
			'nilai'=>$nilai,
		];

		$content = view('guru.walikelas.isian_wk.pages.delapan.modal',$data)->render();

		return ['content'=>$content];
	}

	function simpan_kesehatan(Request $request){
		$tinggi = $request->tinggi;
		$beratbadan = $request->beratbadan;
		$lihat = $request->lihat;
		$dengar = $request->dengar;
		$gigi = $request->gigi;
		$lainnya = $request->lainnya;

		$id_siswa = $request->id_siswa;
		$schema = $request->schema;
		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$id_rombel = Session::get('id_rombel');

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$data_insert = [];

		$data_insert = array_merge($data_insert,[
			'tinggi_badan' => $tinggi[0],
			'berat_badan' => $beratbadan[0],
			'pendengaran' => $dengar[0],
			'penglihatan' => $lihat[0],
			'gizi' => $gigi[0],
			'lainnya' => $lainnya[0],
		]);	

		$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("id_siswa='$id_siswa' AND rombongan_belajar_id='$id_rombel'")->first();
		if(!empty($anggota)){
			$get_ekskul = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->whereRaw("npsn='$npsn' AND anggota_rombel_id='$anggota->id_anggota_rombel'")->first();

			if(!empty($get_ekskul)){
				$simpan = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->whereRaw("npsn='$npsn' AND anggota_rombel_id='$anggota->id_anggota_rombel'")->update($data_insert);
			}else{
				$data_insert = array_merge($data_insert,[
					'anggota_rombel_id'=>$anggota->id_anggota_rombel,
					'npsn'=>$npsn,
				]);
				$simpan = DB::connection($conn)->table($this->schema.'.nilai_perilaku')->insert($data_insert);
			}

			if($simpan){
				$return = ['code'=>'200','title'=>'Success','message'=>'Berhasil disimpan','type'=>'success'];
			}else{
				$return = ['code'=>'250','title'=>'Whooops','message'=>'Gagal disimpan','type'=>'error'];
			}
		}else{
			$return = ['code'=>'250','title'=>'Whooops','message'=>'Siswa tidak termasuk dalam anggota rombel','type'=>'error'];			
		}

		return $return;
	}

	function cetak_dkn(Request $request){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$id_rombel = Session::get('id_rombel');
		$nama_schema = $this->schema;
		$jenjang = Session::get('jenjang');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$sekolah = DB::connection($conn)->table('public.sekolah as a')
		->whereRaw("a.npsn='$npsn'")
		->first();

		// COLSPAN KOLOM KELOMPOK
		if($npsn=='20533172' or $npsn=='20533148'){
			$where_mapel = "nm.mapel_id not in ( 11, 12, 13, 14, 15) and nm.anggota_rombel_id = '".$npsn."'";
		}elseif ($npsn=='20532825' and $kelas=='4' and $rombel=='D') {
			$where_mapel = "nm.mapel_id not in ( 12, 13, 14, 15) and nm.anggota_rombel_id = '".$npsn."'";
		}else{
			$where_mapel = "nm.mapel_id not in (10, 11, 12, 13, 14, 15) and nm.anggota_rombel_id = '".$npsn."'";
		}

		// DATA MAPEL
		if($npsn=='20533172' or $npsn=='20533148' or $npsn=='20533510'){
			$where_nama = "mapel_id not in (10,11, 12, 13, 14, 15)";
		}elseif ($npsn=='20532825' and $kelas=='4' and $rombel=='D') {
			$where_nama = "mapel_id not in (10, 12, 13, 14, 15)";
		}else{
			$where_nama = "mapel_id not in (10, 11, 12, 13, 14, 15)";
		}

		// DATA SISWA
		if($npsn=='20532318' or $npsn=='20533540' or $npsn=='20533423' or $npsn=='20533523' or $npsn=='20539118' or $npsn=='20533172' or $npsn=='20533075' or $npsn=='20533148' or $npsn=='20533510'){
			$where_siswa = "(n.npsn=s.npsn OR n.npsn=npsn_asal) AND n.id_siswa=s.id_siswa AND n.npsn='".$npsn."' AND n.kelas='".$kelas."' AND n.rombel='".$rombel."' AND n.mapel_id=1";
		}elseif($npsn=='20532825' and $kelas=='4' and $rombel=='D') {
			$where_siswa = "(n.npsn=s.npsn OR n.npsn=npsn_asal) AND n.id_siswa=s.id_siswa AND n.npsn='".$npsn."' AND n.kelas='".$kelas."' AND n.rombel='".$rombel."'  ";

		}elseif($npsn=='20533421' and ($kelas=='1' or $kelas=='3')){
			$where_siswa = "(n.npsn=s.npsn OR n.npsn=npsn_asal) AND n.id_siswa=s.id_siswa AND n.npsn='".$npsn."' AND n.kelas='".$kelas."' AND n.rombel='".$rombel."'  ";
		}
		else{
			$where_siswa = "(n.npsn=s.npsn OR n.npsn=npsn_asal) AND n.id_siswa=s.id_siswa AND n.npsn='".$npsn."' AND n.kelas='".$kelas."' AND n.rombel='".$rombel."' AND n.mapel_id!=1";
		}

		$siswa = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->selectRaw("ar.id_anggota_rombel,s.nama,rb.kelas,rb.rombel,s.id_siswa")
		->whereRaw("ar.rombongan_belajar_id='$id_rombel' AND s.status_siswa='Aktif'")->orderBy('s.nama','ASC')->get();

		$rombel_genap = DB::connection($conn)->table('public.rombongan_belajar')->where('id_rombongan_belajar',$id_rombel)->first();
		$rombel_ganjil = DB::connection($conn)->table('public.rombongan_belajar')->whereRaw("kelas='$rombel_genap->kelas' AND rombel='$rombel_genap->rombel' AND npsn='$npsn' AND semester='1'")->first();
		$id_rombel_ganjil = $rombel_ganjil->id_rombongan_belajar;

		$kategori = DB::connection($conn)->table($this->schema.'.mengajar as nm')
		->join('public.rapor_mapel as m','m.mapel_id','nm.mapel_id')
		->selectRaw("m.kategori_baru")
		->whereRaw("nm.rombel_id IN ('$id_rombel','$id_rombel_ganjil')")
		->groupBy('m.kategori_baru')->get();

		$mapel = DB::connection($conn)->table($this->schema.'.mengajar as nm')->join('public.rapor_mapel as m','m.mapel_id','nm.mapel_id')->selectRaw("nm.mapel_id,m.nama")->whereRaw("nm.rombel_id IN ('$id_rombel','$id_rombel_ganjil')")->groupBy('nm.mapel_id','m.nama')->orderBy('m.nama','ASC')->get();

		foreach($kategori as $v){
			$jml = DB::connection($conn)->table($this->schema.'.mengajar as nm')->join('public.rapor_mapel as m','m.mapel_id','nm.mapel_id')->selectRaw("nm.mapel_id,m.nama")->whereRaw("nm.rombel_id IN ('$id_rombel','$id_rombel_ganjil') AND m.kategori_baru='$v->kategori_baru'")->groupBy('nm.mapel_id','m.nama')->orderBy('m.nama','ASC')->get();
			$v->jml = $jml->count();
		}

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$pengetahuan_1 = [];
				$keterampilan_1 = [];
				$pengetahuan_2 = [];
				$keterampilan_2 = [];
				foreach($mapel as $m){
					$n_mapel_1 = DB::connection($conn)->table($nama_schema.'.nilai_mapel as np')
					->join('public.anggota_rombel as ar','ar.id_anggota_rombel','np.anggota_rombel_id')
					->join('public.rombongan_belajar as rb','rb.id_rombongan_belajar','ar.rombongan_belajar_id')
					->selectRaw("COALESCE(np.nilai_ki3,0) as nilai_ki3,COALESCE(np.nilai_ki4,0) as nilai_ki4")
					->whereRaw("ar.rombongan_belajar_id='$id_rombel_ganjil' AND rb.semester='1' AND np.mapel_id='$m->mapel_id' AND ar.id_siswa='$s->id_siswa'")->first();

					$n_mapel_2 = DB::connection($conn)->table($nama_schema.'.nilai_mapel')->selectRaw("COALESCE(nilai_ki3,0) as nilai_ki3,COALESCE(nilai_ki4,0) as nilai_ki4" )->whereRaw("anggota_rombel_id='$s->id_anggota_rombel' AND mapel_id='$m->mapel_id'")->first();

					array_push($pengetahuan_1,(!empty($n_mapel_1)) ? number_format($n_mapel_1->nilai_ki3,0,',','.') : '');
					array_push($keterampilan_1,(!empty($n_mapel_1)) ? number_format($n_mapel_1->nilai_ki4,0,',','.') : '');

					array_push($pengetahuan_2,(!empty($n_mapel_2)) ? number_format($n_mapel_2->nilai_ki3,0,',','.') : '');
					array_push($keterampilan_2,(!empty($n_mapel_2)) ? number_format($n_mapel_2->nilai_ki4,0,',','.') : '');
				}

				$s->pengetahuan_1 = $pengetahuan_1;
				$s->keterampilan_1 = $keterampilan_1;
				$s->pengetahuan_2 = $pengetahuan_2;
				$s->keterampilan_2 = $keterampilan_2;
			}
		}

		$walikelas = DB::connection($conn)->table('public.rombongan_belajar as wk')
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('wk.wali_kelas_peg_id','=',DB::raw("CAST(peg.peg_id as varchar)"))->on('wk.nik_wk','=','peg.nik');
		})
		->leftjoin('public.gelar_akademik as g1',DB::raw("CAST(g1.gelar_akademik_id as varchar)"),'=','peg.gelar')
		->leftjoin('public.gelar_akademik as g2',DB::raw("CAST(g2.gelar_akademik_id as varchar)"),'=','peg.gelar2')
		->selectRaw("peg.gelar, peg.nama, peg.gelar2, peg.nip, peg.nuptk,g1.kode as gelar_depan,g2.kode as gelar_belakang, wk.npsn")->whereRaw("wk.npsn='$npsn' and wk.kelas='$kelas' and wk.rombel='$rombel'")->first();

		$ks = DB::connection($conn)->table('public.pegawai as peg')
		->leftjoin('public.gelar_akademik as g1',DB::raw("CAST(g1.gelar_akademik_id as varchar)"),'=','peg.gelar')
		->leftjoin('public.gelar_akademik as g2',DB::raw("CAST(g2.gelar_akademik_id as varchar)"),'=','peg.gelar2')
		->selectRaw("peg.gelar, peg.nama, peg.gelar2, peg.nip, peg.nuptk,g1.kode as gelar_depan,g2.kode as gelar_belakang, peg.npsn")
		->whereRaw("(npsn='$npsn' or sekolah_plt='$npsn') and jabatan='2' and keterangan='Aktif'")->first();

		$data = [
			'sekolah'=>$sekolah,
			'kelas'=>$kelas,
			'rombel'=>$rombel,
			'npsn'=>$npsn,
			'kategori'=>$kategori,
			'nama_mapel'=>$mapel,
			'siswa'=>$siswa,
			'nama_schema'=>$nama_schema,
			'walikelas'=>$walikelas,
			'ks'=>$ks,
			'semester'=>Session::get('ta_wk').' '.strtoupper(Session::get('semester_wk')),
			'excel'=>isset($request->excel) ? $request->excel : '',
		];

		$content = view('guru.walikelas.isian_wk.pages.cetak_dkn',$data)->render();

		return $content;
	}

	function generate_anggota(){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$id_rombel = Session::get('id_rombel');

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND (status_siswa='Aktif' AND alumni is not true)")->orderBy('nama','ASC')->get();
		$siswa_ta = DB::connection($conn)->table('public.rombongan_belajar as rb')
		->join('public.anggota_rombel as ar','ar.rombongan_belajar_id','rb.id_rombongan_belajar')
		->join('public.siswa as s',function($join){
			return $join->on('s.id_siswa','=','ar.id_siswa')->on('s.siswa_id','=','ar.siswa_id');
		})
		->selectRaw("rb.*,s.nama")
		->whereRaw("rb.id_rombongan_belajar='$id_rombel' AND s.npsn='$npsn' AND s.status_siswa='Aktif'")->orderBy('s.nama','ASC')->get();

		$data = [
			'siswa_skrg'=>$siswa,
			'siswa_ta'=>$siswa_ta,
		];

		$content = view('guru.walikelas.isian_wk.pages.anggota',$data)->render();
		return $content;
	}

	function do_generate_anggota(Request $request){
		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$npsn = Session::get('npsn');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND (status_siswa='Aktif' AND alumni is not true)")->get();

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$data = [
					'siswa_id'=>$s->id_siswa,
					'rombongan_belajar_id'=>$id_rombel,
				];

				$cek_exist = DB::connection($conn)->table('public.anggota_rombel')->where($data)->first();
				if(!empty($cek_exist)){
					
				}else{
					$simpan = DB::connection($conn)->table('public.anggota_rombel')->insert($data);
				}
			}
		}

		return ['code'=>'200'];
	}
}
