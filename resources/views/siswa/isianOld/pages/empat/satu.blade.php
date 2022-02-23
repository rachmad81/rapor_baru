<table border="1" style="width: 100%;border-collapse: collapse;">
	<tr>
		<th>No</th>
		<th>Id Siswa</th>
		<th>Nama Siswa</th>
		<th>Akhir</th>
	</tr>
	<tr>
		<td>1</td>
		<td>{{Session::get('nik')}}</td>
		<td>{{Session::get('nama')}}</td>
		<td><input type="number" name="nilai"></td>
	</tr>
</table>