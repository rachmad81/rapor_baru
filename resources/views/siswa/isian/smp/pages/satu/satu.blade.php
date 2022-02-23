{{-- <table border="1" style="width: 100%;border-collapse: collapse;">
	<tr>
		<th>No</th>
		<th>Nama</th>
		<th>PTS</th>
		<th>PAS</th>
		<th>#</th>
	</tr>
	<tr>
		<td>1</td>
		<td>{{Session::get('nama')}}</td>
		<td><input type="number" name="pts" value="{{$mengajar->uts}}"></td>
		<td><input type="number" name="pas" value="{{$mengajar->uas}}"></td>
		<td><a href="javascript:void(0)" class="btn btn-sm btn-primary">Simpan</a></td>
	</tr>
</table> --}}

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 xol-xs-12">
				<div class="card card-info">
					<div class="card-header">
						PTS
					</div>
					<div class="card-body" style="background: #ccc">
						<input type="number" class="form-control" name="uts" value="{{(!empty($mengajar)) ? $mengajar->uts : ''}}" @if($mengajar->islock_uts==true) readonly @endif>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-12 xol-xs-12">
				<div class="card card-info">
					<div class="card-header">
						PAS
					</div>
					<div class="card-body" style="background: #ccc">
						<input type="number" class="form-control" name="uas" value="{{(!empty($mengajar)) ? $mengajar->uas : ''}}" @if($mengajar->islock_uts==true) readonly @endif>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div style="width: 100%">
		<a style="width: 100%" href="javascript:void(0)" class="btn btn-primary btn-lg" @if($mengajar->islock_uts==true) onclick="alert('Pengisian nilai sudah dikunci')" @else onclick="simpan11()" @endif>Simpan</a>
	</div>
</div>