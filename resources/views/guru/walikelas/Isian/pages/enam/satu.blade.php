<table border="1" style="width: 100%;border-collapse: collapse;">
	<tr>
		<th>No</th>
		<th>Id Siswa</th>
		<th>Nama Siswa</th>
		<th>Akhir</th>
	</tr>
	@if($siswa->count()!=0)
	@foreach($siswa as $k=>$s)
	<tr style="@if($k%2==0) background: #eee @endif">
		<td>{{($k+1)}}</td>
		<td>{{$s->nis}}</td>
		<td>{!!$s->nama!!}</td>
		<td><input type="number" name="nilai"></td>
	</tr>
	@endforeach
	@endif
</table>