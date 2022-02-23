<div class="card card-primary">
	<div class="card-header">DAFTAR MATA PELAJARAN</div>
	<div class="card-body">
		<div class="row">
			<div class="col-lg-10 col-md-10">{{$mengajar->nama_mapel}}</div>
			<div class="col-lg-2 col-md-2"><input type="number" name="kkm" value="{{$mengajar->kkm}}" onblur="simpan_kkm(this)"></div>
		</div>
	</div>
</div>