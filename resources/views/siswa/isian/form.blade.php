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
								<td>: {{Session::get('kelas_rombel')}}</td>
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
										<th style="cursor: pointer;vertical-align: top" onclick="nama_kolom('bg-lime','KD {{$k+1}}','{{$v->isi}}','NPH_{{$k+1}}')">
											<i class="fa fa-comment"></i> KD {{($k+1)}}
											<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123" id="NPH_{{$k+1}}">
												{{$v->isi}}
											</div>
										</th>
										@endforeach
										@endif
									</tr>
									<tr>
										<td style="white-space: nowrap;">{!!Session::get('nama')!!}</td>
										@if($kd3->count()!=0)
										@foreach($kd3 as $k=>$v)
										<td>
											<input type="hidden" class="form-control" name="id_uh[]" value="{{$v->id_kd}}" @if($nilai_mapel->is_kunci==true) readonly @endif>
											@php
											$nilai = DB::connection($conn)->table($schema.'.nilai_mapel as nm')
											->join($schema.'.detail_nilai_mapel as dm','dm.nilai_mapel_id','nm.id_nilai_mapel')
											->whereRaw("mapel_id='".Session::get('id_mapel')."' AND anggota_rombel_id='".Session::get('id_anggota_rombel')."' AND kd_id='$v->id_kd'")->first();						
											@endphp
											@if(!empty($nilai))
											@if($nilai->kd_id==$v->id_kd)
											<input type="number" class="form-control" name="uh[]" value="{{$nilai->nph}}" @if($nilai->is_kunci==true) readonly @endif>
											@else
											<input type="number" class="form-control" name="uh[]" value="" @if($nilai->is_kunci==true) readonly @endif>
											@endif
											@else
											<input type="number" class="form-control" name="uh[]" value="" @if($nilai->is_kunci==true) readonly @endif>
											@endif
										</td>
										@endforeach
										@endif
										<td>
											<a href="javascript:void(0)" class="btn btn-sm btn-primary" @if($nilai_mapel->is_kunci==true) onclick="alert('Pengisian nilai sudah dikunci')" @else onclick="simpankd('uh','nph')" @endif>Simpan</a>
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
										<th style="cursor: pointer;vertical-align: top" onclick="nama_kolom('bg-lime','KD {{$k+1}}','{{$v->isi}}','NPTS_{{$k+1}}')">
											<i class="fa fa-comment"></i> KD {{($k+1)}}
											<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123" id="NPTS_{{$k+1}}">
												{{$v->isi}}
											</div>
										</th>
										@endforeach
										@endif
									</tr>
									<tr>
										<td style="white-space: nowrap;">{!!Session::get('nama')!!}</td>
										@if($kd3->count()!=0)
										@foreach($kd3 as $k=>$v)
										<td>
											<input type="hidden" class="form-control" name="id_npts[]" value="{{$v->id_kd}}" @if($nilai_mapel->is_kunci==true) readonly @endif>
											@php
											$nilai = DB::connection($conn)->table($schema.'.nilai_mapel as nm')
											->join($schema.'.detail_nilai_mapel as dm','dm.nilai_mapel_id','nm.id_nilai_mapel')
											->whereRaw("mapel_id='".Session::get('id_mapel')."' AND anggota_rombel_id='".Session::get('id_anggota_rombel')."' AND kd_id='$v->id_kd'")->first();						
											@endphp
											@if(!empty($nilai))
											@if($nilai->kd_id==$v->id_kd)
											<input type="number" class="form-control" name="npts[]" value="{{$nilai->npts}}" @if($nilai->is_kunci==true) readonly @endif>
											@else
											<input type="number" class="form-control" name="npts[]" value="" @if($nilai->is_kunci==true) readonly @endif>
											@endif
											@else
											<input type="number" class="form-control" name="npts[]" value="" @if($nilai->is_kunci==true) readonly @endif>
											@endif
										</td>
										@endforeach
										@endif
										<td>
											<a href="javascript:void(0)" class="btn btn-sm btn-primary" @if($nilai_mapel->is_kunci==true) onclick="alert('Pengisian nilai sudah dikunci')" @else onclick="simpankd('npts','npts')" @endif>Simpan</a>
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
										<th style="cursor: pointer;vertical-align: top" onclick="nama_kolom('bg-lime','KD {{$k+1}}','{{$v->isi}}','NPAS_{{$k+1}}')">
											<i class="fa fa-comment"></i> KD {{($k+1)}}
											<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123" id="NPAS_{{$k+1}}">
												{{$v->isi}}
											</div>
										</th>
										@endforeach
										@endif
									</tr>
									<tr>
										<td style="white-space: nowrap;">{!!Session::get('nama')!!}</td>
										@if($kd3->count()!=0)
										@foreach($kd3 as $k=>$v)
										<td>
											<input type="hidden" class="form-control" name="id_npas[]" value="{{$v->id_kd}}" @if($nilai_mapel->is_kunci==true) readonly @endif>
											@php
											$nilai = DB::connection($conn)->table($schema.'.nilai_mapel as nm')
											->join($schema.'.detail_nilai_mapel as dm','dm.nilai_mapel_id','nm.id_nilai_mapel')
											->whereRaw("mapel_id='".Session::get('id_mapel')."' AND anggota_rombel_id='".Session::get('id_anggota_rombel')."' AND kd_id='$v->id_kd'")->first();						
											@endphp
											@if(!empty($nilai))
											@if($nilai->kd_id==$v->id_kd)
											<input type="number" class="form-control" name="npas[]" value="{{$nilai->npas}}" @if($nilai->is_kunci==true) readonly @endif>
											@else
											<input type="number" class="form-control" name="npas[]" value="" @if($nilai->is_kunci==true) readonly @endif>
											@endif
											@else
											<input type="number" class="form-control" name="npas[]" value="" @if($nilai->is_kunci==true) readonly @endif>
											@endif
										</td>
										@endforeach
										@endif
										<td>
											<a href="javascript:void(0)" class="btn btn-sm btn-primary" @if($nilai_mapel->is_kunci==true) onclick="alert('Pengisian nilai sudah dikunci')" @else onclick="simpankd('npas','npas')" @endif>Simpan</a>
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
										<th colspan="{{$kd4->count()}}">Praktek</th>
										<th rowspan="2">#</th>
									</tr>
									<tr>
										@if($kd4->count()!=0)
										@foreach($kd4 as $k=>$v)
										<th style="cursor: pointer;vertical-align: top" onclick="nama_kolom('bg-dark','KD {{$k+1}}','{{$v->isi}}','Praktek_{{$k+1}}')">
											<i class="fa fa-comment"></i> KD {{($k+1)}}
											<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123" id="Praktek_{{$k+1}}">
												{{$v->isi}}
											</div>
										</th>
										@endforeach
										@endif
									</tr>
									<tr>
										<td style="white-space: nowrap;">{!!Session::get('nama')!!}</td>
										@if($kd4->count()!=0)
										@foreach($kd4 as $k=>$v)
										<td>
											<input type="hidden" class="form-control" name="id_keterampilan[]" value="{{$v->id_kd}}" @if($nilai_mapel->is_kunci==true) readonly @endif>
											@php
											$nilai = DB::connection($conn)->table($schema.'.nilai_mapel as nm')
											->join($schema.'.detail_nilai_mapel as dm','dm.nilai_mapel_id','nm.id_nilai_mapel')
											->whereRaw("mapel_id='".Session::get('id_mapel')."' AND anggota_rombel_id='".Session::get('id_anggota_rombel')."' AND kd_id='$v->id_kd'")->first();						
											@endphp
											@if(!empty($nilai))
											@if($nilai->kd_id==$v->id_kd)
											<input type="number" class="form-control" name="keterampilan[]" value="{{$nilai->keterampilan}}" @if($nilai->is_kunci==true) readonly @endif>
											@else
											<input type="number" class="form-control" name="keterampilan[]" value="" @if($nilai->is_kunci==true) readonly @endif>
											@endif
											@else
											<input type="number" class="form-control" name="keterampilan[]" value="" @if($nilai->is_kunci==true) readonly @endif>
											@endif
										</td>
										@endforeach
										@endif
										<td>
											<a href="javascript:void(0)" class="btn btn-sm btn-primary" @if($nilai_mapel->is_kunci==true) onclick="alert('Pengisian nilai sudah dikunci')" @else onclick="simpankd('keterampilan','keterampilan')" @endif>Simpan</a>
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

	function simpankd(name,kolom){
		var nilai = $("input[name='"+name+"[]']").map(function(){return $(this).val();}).get();;
		var id_kd = $("input[name='id_"+name+"[]']").map(function(){return $(this).val();}).get();;
		var data = {
			namenya:name,
			nilai:nilai,
			id_kd:id_kd,
			kolom:kolom,
		};

		$.post("{{route('simpan_nilai_siswa_kd')}}",data,function(data){
			swal(data.title,data.message,data.type);
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});
	}
</script>
@endsection