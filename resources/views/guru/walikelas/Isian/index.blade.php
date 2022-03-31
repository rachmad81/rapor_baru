@extends('master.index')

@section('extend_css')
<style type="text/css">
	.nav-tabs.flex-column .nav-item.show .nav-link, .nav-tabs.flex-column .nav-link.active{
		border-left: 3px solid #dc3545;
	}

	.headcol {
		position: sticky;
		width: 50px;
		background: #eee;
		left: 0;
		top: auto;
		border-top-width: 1px;
		/*only relevant for first row*/
		margin-top: -1px;
		/*compensate for top border*/
	}

	.headcol:before {
		/*content: 'Row ';*/
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
								<td style="text-align: right;">Pengasuh</td>
								<td>: {{Session::get('nama')}}</td>
							</tr>
							<tr>
								<td style="text-align: right;">Kelas</td>
								<td>: {{$rombongan_belajar->kelas}} - {{$rombongan_belajar->rombel}}</td>
							</tr>
							<tr>
								<td style="text-align: right;">Semester</td>
								<td>: {{$rombongan_belajar->nama_tahun_ajaran}} {{($rombongan_belajar->semester=='1') ? 'Semester Ganjil' : 'Semester Genap';}}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="card card-danger card-outline card-outline-tabs">
					<div class="card-header p-0 border-bottom-0">
						<ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
							{{-- <li class="nav-item">
								<a class="nav-link" id="tab-1" onclick="get_pages(1)">Setting KKM</a>
							</li> --}}
							<li class="nav-item">
								<a class="nav-link" id="tab-2" onclick="get_pages(2)">Pengetahuan (KI-3)</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-3" onclick="get_pages(3)">Keterampilan (KI-4)</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-4" onclick="get_pages(4)">UPDOWN NILAI (beta)</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-5" onclick="generate_nilai_akhir()">Hasil Akhir</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-6" onclick="get_pages(6)">Ujian Sekolah</a>
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

		if(i=='2'){
			setTimeout(function(){pages2('1')},500);
		}else if(i=='3'){
			setTimeout(function(){pages3('1')},500);
		}else if(i=='6'){
			setTimeout(function(){pages6('1')},500);
		}

		$('.overlay').show();
		$('#custom-tabs-four-home').hide();
		$.post("{{route('guru-isian-pages')}}",{i:i},function(data){
			$('#custom-tabs-four-home').show();
			$('#custom-tabs-four-home').html(data.content);
			$('.overlay').hide();
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});;
	}

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

	function simpan_kkm(ini){
		var kkm = ini.value;
		if(kkm!=''){
			$.post("{{route('guru-isian-simpan_kkm')}}",{kkm:kkm},function(data){
				swal(data.title,data.message,data.type);
			}).fail(function(){
				swal('Whooops','Terjadi kesalahan pada aplikasi','error');
			});
		}
	}

	function pages2(id){
		$('.active-tab2').removeClass('active');
		$('#tab2-'+id).addClass('active');

		$('#pages2').hide();
		$('.loading-page').show();
		$.post("{{route('guru-isian-pages2')}}",{id:id},function(data){
			$('#pages2').show();
			$('#pages2').html(data.content);
			$('.loading-page').hide();
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});;
	}

	function pages3(id){
		$('.active-tab3').removeClass('active');
		$('#tab3-'+id).addClass('active');

		$('#pages3').hide();
		$('.loading-page').show();
		$.post("{{route('guru-isian-pages3')}}",{id:id},function(data){
			$('#pages3').show();
			$('#pages3').html(data.content);
			$('.loading-page').hide();
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});;
	}

	function pages6(id){
		$('.active-tab6').removeClass('active');
		$('#tab6-'+id).addClass('active');

		$('#pages6').hide();
		$('.loading-page').show();
		$.post("{{route('guru-isian-pages6')}}",{id:id},function(data){
			$('#pages6').show();
			$('#pages6').html(data.content);
			$('.loading-page').hide();
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});;
	}

	function generate_nilai_akhir(){
		swal({
			title: "Apakah anda yakin?",
			text: "Nilai akan digenerate!",
			icon: "warning",
			buttons: ['Tidak','Ya'],
			primaryMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				get_pages(5);
			}
		});
	}

	function simpan_nilai(id_siswa,kategori,no_ki){
		var nilai = $("input[name='"+kategori+"_"+id_siswa+"[]']").map(function(){return $(this).val();}).get();
		var id_kd = $("input[name='id_kd_"+id_siswa+"[]']").map(function(){return $(this).val();}).get();

		var data = {
			id_siswa:id_siswa,
			kategori:kategori,
			nilai:nilai,
			id_kd:id_kd,
			no_ki:no_ki,
		};

		$.post("{{route('guru-isian-simpan_nilai')}}",data,function(data){
			swal(data.title,data.message,data.type);
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});
	}

	function simpan_uts(id_siswa){
		var uts = $('input[name=uts_'+id_siswa+']').val();
		var uas = $('input[name=uas_'+id_siswa+']').val();

		var data = {
			id_siswa:id_siswa,
			uts:uts,
			uas:uas,
		};

		$.post("{{route('guru-isian-simpan_uts')}}",data,function(data){
			swal(data.title,data.message,data.type);
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});
	}
</script>
@endsection