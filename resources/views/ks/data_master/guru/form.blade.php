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
					<label>User Lama</label>
					<div class="input-group mb-3">
						<input type="text" name="user_lama" class="form-control" value="{{$guru->user_rapor}}" readonly>
					</div>
					<label>User Baru</label>
					<div class="input-group mb-3">
						<input type="text" name="user_baru" value="" class="form-control">
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