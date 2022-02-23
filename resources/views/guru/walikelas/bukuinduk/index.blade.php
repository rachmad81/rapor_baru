<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Buku Induk {{Session::get('nama_sekolah')}}</title>
	<link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
	<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
	<style type="text/css">
		*{
			font-family: "Times New Roman", Times, serif !important;
		}
	</style>
	<form id="bukuinduk_form">
		@if($cetak!='')
		@include('guru.walikelas.bukuinduk.cetak.cover')
		@endif
		<table style="width: 100%">
			<tr>
				<td colspan="4" style="font-weight: bold;font-size: 14pt;text-align: center;">LEMBAR BUKU INDUK PESERTA DIDIK {{Session::get('jenjang')}}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" style="font-size: 12pt;text-align: center;">NOMOR INDUK PESERTA DIDIK : {{$siswa->nis}}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>

			@if($cetak=='')
			@include('guru.walikelas.bukuinduk.form_a')
			@include('guru.walikelas.bukuinduk.form_b')
			@include('guru.walikelas.bukuinduk.form_c')
			@include('guru.walikelas.bukuinduk.form_d')
			@include('guru.walikelas.bukuinduk.form_e')
			@include('guru.walikelas.bukuinduk.form_f')
			@include('guru.walikelas.bukuinduk.form_g')
			@include('guru.walikelas.bukuinduk.form_h')
			@include('guru.walikelas.bukuinduk.form_i')
			@include('guru.walikelas.bukuinduk.form_j')
			@else
			@include('guru.walikelas.bukuinduk.cetak.form_a')
			@include('guru.walikelas.bukuinduk.cetak.form_b')
			@include('guru.walikelas.bukuinduk.cetak.form_c')
			@include('guru.walikelas.bukuinduk.cetak.form_d')
			@include('guru.walikelas.bukuinduk.cetak.form_e')
			@include('guru.walikelas.bukuinduk.cetak.form_f')
			@include('guru.walikelas.bukuinduk.cetak.form_g')
			@include('guru.walikelas.bukuinduk.cetak.form_h')
			@include('guru.walikelas.bukuinduk.cetak.form_i')
			@include('guru.walikelas.bukuinduk.cetak.form_j')
			@endif
		</table>
		<input type="hidden" name="id_siswa" value="{{$siswa->id_siswa}}">
		<input type="hidden" name="npsn" value="{{$siswa->npsn}}">
		@if($cetak=='')
		<a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="cetak()">Cetak</a>
		@endif
	</form>
	<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
	<script type="text/javascript">
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	</script>
	<!-- Bootstrap 4 -->
	<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
	<script src="{{asset('dist/js/adminlte.js')}}"></script>
	<script type="text/javascript">
		function cetak(){
			var data = $('form#bukuinduk_form').serialize(0);

			$.post("{{route('buku_induk-simpan',)}}",data,function(data){
				if(data.code=='200'){
					window.open("{{Request::url()}}?cetak=cetak");
				}else{
					alert(data.message);
				}
			});
		}
	</script>
</body>
</html>