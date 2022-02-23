@extends('master.index')
@section('extend_css')
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Pengisian Nilai</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Pengisian Nilai</li>
				</ol>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="card card-warning card-outline">
			<div class="card-header">
				Pengisian Nilai
			</div>
			<div class="card-body">
				<div class="row">
					@if($mapel->count()!=0)
					@foreach($mapel as $k=>$v)
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="small-box bg-yellow">
							<div class="inner">
								<div style="overflow: hidden;">
									<h3>{{$v->nama_mapel}}</h3>
									<p>{{$v->kategori}}</p>
								</div>
							</div>
							<div class="icon">
								<i class="fa fa-book"></i>
							</div>
							<a href="{{route('mengajar_nilai_siswa',['id_mengajar'=>$v->mapel_id])}}" class="small-box-footer">Klik di sini (Isikan Nilai) <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					@endforeach
					@endif
				</div>
			</div>
		</div>
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('extend_js')
<script type="text/javascript">
</script>
@endsection