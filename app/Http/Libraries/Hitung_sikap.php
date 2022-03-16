<?php 
namespace App\Http\Libraries;
use Illuminate\Http\Request;
use App\Http\Libraries\Setkoneksi;
use DB,Session;

class Hitung_sikap
{
	public static function schema_func(){
		$schema = env('CURRENT_SCHEMA','production');
		return $schema;
	}	

	public static function nilai_sikap_sd($id_siswa){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$schema = Hitung_sikap::schema_func();
		$id_rombel = Session::get('id_rombel');
		$npsn = Session::get('npsn');
		
		$hurufk1 = '';
		$hurufk2 = '';
		$catatan1 = '';
		$catatan2 = '';
		$jumkd = 6;

		$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("siswa_id='$id_siswa' AND rombongan_belajar_id='$id_rombel'")->first();
		if(!empty($anggota)){
			// NILAI KI1
			$nama_aspek = ['Ketaan Beribadah','Perilaku Bersyukur','Berdoa','Toleransi beribadah'];
			$aspek = ['ibadah','syukur','berdoa','toleransi'];
			$arr_nilai = [];
			$nilai_tot_1 = 0;
			$nilai_tot_2 = 0;
			$nilai_tot_3 = 0;
			$nilai_tot_4 = 0;
			for ($i=0; $i < count($aspek); $i++) {
				$nilai_1 = 0;
				$nilai_2 = 0;
				$nilai_3 = 0;
				$nilai_4 = 0;
				for ($bulan=1; $bulan <=6 ; $bulan++) {
					$kolom = $aspek[$i];
					$nilai = DB::connection($conn)->table($schema.'.detail_nilai_perilaku as dp')
					->join($schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
					->whereRaw("np.anggota_rombel_id='$anggota->id_anggota_rombel' AND dp.bulan='$bulan'")->first();

					if(!empty($nilai)){
						switch($nilai->$kolom){
							case '1':
							$nilai_1++;
							$nilai_tot_1++;
							break;
							case '2':
							$nilai_2++;
							$nilai_tot_2++;
							break;
							case '3':
							$nilai_3++;
							$nilai_tot_3++;
							break;
							case '4':
							$nilai_4++;
							$nilai_tot_4++;
							break;

							default:
							break;
						}
					}

					$paling_besar = 0;
					$mod_paling_besar = 0;

					if($mod_paling_besar<$nilai_1){
						$paling_besar=1;
						$mod_paling_besar = $nilai_1;
					}
					if($mod_paling_besar<$nilai_2){
						$paling_besar=2;
						$mod_paling_besar = $nilai_2;
					}
					if($mod_paling_besar<$nilai_3){
						$paling_besar=3;
						$mod_paling_besar = $nilai_3;
					}
					if($mod_paling_besar<$nilai_4){
						$paling_besar=4;
						$mod_paling_besar = $nilai_4;
					}
					array_push($arr_nilai,$paling_besar);
				}
			}

			$paling_besar = 0;
			$mod_paling_besar = 0;
			for ($i=0; $i < count($arr_nilai); $i++) { 
				if($mod_paling_besar<$nilai_tot_1){
					$paling_besar=1;
					$mod_paling_besar = $nilai_tot_1;
				}
				if($mod_paling_besar<$nilai_tot_2){
					$paling_besar=2;
					$mod_paling_besar = $nilai_tot_2;
				}
				if($mod_paling_besar<$nilai_tot_3){
					$paling_besar=3;
					$mod_paling_besar = $nilai_tot_3;
				}
				if($mod_paling_besar<$nilai_tot_4){
					$paling_besar=4;
					$mod_paling_besar = $nilai_tot_4;
				}
			}

			if($paling_besar==1){ 
				$hurufk1='K';							
				$sikap_modus_1="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==1) $sikap_modus_1.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_1=substr($sikap_modus_1,1);
				$catatan1="Dengan bimbingan dan pendampingan yang lebih, peserta didik mampu ". $sikap_modus_1;
			}
			if($paling_besar==2){
				$hurufk1='C';
				$sikap_modus_2="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==2) $sikap_modus_2.= ", ".$nama_aspek[$a];
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
					if($arr_nilai[$a]==3) $sikap_modus_3.= ", ".$nama_aspek[$a];
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
					if($arr_nilai[$a]==4) $sikap_modus_4.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_4=substr($sikap_modus_4,1);
				$sikap_modus_kurang="";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<4) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_kurang=substr($sikap_modus_kurang,1);
				$catatan1="Peserta didik Sangat Baik dalam sikap spiritual $sikap_modus_4";
				if($sikap_modus_kurang<>"") $catatan1.=", dengan bimbingan dan pendampingan yang lebih, peserta didik akan mampu $sikap_modus_kurang.";
			}

			// NILAI KI2
			$nama_aspek = ['Jujur','Disiplin','Tanggung jawab','Sopan Santun','Percaya Diri','Peduli'];
			$aspek = ['jujur','disiplin','tanggung_jawab','sopan_santun','percaya_diri','peduli'];
			$arr_nilai = [];
			$nilai_tot_1 = 0;
			$nilai_tot_2 = 0;
			$nilai_tot_3 = 0;
			$nilai_tot_4 = 0;
			for ($i=0; $i < count($aspek); $i++) {
				$nilai_1 = 0;
				$nilai_2 = 0;
				$nilai_3 = 0;
				$nilai_4 = 0;
				for ($bulan=1; $bulan <=6 ; $bulan++) {
					$kolom = $aspek[$i];
					$nilai = DB::connection($conn)->table($schema.'.detail_nilai_perilaku as dp')
					->join($schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
					->whereRaw("np.anggota_rombel_id='$anggota->id_anggota_rombel' AND dp.bulan='$bulan'")->first();

					if(!empty($nilai)){
						switch($nilai->$kolom){
							case '1':
							$nilai_1++;
							$nilai_tot_1++;
							break;
							case '2':
							$nilai_2++;
							$nilai_tot_2++;
							break;
							case '3':
							$nilai_3++;
							$nilai_tot_3++;
							break;
							case '4':
							$nilai_4++;
							$nilai_tot_4++;
							break;

							default:
							break;
						}
					}

					$paling_besar = 0;
					$mod_paling_besar = 0;

					if($mod_paling_besar<$nilai_1){
						$paling_besar=1;
						$mod_paling_besar = $nilai_1;
					}
					if($mod_paling_besar<$nilai_2){
						$paling_besar=2;
						$mod_paling_besar = $nilai_2;
					}
					if($mod_paling_besar<$nilai_3){
						$paling_besar=3;
						$mod_paling_besar = $nilai_3;
					}
					if($mod_paling_besar<$nilai_4){
						$paling_besar=4;
						$mod_paling_besar = $nilai_4;
					}
					array_push($arr_nilai,$paling_besar);
				}
			}

			$paling_besar = 0;
			$mod_paling_besar = 0;
			for ($i=0; $i < count($arr_nilai); $i++) { 
				if($mod_paling_besar<$nilai_tot_1){
					$paling_besar=1;
					$mod_paling_besar = $nilai_tot_1;
				}
				if($mod_paling_besar<$nilai_tot_2){
					$paling_besar=2;
					$mod_paling_besar = $nilai_tot_2;
				}
				if($mod_paling_besar<$nilai_tot_3){
					$paling_besar=3;
					$mod_paling_besar = $nilai_tot_3;
				}
				if($mod_paling_besar<$nilai_tot_4){
					$paling_besar=4;
					$mod_paling_besar = $nilai_tot_4;
				}
			}

			if($paling_besar==1){ 
				$hurufk2='K';							
				$sikap_modus_1="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==1) $sikap_modus_1.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_1=substr($sikap_modus_1,1);
				$catatan2="Dengan bimbingan dan pendampingan yang lebih, peserta didik mampu ". $sikap_modus_1;
			}
			if($paling_besar==2){
				$hurufk2='C';
				$sikap_modus_2="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==2) $sikap_modus_2.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_2=substr($sikap_modus_2,1);
				$sikap_modus_kurang="";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<2) and $arr_nilai[$a]>0) $sikap_modus_kurang.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_kurang=substr($sikap_modus_kurang,1);
				$catatan2="Dengan bimbingan dan pendampingan yang lebih, peserta didik mampu $sikap_modus_2";
											#if($sikap_modus_kurang<>"") $catatan2.=", dan perlu ditingkatkan dalam sikap $sikap_modus_kurang.";
			}
			if($paling_besar==3){ 
				$hurufk2='B';
				$sikap_modus_3="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==3) $sikap_modus_3.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_3=substr($sikap_modus_3,1);
				$sikap_modus_kurang="";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<3) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_kurang=substr($sikap_modus_kurang,1);
				$catatan2="Peserta didik Baik dalam sikap sosial $sikap_modus_3";
				if($sikap_modus_kurang<>"") $catatan2.=", dengan bimbingan dan pendampingan yang lebih, peserta didik akan mampu $sikap_modus_kurang.";

			}
			if($paling_besar==4) {
				$hurufk2='SB';
				$sikap_modus_4="";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==4) $sikap_modus_4.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_4=substr($sikap_modus_4,1);
				$sikap_modus_kurang="";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<4) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_kurang=substr($sikap_modus_kurang,1);
				$catatan2="Peserta didik Sangat Baik dalam sikap sosial $sikap_modus_4";
				if($sikap_modus_kurang<>"") $catatan2.=", dengan bimbingan dan pendampingan yang lebih, peserta didik akan mampu $sikap_modus_kurang.";
			}

			$data_insert = [
				'anggota_rombel_id'=>$anggota->id_anggota_rombel,
				'npsn'=>$npsn,
				'predikat_ki1'=>$hurufk1,
				'deskripsi_ki1'=>$catatan1,
				'predikat_ki2'=>$hurufk2,
				'deskripsi_ki2'=>$catatan2,
				'tgl_perhitungan'=>date('Y-m-d H:i:s'),
				'updated_at'=>date('Y-m-d H:i:s'),
			];

			$nilai_perilaku = DB::connection($conn)->table($schema.'.nilai_perilaku')->whereRaw("npsn='$npsn' AND anggota_rombel_id='$anggota->id_anggota_rombel'")->first();
			if(!empty($nilai_perilaku)){
				$simpan = DB::connection($conn)->table($schema.'.nilai_perilaku')->whereRaw("npsn='$npsn' AND anggota_rombel_id='$anggota->id_anggota_rombel'")->update($data_insert);
			}else{
				$data_insert = array_merge($data_insert,[
					'catatan_siswa'=>null,
					'sakit'=>null,
					'izin'=>null,
					'tanpa_keterangan'=>null,
					'tinggi_badan'=>null,
					'berat_badan'=>null,
					'pendengaran'=>null,
					'penglihatan'=>null,
					'gizi'=>null,
					'lainnya'=>null,
					'created_at'=>date('Y-m-d H:i:s'),
				]);
				$simpan = DB::connection($conn)->table($schema.'.nilai_perilaku')->insert($data_insert);
			}
		}else{

		}

		$data_insert = [
			'hurufk1'=>$hurufk1,
			'catatan1'=>$catatan1,
			'hurufk2'=>$hurufk2,
			'catatan2'=>$catatan2,
		];

		return $data_insert;
	}	

	public static function nilai_sikap($id_siswa){
		$coni = new Request;
		$coni->jenjang = Session::get('jenjang');
		$conn = Setkoneksi::set_koneksi($coni);
		$schema = Hitung_sikap::schema_func();
		$id_rombel = Session::get('id_rombel');
		$npsn = Session::get('npsn');
		
		$hurufk1 = '';
		$hurufk2 = '';
		$catatan1 = '';
		$catatan2 = '';
		$jumkd = 6;

		$anggota = DB::connection($conn)->table('public.anggota_rombel')->whereRaw("siswa_id='$id_siswa' AND rombongan_belajar_id='$id_rombel'")->first();
		if(!empty($anggota)){
			// NILAI KI1
			$nama_aspek = ['Perilaku Bersyukur','Ketaan Beribadah'];
			$aspek = ['syukur','ibadah'];
			$arr_nilai = [];
			$nilai_tot_1 = 0;
			$nilai_tot_2 = 0;
			$nilai_tot_3 = 0;
			$nilai_tot_4 = 0;
			for ($i=0; $i < count($aspek); $i++) {
				$nilai_1 = 0;
				$nilai_2 = 0;
				$nilai_3 = 0;
				$nilai_4 = 0;
				for ($bulan=1; $bulan <=6 ; $bulan++) {
					$kolom = $aspek[$i];
					$nilai = DB::connection($conn)->table($schema.'.detail_nilai_perilaku as dp')
					->join($schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
					->whereRaw("np.anggota_rombel_id='$anggota->id_anggota_rombel' AND dp.bulan='$bulan'")->first();

					if(!empty($nilai)){
						switch($nilai->$kolom){
							case '1':
							$nilai_1++;
							$nilai_tot_1++;
							break;
							case '2':
							$nilai_2++;
							$nilai_tot_2++;
							break;
							case '3':
							$nilai_3++;
							$nilai_tot_3++;
							break;
							case '4':
							$nilai_4++;
							$nilai_tot_4++;
							break;

							default:
							break;
						}
					}

					$paling_besar = 0;
					$mod_paling_besar = 0;

					if($mod_paling_besar<$nilai_1){
						$paling_besar=1;
						$mod_paling_besar = $nilai_1;
					}
					if($mod_paling_besar<$nilai_2){
						$paling_besar=2;
						$mod_paling_besar = $nilai_2;
					}
					if($mod_paling_besar<$nilai_3){
						$paling_besar=3;
						$mod_paling_besar = $nilai_3;
					}
					if($mod_paling_besar<$nilai_4){
						$paling_besar=4;
						$mod_paling_besar = $nilai_4;
					}
					array_push($arr_nilai,$paling_besar);
				}
			}

			$paling_besar = 0;
			$mod_paling_besar = 0;
			for ($i=0; $i < count($arr_nilai); $i++) { 
				if($mod_paling_besar<$nilai_tot_1){
					$paling_besar=1;
					$mod_paling_besar = $nilai_tot_1;
				}
				if($mod_paling_besar<$nilai_tot_2){
					$paling_besar=2;
					$mod_paling_besar = $nilai_tot_2;
				}
				if($mod_paling_besar<$nilai_tot_3){
					$paling_besar=3;
					$mod_paling_besar = $nilai_tot_3;
				}
				if($mod_paling_besar<$nilai_tot_4){
					$paling_besar=4;
					$mod_paling_besar = $nilai_tot_4;
				}
			}

			if($paling_besar == 1){ 
				$hurufk1 		= 'D';							
				$sikap_modus_1	= "";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==1) $sikap_modus_1.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_1	= substr($sikap_modus_1,1);
				$catatan1		= "Perlu pemantauan dalam melaksanakan ibadah keseharian yang diwajibkan, sesuai agama dan keyakinannya.";
			}
			if($paling_besar == 2){
				$hurufk1		= 'C';
				$sikap_modus_2	= "";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==2) $sikap_modus_2.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_2		= substr($sikap_modus_2,1);
				$sikap_modus_kurang	= "";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<2) and $arr_nilai[$a]>0) $sikap_modus_kurang.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_kurang	= substr($sikap_modus_kurang,1);
				$catatan1			= "Perlu ditingkatkan dalam melaksanakan ibadah keseharian yang diwajibkan, sesuai agama dan keyakinannya";
				if($sikap_modus_kurang <> "") $catatan1.= " serta perlu peningkatan $sikap_modus_kurang.";
			}
			if($paling_besar == 3){ 
				$hurufk1 		= 'B';
				$sikap_modus_3	= "";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==3) $sikap_modus_3.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_3		= substr($sikap_modus_3,1);
				$sikap_modus_kurang	= "";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<3) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_kurang	= substr($sikap_modus_kurang,1);
				$catatan1			= "Melaksanakan ibadah keseharian yang diwajibkan, sesuai agama dan keyakinannya dengan baik serta menunjukkan sikap".$sikap_modus_3;
			}
			if($paling_besar == 4) {
				$hurufk1 		= 'SB';
				$sikap_modus_4	= "";
				for($a=0;$a<count($aspek);$a++){
					if($arr_nilai[$a]==4) $sikap_modus_4.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_4		= substr($sikap_modus_4,1);
				$sikap_modus_kurang	= "";
				for($a=0;$a<count($aspek);$a++){
					if(($arr_nilai[$a]<4) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_kurang = substr($sikap_modus_kurang,1);
				$catatan1 			= "Sangat baik dalam melaksanakan ibadah keseharian, baik yang diwajibkan maupun yang dianjurkan sesuai dengan agama dan keyakinannya serta menunjukkan ".$sikap_modus_4;
			}

			// NILAI KI2
			$nama_aspek = ['Jujur','Disiplin','Tanggung jawab','Sopan Santun','Percaya Diri','Peduli','Kerjasama'];
			$aspek = ['jujur','disiplin','tanggung_jawab','sopan_santun','percaya_diri','peduli','kerjasama'];
			$arr_nilai = [];
			$nilai_tot_1 = 0;
			$nilai_tot_2 = 0;
			$nilai_tot_3 = 0;
			$nilai_tot_4 = 0;
			for ($i=0; $i < count($aspek); $i++) {
				$nilai_1 = 0;
				$nilai_2 = 0;
				$nilai_3 = 0;
				$nilai_4 = 0;
				for ($bulan=1; $bulan <=6 ; $bulan++) {
					$kolom = $aspek[$i];
					$nilai = DB::connection($conn)->table($schema.'.detail_nilai_perilaku as dp')
					->join($schema.'.nilai_perilaku as np','np.id_nilai_perilaku','dp.nilai_perilaku_id')
					->whereRaw("np.anggota_rombel_id='$anggota->id_anggota_rombel' AND dp.bulan='$bulan'")->first();

					if(!empty($nilai)){
						switch($nilai->$kolom){
							case '1':
							$nilai_1++;
							$nilai_tot_1++;
							break;
							case '2':
							$nilai_2++;
							$nilai_tot_2++;
							break;
							case '3':
							$nilai_3++;
							$nilai_tot_3++;
							break;
							case '4':
							$nilai_4++;
							$nilai_tot_4++;
							break;

							default:
							break;
						}
					}

					$paling_besar = 0;
					$mod_paling_besar = 0;

					if($mod_paling_besar<$nilai_1){
						$paling_besar=1;
						$mod_paling_besar = $nilai_1;
					}
					if($mod_paling_besar<$nilai_2){
						$paling_besar=2;
						$mod_paling_besar = $nilai_2;
					}
					if($mod_paling_besar<$nilai_3){
						$paling_besar=3;
						$mod_paling_besar = $nilai_3;
					}
					if($mod_paling_besar<$nilai_4){
						$paling_besar=4;
						$mod_paling_besar = $nilai_4;
					}
					array_push($arr_nilai,$paling_besar);
				}
			}

			$paling_besar = 0;
			$mod_paling_besar = 0;
			for ($i=0; $i < count($arr_nilai); $i++) { 
				if($mod_paling_besar<$nilai_tot_1){
					$paling_besar=1;
					$mod_paling_besar = $nilai_tot_1;
				}
				if($mod_paling_besar<$nilai_tot_2){
					$paling_besar=2;
					$mod_paling_besar = $nilai_tot_2;
				}
				if($mod_paling_besar<$nilai_tot_3){
					$paling_besar=3;
					$mod_paling_besar = $nilai_tot_3;
				}
				if($mod_paling_besar<$nilai_tot_4){
					$paling_besar=4;
					$mod_paling_besar = $nilai_tot_4;
				}
			}

			if($paling_besar == 1){ 
				$hurufk2		= 'D';							
				$sikap_modus_1	= "";
				for($a=0;$a<=6;$a++){
					if($arr_nilai[$a]==1) $sikap_modus_1.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_1	= substr($sikap_modus_1,1);
				$catatan2		= "Perlu pembinaan dalam menjaga hubungan baik dengan teman, guru/pegawai dan kerjasama dalam kegiatan positif di sekolah.";
			}
			if($paling_besar == 2){
				$hurufk2		= 'C';
				$sikap_modus_2	= "";
				for($a=0;$a<=6;$a++){
					if($arr_nilai[$a]==2) $sikap_modus_2.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_2		= substr($sikap_modus_2,1);
				$sikap_modus_kurang	= "";
				for($a=0;$a<=6;$a++){
					if(($arr_nilai[$a]<2) and $arr_nilai[$a]>0) $sikap_modus_kurang.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_kurang	= substr($sikap_modus_kurang,1);
				$catatan2			= "Cukup dalam menjaga hubungan baik dengan teman, guru/pegawai, kadang  menolong temannya , bekerjasama dalam kegiatan positif di sekolah";
				if($sikap_modus_kurang <> "") $catatan2.= ", serta sikap ".$sikap_modus_kurang." perlu ditingkatkan.";
			}
			if($paling_besar == 3){ 
				$hurufk2		= 'B';
				$sikap_modus_3	= "";
				for($a=0;$a<=6;$a++){
					if($arr_nilai[$a]==3) $sikap_modus_3.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_3		= substr($sikap_modus_3,1);
				$sikap_modus_kurang	= "";
				for($a=0;$a<=6;$a++){
					if(($arr_nilai[$a]<3) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_kurang	= substr($sikap_modus_kurang,1);
				$catatan2			= "Mampu menjaga hubungan baik dengan teman, guru/pegawai, sering menolong temannya, mampu bekerjasama dalam kegiatan positif di sekolah.";
				if($sikap_modus_kurang <> "") $catatan2.= " Sikap ".$sikap_modus_kurang." perlu ditingkatkan.";

			}
			if($paling_besar == 4) {
				$hurufk2 		= 'SB';
				$sikap_modus_4	= "";
				for($a=0;$a<=6;$a++){
					if($arr_nilai[$a]==4) $sikap_modus_4.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_4		= substr($sikap_modus_4,1);
				$sikap_modus_kurang	= "";
				for($a=0;$a<=6;$a++){
					if(($arr_nilai[$a]<4) and ($arr_nilai[$a]>0)) $sikap_modus_kurang.= ", ".$nama_aspek[$a];
				}
				$sikap_modus_kurang	= substr($sikap_modus_kurang,1);
				$catatan2			= "Selalu menjaga hubungan baik dengan teman, guru/pegawai, menolong temannya, selalu bekerjasama dalam kegiatan positif di sekolah serta sangat baik dalam sikap ".$sikap_modus_4;
											//if($sikap_modus_kurang<>"") $catatan2.=",namun perlu ditingkatkan dalam sikap $sikap_modus_kurang .";
			}

			$data_insert = [
				'anggota_rombel_id'=>$anggota->id_anggota_rombel,
				'npsn'=>$npsn,
				'predikat_ki1'=>$hurufk1,
				'deskripsi_ki1'=>$catatan1,
				'predikat_ki2'=>$hurufk2,
				'deskripsi_ki2'=>$catatan2,
				'tgl_perhitungan'=>date('Y-m-d H:i:s'),
				'updated_at'=>date('Y-m-d H:i:s'),
			];

			$nilai_perilaku = DB::connection($conn)->table($schema.'.nilai_perilaku')->whereRaw("npsn='$npsn' AND anggota_rombel_id='$anggota->id_anggota_rombel'")->first();
			if(!empty($nilai_perilaku)){
				$simpan = DB::connection($conn)->table($schema.'.nilai_perilaku')->whereRaw("npsn='$npsn' AND anggota_rombel_id='$anggota->id_anggota_rombel'")->update($data_insert);
			}else{
				$data_insert = array_merge($data_insert,[
					'catatan_siswa'=>null,
					'sakit'=>null,
					'izin'=>null,
					'tanpa_keterangan'=>null,
					'tinggi_badan'=>null,
					'berat_badan'=>null,
					'pendengaran'=>null,
					'penglihatan'=>null,
					'gizi'=>null,
					'lainnya'=>null,
					'created_at'=>date('Y-m-d H:i:s'),
				]);
				$simpan = DB::connection($conn)->table($schema.'.nilai_perilaku')->insert($data_insert);
			}
		}else{

		}

		$data_insert = [
			'hurufk1'=>$hurufk1,
			'catatan1'=>$catatan1,
			'hurufk2'=>$hurufk2,
			'catatan2'=>$catatan2,
		];

		return $data_insert;
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