<div style="width: 100%;text-align: center;">
	<div class="sikap_title" style="margin-top: 20px;font-weight: bold;margin-top: 3cm">
		RAPOR<br> 
		PESERTA DIDIK<br>
		@if(Session::get('jenjang')=='SD') SEKOLAH DASAR (SD) @else SEKOLAH MENENGAH PERTAMA (SMP) @endif
	</div>
</div>
<div class="kontent_rapor" style="width: 100%;">
	<table style="width: 50%;margin: auto">
		<tr style="vertical-align: top;">
			<td style="width: 50%">Nama Sekolah</td>
			<td style="width: 50%">: {{$siswa->nama_sekolah}}</td>
		</tr>
		<tr style="vertical-align: top;">
			<td>NIS/NSS/NDS</td>
			<td>: {{$siswa->nss}}</td>
		</tr>
		<tr style="vertical-align: top;">
			<td>Alamat Sekolah</td>
			<td>: {{$siswa->alamat_sekolah}}</td>
		</tr>
		<tr style="vertical-align: top;">
			<td>Kelurahan</td>
			<td>: {{$siswa->kelurahan_dispenduk}}</td>
		</tr>
		<tr style="vertical-align: top;">
			<td>Kecamatan</td>
			<td>: {{$siswa->kecamatan_dispenduk}}</td>
		</tr>
		<tr style="vertical-align: top;">
			<td>Kota/Kabupaten</td>
			<td>: KOTA SURABAYA</td>
		</tr>
		<tr style="vertical-align: top;">
			<td>Provinsi</td>
			<td>: JAWA TIMUR</td>
		</tr>
		<tr style="vertical-align: top;">
			<td>Website</td>
			<td>: {{$siswa->website_sekolah}}</td>
		</tr>
		<tr style="vertical-align: top;">
			<td>Email</td>
			<td>: {{$siswa->email_sekolah}}</td>
		</tr>
	</table>
</div>
<div style="width: 100%;margin-top: 2cm">
	<div class="sikap_title" style="font-weight:bold;text-align: center">	
		PETUNJUK PENGGUNAAN
	</div>

	<div class="label_rapor">
		<ol>
			<li>
				Buku Laporan Hasil Capaian ini dipergunakan selama peserta didik mengikuti pelajaran di @if(Session::get('jenjang')=='SD') SEKOLAH DASAR (SD) @else SEKOLAH MENENGAH PERTAMA (SMP) @endif.
			</li>
			<li>
				Apabila peserta didik pindah sekolah, buku Laporan Hasil Capaian dibawa oleh peserta didik yang bersangkutan untuk dipergunakan sebagai bukti Capaian .
			</li>
			<li>
				Apabila buku Laporan Hasil Capaian peserta didik yang bersangkutan hilang, dapat diganti dengan buku Laporan Hasil Capaian Pengganti dan diisi dengan nilai-nilai yang dikutip dari Buku Induk Sekolah asal peserta didik dan disahkan oleh Kepala Sekolah yang bersangkutan.
			</li>
			<li>
				Buku Laporan Hasil Capaian peserta didik ini harus dilengkapi dengan pas foto ukuran 3 x 4 cm, dan pengisiannya dilakukan oleh wali kelas.
			</li>
		</ol>
	</div>
	<div style="text-align:right">
		{!! $qrcode !!}<br>
		{!!$siswa->nama_siswa!!}
	</div>
</div>