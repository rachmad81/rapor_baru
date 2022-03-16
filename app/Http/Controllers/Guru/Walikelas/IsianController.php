<?php

namespace App\Http\Controllers\Guru\Walikelas;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;
use App\Http\Libraries\Get_data;
use App\Http\Libraries\Convert;

use App\Http\Controllers\Controller;

use DB,Session,Redirect;

class IsianController extends Controller
{
	protected $schema;

	public function __construct() 
	{
		$this->schema = env('CURRENT_SCHEMA','production');
	}

	function main(Request $request){
		$id_rombel = $request->id_rombel;
		$mapel_id = $request->mapel_id;
		$jenjang = Session::get('jenjang');
		$request->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($request);

		$rombongan_belajar = DB::connection($conn)->table('public.rombongan_belajar as rb')->join('public.tahun_ajaran as ta','ta.id_tahun_ajaran','rb.tahun_ajaran_id')->where('rb.id_rombongan_belajar',$id_rombel)->first();

		if(!empty($rombongan_belajar)){
			Session::put('kelas_wk',$rombongan_belajar->kelas);
			Session::put('rombel_wk',$rombongan_belajar->rombel);
			Session::put('ta_wk',$rombongan_belajar->nama_tahun_ajaran);
			Session::put('semester_wk',($rombongan_belajar->semester==1) ? 'Semester Ganjil' : 'Semester Genap');
			$semester = ($rombongan_belajar->semester==1) ? 'Semester Ganjil' : 'Semester Genap';
		}else{
			Session::put('kelas_wk','0xx');
			Session::put('rombel_wk','0xx');
			Session::put('ta_wk','0xx');
			Session::put('semester_wk','0xx');
			$semester = '';
		}
		$mengajar = DB::connection($conn)->table($this->schema.'.mengajar as m')
		->join('public.rapor_mapel as ma','ma.mapel_id','m.mapel_id')
		->selectRaw("*,ma.nama as nama_mapel")
		->whereRaw("m.rombel_id='$id_rombel' AND m.mapel_id='$mapel_id'")->first();

		Session::put('id_rombel',$id_rombel);
		Session::put('mapel_id',$mapel_id);

		if(!empty($mengajar)){
			$data = [
				'main_menu'=>'walikelas',
				'sub_menu'=>'',
				'mengajar'=>$mengajar,
				'rombongan_belajar'=>$rombongan_belajar,
			];

			return view('guru.walikelas.isian.index',$data);
		}else{
			Session::flash('title','Whooops');
			Session::flash('message','Tidak memiliki akses');
			Session::flash('type','warning');
			return Redirect::route('dashboard_guru');
		}

	}

