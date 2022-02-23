<table style="width: 80%;">
	<tr>
		<td>Nama</td>
		<td>: {{Session::get('nama')}}</td>
		<td>Kelas</td>
		<td>: {{$sikap->kelas}} - {{$sikap->rombel}}</td>
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

<label>A. Sikap</label>
<table style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<td colspan="2" style="text-align: center;"><label>Deskripsi</label></td>
	</tr>
	<tr>
		<td style="width: 20%">1. Sikap Spiritual</td>
		<td>{{$sikap->deskripsi_ki1}}</td>
	</tr>
	<tr>
		<td>2. Sikap Sosial</td>
		<td>{{$sikap->deskripsi_ki2}}</td>
	</tr>
</table>
<label>B. Pengetahuan dan Keterampilan</label>
<table style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<td rowspan="2" style="width: 5%">No</td>
		<td rowspan="2" style="text-align: center;"><label>Mata Pelajaran</label></td>
		<td colspan="3" style="text-align: center;"><label>Pengetahuan</label></td>
		<td colspan="3" style="text-align: center;"><label>Keterampilan</label></td>
	</tr>
	<tr style="background: #ddd;">
		<td style="text-align: center;"><label>Angka</label></td>
		<td style="text-align: center;"><label>Predikat</label></td>
		<td style="text-align: center;"><label>Deskripsi</label></td>
		<td style="text-align: center;"><label>Angka</label></td>
		<td style="text-align: center;"><label>Predikat</label></td>
		<td style="text-align: center;"><label>Deskripsi</label></td>
	</tr>
	<tr>
		<td style="width: 5%">A.</td>
		<td colspan="7">Kelompok A</td>
	</tr>
	<?php
	$nomor = 1;
	?>
	@if($nilaia->count()!=0)
	@foreach($nilaia as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}</td>
		<td style="text-align: center">{{number_format($v->nilai_ki3,0,'','')}}</td>
		<td style="text-align: center">{{$v->predikat_ki3}}</td>
		<td>{{$v->deskripsi_ki3}}</td>
		<td style="text-align: center">{{number_format($v->nilai_ki4,0,'','')}}</td>
		<td style="text-align: center">{{$v->predikat_ki4}}</td>
		<td>{{$v->deskripsi_ki4}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
	<tr>
		<td style="width: 5%">B.</td>
		<td colspan="7">Kelompok B</td>
	</tr>
	@if($nilaib->count()!=0)
	@foreach($nilaib as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}</td>
		<td style="text-align: center">{{number_format($v->nilai_ki3,0,'','')}}</td>
		<td style="text-align: center">{{$v->predikat_ki3}}</td>
		<td>{{$v->deskripsi_ki3}}</td>
		<td style="text-align: center">{{number_format($v->nilai_ki4,0,'','')}}</td>
		<td style="text-align: center">{{$v->predikat_ki4}}</td>
		<td>{{$v->deskripsi_ki4}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
</table>
<label>C. Ekstrakurikuler</label>
<table style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<td style="text-align: center;"><label>Kegiatan Ektrakurikuler</label></td>
		<td style="text-align: center;"><label>Keterangan</label></td>
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
<label>D. Ketidakhadiran</label>
<table style="width: 50%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<td style="text-align: center;"><label>Ketidakhadiran</label></td>
	</tr>
	<tr>
		<td>
			<table style="width: 100%">
				<tr>
					<td>Sakit</td>
					<td>: 0</td>
					<td>Hari</td>
				</tr>
				<tr>
					<td>Izin</td>
					<td>: 0</td>
					<td>Hari</td>
				</tr>
				<tr>
					<td>Tanpa Keterangan</td>
					<td>: 0</td>
					<td>Hari</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table style="width: 100%">
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
			Kepala {{$siswa->nama_sekolah}}<br>
			<br>
			<br>
			<br>
			<br>
			<u>{{$siswa->kepala}}</u><br>
			NUPTK. 4340751653200013
		</td>
		<td style="width: 30%"></td>
	</tr>
</table>