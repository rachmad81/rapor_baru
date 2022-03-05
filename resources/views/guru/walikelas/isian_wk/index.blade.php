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
		left: 0px;
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
				<div class="card card-danger card-outline card-outline-tabs">
					<div class="card-header p-0 border-bottom-0">
						<ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
							<li class="nav-item">
								<a class="nav-link" id="tab-generate" onclick="get_pages('generate')">Generate</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-1" onclick="get_pages(1)">Rapor Siswa</a>
							</li>
							@if(!(Session::get('kelas_wk')=='6' || Session::get('kelas_wk')=='9') && $semester=='genap')
							<li class="nav-item">
								<a class="nav-link" href="{{route('cetak_dkn_wk')}}" target="_blank">Download DKN</a>
							</li>
							@endif
							<li class="nav-item">
								<a class="nav-link" id="tab-2" onclick="get_pages(2)">Spiritual (KI-1)</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-3" onclick="get_pages(3)">Sosial (KI-2)</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-4" onclick="generate_nilai_akhir()">Hasil Akhir Sikap</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-5" onclick="get_pages(5)">Ekstrakurikuler</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-6" onclick="get_pages(6)">Absensi</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-7" onclick="get_pages(7)">Catatan</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tab-8" onclick="get_pages(8)">Kesehatan</a>
							</li>
							@if(!(Session::get('kelas_wk')=='6' || Session::get('kelas_wk')=='9') && $semester=='genap')
							<li class="nav-item">
								<a class="nav-link" id="tab-9" onclick="get_pages(9)">Naik Kelas</a>
							</li>
							@endif
						</ul>
					</div>
					<div class="card-body">
						<div class="tab-content" id="custom-tabs-four-tabContent">
							<div class="overlay">
								<i class="fas fa-3x fa-sync-alt fa-spin"></i>
								<div class="text-bold pt-2">Loading...</div>
							</div>
							<div class="tab-pane fade active show" style="overflow: auto" id="custom-tabs-four-home" role="tabpanel"></div>
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
		}

		$('.overlay').show();
		$('#custom-tabs-four-home').hide();
		$.post("{{route('guru-isian_wk-page')}}",{i:i},function(data){
			$('#custom-tabs-four-home').show();
			$('#custom-tabs-four-home').html(data.content);
			$('.overlay').hide();
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});;
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

	function pages2(i){
		$('.active-tab2').removeClass('active');
		$('#tab2-'+i).addClass('active');

		$('.overlay1').show();
		$('#pages2').html('');
		$.post("{{route('guru-isian_wk-pages2')}}",{i:i},function(data){
			$('#pages2').html(data.content);
			$('.overlay1').hide();
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});;
	}

	function pages3(i){
		$('.active-tab3').removeClass('active');
		$('#tab3-'+i).addClass('active');

		$('.overlay1').show();
		$('#pages3').html('');
		$.post("{{route('guru-isian_wk-pages3')}}",{i:i},function(data){
			$('#pages3').html(data.content);
			$('.overlay1').hide();
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
				get_pages(4);
			}
		});
	}

	function simpan_nilai(kolom,id_siswa,ini,no_ki){
		var data = {
			kolom:kolom,
			id_siswa:id_siswa,
			nilai:ini.value,
			no_ki:no_ki,
		};

		$.post("{{route('guru-isian_wk-simpan_nilai')}}",data,function(data){
			swal(data.title,data.message,data.type);
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		})
	}

	function cetak_rapor(id_siswa,schema,sisipan=null){
		var data = {
			id_siswa:id_siswa,
			schema:schema,
			sisipan:sisipan,
		};

		window.open("{{url('/')}}/guru/isian_wk/cetak_rapor/"+schema+"/"+id_siswa+"?sisipan="+sisipan);
	}

	function simpan_ekskul(id_siswa,schema,ekskul,nilai){
		var ekskul = $("select[name='"+ekskul+"[]']").map(function(){return $(this).val();}).get();
		var nilai_ekskul = $("select[name='"+nilai+"[]']").map(function(){return $(this).val();}).get();

		var data = {
			id_siswa:id_siswa,
			schema:schema,
			ekskul:ekskul,
			nilai:nilai_ekskul,
		};

		$.post("{{route('guru-isian_wk-simpan_ekskul')}}",data,function(data){
			swal(data.title,data.message,data.type);
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});
	}

	function simpan_absen(id_siswa,schema,absen){
		var absen = $("input[name='"+absen+"[]']").map(function(){return $(this).val();}).get();

		var data = {
			id_siswa:id_siswa,
			schema:schema,
			absen:absen,
		};

		$.post("{{route('guru-isian_wk-simpan_absen')}}",data,function(data){
			swal(data.title,data.message,data.type);
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});
	}

	function simpan_catatan(id_siswa,schema,absen,kolom='catatan_siswa'){
		if(kolom=='catatan_siswa'){
			var catatan = $("input[name="+absen+"]").val();
		}else{
			var catatan = $("select[name="+absen+"]").val();
		}

		var data = {
			id_siswa:id_siswa,
			schema:schema,
			catatan:catatan,
			kolom:kolom,
		};

		$.post("{{route('guru-isian_wk-simpan_catatan')}}",data,function(data){
			swal(data.title,data.message,data.type);
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});
	}

	function modal_kesehatan(id_siswa,schema){
		$.post("{{route('guru-isian_wk-modal_kesehatan')}}",{id_siswa:id_siswa,schema:schema},function(data){
			$('.modal_page').html(data.content);
			$('#modal-default').modal('show');
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});;
	}

	function simpan_kesehatan(id_siswa,schema){
		var tinggi = $("input[name='tinggi_modal[]']").map(function(){return $(this).val();}).get();
		var beratbadan = $("input[name='beratbadan_modal[]']").map(function(){return $(this).val();}).get();
		var lihat = $("input[name='lihat_modal[]']").map(function(){return $(this).val();}).get();
		var dengar = $("input[name='dengar_modal[]']").map(function(){return $(this).val();}).get();
		var gigi = $("input[name='gigi_modal[]']").map(function(){return $(this).val();}).get();
		var lainnya = $("input[name='lainnya_modal[]']").map(function(){return $(this).val();}).get();

		var data = {
			id_siswa:id_siswa,
			schema:schema,
			tinggi:tinggi,
			beratbadan:beratbadan,
			lihat:lihat,
			dengar:dengar,
			gigi:gigi,
			lainnya:lainnya,
		};

		$.post("{{route('guru-isian_wk-simpan_kesehatan')}}",data,function(data){
			swal(data.title,data.message,data.type);
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
		});
	}

	function generate_anggota(){
		swal({
			title: "Apakah anda yakin?",
			text: "Data siswa saat ini akan disimpan sebagai anggota rombel!",
			icon: "warning",
			buttons: true,
			dangerMode: false,
		})
		.then((willDelete) => {
			if (willDelete) {
				var data = {
				};
				$.post("{{route('guru-isian_wk-generate_anggota')}}",data,function(data){
					if(data.code=='200'){
						get_pages('generate');
					}else{
						swal('Whooops','gagal generate','error');
					}
				}).fail(function(){
					swal('Whooops','Terjadi kesalahan dengan aplikasi','error');
				});
			}
		});
	}
</script>
@endsection