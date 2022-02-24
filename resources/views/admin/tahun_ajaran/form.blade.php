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
				@php
				$id_tahun_ajaran = (!empty($ta)) ? $ta->id_tahun_ajaran : '0';
				$nama_tahun_ajaran = (!empty($ta)) ? $ta->nama_tahun_ajaran : '';
				$tgl_setting_awal = (!empty($ta)) ? date('Y-m-d',strtotime($ta->tgl_setting_awal)) : '';
				$tgl_setting_akhir = (!empty($ta)) ? date('Y-m-d',strtotime($ta->tgl_setting_akhir)) : '';
				@endphp
				<form id="form_simpan">
					<label>Tahun Ajaran</label>
					<div class="input-group mb-3">
						<input type="hidden" value="{{$id_tahun_ajaran}}" name="id_tahun_ajaran" class="form-control">
						<input type="text" value="{{$nama_tahun_ajaran}}" name="nama_tahun_ajaran" class="form-control">
					</div>
					<label>Tanggal Awal</label>
					<div class="input-group mb-3">
						<input type="text" value="{{$tgl_setting_awal}}" name="tgl_setting_awal" class="form-control">
					</div>
					<label>Tanggal Akhir</label>
					<div class="input-group mb-3">
						<input type="text" value="{{$tgl_setting_akhir}}" name="tgl_setting_akhir" class="form-control">
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
<script type="text/javascript">
	$('input[name=tgl_setting_awal]').datepicker({
        footer: false,
        uiLibrary: 'bootstrap4', 
        iconsLibrary: 'fontawesome',
        modal: true,
        format: 'yyyy-mm-dd',
	});
	$('input[name=tgl_setting_akhir]').datepicker({
        footer: false,
        uiLibrary: 'bootstrap4', 
        iconsLibrary: 'fontawesome',
        modal: true,
        format: 'yyyy-mm-dd',
	});
</script>