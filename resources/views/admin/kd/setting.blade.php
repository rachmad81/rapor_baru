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
					<label>Tahun Ajaran</label>
					<div>
						<select name="tahun_ajaran" class="form-control" onchange="get_kd()">
							<option value="">-- Pilih Tahun Ajaran --</option>
							@if($tahun_ajaran->count()!=0)
							@foreach($tahun_ajaran as $ta)
							<option value="{{$ta->id_tahun_ajaran}}">{{$ta->nama_tahun_ajaran}}</option>
							@endforeach
							@endif
						</select>
					</div>
					<label>Semester</label>
					<div>
						<select name="semester" class="form-control" onchange="get_kd()">
							<option value="1">Ganjil</option>
							<option value="2">Genap</option>
						</select>
					</div>
					<br>
					<input type="hidden" name="mapel_id" value="{{$mapel_id}}">
					<input type="hidden" name="kelas" value="{{$kelas}}">
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