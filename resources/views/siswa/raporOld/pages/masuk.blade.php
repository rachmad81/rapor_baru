<h1 style="text-align: center;">KETERANGAN PINDAH SEKOLAH</h1>
<label>NAMA PESERTA DIDIK</label>
<table style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<td><label>No</label></td>
		<td colspan="4"><label>KELUAR</label></td>
	</tr>
	@for($i=0;$i<3;$i++)
	<tr style="vertical-align: top;">
		<td>
			1.<br>
			2.<br>
			3.<br>
			4.<br>
		</td>
		<td>
			Nama Peserta Didik<br>
			No Induk/NISN<br>
			Nama Sekolah Asal<br>
			Masuk di Sekolah ini :<br>
			a. Tanggal<br>
			b. Di Kelas<br>
			c. Tahun Pelajaran<br>
		</td>
		<td>
			..................................<br>
			..................................<br>
			..................................<br>
			..................................<br>
			..................................<br>
			..................................<br>
			..................................<br>
		</td>
		<td>
			Surabaya, ..................................<br>
			Kepala Sekolah<br>
			<br>
			<br>
			<br>
			{{$siswa->kepala}}<br>
			NIP.<br>
		</td>
	</tr>
	@endfor
</table>