@extends('master.index')
@section('extend_css')
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Rapor Akhir</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Rapor Akhir</li>
				</ol>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<label>Tahun ajaran</label>
		<div class="row">
			<div class="col-lg-8 col-md-8">
				<select class="form-control" name="tahun_ajaran" onchange="get_data()">
					<option value="">..:: Tahun Ajaran ::..</option>
					@foreach($tahun_ajaran as $ta)
					<option value="{{$ta->id_anggota_rombel}}">{{$ta->nama_tahun_ajaran}} ({{$ta->nama_semester}})</option>
					@endforeach
				</select>
			</div>
		</div>
		<a href="javascript:void(0)" class="btn btn-success" onclick="printDiv('print_area')"><i class="fa fa-print"></i> Cetak</a>
		<div class="row">
			<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 ">
				<div class="overlay" style="display: none">
					<i class="fas fa-3x fa-sync-alt fa-spin"></i>
					<div class="text-bold pt-2">Loading...</div>
				</div>
				<div id="print_area">
					
				</div>
			</div>
		</div>
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('extend_js')
<script type="text/javascript">
	function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;

		document.body.innerHTML = printContents;

		window.print();

		document.body.innerHTML = originalContents;
	}

	function get_data(){
		var ta = $('select[name=tahun_ajaran]').val();
		$('.overlay').show();
		$('#print_area').html('');
		$.post("{{route('data_rapor_akhir')}}",{ta:ta},function(data){
			$('#print_area').html(data.content);
			$('.overlay').hide();
		}).fail(function(){
			$('#print_area').html('');
			swal('Data belum ada');
			$('.overlay').hide();
		});
	}
</script>
@endsection