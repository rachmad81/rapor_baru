<table class="kontent_rapor" style="width: 100%">
	<tr style="vertical-align: top;">
		<td style="width: 30%">
			<br>
			Mengetahui<br>
			Orang Tua/ Wali<br>
			<br>
			<br>
			<br>
			<br>
		</td>
		<td style="width: 40%"></td>
		<td style="width: 30%">
			Surabaya, 
			<?php 
			if(!empty($rapor_semester)){
				if($siswa->kelas=='6'){
					echo '15 Juni '.date('Y',strtotime($siswa->tgl_setting_akhir));
				}else{
					echo App\Http\Libraries\Convert::tgl_indo(date('Y-m-d',strtotime($siswa->tgl_setting_akhir)));
				}
			}else{
				echo '15 Juni '.date('Y',strtotime($siswa->tgl_setting_akhir));
			}
		?>
		<br>
		Wali Kelas<br>
		<br>
		<br>
		<br>
		<br>
		<u>{{$walikelas->gelar_depan}} {{$walikelas->nama_wk}} {{$walikelas->gelar_belakang}}</u><br>
		{{$walikelas->nip}}
	</td>
</tr>
@if(isset($sisipan) && ($sisipan=='' || $sisipan=='null'))
<tr style="vertical-align: top;">
	<td style="width: 30%"></td>
	<td style="width: 40%;text-align: center;">
		Kepala {{$siswa->nama_sekolah}}<br>
		<br>
		<br>
		<br>
		<br>
		<u>{{$ks->gelar_depan}} {{$ks->nama_ks}} {{$ks->gelar_belakang}}</u><br>
		{{$ks->nip}}
	</td>
	<td style="width: 30%;text-align: right;vertical-align: bottom;">
		{!! $qrcode !!}<br>
		{{$siswa->nama_siswa}}
	</td>
</tr>
@endif
</table>