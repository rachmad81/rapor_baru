<div class="modal fade show" id="modal-default">
	<div class="modal-dialog modal-xl">
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
					<input type="hidden" name="mapel_id" value="{{$mapel_id}}">
					<table style="width: 100%;border-collapse: collapse;" border="1">
						<tr>
							<td>KI</td>
							<td>No KD</td>
							<td style="width: 75%">Uraian KD</td>
							<td style="width: 15%">Aksi</td>
						</tr>
						<tbody id="tempat_3">
							
						</tbody>
					</table>
				</form>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>