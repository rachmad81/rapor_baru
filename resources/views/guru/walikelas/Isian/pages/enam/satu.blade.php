<i style="font-size: 9pt;color: red">
	<b>( I N F O R M A S I )</b><br>
	Ubah nilai kemudian klik di luar kotak inputan untuk <b>menyimpan nilai</b>
</i>
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
		<td>{{$s->id_siswa}}</td>
		<td>{!!$s->nama!!}</td>
		<td><input type="number" name="usek_{{$s->id_siswa}}" value="{{$s->usek}}" onblur="simpan_uts('{{$s->id_siswa}}','usek')"></td>
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