<style type="text/css">
	.page_break {
		page-break-before: always;
	}
	@media print {
		.page_break {page-break-before: always;}
	}
	.kontent_rapor{
		font-size: 14px !important;
	}
	.sikap_title{
		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		font-weight: 500;
		line-height: 1.1;
		color: inherit;
		font-size:  24px !important;
	}
	.label_rapor{
		font-size: 14px !important;
		margin-bottom: 0px !important;
		margin-top: 0.5rem;
	}
</style>
@if(isset($sisipan) && ($sisipan=='' || $sisipan=='null'))
@if($siswa->kelas=='1' || $siswa->kelas=='4' || $siswa->kelas=='7')
@include('siswa.rapor.cover.cover')
<div class="page_break">&nbsp;</div>
@include('siswa.rapor.cover.cover1')
<div class="page_break">&nbsp;</div>
@include('siswa.rapor.cover.cover2')
<div class="page_break">&nbsp;</div>
@endif

@include('siswa.rapor.'.$pagesnya.'.sikap')
<div class="page_break">&nbsp;</div>
@include('siswa.rapor.umum.keluar')
<div class="page_break">&nbsp;</div>
@include('siswa.rapor.umum.masuk')
@else
@include('siswa.rapor.'.$pagesnya.'.sisipan')
@endif