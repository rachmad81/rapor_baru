<div style="width: 100%;text-align: center;margin-top: 3cm">
	<img src="https://rapor.dispendik.surabaya.go.id/2019_ganjil/img/tutwuri.png" style="width: 20%">
	<div class="sikap_title" style="margin-top: 20px;font-weight: bold;margin-top: 3cm">
		RAPOR<br> 
		PESERTA DIDIK<br>
		@if(Session::get('jenjang')=='SD') SEKOLAH DASAR (SD) @else SEKOLAH MENENGAH PERTAMA (SMP) @endif
	</div>
</div>
<div class="kontent_rapor" style="width: 100%;text-align: center;margin-top: 3cm">
	<div style="font-weight: bold">
		Nama Peserta Didik :
	</div>
	<div style="margin: auto;width: 50%;border: 1px solid black">
		{!!$siswa->nama_siswa!!}
	</div>
	<br>
	<div style="font-weight: bold">
		NISN :
	</div>
	<div style="margin: auto;width: 50%;border: 1px solid black">
		{{$siswa->nisn}}
	</div>
</div>
<div class="kontent_rapor" style="width: 100%;text-align: center;margin-top: 5cm">
	<div style="font-weight: bold">
		KEMENTRIAN PENDIDIKAN DAN KEBUDAYAAN<br>
		REPUBLIK INDONESIA
	</div>
	<div style="text-align: right">
		{!! $qrcode !!}<br>
		{!!$siswa->nama_siswa!!}
	</div>
</div>