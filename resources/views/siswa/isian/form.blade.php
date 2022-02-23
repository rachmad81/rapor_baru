@extends('master.index')

@section('extend_css')
<style type="text/css">
	.nav-tabs.flex-column .nav-item.show .nav-link, .nav-tabs.flex-column .nav-link.active{
		border-left: 3px solid #dc3545;
	}
</style>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Inputan Nilai</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Inputan Nilai</li>
				</ol>
			</div>
		</div>
	</div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card card-danger card-outline">
					<div class="card-header">Penilaian untuk:</div>
					<div class="card-body">
						<table style="width: 100%">
							<tr>
								<td style="text-align: right;">Mata Pelajaran</td>
								<td>: {{$mengajar->nama_mapel}}</td>
							</tr>
							<tr>
								<td style="text-align: right;">Nama Siswa</td>
								<td>: {!!Session::get('nama')!!}</td>
							</tr>
							<tr>
								<td style="text-align: right;">Kelas</td>
								<td>: {{$mengajar->kelas}}.{{$mengajar->rombel}}</td>
							</tr>
							<tr>
								<td style="text-align: right;">Semester</td>
								<td>: {{$semester}}</td>
							</tr>
						</table>
						<h3>PENGETAHUAN</h3>
						<div class="card card-danger card-outline">
							<div class="card-header">Nilai Penilaian Harian</div>
							<div class="card-body" style="overflow: auto">
								<table style="width: 100%;border-collapse: collapse;" border="1">
									<tr>
										<th rowspan="2">Nama</th>
										<th colspan="{{$kd3->count()}}">NPH</th>
										<th rowspan="2">#</th>
									</tr>
									<tr>
										@if($kd3->count()!=0)
										@foreach($kd3 as $k=>$v)
										<th style="cursor: pointer;vertical-align: top" onclick="nama_kolom('bg-lime','KD {{$k+1}}','{{$v->kd_isi}}','NPH_{{$k+1}}')">
											<i class="fa fa-comment"></i> KD {{($k+1)}}
											<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123" id="NPH_{{$k+1}}">
												{{$v->kd_isi}}
											</div>
										</th>
										@endforeach
										@endif
									</tr>
									<tr>
										<td style="white-space: nowrap;">{!!Session::get('nama')!!}</td>
										@if($kd3->count()!=0)
										@foreach($kd3 as $k=>$v)
										<?php $kolom = 'nph_'.($k+1);?>
										<td><input type="number" name="nph[]" value="{{$mengajar->$kolom}}" @if($mengajar->islock_uts==true) readonly @endif></td>
										@endforeach
										@endif
										<td>
											<a href="javascript:void(0)" class="btn btn-sm btn-primary" @if($mengajar->islock_uts==true) onclick="alert('Pengisian nilai sudah dikunci')" @else onclick="simpansd('nph')" @endif>Simpan</a>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="card card-danger card-outline">
							<div class="card-header">Nilai Penilaian Tengah Semester</div>
							<div class="card-body" style="overflow: auto">
								<table style="width: 100%;border-collapse: collapse;" border="1">
									<tr>
										<th rowspan="2">Nama</th>
										<th colspan="{{$kd3->count()}}">NPTS</th>
										<th rowspan="2">#</th>
									</tr>
									<tr>
										@if($kd3->count()!=0)
										@foreach($kd3 as $k=>$v)
										<th style="cursor: pointer;vertical-align: top" onclick="nama_kolom('bg-lime','KD {{$k+1}}','{{$v->kd_isi}}','NPTS_{{$k+1}}')">
											<i class="fa fa-comment"></i> KD {{($k+1)}}
											<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123" id="NPTS_{{$k+1}}">
												{{$v->kd_isi}}
											</div>
										</th>
										@endforeach
										@endif
									</tr>
									<tr>
										<td style="white-space: nowrap;">{!!Session::get('nama')!!}</td>
										@if($kd3->count()!=0)
										@foreach($kd3 as $k=>$v)
										<?php $kolom = 'npts_'.($k+1);?>
										<td><input type="number" name="npts[]" value="{{$mengajar->$kolom}}" @if($mengajar->islock_uts==true) readonly @endif></td>
										@endforeach
										@endif
										<td>
											<a href="javascript:void(0)" class="btn btn-sm btn-primary" @if($mengajar->islock_uts==true) onclick="alert('Pengisian nilai sudah dikunci')" @else onclick="simpansd('npts')" @endif>Simpan</a>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="card card-danger card-outline">
							<div class="card-header">Nilai Penilaian Akhir Semester</div>
							<div class="card-body" style="overflow: auto">
								<table style="width: 100%;border-collapse: collapse;" border="1">
									<tr>
										<th rowspan="2">Nama</th>
										<th colspan="{{$kd3->count()}}">NPAS</th>
										<th rowspan="2">#</th>
									</tr>
									<tr>
										@if($kd3->count()!=0)
										@foreach($kd3 as $k=>$v)
										<th style="cursor: pointer;vertical-align: top" onclick="nama_kolom('bg-lime','KD {{$k+1}}','{{$v->kd_isi}}','NPAS_{{$k+1}}')">
											<i class="fa fa-comment"></i> KD {{($k+1)}}
											<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123" id="NPAS_{{$k+1}}">
												{{$v->kd_isi}}
											</div>
										</th>
										@endforeach
										@endif
									</tr>
									<tr>
										<td style="white-space: nowrap;">{!!Session::get('nama')!!}</td>
										@if($kd3->count()!=0)
										@foreach($kd3 as $k=>$v)
										<?php $kolom = 'npas_'.($k+1);?>
										<td><input type="number" name="npas[]" value="{{$mengajar->$kolom}}" @if($mengajar->islock_uts==true) readonly @endif></td>
										@endforeach
										@endif
										<td>
											<a href="javascript:void(0)" class="btn btn-sm btn-primary" @if($mengajar->islock_uts==true) onclick="alert('Pengisian nilai sudah dikunci')" @else onclick="simpansd('npas')" @endif>Simpan</a>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<h3>KETERAMPILAN</h3>

						<div class="card card-info card-outline">
							<div class="card-header">Praktek</div>
							<div class="card-body" style="overflow: auto">
								<table style="width: 100%;border-collapse: collapse;" border="1">
									<tr>
										<th rowspan="2">Nama</th>
										<th colspan="{{$kd3->count()}}">Praktek</th>
										<th rowspan="2">#</th>
									</tr>
									<tr>
										@if($kd3->count()!=0)
										@foreach($kd3 as $k=>$v)
										<th style="cursor: pointer;vertical-align: top" onclick="nama_kolom('bg-dark','KD {{$k+1}}','{{$v->kd_isi}}','Praktek_{{$k+1}}')">
											<i class="fa fa-comment"></i> KD {{($k+1)}}
											<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123" id="Praktek_{{$k+1}}">
												{{$v->kd_isi}}
											</div>
										</th>
										@endforeach
										@endif
									</tr>
									<tr>
										<td style="white-space: nowrap;">{!!Session::get('nama')!!}</td>
										@if($kd3->count()!=0)
										@foreach($kd3 as $k=>$v)
										<?php $kolom = 'praktek_'.($k+1);?>
										<td><input type="number" name="praktek[]" value="{{$mengajar->$kolom}}" @if($mengajar->islock_uts==true) readonly @endif></td>
										@endforeach
										@endif
										<td>
											<a href="javascript:void(0)" class="btn btn-sm btn-primary" @if($mengajar->islock_uts==true) onclick="alert('Pengisian nilai sudah dikunci')" @else onclick="simpansd('praktek')" @endif>Simpan</a>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('extend_js')
<script type="text/javascript">
	$(document).ready(function(){
	});

	function nama_kolom(bg,title,pesan,kategori) {
		// $(document).Toasts('create', {
		// 	class: bg,
		// 	title: title,
		// 	autohide: true,
		// 	delay: 10000,
		// 	body: pesan
		// })

		if($('#'+kategori).is(':visible')){
			$('.tooltip123').hide();
		}else{
			$('.tooltip123').hide();
			$('#'+kategori).show();
		}
	}

	function simpansd(name){
		var nilai = $("input[name='"+name+"[]']").map(function(){return $(this).val();}).get();
		var data = {
			namenya:name,
			nilai:nilai,
		};

		$.post("{{route('simpan_nilai_siswa_sd')}}",data,function(data){
			swal(data.title,data.message,data.type);
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});
	}
</script>
@endsection