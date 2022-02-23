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
					<label>Pilih Tahun Ajaran</label>
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
					<label>Pilih Mata Kategori Pelajaran</label>
					<div class="input-group mb-3">
						<select class="form-control" name="kategori" onchange="set_mapel(this)">
							@if($kategori->count()!=0)
							<option value="">..:: Pilih Kategori Mapel ::..</option>
							@foreach($kategori as $kat)
							<option value="{{$kat->kategori}}">{{$kat->kategori}}</option>
							@endforeach
							@endif
						</select>
					</div>
					<label>Pilih Mata Pelajaran</label>
					<div class="input-group mb-3">
						<select name="mapel_id" id="select_mapel" class="form-control">
							<option value="">..:: Pilih Mapel ::..</option>
						</select>
					</div>
					<label>Pilih Guru</label>
					<div class="input-group mb-3">
						<select name="guru" class="form-control">
							<option value="">..:: Pilih Guru ::..</option>
							@if($guru->count()!=0)
							@foreach($guru as $g)
							<option value="{{$g->peg_id}}">{{$g->nama}}</option>
							@endforeach
							@endif
						</select>
					</div>
					<label>Pilih Kelas dan Rombel</label>
					<div class="input-group mb-3">
						<select name="rombel" class="form-control" id="rombel">
							<option value="">..:: Pilih Kelas Rombel ::..</option>
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