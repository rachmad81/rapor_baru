<h1 class="sikap_title" style="text-align: center;">KETERANGAN PINDAH SEKOLAH</h1>
<label class="label_rapor">NAMA PESERTA DIDIK : {{$siswa->nama_siswa}}</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<td colspan="4"><label>KELUAR</label></td>
	</tr>
	<tr>
		<td><label>Tanggal</label></td>
		<td><label>Kelas yang Ditinggalkan</label></td>
		<td><label>Sebab-sebab Keluar atau Atas Permintaan (Tertulis)</label></td>
		<td><label>
			Tanda Tangan dan Nama
			Kepala Sekolah, Stempel Sekolah,
			Tanda Tangan dan
			Nama Orang Tua/Wali
		</label></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td>
			Surabaya, ..................................<br>
			Kepala Sekolah<br>
			<br>
			<br>
			<br>
			<u>{{$ks->gelar_depan}} {{$ks->nama_ks}} {{$ks->gelar_belakang}}</u><br>
			{{$ks->nip}}<br>
			Orang Tua/Wali<br>
			<br>
			<br>
			<br>
			________________________<br>
		</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td>
			Surabaya, ..................................<br>
			Kepala Sekolah<br>
			<br>
			<br>
			<br>
			<u>{{$ks->gelar_depan}} {{$ks->nama_ks}} {{$ks->gelar_belakang}}</u><br>
			{{$ks->nip}}<br>
			Orang Tua/Wali<br>
			<br>
			<br>
			<br>
			________________________<br>
		</td>
	</tr>
</table>

<table class="kontent_rapor" style="width: 100%">
	<tr style="vertical-align: top;">
		<td style="width: 30%;text-align: right;vertical-align: bottom;">
			{!! $qrcode !!}<br>
			{{$siswa->nama_siswa}}
		</td>
	</tr>
</table>