<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Dinas Pendidika Kota Surabaya</title>
	<?php
	if($excel!=''){
		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=dkn_".$nama_schema."_".$kelas."_".$rombel."_".$npsn.".xls");
	} ?>
</head>
<body>
	<table width='100%' border='0' cellpadding='0' cellspacing='0'>
		<tr>
			<td colspan="14" style="padding-right:90px">
				<h3 align="center">
					<b>DAFTAR KUMPULAN NILAI 
						SEMESTER 1 DAN 2<br/>
						TAHUN PELAJARAN {{$semester}}/{{($semester+1)}}<br/>
						SEKOLAH DASAR <br/>
					(SD)</b>
				</h3><br />
			</td>
		</tr>
		<tr>
			<td colspan="2">Kelas</td>	
			<td colspan="12">: {{$kelas}}.{{$rombel}} {{$npsn}}</td>
		</tr>
		<tr>
			<td colspan="2">Nama Sekolah</td>	
			<td colspan="12">: <a href="{{Request::url()}}?excel=export">{{$sekolah->nama}}</a></td>
		</tr>
		<tr>
			<td colspan="2">Alamat Sekolah</td>
			<td colspan="12">: {{$sekolah->alamat}}</td>
		</tr>
		<tr>
			<td colspan="2">Telpon/Fax</td>
			<td colspan="12">: {{$sekolah->telp}} / {{$sekolah->fax}}</td>
		</tr>
		<tr>
			<td colspan="14"><br /></td>				
		</tr>
		<tr valign='middle' bgcolor="#E9EAEB">
			<th width="2%" rowspan="2" align="center" style="font-size:12px;border-left: 1px solid black;border-top: 1px solid black;border-bottom: 1px solid black;">No</th>
			<th width="15%" rowspan="2" align="center" style="font-size:12px;border-left: 1px solid black;border-top: 1px solid black;border-bottom: 1px solid black;">Nama</th>
			<th width="3%" rowspan="2" align="center" style="font-size:12px;border-left: 1px solid black;border-top: 1px solid black;border-bottom: 1px solid black;">Semester</th>
			<th width="3%" rowspan="2" align="center" style="font-size:12px;border-left: 1px solid black;border-right: 1px solid black;border-top: 1px solid black;border-bottom: 1px solid black;">Aspek</th>
			<?php
			foreach($mapel as $k=>$v){ ?>
			<th colspan="{{$v->jml}}" align="center" style="font-size:12px;border-right: 1px solid black;border-top: 1px solid black;">{{$v->kategori}}</th>
			<?php } ?>
		</tr>
		<tr valign='middle' bgcolor="#E9EAEB">
			<?php foreach($nama_mapel as $k=>$v){ ?>
			<th width="6%" class="vert" align="center" style="padding-left:2px;padding-right:2px;padding-top:5px;padding-bottom:5px;font-size:12px;border-right: 1px solid black;border-bottom: 1px solid black;border-top: 1px solid black;">
				<?php 
				if($v->urutan_mapel==1){
					$v->nama_mapel = 'Pendidikan Agama';
				} ?>
				{!! $v->nama_mapel !!} 
			</th>
			<?php } ?>
		</tr>
		<?php
		foreach($siswa as $k=>$s){
		?>
		<tr>
			<td rowspan="4" align="center" style="font-size:12px;border-left: 1px solid black;border-bottom: 1px solid black;">{{($k+1)}}</td>
			<td rowspan="4" align="justify" style="padding-bottom:5px;padding-top:5px;padding-left:5px;padding-right:5px;font-size:12px;border-left: 1px solid black;border-bottom: 1px solid black;">
				{{$s->nama_siswa}}
			</td>
			<td rowspan="2" style="border-left:1px solid black;border-bottom:1px solid black;font-size:12px;" align='center'>1</td>
			<td align="justify" style="padding-bottom:5px;padding-top:5px;padding-left:5px;padding-right:5px;font-size:12px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">Pengetahuan</td>
			<?php
			foreach($nama_mapel as $k1=>$m_smt_1){
			?>
			<td align="center" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align='center'>
				<?php 
				$nilai_3 = App\Http\Libraries\Hitung_sikap::hitung_dkn_ki3_smt1($s->id_siswa,$kelas,$rombel,$nama_schema,$npsn,$m_smt_1->mapel_id); ?>
				@if(!empty($nilai_3)) {{number_format($nilai_3->nilai_ki3,0)}} @endif
			</td>
			<?php } ?>
		</tr>
		<tr>
			<td align="justify" style="padding-bottom:5px;padding-top:5px;padding-left:5px;padding-right:5px;font-size:12px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">Keterampilan</td>
			<?php
			foreach($nama_mapel as $k1=>$m_smt_1){
			?>
			<td align="center" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align='center'>
				<?php 
				$nilai_3 = App\Http\Libraries\Hitung_sikap::hitung_dkn_ki3_smt1($s->id_siswa,$kelas,$rombel,$nama_schema,$npsn,$m_smt_1->mapel_id); ?>
				@if(!empty($nilai_3)) {{number_format($nilai_3->nilai_ki4,0)}} @endif
			</td>
			<?php } ?>
		</tr>
		<!------ SEMESTER 2 -->
		<tr>
			<td rowspan="2" style="border-left:1px solid black;border-bottom:1px solid black;font-size:12px;" align='center'>2</td>
			<td align="justify" style="padding-bottom:5px;padding-top:5px;padding-left:5px;padding-right:5px;font-size:12px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">Pengetahuan</td>
			<?php
			foreach($nama_mapel as $k1=>$m_smt_1){
			?>
			<td align="center" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align='center'>
				<?php 
				$nilai_3 = App\Http\Libraries\Hitung_sikap::hitung_dkn_ki3($s->id_siswa,$kelas,$rombel,$nama_schema,$npsn,$m_smt_1->mapel_id); ?>
				@if(!empty($nilai_3)) {{number_format($nilai_3->nilai_ki3,0)}} @endif
			</td>
			<?php } ?>
		</tr>
		<tr>
			<td align="justify" style="padding-bottom:5px;padding-top:5px;padding-left:5px;padding-right:5px;font-size:12px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">Keterampilan</td>
			<?php
			foreach($nama_mapel as $k1=>$m_smt_1){
			?>
			<td align="center" style="border-right:1px solid black;border-bottom:1px solid black;font-size:12px;" align='center'>
				<?php 
				$nilai_3 = App\Http\Libraries\Hitung_sikap::hitung_dkn_ki3($s->id_siswa,$kelas,$rombel,$nama_schema,$npsn,$m_smt_1->mapel_id); ?>
				@if(!empty($nilai_3)) {{number_format($nilai_3->nilai_ki4,0)}} @endif
			</td>
			<?php } ?>
		</tr>
		<?php } ?>

		<!-- WALI KELAS -->
		<tr valign="top">
			<td colspan="7" align="center" style="font-size:13px;">
				<br /><br />Wali Kelas,
				<br /><br /><br /><br /><br /><br/>
				@if(!empty($walikelas))
				<u>
					{!! $walikelas->gelar_depan !!}
					{!! $walikelas->nama !!}
					{!! $walikelas->gelar_belakang !!}
				</u>
				<br/>
				@if($walikelas->nip!='')
				NIP. {!! $walikelas->nip !!}
				@else
				NUPTK. {!! $walikelas->nuptk !!}
				@endif
				@endif
			</td>						
			<td colspan="7" align="center" style="font-size:13px;">
				<br />Surabaya, <?php echo $kelas=='6' ? '15' : '20' ;?> Juni 2020
				<br />Kepala {{$sekolah->nama}}<br /><br /><br /><br /><br /><br/>
				@if(!empty($ks))
				<u>
					{!! $ks->gelar_depan !!}
					{!! $ks->nama !!}
					{!! $ks->gelar_belakang !!}
				</u>
				<br/>
				@if($ks->nip!='')
				NIP. {!! $ks->nip !!}
				@else
				NUPTK. {!! $ks->nuptk !!}
				@endif
				@endif
				<br /><br />
			</td>
		</tr>
	</table>
</body>
</html>