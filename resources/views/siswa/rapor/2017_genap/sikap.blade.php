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
		<th colspan="2" style="text-align: center;">Deskripsi</th>
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
<?php
$nomor = 1;
?>
<label class="label_rapor">B. Pengetahuan dan Keterampilan</label><br>
<label class="label_rapor">KKM Satuan Pendidikan : 75</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<th rowspan="2" style="width: 5%">No</th>
		<th rowspan="2" style="text-align: center;">Mata Pelajaran</th>
		<th colspan="3" style="text-align: center;">Pengetahuan</th>
		<th colspan="3" style="text-align: center;">Keterampilan</th>
	</tr>
	<tr style="background: #ddd;">
		<th style="text-align: center;">Angka</th>
		<th style="text-align: center;">Predikat</th>
		<th style="text-align: center;">Deskripsi</th>
		<th style="text-align: center;">Angka</th>
		<th style="text-align: center;">Predikat</th>
		<th style="text-align: center;">Deskripsi</th>
	</tr>
	<tr>
		<th style="width: 5%">A.</th>
		<th colspan="7">Kelompok A</th>
	</tr>
	@if($nilaia->count()!=0)
	@foreach($nilaia as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}<br><i style="font-size: 10px">{{$v->guru_mengajar}}</i></td>
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
		<th style="width: 5%">B.</th>
		<th colspan="7">Kelompok B</th>
	</tr>
	@if($nilaib->count()!=0)
	@foreach($nilaib as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}<br><i style="font-size: 10px">{{$v->guru_mengajar}}</i></td>
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
<label class="label_rapor">C. Ekstrakurikuler</label>
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
<label class="label_rapor">D. Saran-Saran</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr>
		<td style="height: 100px;padding: 10px;vertical-align: top;">
			{{Session::get('nama')}} {{$ekskul->catatan}}
		</td>
	</tr>
</table>
<label class="label_rapor">E. Tinggi dan Berat Badan</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;text-align: center;">
		<th rowspan="2">No</th>
		<th rowspan="2">Aspek yang dinilai</th>
		<th>Semester</th>
		<th>Semester</th>
	</tr>
	<tr style="background: #ddd;text-align: center;">
		<th>1</th>
		<th>2</th>
	</tr>
	<tr>
		<td>1</td>
		<td>Tinggi</td>
		<td>{{$ekskul->tinggi_semester1}} Cm</td>
		<td>{{$ekskul->tinggi_semester2}} Cm</td>
	</tr>
	<tr>
		<td>2</td>
		<td>Berat Badan</td>
		<td>{{$ekskul->beratbadan_semester1}} Kg</td>
		<td>{{$ekskul->beratbadan_semester2}} Kg</td>
	</tr>
</table>
<label class="label_rapor">F. Kondisi Kesehatan</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;text-align: center;">
		<th rowspan="2">No</th>
		<th rowspan="2">Aspek yang dinilai</th>
		<th>Semester</th>
		<th>Semester</th>
	</tr>
	<tr style="background: #ddd;text-align: center;">
		<th>1</th>
		<th>2</th>
	</tr>
	<tr>
		<td>1</td>
		<td>Pendengaran</td>
		<td>{{$ekskul->pendengaran_semester1}}</td>
		<td>{{$ekskul->pendengaran_semester2}}</td>
	</tr>
	<tr>
		<td>2</td>
		<td>Penglihatan</td>
		<td>{{$ekskul->penglihatan_semester1}}</td>
		<td>{{$ekskul->penglihatan_semester2}}</td>
	</tr>
	<tr>
		<td>3</td>
		<td>Gigi</td>
		<td>{{$ekskul->gigi_semester1}}</td>
		<td>{{$ekskul->gigi_semester2}}</td>
	</tr>
	<tr>
		<td>4</td>
		<td>Lainnya</td>
		<td>{{$ekskul->lainnya_semester1}}</td>
		<td>{{$ekskul->lainnya_semester2}}</td>
	</tr>
</table>
<label class="label_rapor">G. Catatan Prestasi</label>
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
<label class="label_rapor">H. Ketidakhadiran</label>
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
			<table style="width: 100%;border-collapse: collapse;" border="1">
				<tr style="padding: 5px;">
					<td>
						Keputusan:<br>
						{!! $kenaikan !!}
					</td>
				</tr>
			</table>
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
			Kepala {{$siswa->nama_sekolah}}<br>
			<br>
			<br>
			<br>
			<br>
			<u>{{$siswa->kepala}}</u><br>
			NIP. 
		</td>
		<td style="width: 30%;text-align: right;vertical-align: bottom;">
			{!! $qrcode !!}<br>
			{{Session::get('nama')}}
		</td>
	</tr>
</table>