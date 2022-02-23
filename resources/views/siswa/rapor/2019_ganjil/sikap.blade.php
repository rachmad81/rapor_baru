<style type="text/css">
	
</style>
<h1 class="sikap_title" style="text-align: center;font-weight: bold;">RAPOR PESERTA DIDIK DAN PROFIL PESERTA DIDIK</h1>
<table class="label_rapor" style="width: 80%;">
	<tr>
		<td>Nama</td>
		<td>: {{Session::get('nama')}}</td>
		<td>Kelas</td>
		<td>: {{$kelas->kelas}} - {{$kelas->rombel}}</td>
	</tr>
	<tr>
		<td>NISN/NIS</td>
		<td>: {{$siswa->nisn}}/{{$siswa->nis}}</td>
		<td>Semester</td>
		<td>: {{$semester}}</td>
	</tr>
	<tr>
		<td>Nama Sekolah</td>
		<td>: {{$siswa->nama_sekolah}}</td>
		<td>Tahun Pelajaran</td>
		<td>: {{$tahun_ajaran}}</td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td>: {{$siswa->alamat_sekolah}}</td>
		<td></td>
		<td></td>
	</tr>
</table>

<label class="label_rapor">A. Sikap</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<th style="text-align: center;">Aspek</th>
		<th style="text-align: center;">Deskripsi</th>
	</tr>
	<tr>
		<td style="width: 20%">1. Sikap Spiritual</td>
		<td>{{(!empty($sikap)) ? $sikap->deskripsi_ki1 : ''}}</td>
	</tr>
	<tr>
		<td>2. Sikap Sosial</td>
		<td>{{(!empty($sikap)) ? $sikap->deskripsi_ki2 : ''}}</td>
	</tr>
</table>
<label class="label_rapor">B. Pengetahuan</label>
<?php
$nomor = 1;
?>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<th style="width: 5%">No</th>
		<th style="text-align: center;">Aspek</th>
		<th style="text-align: center;">Deskripsi</th>
	</tr>
	@if($nilaia->count()!=0)
	@foreach($nilaia as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}<br><i style="font-size: 10px">{{$v->guru_mengajar}}</i></td>
		<td>{{$v->deskripsi_ki3}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
	@if($nilaib->count()!=0)
	@foreach($nilaib as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}<br><i style="font-size: 10px">{{$v->guru_mengajar}}</i></td>
		<td>{{$v->deskripsi_ki3}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
</table>
<?php
$nomor = 1;
?>
<label class="label_rapor">C. Keterampilan</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<th style="width: 5%">No</th>
		<th style="text-align: center;">Aspek</th>
		<th style="text-align: center;">Deskripsi</th>
	</tr>
	@if($nilaia->count()!=0)
	@foreach($nilaia as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}<br><i style="font-size: 10px">{{$v->guru_mengajar}}</i></td>
		<td>{{$v->deskripsi_ki4}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
	@if($nilaib->count()!=0)
	@foreach($nilaib as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}<br><i style="font-size: 10px">{{$v->guru_mengajar}}</i></td>
		<td>{{$v->deskripsi_ki4}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
</table>
<label class="label_rapor">D. Ekstrakurikuler</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<th style="text-align: center;">Kegiatan Ektrakurikuler</th>
		<th style="text-align: center;">Keterangan</th>
	</tr>
	<tr>
		<td style="width: 50%">1 {{$ekskul->ekskul_1}}</td>
		<td style="text-align: center;">{{$ekskul->nilai_ekskul_1}}</td>
	</tr>
	<tr>
		<td>2 {{$ekskul->ekskul_2}}</td>
		<td style="text-align: center;">{{$ekskul->nilai_ekskul_2}}</td>
	</tr>
	<tr>
		<td>3 {{$ekskul->ekskul_3}}</td>
		<td style="text-align: center;">{{$ekskul->nilai_ekskul_3}}</td>
	</tr>
</table>
<label class="label_rapor">E. Saran-Saran</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr>
		<td style="height: 100px;padding: 10px;vertical-align: top;">
			{{Session::get('nama')}} {{$ekskul->catatan}}
		</td>
	</tr>
</table>
<label>&nbsp;</label>
<table class="kontent_rapor" style="width: 100%;">
	<tr>
		<td style="width: 30%">
			<table style="width: 100%;border-collapse: collapse;" border="1">
				<tr style="background: #ddd;">
					<th style="text-align: center;">Ketidakhadiran</th>
				</tr>
				<tr>
					<td>
						<table style="width: 100%">
							<tr>
								<td>Sakit</td>
								<td>: {{$ekskul->sakit}}</td>
								<td>Hari</td>
							</tr>
							<tr>
								<td>Izin</td>
								<td>: {{$ekskul->ijin}}</td>
								<td>Hari</td>
							</tr>
							<tr>
								<td>Tanpa Keterangan</td>
								<td>: {{$ekskul->tanpa_keterangan}}</td>
								<td>Hari</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td style="width: 30%">
			&nbsp;
		</td>
		<td style="vertical-align: top">
			&nbsp;
		</td>
	</tr>
</table>
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
			Surabaya, 19 Juni 2021<br>
			Wali Kelas<br>
			<br>
			<br>
			<br>
			<br>
			{{$walikelas->nama}}
		</td>
	</tr>
	<tr style="vertical-align: top;">
		<td style="width: 30%"></td>
		<td style="width: 40%;text-align: center;">
			&nbsp; 
		</td>
		<td style="width: 30%;text-align: right;vertical-align: bottom;">
			{!! $qrcode !!}<br>
			{{Session::get('nama')}}
		</td>
	</tr>
</table>
<div class="page_break">&nbsp;</div>


<label class="label_rapor">Catatan Prestasi</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;text-align: center;">
		<th>No</th>
		<th>Jenis Prestasi</th>
		<th>Keterangan</th>
	</tr>
	<tr>
		<td>1</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>2</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>3</td>
		<td></td>
		<td></td>
	</tr>
</table>

<table class="kontent_rapor" style="width: 100%">
	<tr style="vertical-align: top;">
		<td style="width: 30%"></td>
		<td style="width: 40%;text-align: center;">
			&nbsp; 
		</td>
		<td style="width: 30%;text-align: right;vertical-align: bottom;">
			{!! $qrcode !!}<br>
			{{Session::get('nama')}}
		</td>
	</tr>
</table>