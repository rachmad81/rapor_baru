<?php 
namespace App\Http\Libraries;

use Illuminate\Http\Request;
use Session,DB;

class Convert
{
	public static function schema_func(){
		$schema = env('CURRENT_SCHEMA','production');
		return $schema;
	}

	public static function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = Convert::penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = Convert::penyebut($nilai/10)." puluh". Convert::penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . Convert::penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = Convert::penyebut($nilai/100) . " ratus" . Convert::penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . Convert::penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = Convert::penyebut($nilai/1000) . " ribu" . Convert::penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = Convert::penyebut($nilai/1000000) . " juta" . Convert::penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = Convert::penyebut($nilai/1000000000) . " milyar" . Convert::penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = Convert::penyebut($nilai/1000000000000) . " trilyun" . Convert::penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}

	public static function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(Convert::penyebut($nilai));
		} else {
			if($nilai==0){
				$hasil = "nol";
			}else{
				$hasil = trim(Convert::penyebut($nilai));
			}
		}     		
		return $hasil;
	}

	public static function tgl_indo($tgl){
		$tanggal = ltrim(substr($tgl,8,2),'0');
		$bulan = Convert::getBulan(substr($tgl,5,2));
		$tahun = substr($tgl,0,4);
		return $tanggal.' '.$bulan.' '.$tahun;		 
	}
	
	public static function getBulan($bln){
		switch ($bln){
			case 1: 
			return "Januari";
			break;
			case 2:
			return "Februari";
			break;
			case 3:
			return "Maret";
			break;
			case 4:
			return "April";
			break;
			case 5:
			return "Mei";
			break;
			case 6:
			return "Juni";
			break;
			case 7:
			return "Juli";
			break;
			case 8:
			return "Agustus";
			break;
			case 9:
			return "September";
			break;
			case 10:
			return "Oktober";
			break;
			case 11:
			return "November";
			break;
			case 12:
			return "Desember";
			break;
		}
	}

	public static function catatan_ki3($mapel_id, $kelas, $huruf, $kd_terendah, $kd_terendah2, $kd_terendah3, $kd_tertinggi){
		$schema = Convert::schema_func();
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$catatan	='';
		
		$kd = DB::connection($conn)->table($schema.'.kd')->whereRaw("mapel_id='$mapel_id' AND no_ki='3' and kelas='$kelas'")->orderBy('id_kd','ASC')->offset($kd_tertinggi)->limit(1)->first();
		$hasil_kd = (!empty($kd)) ? $kd->isi : "nothing";
		
		if($huruf == 'A'){
			$catatan	= "Sangat Baik, menguasai dan memahami semua kompetensi, terutama ";
			$catatan.= $hasil_kd;
		}elseif($huruf == 'B'){
			$kd = DB::connection($conn)->table($schema.'.kd')->whereRaw("mapel_id='$mapel_id' AND no_ki='3' and kelas='$kelas'")->orderBy('id_kd','ASC')->offset($kd_terendah)->limit(1)->first();
			$hasil_kd = (!empty($kd)) ? $kd->isi : 'nothing';

			$catatan	= "Menguasai sebagian besar kompetensi yang dipersyaratkan dengan baik. Perlu ditingkatkan pemahaman pada kompetensi ";
			$catatan.= $hasil_kd. " perlu ditingkatkan";	
		}elseif($huruf == 'C'){
			$kd = DB::connection($conn)->table($schema.'.kd')->whereRaw("mapel_id='$mapel_id' AND no_ki='3' and kelas='$kelas'")->orderBy('id_kd','ASC')->offset($kd_terendah)->limit(1)->first();
			$hasil_kd = (!empty($kd)) ? $kd->isi : 'nothing';

			$catatan	= "Beberapa kompetensi telah dikuasai dengan cukup baik. Kompetensi " ;
			$catatan.= $hasil_kd ;
			$catatan.= " perlu ditingkatkan.";
		}elseif($huruf == 'D'){
			$catatan = "Belum menguasai sebagian besar kompetensi yang dipersyaratkan.";
		}
		
		return $catatan;
	}

	public static function catatan_ki4($mapel_id, $kelas, $huruf, $kd_terendah, $kd_terendah2, $kd_terendah3, $kd_tertinggi){
		$schema = Convert::schema_func();
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$catatan	= '';
		
		$kd = DB::connection($conn)->table($schema.'.kd')->whereRaw("mapel_id='$mapel_id' AND no_ki='4' and kelas='$kelas'")->orderBy('id_kd','ASC')->offset($kd_tertinggi)->limit(1)->first();
		$hasil_kd = (!empty($kd)) ? $kd->isi : 'nothing';
		if($huruf == 'A'){
			$catatan 	= "Sangat baik, terampil dan mahir dalam semua kompetensi, terutama ";

			$catatan.= $hasil_kd;			
		}elseif($huruf == 'B'){
			$kd = DB::connection($conn)->table($schema.'.kd')->whereRaw("mapel_id='$mapel_id' AND no_ki='4' and kelas='$kelas'")->orderBy('id_kd','ASC')->offset($kd_terendah)->limit(1)->first();
			$hasil_kd = (!empty($kd)) ? $kd->isi : 'nothing';

			$catatan	= "Terampil pada sebagian besar  kompetens yang dipersyaratkani. Perlu ditingkatkan keterampilan pada kompetensi ";
			$catatan.= $hasil_kd. " perlu ditingkatkan";
		}elseif($huruf == 'C'){
			$kd = DB::connection($conn)->table($schema.'.kd')->whereRaw("mapel_id='$mapel_id' AND no_ki='4' and kelas='$kelas'")->orderBy('id_kd','ASC')->offset($kd_terendah)->limit(1)->first();
			$hasil_kd = (!empty($kd)) ? $kd->isi : 'nothing';

			$catatan	= "Cukup terampil dalam beberapa kompetensi. Kompetensi ";
			$catatan.= $hasil_kd ;
			$catatan.=" perlu ditingkatkan.";
		}elseif($huruf == 'D'){
			$catatan = "Belum terampil pada sebagian besar kompetensi yang dipersyaratkan.";
		}

		return $catatan;
	}

	public static function angka2hurufsma($angka,$kkm){
		$temp 	= round(((100 - $kkm)/3),0);
		$c		= $kkm + ($temp - 1);
		$b		= $c + ($temp - 1);
		
		if($angka < $kkm){
			$huruf = 'D';
		}elseif($angka >= $kkm and $angka <= $c){
			$huruf = 'C';	
		}elseif($angka > $c and $angka <= $b){	
			$huruf = 'B';
		}elseif($angka > $b){
			$huruf = 'A';
		}elseif($angka == 0){
			$huruf = '';
		}	
		return $huruf;
	}
}
?>