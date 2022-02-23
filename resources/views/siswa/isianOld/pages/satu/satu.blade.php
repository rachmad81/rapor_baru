<table border="1" style="width: 100%;border-collapse: collapse;">
	<tr>
		<th>No</th>
		<th>Nama</th>
		<th>PTS</th>
		<th>PAS</th>
	</tr>
	<tr>
		<td>1</td>
		<td>{{Session::get('nama')}}</td>
		<td><input type="number" name="pts" value="{{$mengajar->uts}}"></td>
		<td><input type="number" name="pas" value="{{$mengajar->uas}}"></td>
	</tr>
</table>