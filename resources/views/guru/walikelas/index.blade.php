@extends('master.index')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Walikelas</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Walikelas</li>
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
					<div class="card-header">
						<h3 class="card-title">Data Walikelas</h3>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<div class="row">
							<div class="col-lg-6">
								<div class="input-group mb-3">
									<select class="form-control" name="tahun_ajaran" onchange="get_data()">
										<option value="">..:: Pilih Tahun Ajaran ::..</option>
										@if(count($tahun_ajaran)!=0)
										@foreach($tahun_ajaran as $ta)
										<option value="{{$ta['nilai']}}" @if(Session::has('tahun_ajaran') && Session::get('tahun_ajaran')==$ta['nilai']) selected @endif>{{$ta['nama']}}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
						</div>
						<div class="row" id="tempat_sekolah">
							
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
<script>
	$(function () {
		$("#example1").DataTable({
			"responsive": true, "lengthChange": false, "autoWidth": false,
			"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
		}).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
		$('#example2').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": false,
			"ordering": true,
			"info": true,
			"autoWidth": false,
			"responsive": true,
		});
	});

	$(document).ready(function(){
		get_data();
	});

	function get_data(){
		var ta = $('select[name=tahun_ajaran]').val();

		var data = {
			ta:ta,
		};

		$.post("{{route('get_rombel_by_ta')}}",data,function(data){
			$('#tempat_sekolah').html(data.content);
		});
	}
</script>
@endsection