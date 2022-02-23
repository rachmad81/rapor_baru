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
								<td>: {{Session::get('nama')}}</td>
							</tr>
							<tr>
								<td style="text-align: right;">Kelas</td>
								<td>: {{$mengajar->kelas}} - {{$mengajar->rombel}}</td>
							</tr>
							<tr>
								<td style="text-align: right;">Semester</td>
								<td>: {{$mengajar->kelas}}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="card card-danger card-outline card-outline-tabs">
					<div class="card-header p-0 border-bottom-0">
						<ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
							<li class="nav-item">
								<a class="nav-link" id="tab-1" onclick="get_pages(1)">Pengetahuan (KI-3)</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-2" onclick="get_pages(2)">Keterampilan (KI-4)</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-3" onclick="get_pages(3)">Hasil Akhir</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-4" onclick="get_pages(4)">Ujian Sekolah</a>
							</li>
						</ul>
					</div>
					<div class="card-body">
						<div class="tab-content" id="custom-tabs-four-tabContent">
							<div class="overlay">
								<i class="fas fa-3x fa-sync-alt fa-spin"></i>
								<div class="text-bold pt-2">Loading...</div>
							</div>
							<div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel"></div>
						</div>
					</div>
					<!-- /.card -->
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
		get_pages('x');
	});

	function get_pages(i){
		$('.nav-link').removeClass('active');
		$('#tab-'+i).addClass('active');

		if(i=='1'){
			setTimeout(function(){pages1('1')},500);
		}else if(i=='2'){
			setTimeout(function(){pages2('1')},500);
		}else if(i=='4'){
			setTimeout(function(){pages4('1')},500);
		}

		$('.overlay').show();
		$('#custom-tabs-four-home').hide();
		$.post("{{route('get_pages_siswa')}}",{i:i},function(data){
			$('#custom-tabs-four-home').show();
			$('#custom-tabs-four-home').html(data.content);
			$('.overlay').hide();
		});
	}

	function nama_kolom(bg,title,pesan) {
		$(document).Toasts('create', {
			class: bg,
			title: title,
			autohide: true,
			delay: 10000,
			body: pesan
		})
	}

	function pages1(id){
		$('.active-tab2').removeClass('active');
		$('#tab2-'+id).addClass('active');

		$('#pages1').hide();
		$('.loading-page').show();
		$.post("{{route('pages1_siswa')}}",{id:id},function(data){
			$('#pages1').show();
			$('#pages1').html(data.content);
			$('.loading-page').hide();
		});
	}

	function pages2(id){
		$('.active-tab3').removeClass('active');
		$('#tab3-'+id).addClass('active');

		$('#pages2').hide();
		$('.loading-page').show();
		$.post("{{route('pages2_siswa')}}",{id:id},function(data){
			$('#pages2').show();
			$('#pages2').html(data.content);
			$('.loading-page').hide();
		});
	}

	function pages4(id){
		$('.active-tab6').removeClass('active');
		$('#tab6-'+id).addClass('active');

		$('#pages4').hide();
		$('.loading-page').show();
		$.post("{{route('pages4_siswa')}}",{id:id},function(data){
			$('#pages4').show();
			$('#pages4').html(data.content);
			$('.loading-page').hide();
		});
	}
</script>
@endsection