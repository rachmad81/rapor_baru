<h1 class="sikap_title" style="text-align: center;">KETERANGAN PINDAH SEKOLAH</h1>
<label class="label_rapor">NAMA PESERTA DIDIK : {{$siswa->nama_siswa}}</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<td><label>No</label></td>
		<td colspan="4"><label>MASUK</label></td>
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
			__________________________________<br>
			NIP.<br>
		</td>
	</tr>
	@endfor
</table>

<table class="kontent_rapor" style="width: 100%">
	<tr style="vertical-align: top;">
		<td style="width: 30%;text-align: right;vertical-align: bottom;">
			{!! $qrcode !!}<br>
			{{$siswa->nama_siswa}}
		</td>
	</tr>
</table>