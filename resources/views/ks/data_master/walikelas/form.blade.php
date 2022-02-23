<div class="modal fade show" id="modal-default">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Form {{$title}}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_simpan">
					{{csrf_field()}}
					<label>Tahun Ajaran</label>
					<div class="input-group mb-3">
						<select class="form-control" name="tahun_ajaran" onchange="set_ta(this)">
							@if(count($tahun_ajaran)!=0)
							<option value="">..:: Pilih Tahun Ajaran ::..</option>
							@foreach($tahun_ajaran as $ta)
							<option value="{{$ta['nilai']}}">{{$ta['nama']}}</option>
							@endforeach
							@endif
						</select>
					</div>
					<label>Pilih Guru</label>
					<div class="input-group mb-3">
						<select class="form-control" name="guru">
							@if($guru->count()!=0)
							<option value="">..:: Pilih Guru ::..</option>
							@foreach($guru as $ta)
							<option value="{{$ta->peg_id}}">{{$ta->nama}}</option>
							@endforeach
							@endif
						</select>
					</div>
					<label>Pilih Kelas dan Rombel</label>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="input-group mb-3">
								<select class="form-control" id="rombel" name="kelas">
								</select>
							</div>
						</div>
					</div>
					<label>Pilih Kurikulum</label>
					<div class="input-group mb-3">
						<select class="form-control" name="kurikulum">
							<option value="">..:: Pilih Kurikulum ::..</option>
							<option value="ktsp">Kurikulum KTSP</option>
							<option value="2013">Kurikulum 2013</option>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="simpan()">Save changes</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>