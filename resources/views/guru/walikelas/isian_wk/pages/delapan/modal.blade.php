<div class="modal fade show" id="modal-default">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Form Kesehatan</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<h5>Nama Siswa : <b>{!!$siswa->nama!!}</b></h5>
				<label>A. Perkembangan Fisik</label>
				<table style="width: 100%;border-collapse: collapse;" border="1">
					<tr style="background: #eee">
						<th rowspan="2">No</th>
						<th rowspan="2">Aspek yang dinilai</th>
						<th>Semester</th>
						<th>Semester</th>
					</tr>
					<tr style="background: #eee">
						<th>1</th>
						<th>2</th>
					</tr>
					<tr>
						<td>1</td>
						<td>Tinggi</td>
						<td><input type="number" name="tinggi_modal[]" value=""> Cm</td>
						<td><input type="number" name="tinggi_modal[]" value=""> Cm</td>
					</tr>
					<tr>
						<td>2</td>
						<td>Berat Badan</td>
						<td><input type="number" name="beratbadan_modal[]" value=""> Kg</td>
						<td><input type="number" name="beratbadan_modal[]" value=""> Kg</td>
					</tr>
				</table>
				<br>
				<label>B. Kondisi Kesehatan</label>
				<table style="width: 100%;border-collapse: collapse;" border="1">
					<tr style="background: #eee">
						<th rowspan="2">No</th>
						<th rowspan="2">Aspek yang dinilai</th>
						<th>Semester</th>
						<th>Semester</th>
					</tr>
					<tr style="background: #eee">
						<th>1</th>
						<th>2</th>
					</tr>
					<tr>
						<td>1</td>
						<td>Pendengaran</td>
						<td><input type="text" name="dengar_modal[]" value=""></td>
						<td><input type="text" name="dengar_modal[]" value=""></td>
					</tr>
					<tr>
						<td>2</td>
						<td>Penglihatan</td>
						<td><input type="text" name="lihat_modal[]" value=""></td>
						<td><input type="text" name="lihat_modal[]" value=""></td>
					</tr>
					<tr>
						<td>3</td>
						<td>Gigi</td>
						<td><input type="text" name="gigi_modal[]" value=""></td>
						<td><input type="text" name="gigi_modal[]" value=""></td>
					</tr>
					<tr>
						<td>4</td>
						<td>Lainnya</td>
						<td><input type="text" name="lainnya_modal[]" value=""></td>
						<td><input type="text" name="lainnya_modal[]" value=""></td>
					</tr>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="simpan_kesehatan('{{$siswa->id_siswa}}','{{$nama_schema}}')">Save changes</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>