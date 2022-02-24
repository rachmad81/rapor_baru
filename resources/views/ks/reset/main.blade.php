@extends('master.index')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Reset Password Guru</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Reset Password</li>
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
					<div class="card-header">
						Reset Password Guru
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<select class="form-control" name="guru">
									<option value="">-- Nama Guru --</option>
									@if($pegawai->count()!=0)
									@foreach($pegawai as $k=>$v)
									<option value="{{$v->user_rapor}}">{{$v->nama}}</option>
									@endforeach
									@endif
								</select>
								<br>
								<label><input type="checkbox" name="reset" value="pilih"> Reset semua guru</label>
								<br>
								<a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="reset()">Reset</a>
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
<script>
	function reset(){
		var guru = $('select[name=guru]').val();
		var semua = $('input[name=reset]:checked').val();

		var data = {guru:guru};

		if(semua=='pilih'){
			var data = {
				guru:guru,
				semua: semua,
			};
		}

		$.post("{{route('ks-reset_password-reset')}}",data,function(data){
			if(data.code=='200'){
				swal('Success',data.message,'success');
			}else{
				swal('Whooops',data.message,'error');
			}
		});
	}
</script>
@endsection