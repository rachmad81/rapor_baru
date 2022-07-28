@extends('master.index')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Data Siswa</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Data Siswa</li>
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
				<div class="card card-primary card-outline">
					<div class="card-header"></div>
					<!-- /.card-header -->
					<div class="card-body">
						<div class="row">
							<div class="col-lg-3">
								<label>Tahun Ajaran</label>
								<select class="form-control" name="tahun_ajaran" onchange="set_ta()">
									@if(count($tahun_ajaran)!=0)
									<option value="">..:: Pilih Tahun Ajaran ::..</option>
									@foreach($tahun_ajaran as $ta)
									<option value="{{$ta->id_tahun_ajaran}}">{{$ta->nama_tahun_ajaran}}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="col-lg-3">
								<label>Semester</label>
								<select class="form-control" name="semester" onchange="set_ta()">
									<option value="">..:: semester ::..</option>
									<option value="1">Ganjil</option>
									<option value="2">Genap</option>
								</select>
							</div>
							<div class="col-lg-3">
								<label>Kelas & Rombel</label>
								<select class="form-control" name="rombel" id="rombel" onchange="mapel()">
									<option value="">.:: Kelas ::..</option>
								</select>
							</div>
							<div class="col-lg-3">
								<label>Mata Pelajaran</label>
								<select class="form-control" name="mapel">
									<option value="">..:: Pilih Mata Pelajaran ::..</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3">
								<a href="javascript:void(0)" class="btn btn-primary" onclick="get_data()"><i class="fa fa-search"></i>Cari</a>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-12">
								<b>Langkah langkah:</b>
								<p>
									<ol>
										<li>
											<form id="upload_excel">
											<input type="file" name="file_excel">
											</form>
										</li>
									</ol>
								</p>
								<a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="upload_nilai()">Upload</a>
							</div>
						</div>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('extend_js')
<script type="text/javascript">
	function set_ta(form=null){
		if(form==null){
			var ta = $('select[name=tahun_ajaran]').val();
			var semester = $('select[name=semester]').val();
		}else{
			var ta = $('#tahun_ajaran').val();
			var semester = $('#semester').val();
		}
		$('select[name=mapel]').html('<option value="">..:: Pilih Mata Pelajaran ::..</option>');
		$.post("{{route('get_rombel')}}",{ta:ta,semester:semester},function(data){
			var selectnya = '<option value="">..:: Pilih Kelas Rombel ::..</option>';
			if(data.code=='200'){
				if(data.kelas.length!=0){
					$.each(data.kelas,function(k,v){
						selectnya += '<option value="'+v.id_rombongan_belajar+'">'+v.kelas+' '+v.rombel+'</option>';
					});
				}
			}else{
				swal('Whooops','Tahun ajaran belum dibuka','warning');
			}
			$('#rombel').html(selectnya);
		});
	}

	function mapel(){
		var kelas = $('select[name=rombel]').val();
		var data = {
			kelas:kelas,
		};
		$.post("{{route('ks-data-master-upload_nilai-mapel')}}",data,function(data){
			if(data.length!=0){
				var html = '<option value="">..:: Pilih Mata Pelajaran ::..</option>';
				html += '<option value="spiritual">Spiritual</option>';
				$.each(data,function(k,v){
					html += '<option value="'+v.mapel_id+'">'+v.nama+'</option>';
				});

				$('select[name=mapel]').html(html);
			}
		});
	}

	function get_data(){
		var kelas = $('select[name=rombel]').val();
		var mapel = $('select[name=mapel]').val();
		var data = "?id_rombel="+kelas+"&mapel="+mapel;

		window.open("{{route('ks-data-master-upload_nilai-template')}}"+data);
	}

	function upload_nilai(){
		var formData = new FormData($('form#upload_excel')[0]);

		$('#btn-upload').hide();

		$.ajax({
			url : "{{route('ks-data-master-upload_nilai-upload')}}",
			type : 'POST',
			data : formData,
			processData: false,  
			contentType: false,  
			success : function(data) {
				swal(data.status,data.message,data.type);
				$('#btn-upload').show();
			}
		}).fail(function(){
			swal('Whooops','Terjadi kesalahan pada aplikasi','error');
			$('#btn-upload').show();
		});
	}
</script>
@endsection