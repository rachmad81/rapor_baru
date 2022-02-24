<?php 
namespace App\Http\Libraries;
use Illuminate\Http\Request;
use App\Http\Libraries\Setkoneksi;
use DB,Session;

class Hitung_sikap
{
	public static function nilai_sikap($id_siswa,$kelas,$rombel,$schema,$npsn){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$query = DB::connection($conn)->table($schema.'.nilai as n')->whereRaw("n.mapel_id='1' AND n.npsn='$npsn' AND n.id_siswa='$id_siswa' AND n.kelas='$kelas' AND n.rombel='$rombel'")->get();

		$hurufk1 = '';
		$hurufk2 = '';
		$catatan1 = '';
		$catatan2 = '';
		$jumkd = 6;

		foreach($query as $k=>$v){
			/* ------------ KI-1 ------------ */																					
										//utk meghitung modus keseluruhan
			$tot_nilai_1=0;
			$tot_nilai_2=0;
			$tot_nilai_3=0;
			$tot_nilai_4=0;
			$aspek=array("syukur");
			$arr_nilai=array();
			for($a=0;$a<=0;$a++){
											//utk meghitung modus per sikap
				$nilai_1=0;
				$nilai_2=0;
				$nilai_3=0;
				$nilai_4=0;
				for($i=1;$i<=$jumkd;$i++){
					for($j=0;$j<=3;$j++){
						$kolom = $aspek[$a].'_'.$i;
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

			if($paling_besar == 1){ 
				$hurufk1 		= 'D';							
				$sikap_modus_1	= "";
				for($a=0;$a<=0;$a++){
					if($arr_nilai[$a]==1) $sikap_modus_1.= ", ".$aspek[$a];
				}
				$sikap_modus_1	= substr($sikap_modus_1,1);
				$catatan1		= "Perlu pemantauan dalam melaksanakan ibadah keseharian yang diwajibkan, sesuai agama dan keyakinannya.";
			}
			if($paling_besar == 2){
				$hurufk1		= 'C';
				$sikap_modus_2	= "";
				for($a=0;$a<=0;$a++){
					if($arr_nilai[$a]==2) $sikap_modus_2.= ", ".$aspek[$a];
				}
				$sikap_modus_2		= substr($sikap_modus_2,1);
				$sikap_modus_kurang	= "";
				for($a=0;$a<=0;$a++){
					if(($arr_nilai[$a]<2) and $arr_nilai[$a]>0) $sikap_modus_kurang.= ", ".$aspek[$a];
				}
				$sikap_modus_kurang	= substr($sikap_modus_kurang,1);
				$catatan1			= "Perlu ditingkatkan dalam melaksanakan ibadah keseharian yang diwajibkan, sesuai agama dan keyakinannya";
				if($sikap_modus_kurang <> "") $catatan1.= " serta perlu peningkatan $sikap_modus_kurang.";
			}
			if($paling_besar == 3){ 
				$hurufk1 		= 'B';
				$sikap_modus_3	= "";
				for($a=0;$a<=0;$a++){
					if($arr_nilai[$a]==3) $sikap_modus_3.= ", ".$aspek[$a];
				}
				$sikap_modus_3		= substr($sikap_modus_3,1);
				$sikap_modus_kurang	= "";
				for($a=0;$a<=0;$a++){
					if(($arr_nilai[$a]<3) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$aspek[$a];
				}
				$sikap_modus_kurang	= substr($sikap_modus_kurang,1);
				$catatan1			= "Melaksanakan ibadah keseharian yang diwajibkan, sesuai agama dan keyakinannya dengan baik serta menunjukkan sikap ".$sikap_modus_3;
											//if($sikap_modus_kurang<>"") $catatan1.=",namun perlu ditingkatkan dalam sikap $sikap_modus_kurang .";

			}
			if($paling_besar == 4) {
				$hurufk1 		= 'SB';
				$sikap_modus_4	= "";
				for($a=0;$a<=0;$a++){
					if($arr_nilai[$a]==4) $sikap_modus_4.= ", ".$aspek[$a];
				}
				$sikap_modus_4		= substr($sikap_modus_4,1);
				$sikap_modus_kurang	= "";
				for($a=0;$a<=0;$a++){
					if(($arr_nilai[$a]<4) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$aspek[$a];
				}
				$sikap_modus_kurang = substr($sikap_modus_kurang,1);
				$catatan1 			= "Sangat baik dalam melaksanakan ibadah keseharian, baik yang diwajibkan maupun yang dianjurkan sesuai dengan agama dan keyakinannya serta menunjukkan ".$sikap_modus_4;
											//if($sikap_modus_kurang<>"") $catatan1.=",namun perlu ditingkatkan dalam sikap $sikap_modus_kurang .";
			}
			/* ------------ END KI-1 ------------ */	

			/* ------------ KI-2 ------------ */																					
										//utk meghitung modus keseluruhan
			$tot_nilai_1=0;
			$tot_nilai_2=0;
			$tot_nilai_3=0;
			$tot_nilai_4=0;
			$aspek=array("disiplin","sopansantun","kerjasama","kepedulian","kejujuran","tanggungjawab","percayadiri") ;
			$arr_nilai=array();
			for($a=0;$a<=6;$a++){
											//utk meghitung modus per sikap
				$nilai_1=0;
				$nilai_2=0;
				$nilai_3=0;
				$nilai_4=0;
				for($i=1;$i<=$jumkd;$i++){
					for($j=0;$j<=3;$j++){
						$kolom = $aspek[$a].'_'.$i;
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

			if($paling_besar == 1){ 
				$hurufk2		= 'D';							
				$sikap_modus_1	= "";
				for($a=0;$a<=6;$a++){
					if($arr_nilai[$a]==1) $sikap_modus_1.= ", ".$aspek[$a];
				}
				$sikap_modus_1	= substr($sikap_modus_1,1);
				$catatan2		= "Perlu pembinaan dalam menjaga hubungan baik dengan teman, guru/pegawai dan kerjasama dalam kegiatan positif di sekolah.";
			}
			if($paling_besar == 2){
				$hurufk2		= 'C';
				$sikap_modus_2	= "";
				for($a=0;$a<=6;$a++){
					if($arr_nilai[$a]==2) $sikap_modus_2.= ", ".$aspek[$a];
				}
				$sikap_modus_2		= substr($sikap_modus_2,1);
				$sikap_modus_kurang	= "";
				for($a=0;$a<=6;$a++){
					if(($arr_nilai[$a]<2) and $arr_nilai[$a]>0) $sikap_modus_kurang.= ", ".$aspek[$a];
				}
				$sikap_modus_kurang	= substr($sikap_modus_kurang,1);
				$catatan2			= "Cukup dalam menjaga hubungan baik dengan teman, guru/pegawai, kadang  menolong temannya , bekerjasama dalam kegiatan positif di sekolah";
				if($sikap_modus_kurang <> "") $catatan2.= ", serta sikap ".$sikap_modus_kurang." perlu ditingkatkan.";
			}
			if($paling_besar == 3){ 
				$hurufk2		= 'B';
				$sikap_modus_3	= "";
				for($a=0;$a<=6;$a++){
					if($arr_nilai[$a]==3) $sikap_modus_3.= ", ".$aspek[$a];
				}
				$sikap_modus_3		= substr($sikap_modus_3,1);
				$sikap_modus_kurang	= "";
				for($a=0;$a<=6;$a++){
					if(($arr_nilai[$a]<3) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$aspek[$a];
				}
				$sikap_modus_kurang	= substr($sikap_modus_kurang,1);
				$catatan2			= "Mampu menjaga hubungan baik dengan teman, guru/pegawai, sering menolong temannya, mampu bekerjasama dalam kegiatan positif di sekolah.";
				if($sikap_modus_kurang <> "") $catatan2.= " Sikap ".$sikap_modus_kurang." perlu ditingkatkan.";

			}
			if($paling_besar == 4) {
				$hurufk2 		= 'SB';
				$sikap_modus_4	= "";
				for($a=0;$a<=6;$a++){
					if($arr_nilai[$a]==4) $sikap_modus_4.= ", ".$aspek[$a];
				}
				$sikap_modus_4		= substr($sikap_modus_4,1);
				$sikap_modus_kurang	= "";
				for($a=0;$a<=6;$a++){
					if(($arr_nilai[$a]<4) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$aspek[$a];
				}
				$sikap_modus_kurang	= substr($sikap_modus_kurang,1);
				$catatan2			= "Selalu menjaga hubungan baik dengan teman, guru/pegawai, menolong temannya, selalu bekerjasama dalam kegiatan positif di sekolah serta sangat baik dalam sikap ".$sikap_modus_4;
											//if($sikap_modus_kurang<>"") $catatan2.=",namun perlu ditingkatkan dalam sikap $sikap_modus_kurang .";
			}
			/* ------------ END KI-2 ------------ */	
		}

		return ['huruf_ki1'=>$hurufk1,'huruf_ki2'=>$hurufk2,'catatan_1'=>$catatan1,'catatan_2'=>$catatan2];
	}

	public static function hitung_dkn_ki3($id_siswa,$kelas,$rombel,$schema,$npsn,$mapel_id){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);

		$nilai = DB::connection($conn)->table($schema.'.nilai_akhir')->whereRaw("npsn='$npsn' AND id_siswa='$id_siswa' AND kelas='$kelas' AND mapel_id='$mapel_id'")->first();

		return $nilai;
	}

	public static function hitung_dkn_ki3_smt1($id_siswa,$kelas,$rombel,$schema,$npsn,$mapel_id){
		$jenjang = Session::get('jenjang');

		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		if($jenjang=='SD'){
			$nama_schema = substr($schema,0,14);
		}else{
			$nama_schema = substr($schema,0,15);
		}
		$nama_schema.='ganjil';


		$nilai = DB::connection($conn)->table($nama_schema.'.nilai_akhir')->whereRaw("npsn='$npsn' AND id_siswa='$id_siswa' AND kelas='$kelas' AND mapel_id='$mapel_id'")->first();

		return $nilai;
	}
}
?>