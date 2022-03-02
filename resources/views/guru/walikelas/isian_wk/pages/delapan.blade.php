<div>
	<table style="width: 100%;border-collapse: collapse;" border="1">
		<tr>
			<th style="width: 5%">No</th>
			<th>Nama</th>
			<th style="width: 10%">Simpan</th>
		</tr>
		@if($siswa->count()!=0)
		@foreach($siswa as $k=>$s)
		<tr>
			<td>{{($k+1)}}</td>
			<td>{!!$s->nama!!}</td>
			<td><a href="javascript:void(0)" class="btn btn-info" onclick="modal_kesehatan('{{$s->id_siswa}}','{{$nama_schema}}')">Lihat Catatan</a></td>
		</tr>
		@endforeach
		@else
		<tr>
			<td colspan="6" style="text-align: center">
				-- Data siswa belum di <i>Generate</i> --
			</td>
		</tr>
		@endif
	</table>
</div>