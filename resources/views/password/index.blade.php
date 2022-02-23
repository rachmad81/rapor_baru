@extends('master.index')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Ubah Password</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Ubah Password</li>
				</ol>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<!-- Main row -->
		<div class="row">
			<div class="col-lg 6 col-md-6"> 
				<div class="card card-primary card-outline">
					<div class="card-header">
						Ubah Password
					</div>
					<div class="card-body">
						{{csrf_field()}}
						<form id="ubah_password">
							<label>Password Baru</label>
							<div class="input-group mb-3">
								<input type="password" name="baru" class="form-control" onkeyup="cek_password()">
								<div class="input-group-append">
									<div class="input-group-text">
										<span class="fas fa-lock"></span>
									</div>
								</div>
							</div>

							<label>Ulangi Password Baru</label>
							<div class="input-group mb-3">
								<input type="password" name="baru1" class="form-control" onkeyup="cek_password()">
								<div class="input-group-append">
									<div class="input-group-text">
										<span class="fas fa-lock"></span>
									</div>
								</div>
							</div>

							<label>Password Lama</label>
							<div class="input-group mb-3">
								<input type="password" name="lama" class="form-control" onkeyup="cek_password()">
								<input type="hidden" name="izinkan">
								<div class="input-group-append">
									<div class="input-group-text">
										<span class="fas fa-lock"></span>
									</div>
								</div>
							</div>

							<div id="error_password" style="display: none;">
								<div id="minimal" style="color: red"><i class="fa fa-times"></i> Minimal 8 Karakter</div>
								<div id="lower" style="color: red"><i class="fa fa-times"></i> Terdapat huruf kecil Karakter</div>
								<div id="upper" style="color: red"><i class="fa fa-times"></i> Terdapat huruf besar Karakter</div>
								<div id="angka" style="color: red"><i class="fa fa-times"></i> Terdapat angka</div>
								<div id="sama" style="color: red"><i class="fa fa-times"></i> Password tidak sama</div>
							</div>

							<a href="javascript:void(0)" class="btn btn-primary" onclick="kirim()">Kirim</a>
						</form>
					</div>
				</div>
			</div>
			<div class="col-lg 6 col-md-6">
			</div>
		</div>
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('extend_js')
<script type="text/javascript">
	function cek_password(){
		var baru = $('input[name=baru]').val();
		var baru1 = $('input[name=baru1]').val();
		var lama = $('input[name=lama]').val();
		var izinkan = 1;
		var upperCaseLetters = /[A-Z]/g;
		var lowwerCaseLetters = /[a-z]/g;
		var numbersCaseLetters = /[0-9]/g;

		if(baru!='' && baru1!=''){
			$('#error_password').show();
		}else{
			$('#error_password').hide();
		}

		if(baru.length>=8){
			$('#minimal').css('color','green');
		}else{
			$('#minimal').css('color','red');
			izinkan = 0;
		}

		if(baru.match(upperCaseLetters)){
			$('#upper').css('color','green');
		}else{
			$('#upper').css('color','red');
			izinkan = 0;
		}

		if(baru.match(lowwerCaseLetters)){
			$('#lower').css('color','green');
		}else{
			$('#lower').css('color','red');
			izinkan = 0;
		}

		if(baru.match(numbersCaseLetters)){
			$('#angka').css('color','green');
		}else{
			$('#angka').css('color','red');
			izinkan = 0;
		}

		if(baru==baru1){
			$('#sama').css('color','green');
		}else{
			$('#sama').css('color','red');
			izinkan = 0;
		}

		if(lama=='' || lama=='null'){
			izinkan = 0;
		}

		$('input[name=izinkan]').val(izinkan);
	}

	function kirim(){
		var izinkan = $('input[name=izinkan]').val();
		var baru = $('input[name=baru]').val();
		var baru1 = $('input[name=baru1]').val();
		var lama = $('input[name=lama]').val();

		var pesannya = "Periksa kembali inputan";
		if(baru!='' && baru1!=''){
		}else{
			if(baru==''){
				pesannya = "Password baru wajib diisi";
			}else{
				pesannya = "Ulangi password baru wajib diisi";
			}
		}

		if(lama=='' || lama=='null'){
			pesannya = "Password lama harus diisi";
		}

		if(izinkan==1){
			var data = $('form#ubah_password').serialize(0);
			$.post("{{route('reset_password')}}",data,function(data){
				window.location = "{{url('/')}}";
			});
		}else{
			swal(pesannya);
		}
	}
</script>
@endsection