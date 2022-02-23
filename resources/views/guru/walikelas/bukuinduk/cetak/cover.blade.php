<div style="width: 100%;text-align: center;">
	<div style="width: 100%;text-align: center;">
		<img src="{{asset('bg/logo_sby.png')}}" style="width: 5cm;margin-top: 3cm">
	</div>
	<p style="font-size: 20pt;font-weight: bold;">
		BUKU INDUK<br>
		HASIL CAPAIAN KOMPETENSI PESERTA DIDIK<br>
		@if(Session::get('jenjang')=='SD')
		SEKOLAH DASAR<br>
		(SD)
		@else
		SEKOLAH MENENGAH PERTAMA<br>
		(SMP)
		@endif
	</p>
</div>
<div style="width: 100%;margin-top: 3cm">
	<table style="width: 40%;margin: auto;font-size: 12pt;">
		<tr>
			<td style="width: 50%;vertical-align: top;">Nama Sekolah</td>
			<td style="vertical-align: top;">: {!! $sekolah->nama !!}</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">NIS/NSS/NDS</td>
			<td style="vertical-align: top;">: {!! $sekolah->nss !!}</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Alamat Sekolah</td>
			<td style="vertical-align: top;">
				: {!! $sekolah->alamat !!}<br>
				&nbsp;
				Kode Pos : {!! $sekolah->kodepos !!}<br>
				&nbsp;
				Telp. {!! $sekolah->telp !!}
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Kelurahan</td>
			<td style="vertical-align: top;">: {!! $sekolah->kelurahan_name !!}</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Kecamatan</td>
			<td style="vertical-align: top;">: {!! $sekolah->kecamatan_name !!}</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Kota Kabupaten</td>
			<td style="vertical-align: top;">: Kota Surabaya</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Provinsi</td>
			<td style="vertical-align: top;">: Jawa Timur</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Website</td>
			<td style="vertical-align: top;">: {!! $sekolah->website !!}</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">Email</td>
			<td style="vertical-align: top;">: {!! $sekolah->email !!}</td>
		</tr>
	</table>
</div>
<div style="width: 100%;text-align: center;margin-top: 3cm;">
	<p style="font-size: 20pt;font-weight: bold;">
		DINAS PENDIDIKAN<br>
		KOTA SURABAYA
	</p>
</div>
<div style="width: 100%;text-align: right;padding-right: 3cm;">
	{!! $qrcode !!}
</div>
<div style="page-break-before: always;"></div>



<div style="width:100%;font-size: 14pt;font-weight: bold;text-align: center;">
	KATA PENGANTAR
</div>
<div style="width:100%;font-size: 12pt;">
	BUKU INDUK PESERTA DIDIK ini berisi data dan informasi serta perkembangan prestasi Peserta Didik
	yang digunakan untuk kepentingan tertib adminstrasi pengelolaan sekolah.<br>
	Buku Induk Peserta Didik ini telah disesuaikan dengan kurikulum Tingkat Satuan Pendidikan, ketentuan �
	ketentuan yang berlaku, masukan dari Pusat Pengembangan Kurikulum dan Direktorat Pendidikan
	Menengah Atas.<br>
	Perubahan dan penyempurnaan Buku Induk ini tetap terbuka disesuaikan dengan perkembangan pendidikan.
	Semoga buku ini bermanfaat untuk peningkatan efisien pengelolaan sekolah.
</div>
<div style="width:100%;font-size: 12pt;padding-left: 60%;margin-top: 3cm">
	Surabaya,<br>
	<b>DINAS PENDIDIKAN KOTA SURABAYA</b>
</div>
<div style="width: 100%;text-align: right;padding-right: 3cm;">
	{!! $qrcode !!}
</div>
<div style="page-break-before: always;"></div>



<div style="width:100%;font-size: 14pt;font-weight: bold;text-align: center;">
	DAFTAR ISI
</div>
<div style="width:100%;font-size: 12pt;">
	KATA PENGANTAR
</div>
<div style="width:100%;font-size: 12pt;">
	DAFTAR ISI
</div>
<div style="width:100%;font-size: 12pt;padding-left: 1cm;">
	I. LEMBAR BUKU INDUK PESERTA DIDIK<br>
	II. LAPORAN HASIL BELAJAR PESERTA DIDIK
</div>

<div style="width: 100%;text-align: right;padding-right: 3cm;">
	{!! $qrcode !!}
</div>
<div style="page-break-before: always;"></div>