	function pages(Request $request){
		$i = $request->i;
		$content = 'Pilih Tab';

		switch ($i) {
			case '1':
			$content = $this->show1();
			break;
			case '2':
			$content = view('guru.walikelas.isian.pages.dua')->render();
			break;
			case '3':
			$content = view('guru.walikelas.isian.pages.tiga')->render();
			break;
			case '4':
			$content = view('guru.walikelas.isian.pages.empat')->render();
			break;
			case '5':
			$content = $this->show5();
			break;
			case '6':
			$content = view('guru.walikelas.isian.pages.enam')->render();
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
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id_wk');
		$nama_schema = Session::get('nama_schema');
		$npsn = Session::get('npsn');

		$mengajar = DB::connection($conn)->table($nama_schema.'.mengajar as m')
		->leftjoin('public.rapor_mapel as rm','rm.mapel_id','m.mapel_id')
		->selectRaw("m.*,rm.nama as nama_mapel")
		->whereRaw("kelas='$kelas' AND rombel='$rombel' AND npsn='$npsn' AND m.mapel_id='$mapel_id'")->first();

		$data = [
			'mengajar'=>$mengajar,
		];

		$content = view('guru.walikelas.isian.pages.satu',$data)->render();

		return $content;
	}

	function simpan_kkm(Request $request){
		$kkm = $request->kkm;

		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id_wk');
		$nama_schema = Session::get('nama_schema');
		$npsn = Session::get('npsn');

		$mengajar = DB::connection($conn)->table($nama_schema.'.mengajar as m')->whereRaw("kelas='$kelas' AND rombel='$rombel' AND npsn='$npsn' AND m.mapel_id='$mapel_id'")->first();
		if(!empty($mengajar)){
			$update = DB::connection($conn)->table($nama_schema.'.mengajar as m')->whereRaw("kelas='$kelas' AND rombel='$rombel' AND npsn='$npsn' AND m.mapel_id='$mapel_id'")->update(['kkm'=>$kkm]);
			if($update){
				$return = ['code'=>'200','title'=>'Success','message'=>'Berhasil disimpan','type'=>'success'];
			}else{
				$return = ['code'=>'250','title'=>'Whooops','message'=>'Ggaal disimpan','type'=>'error'];
			}
		}else{
			$return = ['code'=>'250','title'=>'Whooops','message'=>'Mapel tidak diajarkan oleh '.Session::get('nama'),'type'=>'error'];
		}

		return $return;
	}

	// PAGE 2
	function pages2(Request $request){
		$i = $request->id;
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
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='3'")->orderBy('id_kd','ASC')->get();

		$kolom = 'nph';

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$arr_nilai = [];
				if($kd->count()!=0){
					foreach($kd as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						$arr_nilai[$k->id_kd] = (!empty($get_nilai)) ? $get_nilai->$kolom : '';
					}
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		$content = view('guru.walikelas.isian.pages.dua.satu',$data)->render();

		return $content;
	}

	function show22(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='3'")->orderBy('id_kd','ASC')->get();

		$kolom = 'npts';

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$arr_nilai = [];
				if($kd->count()!=0){
					foreach($kd as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						$arr_nilai[$k->id_kd] = (!empty($get_nilai)) ? $get_nilai->$kolom : '';
					}
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		$content = view('guru.walikelas.isian.pages.dua.dua',$data)->render();

		return $content;
	}

	function show23(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='3'")->orderBy('id_kd','ASC')->get();

		$kolom = 'npas';

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$arr_nilai = [];
				if($kd->count()!=0){
					foreach($kd as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						$arr_nilai[$k->id_kd] = (!empty($get_nilai)) ? $get_nilai->$kolom : '';
					}
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		$content = view('guru.walikelas.isian.pages.dua.tiga',$data)->render();

		return $content;
	}

	// PAGE 3
	function pages3(Request $request){
		$i = $request->id;
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
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$kolom = 'keterampilan';

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$arr_nilai = [];
				if($kd->count()!=0){
					foreach($kd as $k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						$arr_nilai[$k->id_kd] = (!empty($get_nilai)) ? $get_nilai->$kolom : '';
					}
				}

				$s->nilai = $arr_nilai;
			}
		}

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		$content = view('guru.walikelas.isian.pages.tiga.satu',$data)->render();

		return $content;
	}

	function show32(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		$content = view('guru.walikelas.isian.pages.tiga.dua',$data)->render();

		return $content;
	}

	function show33(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		$content = view('guru.walikelas.isian.pages.tiga.tiga',$data)->render();

		return $content;
	}

	function show34(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn'")->orderBy('s.nama')->get();

		$kd = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();

		$data = [
			'siswa'=>$siswa,
			'kd'=>$kd,
		];

		$content = view('guru.walikelas.isian.pages.tiga.empat',$data)->render();

		return $content;
	}

	// PAGE 5
	function show5(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn'")->orderBy('s.nama','ASC')->get();

		$kd3 = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='3'")->orderBy('id_kd','ASC')->get();
		$kd4 = DB::connection($conn)->table($this->schema.'.kd')->whereRaw("kelas='$kelas' AND mapel_id='$mapel_id' AND no_ki='4'")->orderBy('id_kd','ASC')->get();
		$kkm = 75;

		$temp = round(((100 - $kkm)/3),0);
		$c	  = $kkm + ($temp-1);
		$b	  = $c + ($temp);
		$a    = 100 - $temp;

		$adanilai=false;
		$kd_pengetahuan_tertinggi	= 1;
		$nilai_pengetahuan_tertinggi= 0;
		$kd_pengetahuan_terendah	= 1;
		$nilai_pengetahuan_terendah	= 100;
		$kd_pengetahuan_terendah2	= 0;
		$kd_pengetahuan_terendah3	= 0;

		$tampil = [];
		$id=1;

		if($siswa->count()!=0){
			foreach($siswa as $s){
				$huruf_ki3 = '';
				$nilai_ki3 = 0;
				$nr_ki4 = '';
				$catatan3 = '';
				$catatan4 = '';
				$arr_nilai = [];
				$pembagi_kd=4;

				// KI-3
				if($kd3->count()!=0){
					$np_pembagi = 0;
					$nilai_kd = 0;
					$np_jumlah = 0;
					foreach($kd3 as $i=>$k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->selectRaw("*,COALESCE(nph,0) as nph,COALESCE(npts,0) as npts,COALESCE(npas,0) as npas")
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						if(!empty($get_nilai)){
							if($get_nilai->nph>0 && $get_nilai->npas>0){
								$adanilai=true;
							}
							$np_pembagi++;

							if($adanilai==true){
								if($pembagi_kd>0){
									$nilai_kd = ((2*$get_nilai->nph)+($get_nilai->npts)+($get_nilai->npas))/$pembagi_kd;
								}else{
									$nilai_kd = 0;
								}
							}else{
								$nilai_kd = 0;
							}

							$np_jumlah+=$nilai_kd;

							if($nilai_kd > $nilai_pengetahuan_tertinggi){
								$nilai_pengetahuan_tertinggi = $nilai_kd;
								$kd_pengetahuan_tertinggi = ($i+1);
							}
							if($nilai_kd < $nilai_pengetahuan_terendah){
								$nilai_pengetahuan_terendah = $nilai_kd;
								$kd_pengetahuan_terendah = ($i+1);
							}
						}
					}

					if($np_pembagi>0){
						$np = $np_jumlah/$np_pembagi;
					}else{
						$np = 0;
					}

					if($np>0){
						$nilai_ki3 = $np;

						$huruf_ki3=Convert::angka2hurufsma($nilai_ki3,$kkm);
						$catatan3=Convert::catatan_ki3($mapel_id,$kelas,$huruf_ki3,$kd_pengetahuan_terendah,$kd_pengetahuan_terendah2,$kd_pengetahuan_terendah3,$kd_pengetahuan_tertinggi);
					}
				}

				// KI-4
				$praktek_pembagi=0;
				$praktek_jumlah=0;
				$nr_pembagi=0;
				$nr_jumlah=0;

				$kd_ketrampilan_tertinggi	= 1;
				$nilai_ketrampilan_tertinggi= 0;
				$kd_ketrampilan_terendah	= 1;
				$nilai_ketrampilan_terendah	= 100;
				$kd_ketrampilan_terendah2	= 0;
				$kd_ketrampilan_terendah3	= 0;
				$pembagi_kd=0;
				$adanilai=false;

				if($kd4->count()!=0){
					foreach($kd4 as $i=>$k){
						$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel as dp')
						->join($this->schema.'.nilai_mapel as np','np.id_nilai_mapel','dp.nilai_mapel_id')
						->selectRaw("*,COALESCE(keterampilan,0) as keterampilan")
						->whereRaw("dp.kd_id='$k->id_kd' AND np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
						
						if(!empty($get_nilai)){
							if($get_nilai->keterampilan>0){
								$praktek_pembagi++;
								$praktek_jumlah+=$get_nilai->keterampilan;
							}

							if($praktek_pembagi>0){ 
								$praktek=$praktek_jumlah/$praktek_pembagi; 
							}else{
								$praktek=0;
							}

							$nilai_ki4=0;
							$nilai_ki4=$praktek;

							$nr_ki4=round(($nilai_ki4),2);
							$huruf_nr_ki4=Convert::angka2hurufsma($nr_ki4,$kkm);
							
							if($get_nilai->keterampilan>0){
								$nr_pembagi++;
								$nr_jumlah+=$get_nilai->keterampilan;
								$pembagi_kd++;
								$adanilai=true;
							}
						}


						if($pembagi_kd>0 ){ 
							$nilai_kd=($get_nilai->keterampilan)/$pembagi_kd;
						}else{
							$nilai_kd=0;
						}

						if($adanilai){
							if($nilai_kd>$nilai_ketrampilan_tertinggi){
								$nilai_ketrampilan_tertinggi=$nilai_kd;
								$kd_ketrampilan_tertinggi=$i;
							}
							if($nilai_kd<$nilai_ketrampilan_terendah){
								$nilai_ketrampilan_terendah=$nilai_kd;									
								$kd_ketrampilan_terendah3=$kd_ketrampilan_terendah2;
								$kd_ketrampilan_terendah2=$kd_ketrampilan_terendah;
								$kd_ketrampilan_terendah=$i;
							}
						}	
					}

					if($nr_pembagi>0){
						$nr=$nr_jumlah/$nr_pembagi; 
						$catatan4=Convert::catatan_ki4($mapel_id,$kelas,$huruf_nr_ki4,$kd_ketrampilan_terendah,$kd_ketrampilan_terendah2,$kd_ketrampilan_terendah3,$kd_ketrampilan_tertinggi);
					}else{
						$nr=0;
					} 

				}

				if($nilai_ki3 != 0){
					$show_hurufk1 = number_format($nilai_ki3,0)." (".$huruf_ki3.")";
				}else{
					$show_hurufk1 = "&nbsp<br />&nbsp";
				}
				if($nr_ki4 != 0){
					$show_hurufk2 = number_format($nr_ki4,0)." (".$huruf_nr_ki4.")";
				}else{
					$show_hurufk2 = "&nbsp<br />&nbsp";
				}

				$baris = [
					'id'=>$id,
					'nama'=>$s->nama,
					'hurufk1'=>$show_hurufk1,
					'catatan1'=>$catatan3,
					'hurufk2'=>$show_hurufk2,
					'catatan2'=>$catatan4,
				];

				array_push($tampil,$baris);

				if($nilai_ki3> 0 OR $nr_ki4 > 0){
					$namasiswa	= str_replace("'", "&apos;", $s->nama);
					$dta_insert = [
						'nilai_ki3'=>$nilai_ki3,
						'predikat_ki3'=>$huruf_ki3,
						'deskripsi_ki3'=>$catatan3,
						'nilai_ki4'=>$nr_ki4,
						'predikat_ki4'=>$huruf_nr_ki4,
						'deskripsi_ki4'=>$catatan4,
					];

					$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel as np')
					->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->first();
					if(!empty($get_nilai_mapel)){
						$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel as np')
						->whereRaw("np.anggota_rombel_id='$s->id_anggota_rombel' AND np.mapel_id='$mapel_id'")->update($dta_insert);
					}
					$id++;
				}
			}
		}

		$data = [
			'tampil'=>$tampil,
			'nama_schema'=>$nama_schema,
			'kkm'=>$kkm,
			'temp'=>$temp,
			'c'=>$c,
			'b'=>$b,
			'a'=>$a,
		];

		$content = view('guru.walikelas.isian.pages.lima',$data)->render();

		return $content;
	}

	// PAGE 5
	function show5Old(){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id_wk');
		$nama_schema = Session::get('nama_schema');
		$npsn = Session::get('npsn');
		$jumkd		= 20;
		$tgl		= date('Ymd');

		$kkm1 = DB::connection($conn)->table($nama_schema.'.mengajar')->select('kkm')->whereRaw("npsn='$npsn' AND mapel_id='$mapel_id' AND kelas='$kelas' AND rombel='$rombel'")->first();
		$kkm2 = DB::connection($conn)->table('public.sekolah')->select('kkm')->whereRaw("npsn='$npsn'")->first();

		$kkm  = (!empty($kkm2)) ? $kkm2->kkm : $kkm1->kkm;

		$temp = round(((100 - $kkm)/3),0);
		$c	  = $kkm + ($temp-1);
		$b	  = $c + ($temp);
		$a    = 100 - $temp;


		$kdki3 = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("kelas='$kelas' and mapel_id='$mapel_id'  and no_ki='3' AND semester='1'")->get();
		$kdki4 = DB::connection($conn)->table($nama_schema.'.master_kd')->whereRaw("kelas='$kelas' and mapel_id='$mapel_id'  and no_ki='4' AND semester='1'")->get();

		$mapel = DB::connection($conn)->table('public.rapor_mapel')->whereRaw("mapel_id='$mapel_id'")->first();

		$tampil = [];

		$id=1;
		$get_siswa = DB::connection($conn)->table('public.siswa as s')
		->leftjoin($nama_schema.'.nilai as n',function($join){
			return $join->on(function($jj){
				return $jj->on('s.npsn','=','n.npsn')->orOn('s.npsn_asal','=','n.npsn');
			})->on('s.id_siswa','=','n.id_siswa');
		},'left outer')
		->selectRaw("s.nama ,s.id_siswa as idsiswa, n.*")
		->whereRaw("n.npsn='$npsn' and n.kelas='$kelas' and n.mapel_id='$mapel_id' and n.rombel='$rombel' AND status_siswa='Aktif'")
		->orderBy("s.nama")->get();

		$hapus_nilai_kahir = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND mapel_id='$mapel_id'")->delete();

		foreach($get_siswa as $k=>$v){
			/* ----- KI-3 ----- */
			$np_pembagi					= 0;
			$np_jumlah					= 0;

			$kd_pengetahuan_tertinggi	= 1;
			$nilai_pengetahuan_tertinggi= 0;
			$kd_pengetahuan_terendah	= 1;
			$nilai_pengetahuan_terendah	= 100;
			$kd_pengetahuan_terendah2	= 0;
			$kd_pengetahuan_terendah3	= 0;

			for($i=1;$i<=$jumkd;$i++){ 
				#$jumkd = 20;
				$pembagi_kd=4;
				$adanilai=false;

				$kolom_nph = 'nph_'.$i;
				$kolom_npas = 'npas_'.$i;
				$kolom_npts = 'npts_'.$i;

				if(($v->$kolom_nph)>0 && ($v->$kolom_npas)>0){
					$np_pembagi++;
					#$np_jumlah+=$v->$kolom_nph;
					#$pembagi_kd++;
					$adanilai=true;
				}
				if($v->$kolom_npts==0){
					$pembagi_kd=$pembagi_kd-1;
				}

				if($adanilai==true){ 
					$nilai_kd=((2*$v->$kolom_nph)+($v->$kolom_npts)+($v->$kolom_npas))/$pembagi_kd;
				}else{ 
					$nilai_kd=0;
				}

				$np_jumlah+=$nilai_kd;

				if($adanilai){
					if($nilai_kd > $nilai_pengetahuan_tertinggi){
						$nilai_pengetahuan_tertinggi=$nilai_kd;
						$kd_pengetahuan_tertinggi=$i;
					}
					if($nilai_kd<$nilai_pengetahuan_terendah){
						$nilai_pengetahuan_terendah=$nilai_kd;
						$kd_pengetahuan_terendah3=$kd_pengetahuan_terendah2;
						$kd_pengetahuan_terendah2=$kd_pengetahuan_terendah;
						$kd_pengetahuan_terendah=$i;
					}
				}
			} 
			# end for

			if($np_pembagi>0){ 
				$np=$np_jumlah/$np_pembagi;
			} 
			else{ 
				$np=0;
			}


			$nilai_ki3=$np;
			/*if($uas == 0){
				$nilai_ki3=round((((2*$np)+$uts)/3),2);
			}else{
				$nilai_ki3=round((((2*$np)+$uts+$uas)/4),2);
			}
			*/

			$huruf_ki3=Convert::angka2hurufsma($nilai_ki3,$kkm);
			$catatan3=Convert::catatan_ki3($mapel_id,$kelas,$huruf_ki3,$kd_pengetahuan_terendah,$kd_pengetahuan_terendah2,$kd_pengetahuan_terendah3,$kd_pengetahuan_tertinggi);
			/* ----- END KI-3 ----- */			


			/* ----- KI-4 ----- */
			// Untuk Mencari Nilai KI-4 //
			$praktek_pembagi=0;
			$praktek_jumlah=0;
			for($i=1;$i<=$jumkd;$i++){
				$kolom_praktek = "praktek_".$i;
				if($v->$kolom_praktek>0){
					$praktek_pembagi++;
					$praktek_jumlah+=$v->$kolom_praktek;
				}
			}
			if($praktek_pembagi>0){ 
				$praktek=$praktek_jumlah/$praktek_pembagi; 
			}else{
				$praktek=0;
			}

			//Mencari Nilai Rata-Rata Optimum dari Setiap Aspek
			$nilai_ki4=0;
			$nilai_ki4=$praktek;

			//$nr=round(($praktek+$proyek+$portofolio)/3);
			$nr_ki4=round(($nilai_ki4),2);
			//$angka_nr=toAngka($nr);
			$huruf_nr_ki4=Convert::angka2hurufsma($nr_ki4,$kkm);
			// End Untuk Mencari Nilai KI-4


			// Untuk Mencari Deskripsi KI-4
			$nr_pembagi=0;
			$nr_jumlah=0;

			$kd_ketrampilan_tertinggi	= 1;
			$nilai_ketrampilan_tertinggi= 0;
			$kd_ketrampilan_terendah	= 1;
			$nilai_ketrampilan_terendah	= 100;
			$kd_ketrampilan_terendah2	= 0;
			$kd_ketrampilan_terendah3	= 0;

			for($i=1;$i<=$jumkd;$i++){
				$kolom_praktek = "praktek_".$i;
				$pembagi_kd=0;
				$adanilai=false;
				if($v->$kolom_praktek>0){
					$nr_pembagi++;
					$nr_jumlah+=$v->$kolom_praktek;
					$pembagi_kd++;
					$adanilai=true;
				}						

				if($pembagi_kd>0 ){ 
					$nilai_kd=($v->$kolom_praktek)/$pembagi_kd;
				}else{
					$nilai_kd=0;
				}

				if($adanilai){
					if($nilai_kd>$nilai_ketrampilan_tertinggi){
						$nilai_ketrampilan_tertinggi=$nilai_kd;
						$kd_ketrampilan_tertinggi=$i;
					}
					if($nilai_kd<$nilai_ketrampilan_terendah){
						$nilai_ketrampilan_terendah=$nilai_kd;									
						$kd_ketrampilan_terendah3=$kd_ketrampilan_terendah2;
						$kd_ketrampilan_terendah2=$kd_ketrampilan_terendah;
						$kd_ketrampilan_terendah=$i;
					}
				}							
			}					
			if($nr_pembagi>0) 
				$nr=$nr_jumlah/$nr_pembagi; 
			else 
				$nr=0;

			$catatan4=Convert::catatan_ki4($mapel_id,$kelas,$huruf_nr_ki4,$kd_ketrampilan_terendah,$kd_ketrampilan_terendah2,$kd_ketrampilan_terendah3,$kd_ketrampilan_tertinggi);
			// End Untuk Mencari Deskripsi KI-4 //
			/* ----- END KI-4 ----- */

			if($nilai_ki3> 0 OR $nr_ki4 > 0){
				
				if($nilai_ki3 != 0){
					$show_hurufk1 = number_format($nilai_ki3,0)." (".$huruf_ki3.")";
				}else{
					$show_hurufk1 = "&nbsp<br />&nbsp";
				}
				if($nr_ki4 != 0){
					$show_hurufk2 = number_format($nr_ki4,0)." (".$huruf_nr_ki4.")";
				}else{
					$show_hurufk2 = "&nbsp<br />&nbsp";
				}
				
				$baris = [
					'id'=>$id,
					'nama'=>$v->nama,
					'hurufk1'=>$show_hurufk1,
					'catatan1'=>$catatan3,
					'hurufk2'=>$show_hurufk2,
					'catatan2'=>$catatan4,
				];

				array_push($tampil,$baris);

				$namasiswa	= str_replace("'", "&apos;", $v->nama);
				$dta_insert = [
					'npsn'=>$npsn,
					'id_siswa'=>$v->id_siswa,
					'nama'=>$namasiswa,
					'kelas'=>$kelas,
					'rombel'=>$rombel,
					'mapel_id'=>$mapel_id,
					'mapel'=>(!empty($mapel)) ? $mapel->nama : '',
					'kategori'=>(!empty($mapel)) ? $mapel->kategori_baru : '',
					'semester'=>'1',
					'nilai_ki3'=>$nilai_ki3,
					'predikat_ki3'=>$huruf_ki3,
					'deskripsi_ki3'=>$catatan3,
					'nilai_ki4'=>$nr_ki4,
					'predikat_ki4'=>$huruf_nr_ki4,
					'deskripsi_ki4'=>$catatan4,
					'urutan'=>'0'.(!empty($mapel)) ? $mapel->urutan : '',
					'tapel'=>'Ganjil 2021/2022',
					'last_update'=>date('Y-m-d H:i:s'),
				];
				$simpan = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->insert($dta_insert);
				$id++;
			}
		}

		$data = [
			'tampil'=>$tampil,
			'nama_schema'=>$nama_schema,
			'kkm'=>$kkm,
			'temp'=>$temp,
			'c'=>$c,
			'b'=>$b,
			'a'=>$a,
		];

		$content = view('guru.walikelas.isian.pages.lima',$data)->render();

		return $content;
	}

	// PAGE 6
	function pages6(Request $request){
		$i = $request->id;
		$content = 'Pilih Tab';

		switch ($i) {
			case '1':
			$content = $this->show61();
			break;
			case '2':
			$content = view('guru.walikelas.isian.pages.enam.dua')->render();
			break;

			default:
			$content = 'Pilih Tab';
			break;
		}

		return ['content'=>$content];
	}

	function show61(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$id_rombel = Session::get('id_rombel');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$mapel_id = Session::get('mapel_id');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel' AND s.npsn='$npsn'")->get();

		$data = [
			'siswa'=>$siswa,
		];

		$content = view('guru.walikelas.isian.pages.enam.satu',$data)->render();

		return $content;
	}

	function simpan_nilai(Request $request){
		$id_siswa = $request->id_siswa;
		$kategori = $request->kategori;
		$data_nilai = $request->nilai;
		$id_kd = $request->id_kd;
		$no_ki = $request->no_ki;
		$mapel_id = Session::get('mapel_id_wk');
		$nama_schema = Session::get('nama_schema');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$id_rombel = Session::get('id_rombel');
		$mapel_id = Session::get('mapel_id');
		$npsn = Session::get('npsn');

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("siswa_id='$id_siswa' AND rombongan_belajar_id='$id_rombel'")->first();
		$id_anggota = '';
		if(!empty($anggota)){
			$id_anggota = $anggota->id_anggota_rombel;
		}

		$data_nilai_mapel = [
			'anggota_rombel_id'=>$id_anggota,
			'mapel_id'=>$mapel_id,
		];

		if($id_anggota!=''){
			$id_nilai_mapel='';
			while($id_nilai_mapel=='') {
				$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($data_nilai_mapel)->first();
				if(!empty($get_nilai_mapel)){
					$id_nilai_mapel = $get_nilai_mapel->id_nilai_mapel;
				}else{
					$simpan = DB::connection($conn)->table($this->schema.'.nilai_mapel')->insert($data_nilai_mapel);
					$get_nilai_mapel = DB::connection($conn)->table($this->schema.'.nilai_mapel')->where($data_nilai_mapel)->first();
					$id_nilai_mapel = $get_nilai_mapel->id_nilai_mapel;
				}
			};
		}

		if($id_nilai_mapel!=''){
			for ($i=0; $i < count($data_nilai); $i++) {
				$data = [
					'nilai_mapel_id'=>$id_nilai_mapel,
					'kd_id'=>$id_kd[$i],
				];

				$where = $data;

				$data = array_merge($data,[
					$kategori=>$data_nilai[$i],
				]);

				$get_nilai = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->where($where)->first();
				if(!empty($get_nilai)){
					$simpan = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->where($where)->update($data);
				}else{
					$simpan = DB::connection($conn)->table($this->schema.'.detail_nilai_mapel')->insert($data);
				}
			}

			if($simpan){
				$return = ['code'=>'200','title'=>'Success','message'=>'Berhasil disimpan','type'=>'success'];
			}else{
				$return = ['code'=>'250','title'=>'Whooops','message'=>'Gagal disimpan','type'=>'error'];
			}
		}else{
			$return = ['code'=>'250','title'=>'Whooops','message'=>'Gagal disimpan','type'=>'error'];			
		}

		return $return;
	}
}
