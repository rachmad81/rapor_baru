<div>
	<table style="width: 100%;border-collapse: collapse;" border="1">
		<tr>
			<th style="width: 5%">No</th>
			<th>Nama</th>
			<th style="width: 10%">Buku Induk</th>
			<th style="width: 20%" colspan="2">Rapor Siswa</th>
		</tr>
		@if($siswa->count()!=0)
		@foreach($siswa as $k=>$s)
		<tr>
			<td>{{($k+1)}}</td>
			<td>{!! $s->nama !!}</td>
			<td><a href="{{route('buku_induk-data',['id_siswa'=>$s->id_siswa])}}" target="_blank" class="btn btn-info btn-sm">Cetak Buku Induk</a></td>
			<td><a href="javascript:void(0)" onclick="cetak_rapor('{{$s->id_siswa}}','{{$nama_schema}}','sisipan')" class="btn btn-warning btn-sm">Rapor Sisipan</a></td>
			<td><a href="javascript:void(0)" onclick="cetak_rapor('{{$s->id_siswa}}','{{$nama_schema}}')" class="btn btn-danger btn-sm">Rapor Akhir</a></td>
		</tr>
		@endforeach
		@endif
	</table>
</div>