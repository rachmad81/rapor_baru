<div>
	<table style="width: 100%;border-collapse: collapse;" border="1">
		<tr>
			<th style="width: 5%">No</th>
			<th>Nama</th>
			<th>Sakit</th>
			<th>Izin</th>
			<th>Tanpa Keterangan</th>
			<th>Simpan</th>
		</tr>
		@if($siswa->count()!=0)
		@foreach($siswa as $k=>$s)
		<tr>
			<td>{{($k+1)}}</td>
			<td style="white-space: nowrap;">{!!$s->nama!!}</td>
			<td><input type="number" name="absen_{{$k+1}}[]" value="" class="form-control"></td>
			<td><input type="number" name="absen_{{$k+1}}[]" value="" class="form-control"></td>
			<td><input type="number" name="absen_{{$k+1}}[]" value="" class="form-control"></td>
			<td><a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="simpan_absen('{{$s->id_siswa}}','{{$nama_schema}}','absen_{{$k+1}}')">Simpan</a></td>
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