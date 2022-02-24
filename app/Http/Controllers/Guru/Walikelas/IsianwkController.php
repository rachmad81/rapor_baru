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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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
			$tampil = $this->page4sd();
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

	function page4sd(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$mapel_id	= 1;
		$jumkd		= 6;

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		if($jenjang=='SD'){
			$tahun_ajaran = substr($nama_schema,9,4);
			$semester = substr($nama_schema,14);
		}else{
			$tahun_ajaran = substr($nama_schema,10,4);
			$semester = substr($nama_schema,15);
		}

		$hapus_nilai_kahir = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND mapel_id='$mapel_id'")->delete();

		$mapel = DB::connection($conn)->table('public.rapor_mapel')->whereRaw("mapel_id='$mapel_id'")->first();

		$get_siswa = DB::connection($conn)->table('public.siswa as s')
		->leftjoin($nama_schema.'.nilai as n',function($join){
			return $join->on(function($jj){
				return $jj->on('s.npsn','=','n.npsn')->orOn('s.npsn_asal','=','n.npsn');
			})->on('s.id_siswa','=','n.id_siswa');
		},'left outer')
		->selectRaw("s.nama ,s.id_siswa as idsiswa ,n.*")
		->whereRaw("n.npsn='$npsn' and n.kelas='$kelas' and n.mapel_id='$mapel_id' and n.rombel='$rombel'")
		->orderBy('s.nama')->get();

		$tampil = [];

		$id=1;
		foreach($get_siswa as $k=>$v){
			/* --- KI-1 --- */
			if($jenjang=='SD'){
				$aspek_kata=array("ketaatan beribadah","berperilaku syukur","berdoa sebelum dan sesudah melakukan kegiatan","toleransi dalam beribadah");
				$aspek=array("ibadah","syukur","berdoa","toleransi");
			}else{
				$aspek_kata=array("ketaatan beribadah","berperilaku syukur");
				$aspek=array("ibadah","syukur");
			}

			//utk meghitung modus keseluruhan
			$tot_nilai_1=0;
			$tot_nilai_2=0;
			$tot_nilai_3=0;
			$tot_nilai_4=0;
			$arr_nilai=array();
			for($a=0;$a<count($aspek);$a++){
				//utk meghitung modus per sikap
				$nilai_1=0;
				$nilai_2=0;
				$nilai_3=0;
				$nilai_4=0;
				for($i=1;$i<=$jumkd;$i++){
					for($j=0;$j<=2;$j++){
						$kolom = $aspek[$a] ."_".$i;
						if($v->$kolom==1 ) {
							$nilai_1++;
							$tot_nilai_1++;
						}
						if($v->$kolom==2 ) {
							$nilai_2++;
							$tot_nilai_2++;
						}
						if($v->$kolom==3 ) {
							$nilai_3++;
							$tot_nilai_3++;
						}
						if($v->$kolom==4 ) {
							$nilai_4++;
							$tot_nilai_4++;
						}
					}
				}

				//hitung modus pada aspek
				$paling_besar=0;
				$mod_paling_besar=0;
				if($mod_paling_besar<$nilai_1) {
					$paling_besar=1;	
					$mod_paling_besar=$nilai_1;
				}
				if($mod_paling_besar<$nilai_2) {
					$paling_besar=2;	
					$mod_paling_besar=$nilai_2;
				}
				if($mod_paling_besar<$nilai_3) {
					$paling_besar=3;	
					$mod_paling_besar=$nilai_3;
				}
				if($mod_paling_besar<$nilai_4) {
					$paling_besar=4;	
					$mod_paling_besar=$nilai_4;
				}
				array_push($arr_nilai,$paling_besar);
			}				

			//hitung modus pada total
			$paling_besar=0;
			$mod_paling_besar=0;
			if($mod_paling_besar<$tot_nilai_1) {
				$paling_besar=1;	
				$mod_paling_besar=$tot_nilai_1;
			}
			if($mod_paling_besar<$tot_nilai_2) {
				$paling_besar=2;	
				$mod_paling_besar=$tot_nilai_2;
			}
			if($mod_paling_besar<$tot_nilai_3) {
				$paling_besar=3;	
				$mod_paling_besar=$tot_nilai_3;
			}
			if($mod_paling_besar<$tot_nilai_4) {
				$paling_besar=4;	
				$mod_paling_besar=$tot_nilai_4;
			}

			if($paling_besar==1){ 
				$hurufk1='K';							
				$sikap_modus_1="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==1) $sikap_modus_1.= ", ".$aspek_kata[$a];
				}
				$sikap_modus_1=substr($sikap_modus_1,1);
				$catatan1="Dengan bimbingan dan pendampingan yang lebih, peserta didik mampu ". $sikap_modus_1;
			}
			if($paling_besar==2){
				$hurufk1='C';
				$sikap_modus_2="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==2) $sikap_modus_2.= ", ".$aspek_kata[$a];
				}
				$sikap_modus_2=substr($sikap_modus_2,1);
				$sikap_modus_kurang="";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<2) and $arr_nilai[$a]>0) $sikap_modus_kurang.= ", ".$aspek[$a];
				}
				$sikap_modus_kurang=substr($sikap_modus_kurang,1);
				$catatan1="Dengan bimbingan dan pendampingan yang lebih, peserta didik mampu $sikap_modus_2 ";
											#if($sikap_modus_kurang<>"") $catatan1.=", dan perlu ditingkatkan dalam sikap $sikap_modus_kurang.";
			}
			if($paling_besar==3){ 
				$hurufk1='B';
				$sikap_modus_3="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==3) $sikap_modus_3.= ", ".$aspek_kata[$a];
				}
				$sikap_modus_3=substr($sikap_modus_3,1);
				$sikap_modus_kurang="";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<3) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$aspek[$a];
				}
				$sikap_modus_kurang=substr($sikap_modus_kurang,1);
				$catatan1="Peserta didik baik dalam sikap spiritual $sikap_modus_3";
				if($sikap_modus_kurang<>"") $catatan1.=", dengan bimbingan dan pendampingan yang lebih, peserta didik akan mampu $sikap_modus_kurang.";

			}
			if($paling_besar==4) {
				$hurufk1='SB';
				$sikap_modus_4="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==4) $sikap_modus_4.= ", ".$aspek_kata[$a];
				}
				$sikap_modus_4=substr($sikap_modus_4,1);
				$sikap_modus_kurang="";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<4) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$aspek[$a];
				}
				$sikap_modus_kurang=substr($sikap_modus_kurang,1);
				$catatan1="Peserta didik Sangat Baik dalam sikap spiritual $sikap_modus_4";
				if($sikap_modus_kurang<>"") $catatan1.=", dengan bimbingan dan pendampingan yang lebih, peserta didik akan mampu $sikap_modus_kurang.";
			}
			/*if($paling_besar==0){
				$hurufk1="&nbsp";
				$catatan1="&nbsp";
			}*/
			/* --- END KI-1 --- */
			
			
			/* --- KI-2 --- */
			if($jenjang=='SD'){
				$aspek_kata=array("Jujur","Disiplin","Tanggungjawab","Sopansantun","Peduli","Percayadiri");
				$aspek=array("jujur","disiplin","tanggungjawab","sopansantun","peduli","percayadiri");
			}else{
				$aspek_kata=array("Jujur","Disiplin","Tanggungjawab","Sopansantun","Peduli","Percayadiri","Kerja Sama");
				$aspek=array("kejujuran","disiplin","tanggungjawab","sopansantun","kepedulian","percayadiri","kerjasama");
			}																			

			//utk meghitung modus keseluruhan
			$tot_nilai_1=0;
			$tot_nilai_2=0;
			$tot_nilai_3=0;
			$tot_nilai_4=0;
			$arr_nilai=array();
			for($a=0;$a<count($aspek);$a++){
				//utk meghitung modus per sikap
				$nilai_1=0;
				$nilai_2=0;
				$nilai_3=0;
				$nilai_4=0;
				for($i=1;$i<=$jumkd;$i++){
					for($j=0;$j<=2;$j++){
						$kolom = $aspek[$a] ."_".$i;
						if($v->$kolom==1 ) {
							$nilai_1++;
							$tot_nilai_1++;
						}
						if($v->$kolom==2 ) {
							$nilai_2++;
							$tot_nilai_2++;
						}
						if($v->$kolom==3 ) {
							$nilai_3++;
							$tot_nilai_3++;
						}
						if($v->$kolom==4 ) {
							$nilai_4++;
							$tot_nilai_4++;
						}
					}
				}

				//hitung modus pada aspek
				$paling_besar=0;
				//$mod_paling_besar=$nilai_1;
				$mod_paling_besar=0;
				if($mod_paling_besar<$nilai_1) {
					$paling_besar=1;	
					$mod_paling_besar=$nilai_1;
				}
				if($mod_paling_besar<$nilai_2) {
					$paling_besar=2;	
					$mod_paling_besar=$nilai_2;
				}
				if($mod_paling_besar<$nilai_3) {
					$paling_besar=3;	
					$mod_paling_besar=$nilai_3;
				}
				if($mod_paling_besar<$nilai_4) {
					$paling_besar=4;	
					$mod_paling_besar=$nilai_4;
				}
				array_push($arr_nilai,$paling_besar);
			}				

			//hitung modus pada total
			$paling_besar=0;
			//$mod_paling_besar=$tot_nilai_1;
			$mod_paling_besar=0;
			if($mod_paling_besar<$tot_nilai_1) {
				$paling_besar=1;	
				$mod_paling_besar=$tot_nilai_1;
			}
			if($mod_paling_besar<$tot_nilai_2) {
				$paling_besar=2;	
				$mod_paling_besar=$tot_nilai_2;
			}
			if($mod_paling_besar<$tot_nilai_3) {
				$paling_besar=3;	
				$mod_paling_besar=$tot_nilai_3;
			}
			if($mod_paling_besar<$tot_nilai_4) {
				$paling_besar=4;	
				$mod_paling_besar=$tot_nilai_4;
			}

			if($paling_besar==1){ 
				$hurufk2='K';							
				$sikap_modus_1="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==1) $sikap_modus_1.= ", ".$aspek[$a];
				}
				$sikap_modus_1=substr($sikap_modus_1,1);
				$catatan2="Dengan bimbingan dan pendampingan yang lebih, peserta didik mampu ". $sikap_modus_1;
			}
			if($paling_besar==2){
				$hurufk2='C';
				$sikap_modus_2="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==2) $sikap_modus_2.= ", ".$aspek[$a];
				}
				$sikap_modus_2=substr($sikap_modus_2,1);
				$sikap_modus_kurang="";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<2) and $arr_nilai[$a]>0) $sikap_modus_kurang.= ", ".$aspek[$a];
				}
				$sikap_modus_kurang=substr($sikap_modus_kurang,1);
				$catatan2="Dengan bimbingan dan pendampingan yang lebih, peserta didik mampu $sikap_modus_2";
											#if($sikap_modus_kurang<>"") $catatan2.=", dan perlu ditingkatkan dalam sikap $sikap_modus_kurang.";
			}
			if($paling_besar==3){ 
				$hurufk2='B';
				$sikap_modus_3="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==3) $sikap_modus_3.= ", ".$aspek[$a];
				}
				$sikap_modus_3=substr($sikap_modus_3,1);
				$sikap_modus_kurang="";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<3) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$aspek[$a];
				}
				$sikap_modus_kurang=substr($sikap_modus_kurang,1);
				$catatan2="Peserta didik Baik dalam sikap sosial $sikap_modus_3";
				if($sikap_modus_kurang<>"") $catatan2.=", dengan bimbingan dan pendampingan yang lebih, peserta didik akan mampu $sikap_modus_kurang.";

			}
			if($paling_besar==4) {
				$hurufk2='SB';
				$sikap_modus_4="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==4) $sikap_modus_4.= ", ".$aspek[$a];
				}
				$sikap_modus_4=substr($sikap_modus_4,1);
				$sikap_modus_kurang="";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<4) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$aspek[$a];
				}
				$sikap_modus_kurang=substr($sikap_modus_kurang,1);
				$catatan2="Peserta didik Sangat Baik dalam sikap sosial $sikap_modus_4";
				if($sikap_modus_kurang<>"") $catatan2.=", dengan bimbingan dan pendampingan yang lebih, peserta didik akan mampu $sikap_modus_kurang.";
			}
			/* --- END KI-2 --- */
			if($paling_besar > 0){
				$baris = [
					'id'=>$id,
					'nama'=>$v->nama,
					'hurufk1'=>$hurufk1,
					'catatan1'=>$catatan1,
					'hurufk2'=>$hurufk2,
					'catatan2'=>$catatan2,
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
					'predikat_ki1'=>$hurufk1,
					'deskripsi_ki1'=>$catatan1,
					'predikat_ki2'=>$hurufk2,
					'deskripsi_ki2'=>$catatan2,
					'urutan'=>'0'.(!empty($mapel)) ? $mapel->urutan : '',
					'tapel'=>'Ganjil 2021/2022',
					'last_update'=>date('Y-m-d H:i:s'),
				];
				$simpan = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->insert($dta_insert);
				$id++;
			}
		}

		return $tampil;
	}

	function page4smp(){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');
		$mapel_id	= 1;
		$jumkd		= 6;

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		if($jenjang=='SD'){
			$tahun_ajaran = substr($nama_schema,9,4);
			$semester = substr($nama_schema,14);
		}else{
			$tahun_ajaran = substr($nama_schema,10,4);
			$semester = substr($nama_schema,15);
		}

		$hapus_nilai_kahir = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND mapel_id='$mapel_id'")->delete();

		$mapel = DB::connection($conn)->table('public.rapor_mapel')->whereRaw("mapel_id='$mapel_id'")->first();

		$get_siswa = DB::connection($conn)->table('public.siswa as s')
		->leftjoin($nama_schema.'.nilai as n',function($join){
			return $join->on(function($jj){
				return $jj->on('s.npsn','=','n.npsn')->orOn('s.npsn_asal','=','n.npsn');
			})->on('s.id_siswa','=','n.id_siswa');
		},'left outer')
		->selectRaw("s.nama ,s.id_siswa as idsiswa ,n.*")
		->whereRaw("n.npsn='$npsn' and n.kelas='$kelas' and n.mapel_id='$mapel_id' and n.rombel='$rombel'")
		->orderBy('s.nama')->get();

		$tampil = [];

		$id=1;
		foreach($get_siswa as $k=>$v){
			$sikap = Hitung_sikap::nilai_sikap($v->id_siswa,$kelas,$rombel,$nama_schema,$npsn);

			$baris = [
				'id'=>$id,
				'nama'=>$v->nama,
				'hurufk1'=>(!empty($sikap)) ? $sikap['huruf_ki1'] : '',
				'catatan1'=>(!empty($sikap)) ? $sikap['catatan_1'] : '',
				'hurufk2'=>(!empty($sikap)) ? $sikap['huruf_ki2'] : '',
				'catatan2'=>(!empty($sikap)) ? $sikap['catatan_2'] : '',
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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

		$ekskul = DB::connection($conn)->table($this->schema.'.master_ekskul')->orderBy('nama_ekskul','asc')->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();


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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("rombongan_belajar_id='$id_rombel'")->get();

		$data = [
			'siswa'=>$siswa,
			'nama_schema'=>$nama_schema,
		];

		$content = view('guru.walikelas.isian_wk.pages.sembilan',$data)->render();

		return $content;
	}

	function simpan_nilai(Request $request){
		return $request->all();
		$id_siswa = $request->id_siswa;
		$kolom = $request->kolom;
		$nilai = $request->nilai;
		$nama_schema = Session::get('nama_schema');
		$npsn = Session::get('npsn');

		$request->jenjang = Session::get('jenjang');

		$conn = Setkoneksi::set_koneksi($request);

		$cari_nilai = DB::connection($conn)->table($nama_schema.'.nilai')->whereRaw("npsn='$npsn' AND id_siswa='$id_siswa'")->get();
		if($cari_nilai->count()!=0){
			$update = DB::connection($conn)->table($nama_schema.'.nilai')->whereRaw("npsn='$npsn' AND id_siswa='$id_siswa'")->update([$kolom=>$nilai]);
		}else{
			$insert = [
				'npsn'=>$npsn,
				'id_siswa'=>$id_siswa,
				'mapel_id'=>'1',
				'kelas'=>Session::get('kelas_wk'),
				'rombel'=>Session::get('rombel_wk'),
				$kolom=>$nilai,
			];
			$update = DB::connection($conn)->table($nama_schema.'.nilai')->insert($insert);
		}

		if($update){
			$return = ['code'=>'200','title'=>'Success','message'=>'Berhasil disimpan','type'=>'success'];
		}else{
			$return = ['code'=>'250','title'=>'Whooops','message'=>'Gagal disimpan','type'=>'error'];
		}

		return $return;
	}

	function cetak_rapor(Request $request){
		$schema = $request->schema;
		$id_siswa = $request->id_siswa;
		$jenjang = Session::get('jenjang');
		if($jenjang=='SD'){
			$content = $this->data_sd($request);
		}else{
			$content = $this->data_smp($request);
		}

		return $content;
	}

	function data_sd(Request $request){
		$jenjang = Session::get('jenjang');
		$id_siswa = $request->id_siswa;
		$nama_schema = $request->schema;
		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.siswa as s')->join('public.sekolah as sek','sek.npsn','s.npsn')
		->leftjoin('public.kecamatan as kec','kec.kecamatan_kode','sek.kec_id')
		->leftjoin('public.kelurahan as kel','kel.kelurahan_kode','sek.desa')
		->leftjoin('public.agama as a','a.aga_id','s.aga_id')
		->leftjoin('public.wali_murid as wm',function($join){
			return $join->on('wm.id_siswa','=','s.id_siswa')->on('wm.npsn','=','s.npsn');
		})
		->leftjoin('public.pekerjaan as pa','pa.kode','wm.pekerjaan_ayah')
		->leftjoin('public.pekerjaan as pi','pi.kode','wm.pekerjaan_ibu')
		->leftjoin('public.pekerjaan as pw','pw.kode','wm.pekerjaan_wali')
		->selectRaw("s.foto,s.nama as nama_siswa,s.tgl_lahir,s.nisn,s.nis,s.tempat_lahir,s.kelas,s.rombel,sek.kepala,sek.email as email_sekolah,sek.website as website_sekolah,s.id_siswa,sek.nama as nama_sekolah,sek.alamat as alamat_sekolah,kec.kecamatan_dispenduk,kel.kelurahan_dispenduk,s.kelamin,s.asal_sekolah,a.aga_nama,s.status_anak,s.anakke,s.alamat_ortu,s.telpon,s.alamat as alamat_siswa,wm.nama_ayah as ayah,wm.nama_ibu as ibu,wm.nama_wali,wm.pekerjaan_wali,pa.nama as pekerjaan_ayah,pi.nama as pekerjaan_ibu,pw.nama as pekerjaan_wali,wm.alamat_rumah,wm.rt,wm.rw,sek.kkm,sek.nss")
		->where('s.id_siswa',$id_siswa)->first();
		$siswa_id = $siswa->id_siswa;

		$kelas = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->whereRaw("id_siswa='$siswa_id'")->orderBy('mapel_id','ASC')->first();
		$sikap = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->whereRaw("id_siswa='$siswa_id' AND kelas='$kelas->kelas' AND rombel='$kelas->rombel' AND mapel_id='1'")->orderBy('mapel_id','ASC')->first();

		$nilaia = DB::connection($conn)->table($nama_schema.'.nilai_akhir as na')
		->leftjoin($nama_schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id')->on('m.npsn','=','na.npsn')->on('m.kelas','=','na.kelas')->on('m.rombel','=','na.rombel');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.user_rapor','=','m.nip')->on('peg.npsn','=','m.npsn');
		})
		->selectRaw("na.id_siswa,na.npsn,na.nama,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4,m.nama as guru_mengajar,na.mapel")->whereRaw("na.id_siswa='$siswa_id' AND na.kategori IN ('KELOMPOK A','WAJIB') AND deskripsi_ki1 is null")->orderBy('na.urutan','ASC')->get();

		$nilaib = DB::connection($conn)->table($nama_schema.'.nilai_akhir as na')
		->leftjoin($nama_schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id')->on('m.npsn','=','na.npsn')->on('m.kelas','=','na.kelas')->on('m.rombel','=','na.rombel');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.user_rapor','=','m.nip')->on('peg.npsn','=','m.npsn');
		})
		->selectRaw("na.id_siswa,na.npsn,na.nama,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4,m.nama as guru_mengajar,na.mapel")->whereRaw("na.id_siswa='$siswa_id' AND na.kategori IN ('KELOMPOK B','MUATAN LOKAL') AND deskripsi_ki1 is null")->orderBy('na.urutan','ASC')->get();

		$nilai_agama = DB::connection($conn)->table($nama_schema.'.nilai_akhir as na')
		->leftjoin($nama_schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id')->on('m.npsn','=','na.npsn')->on('m.kelas','=','na.kelas')->on('m.rombel','=','na.rombel');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.user_rapor','=','m.nip')->on('peg.npsn','=','m.npsn');
		})
		->selectRaw("na.id_siswa,na.npsn,na.nama,na.nilai_ki3,na.predikat_ki3,na.deskripsi_ki3,na.nilai_ki4,na.predikat_ki4,na.deskripsi_ki4,m.nama as guru_mengajar,na.mapel")->whereRaw("na.id_siswa='$siswa_id' AND na.kategori IN ('AGAMA ISLAM') AND deskripsi_ki1 is null")->orderBy('na.urutan','ASC')->get();

		$walikelas = DB::connection($conn)->table($nama_schema.'.walikelas as wk')
		->leftjoin('public.pegawai as p',function($join){
			return $join->on('wk.nip','=','p.user_rapor')->on('wk.npsn','=','p.npsn');
		})
		->leftjoin('public.gelar_akademik as gd',function($join){
			return $join->on('p.gelar','=',DB::raw('CAST(gd.gelar_akademik_id as varchar)'));
		})
		->leftjoin('public.gelar_akademik as gb',function($join){
			return $join->on('p.gelar2','=',DB::raw('CAST(gb.gelar_akademik_id as varchar)'));
		})
		->selectRaw("p.nama as nama_wk,gd.kode as gelar_depan,gb.kode as gelar_belakang,CONCAT('NIP. ',p.nip) as nip")
		->whereRaw("wk.npsn='".Session::get('npsn')."' AND wk.kelas='$kelas->kelas' AND wk.rombel='$kelas->rombel'")->first();
		$ekskul = DB::connection($conn)->table($nama_schema.'.ekskul_absen')->whereRaw("npsn='".Session::get('npsn')."' AND id_siswa='$siswa_id' AND kelas='$kelas->kelas' AND rombel='$kelas->rombel'")->first();

		$prestasi = DB::connection($conn)->table('public.prestasi_siswa')->whereRaw("id_siswa='$siswa_id' AND npsn='".Session::get('npsn')."'")->get();

		$ks = DB::connection($conn)->table('public.pegawai as p')
		->leftjoin('public.gelar_akademik as gd',function($join){
			return $join->on('p.gelar','=',DB::raw('CAST(gd.gelar_akademik_id as varchar)'));
		})
		->leftjoin('public.gelar_akademik as gb',function($join){
			return $join->on('p.gelar2','=',DB::raw('CAST(gb.gelar_akademik_id as varchar)'));
		})
		->selectRaw("p.nama as nama_ks,gd.kode as gelar_depan,gb.kode as gelar_belakang,CONCAT('NIP. ',p.nip) as nip")
		->whereRaw("(npsn='".Session::get('npsn')."' or sekolah_plt='".Session::get('npsn')."') AND jabatan='2' AND keterangan='Aktif'")->first();

		if($jenjang=='SD'){
			$tahun_ajaran = substr($nama_schema,9,4);
			$semester = substr($nama_schema,14);
		}else{
			$tahun_ajaran = substr($nama_schema,10,4);
			$semester = substr($nama_schema,15);
		}

		$rapor_semester = DB::connection($conn)->table('public.rapor_semester')->whereRaw("semester='".$tahun_ajaran."_".$semester."'")->first();

		$qrcode = QrCode::size(50)->generate($request->url());

		switch ($nama_schema) {
			// case 'rapor_sd_2017_ganjil':
			// $pagesnya = '2017_ganjil';
			// break;
			// case 'rapor_sd_2017_genap':
			// $pagesnya = '2017_genap';
			// break;
			// case 'rapor_sd_2018_ganjil':
			// $pagesnya = '2018_ganjil';
			// break;
			// case 'rapor_sd_2018_genap':
			// $pagesnya = '2018_genap';
			// break;
			// case 'rapor_sd_2019_ganjil':
			// $pagesnya = '2019_ganjil';
			// break;
			// case 'rapor_sd_2019_genap':
			// $pagesnya = '2019_genap';
			// break;
			// case 'rapor_sd_2020_ganjil':
			// $pagesnya = '2020_ganjil';
			// break;
			// case 'rapor_sd_2020_genap':
			// $pagesnya = '2020_genap';
			// break;
			// case 'rapor_sd_2021_ganjil':
			// $pagesnya = '2021_ganjil';
			// break;

			default:
			$pagesnya = 'pages';
			break;
		}

		$kenaikan = "Berdasarkan pencapaian kompetensi pada semester ke-1 dan ke-2, peserta didik *) <br>Tidak Naik dan tetap di kelas ".$kelas->kelas.' ('.Convert::terbilang($kelas->kelas).')';
		if(isset($ekskul->kenaikan_kelas) && $ekskul->kenaikan_kelas==true){
			$kenaikan = "Berdasarkan pencapaian kompetensi pada semester ke-1 dan ke-2, peserta didik *) <br>Naik ke kelas ".($kelas->kelas+1).' ('.Convert::terbilang(($kelas->kelas+1)).')';
		}

		$data = [
			'siswa'=>$siswa,
			'sikap'=>$sikap,
			'kelas'=>$kelas,
			'semester'=>($semester=='genap') ? 'II (Dua)' : 'I (Satu)',
			'tahun_ajaran'=>$tahun_ajaran.'/'.($tahun_ajaran+1),
			'walikelas'=>$walikelas,
			'nilaia'=>$nilaia,
			'nilaib'=>$nilaib,
			'nilai_agama'=>$nilai_agama,
			'ekskul'=>$ekskul,
			'qrcode'=>$qrcode,
			'pagesnya'=>$pagesnya,
			'kenaikan'=>$kenaikan,
			'prestasi'=>$prestasi,
			'rapor_semester'=>$rapor_semester,
			'ks'=>$ks,
			'foto'=>'https://profilsekolah.dispendik.surabaya.go.id/profilsekolahlama/foto/siswa/'.$siswa->foto,
			'sisipan'=>$request->sisipan,
		];

		$content = view('siswa.rapor.data',$data);

		return $content;
	}

	function data_smp(Request $request){
		$jenjang = Session::get('jenjang');
		$id_siswa = $request->id_siswa;
		$nama_schema = $request->schema;
		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);
		$siswa = DB::connection($conn)->table('public.siswa as s')->join('public.sekolah as sek','sek.npsn','s.npsn')
		->leftjoin('public.kecamatan as kec','kec.kecamatan_kode','sek.kec_id')
		->leftjoin('public.kelurahan as kel','kel.kelurahan_kode','sek.desa')
		->leftjoin('public.agama as a','a.aga_id','s.aga_id')
		->leftjoin('public.wali_murid as wm',function($join){
			return $join->on('wm.id_siswa','=','s.id_siswa')->on('wm.npsn','=','s.npsn');
		})
		->leftjoin('public.pekerjaan as pa','pa.kode','wm.pekerjaan_ayah')
		->leftjoin('public.pekerjaan as pi','pi.kode','wm.pekerjaan_ibu')
		->leftjoin('public.pekerjaan as pw','pw.kode','wm.pekerjaan_wali')
		->selectRaw("s.foto,s.nama as nama_siswa,s.tgl_lahir,s.nisn,s.nis,s.tempat_lahir,s.kelas,s.rombel,sek.kepala,sek.email as email_sekolah,sek.website as website_sekolah,s.id_siswa,sek.nama as nama_sekolah,sek.alamat as alamat_sekolah,kec.kecamatan_dispenduk,kel.kelurahan_dispenduk,s.kelamin,s.asal_sekolah,a.aga_nama,s.status_anak,s.anakke,s.alamat_ortu,s.telpon,s.alamat as alamat_siswa,wm.nama_ayah as ayah,wm.nama_ibu as ibu,wm.nama_wali,wm.pekerjaan_wali,pa.nama as pekerjaan_ayah,pi.nama as pekerjaan_ibu,pw.nama as pekerjaan_wali,wm.alamat_rumah,wm.rt,wm.rw,sek.kkm,sek.nss")
		->where('s.id_siswa',$id_siswa)->first();
		$siswa_id = $siswa->id_siswa;

		$kelas = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->whereRaw("id_siswa='$siswa_id'")->orderBy('mapel_id','ASC')->first();

		$sikap = Hitung_sikap::nilai_sikap($siswa_id,$kelas->kelas,$kelas->rombel,$nama_schema,Session::get('npsn'));

		$nilaia = DB::connection($conn)->table($nama_schema.'.nilai_akhir as na')
		->leftjoin($nama_schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id')->on('m.npsn','=','na.npsn')->on('m.kelas','=','na.kelas')->on('m.rombel','=','na.rombel');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.user_rapor','=','m.nip')->on('peg.npsn','=','m.npsn');
		})
		->selectRaw("*")->whereRaw("na.id_siswa='$siswa_id' AND na.kategori IN ('KELOMPOK A','WAJIB') AND m.mapel_id!='1'")->orderBy('na.urutan','ASC')->get();

		$nilaib = DB::connection($conn)->table($nama_schema.'.nilai_akhir as na')
		->leftjoin($nama_schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id')->on('m.npsn','=','na.npsn')->on('m.kelas','=','na.kelas')->on('m.rombel','=','na.rombel');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.user_rapor','=','m.nip')->on('peg.npsn','=','m.npsn');
		})
		->selectRaw("*")->whereRaw("na.id_siswa='$siswa_id' AND na.kategori IN ('KELOMPOK B','MUATAN LOKAL') AND m.mapel_id!='1'")->orderBy('na.urutan','ASC')->get();

		$nilai_agama = DB::connection($conn)->table($nama_schema.'.nilai_akhir as na')
		->leftjoin($nama_schema.'.mengajar as m',function($join){
			return $join->on('m.mapel_id','=','na.mapel_id')->on('m.npsn','=','na.npsn')->on('m.kelas','=','na.kelas')->on('m.rombel','=','na.rombel');
		})
		->leftjoin('public.pegawai as peg',function($join){
			return $join->on('peg.user_rapor','=','m.nip')->on('peg.npsn','=','m.npsn');
		})
		->selectRaw("*")->whereRaw("na.id_siswa='$siswa_id' AND na.kategori IN ('AGAMA ISLAM') AND m.mapel_id!='1'")->orderBy('na.urutan','ASC')->get();

		$walikelas = DB::connection($conn)->table($nama_schema.'.walikelas as wk')
		->leftjoin('public.pegawai as p',function($join){
			return $join->on('wk.nip','=','p.user_rapor')->on('wk.npsn','=','p.npsn');
		})
		->leftjoin('public.gelar_akademik as gd',function($join){
			return $join->on('p.gelar','=',DB::raw('CAST(gd.gelar_akademik_id as varchar)'));
		})
		->leftjoin('public.gelar_akademik as gb',function($join){
			return $join->on('p.gelar2','=',DB::raw('CAST(gb.gelar_akademik_id as varchar)'));
		})
		->selectRaw("p.nama as nama_wk,gd.kode as gelar_depan,gb.kode as gelar_belakang,CONCAT('NIP. ',p.nip) as nip")
		->whereRaw("wk.npsn='".Session::get('npsn')."' AND wk.kelas='$kelas->kelas' AND wk.rombel='$kelas->rombel'")->first();
		$ekskul = DB::connection($conn)->table($nama_schema.'.ekskul_absen')->whereRaw("npsn='".Session::get('npsn')."' AND id_siswa='$siswa_id' AND kelas='$kelas->kelas' AND rombel='$kelas->rombel'")->first();

		$prestasi = DB::connection($conn)->table('public.prestasi_siswa')->whereRaw("id_siswa='$siswa_id' AND npsn='".Session::get('npsn')."'")->get();

		$ks = DB::connection($conn)->table('public.pegawai as p')
		->leftjoin('public.gelar_akademik as gd',function($join){
			return $join->on('p.gelar','=',DB::raw('CAST(gd.gelar_akademik_id as varchar)'));
		})
		->leftjoin('public.gelar_akademik as gb',function($join){
			return $join->on('p.gelar2','=',DB::raw('CAST(gb.gelar_akademik_id as varchar)'));
		})
		->selectRaw("p.nama as nama_ks,gd.kode as gelar_depan,gb.kode as gelar_belakang,CONCAT('NIP. ',p.nip) as nip")
		->whereRaw("(npsn='".Session::get('npsn')."' or sekolah_plt='".Session::get('npsn')."') AND jabatan='2' AND keterangan='Aktif'")->first();

		if($jenjang=='SD'){
			$tahun_ajaran = substr($nama_schema,9,4);
			$semester = substr($nama_schema,14);
		}else{
			$tahun_ajaran = substr($nama_schema,10,4);
			$semester = substr($nama_schema,15);
		}

		$rapor_semester = DB::connection($conn)->table('public.rapor_semester')->whereRaw("semester='".$tahun_ajaran."_".$semester."'")->first();

		$qrcode = QrCode::size(50)->generate($request->url());

		switch ($nama_schema) {
			// case 'rapor_sd_2017_ganjil':
			// $pagesnya = '2017_ganjil';
			// break;
			// case 'rapor_sd_2017_genap':
			// $pagesnya = '2017_genap';
			// break;
			// case 'rapor_sd_2018_ganjil':
			// $pagesnya = '2018_ganjil';
			// break;
			// case 'rapor_sd_2018_genap':
			// $pagesnya = '2018_genap';
			// break;
			// case 'rapor_sd_2019_ganjil':
			// $pagesnya = '2019_ganjil';
			// break;
			// case 'rapor_sd_2019_genap':
			// $pagesnya = '2019_genap';
			// break;
			// case 'rapor_sd_2020_ganjil':
			// $pagesnya = '2020_ganjil';
			// break;
			// case 'rapor_sd_2020_genap':
			// $pagesnya = '2020_genap';
			// break;
			// case 'rapor_sd_2021_ganjil':
			// $pagesnya = '2021_ganjil';
			// break;

			default:
			$pagesnya = 'pages_smp';
			break;
		}

		$kenaikan = "Berdasarkan pencapaian kompetensi pada semester ke-1 dan ke-2, peserta didik *) <br>Tidak Naik dan tetap di kelas ".$kelas->kelas.' ('.Convert::terbilang($kelas->kelas).')';
		if(isset($ekskul->kenaikan_kelas) && $ekskul->kenaikan_kelas==true){
			$kenaikan = "Berdasarkan pencapaian kompetensi pada semester ke-1 dan ke-2, peserta didik *) <br>Naik ke kelas ".($kelas->kelas+1).' ('.Convert::terbilang(($kelas->kelas+1)).')';
		}

		$data = [
			'siswa'=>$siswa,
			'sikap'=>$sikap,
			'kelas'=>$kelas,
			'semester'=>($semester=='genap') ? 'II (Dua)' : 'I (Satu)',
			'tahun_ajaran'=>$tahun_ajaran.'/'.($tahun_ajaran+1),
			'walikelas'=>$walikelas,
			'nilaia'=>$nilaia,
			'nilaib'=>$nilaib,
			'nilai_agama'=>$nilai_agama,
			'ekskul'=>$ekskul,
			'qrcode'=>$qrcode,
			'pagesnya'=>$pagesnya,
			'kenaikan'=>$kenaikan,
			'prestasi'=>$prestasi,
			'rapor_semester'=>$rapor_semester,
			'ks'=>$ks,
			'foto'=>'https://profilsekolah.dispendik.surabaya.go.id/profilsekolahlama/foto/siswa/'.$siswa->foto,
			'sisipan'=>$request->sisipan,
		];

		$content = view('siswa.rapor.data',$data);

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
		return $request->all();
		$absen = $request->absen; // ["Ngaji", "pramuka", null]
		$id_siswa = $request->id_siswa; // "35451514"
		$schema = $request->schema; // "rapor_sd_2021_ganjil"
		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$get_ekskul = DB::connection($conn)->table($schema.'.ekskul_absen')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND id_siswa='$id_siswa'")->first();

		$data_insert = [
			'sakit'=>$absen[0],
			'ijin'=>$absen[1],
			'tanpa_keterangan'=>$absen[2],
		];

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

	function simpan_catatan(Request $request){
		return $request->all();
		$catatan = $request->catatan; // ["Ngaji", "pramuka", null]
		$id_siswa = $request->id_siswa; // "35451514"
		$schema = $request->schema; // "rapor_sd_2021_ganjil"
		$kolom = $request->kolom; // "rapor_sd_2021_ganjil"
		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$get_ekskul = DB::connection($conn)->table($schema.'.ekskul_absen')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND id_siswa='$id_siswa'")->first();

		$data_insert = [
			$kolom=>$catatan,
		];

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

	function modal_kesehatan(Request $request){
		$coni = new Request;
		$id_siswa = $request->id_siswa;
		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$schema = $request->schema;

		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$siswa = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->whereRaw("id_anggota_rombel='$id_siswa'")->first();

		$data = [
			'siswa'=>$siswa,
			'nama_schema'=>$schema,
		];

		$content = view('guru.walikelas.isian_wk.pages.delapan.modal',$data)->render();

		return ['content'=>$content];
	}

	function simpan_kesehatan(Request $request){
		return $request->all();
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

		$request->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($request);

		$get_ekskul = DB::connection($conn)->table($schema.'.ekskul_absen')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND id_siswa='$id_siswa'")->first();

		$data_insert = [];

		for ($i=0; $i < count($tinggi); $i++) { 
			$data_insert = array_merge($data_insert,[
				'tinggi_semester'.($i+1) => $tinggi[$i],
				'beratbadan_semester'.($i+1) => $beratbadan[$i],
				'pendengaran_semester'.($i+1) => $dengar[$i],
				'penglihatan_semester'.($i+1) => $lihat[$i],
				'gigi_semester'.($i+1) => $gigi[$i],
				'lainnya_semester'.($i+1) => $lainnya[$i],
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

	function cetak_dkn(Request $request){
		$coni = new Request;

		$npsn = Session::get('npsn');
		$kelas = Session::get('kelas_wk');
		$rombel = Session::get('rombel_wk');
		$nama_schema = Session::get('nama_schema');
		$jenjang = Session::get('jenjang');

		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		if($jenjang=='SD'){
			$tahun_ajaran = substr($nama_schema,9,4);
			$semester = substr($nama_schema,14);
		}else{
			$tahun_ajaran = substr($nama_schema,10,4);
			$semester = substr($nama_schema,15);
		}

		$sekolah = DB::connection($conn)->table('public.sekolah as a')
		->whereRaw("a.npsn='$npsn'")
		->first();

		$tampil = [];

		// COLSPAN KOLOM KELOMPOK
		if($npsn=='20533172' or $npsn=='20533148'){
			$where_mapel = "mapel_id not in ( 11, 12, 13, 14, 15) and npsn = '".$npsn."' and kelas = '".$kelas."' and rombel = '".$rombel."'";
		}elseif ($npsn=='20532825' and $kelas=='4' and $rombel=='D') {
			$where_mapel = "mapel_id not in ( 12, 13, 14, 15) and npsn = '".$npsn."' and kelas = '".$kelas."' and rombel = '".$rombel."'";
		}else{
			$where_mapel = "mapel_id not in (10, 11, 12, 13, 14, 15) and npsn = '".$npsn."' and kelas = '".$kelas."' and rombel = '".$rombel."'";
		}

		// DATA MAPEL
		if($npsn=='20533172' or $npsn=='20533148' or $npsn=='20533510'){
			$where_nama = "mapel_id not in (10,11, 12, 13, 14, 15) and npsn = '".$npsn."' and kelas = '".$kelas."' and rombel = '".$rombel."'";
		}elseif ($npsn=='20532825' and $kelas=='4' and $rombel=='D') {
			$where_nama = "mapel_id not in (10, 12, 13, 14, 15) and npsn = '".$npsn."' and kelas = '".$kelas."' and rombel = '".$rombel."'";
		}else{
			$where_nama = "mapel_id not in (10, 11, 12, 13, 14, 15) and npsn = '".$npsn."' and kelas = '".$kelas."' and rombel = '".$rombel."'";
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

		// $qkwali=pg_fetch_assoc(pg_query("SELECT gelar, gelar2, nip, nuptk,
		// 	(SELECT kode as kode_gelar_depan FROM gelar_akademik WHERE gelar_akademik_id::text=gelar) AS gelar_dpn_wali,
		// 	(SELECT kode as kode_gelar_belakang FROM gelar_akademik WHERE gelar_akademik_id::text=gelar2) AS gelar_bkl_wali
		// 	FROM pegawai 
		// 	WHERE npsn='$tampnpsn' and user_rapor='$nip_wali'"));


		$ht_mapel = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->selectRaw("COUNT(DISTINCT(mapel_id)) as jml, kategori")->whereRaw("$where_mapel")->groupBy('mapel_id','kategori')->orderBy('kategori');
		$mapel = DB::connection($conn)->table(DB::raw("({$ht_mapel->toSql()}) as budi"))->selectRaw("COUNT(kategori) as jml,kategori")->groupBy("kategori")->get();
		$nama_mapel = DB::connection($conn)->table($nama_schema.'.nilai_akhir as n')->selectRaw("distinct(mapel) AS nama_mapel,kategori, urutan AS urutan_mapel,n.mapel_id")->whereRaw("$where_nama")->groupByRaw("mapel,kategori,urutan,mapel_id")->orderBy('kategori','asc','urutan','asc')->get();

		$siswa = DB::connection($conn)->table($nama_schema.'.nilai_akhir as n')->leftjoin('public.siswa as s',function($join){
			return $join->on(function($jj){
				return $jj->on('s.npsn','=','n.npsn')->orOn('s.npsn_asal','=','n.npsn');
			})->on('s.id_siswa','=','n.id_siswa');
		})
		->selectRaw("DISTINCT(n.id_siswa) AS id_siswa, s.nama AS nama_siswa")
		->whereRaw("$where_siswa")->orderBy('s.nama','asc')->get();

		$walikelas = DB::connection($conn)->table($nama_schema.'.walikelas as wk')
		->leftjoin('public.pegawai as peg','wk.nip','=','peg.user_rapor')
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
			'mapel'=>$mapel,
			'nama_mapel'=>$nama_mapel,
			'siswa'=>$siswa,
			'nama_schema'=>$nama_schema,
			'walikelas'=>$walikelas,
			'ks'=>$ks,
			'semester'=>$tahun_ajaran,
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

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND status_siswa='Aktif' AND alumni is not true")->get();
		$siswa_ta = DB::connection($conn)->table('public.anggota_rombel as ar')
		->join('public.rombongan_belajar as rb','rb.id_rombongan_belajar','ar.rombongan_belajar_id')
		->join('public.siswa as s','s.id_siswa','ar.siswa_id')
		->selectRaw("rb.*,s.nama")
		->whereRaw("rb.id_rombongan_belajar='$id_rombel'")->get();

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

		$siswa = DB::connection($conn)->table('public.siswa')->whereRaw("npsn='$npsn' AND kelas='$kelas' AND rombel='$rombel' AND status_siswa='Aktif' AND alumni is not true")->get();

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
