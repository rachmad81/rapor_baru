<table style="width: 80%;">
	<tr>
		<td>Nama</td>
		<td>: {{Session::get('nama')}}</td>
		<td>Kelas</td>
		<td>: {{$sikap->kelas}} - {{$sikap->rombel}}</td>
	</tr>
	<tr>
		<td>NISN/NIS</td>
		<td>: {{$siswa->nis}}</td>
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

<label>E. Deskripsi</label>
<table style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<td style="text-align: center;"><label>Mata Pelajaran</label></td>
		<td style="text-align: center;"><label>Kompetensi</label></td>
		<td style="text-align: center;"><label>Catatan</label></td>
	</tr>
	<tr>
		<td colspan="3">Kelompok A</td>
	</tr>
	<tr>
		<td colspan="3">Kelompok B</td>
	</tr>
</table>
<table style="width: 100%">
	<tr>
		<td style="width:50%"></td>
		<td style="width:50%">
			<div style="border: 1px solid black">
				<label>Keputusan</label><br>
				Berdasarkan kriteria kelulusan, peserta didik ditetapkan Tidak Lulus dari satuan pendidikan
			</div>
		</td>
	</tr>
</table>
<table style="width: 100%">
	<tr style="vertical-align: top;text-align: center;">
		<td style="width: 30%">
			Mengetahui<br>
			Orang Tua/ Wali<br>
			<br>
			<br>
			<br>
			<br>
		</td>
		<td style="width: 30%">
			<br>
			Wali Kelas<br>
			<br>
			<br>
			<br>
			<br>
			{{$walikelas->nama}}
		</td>
		<td style="width: 40%">
			Surabaya, 19 Juni 2021<br>
			Kepala {{$siswa->nama_sekolah}}<br>
			<br>
			<br>
			<br>
			<br>
			<u>{{$siswa->kepala}}</u><br>
			NUPTK. 4340751653200013
		</td>
	</tr>
</table>