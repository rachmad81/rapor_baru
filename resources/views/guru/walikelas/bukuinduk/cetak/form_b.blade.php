{{-- DATA B --}}
<tr>
	<td colspan="4" style="font-size: 12pt;">B. KETERANGAN TEMPAT TINGGAL : </td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px">12.</td>
	<td>Alamat</td>
	<td>: {!!strtoupper($siswa->alamat)!!}</td>
</tr>
<tr>
	<td></td>
	<td>13.</td>
	<td>Nomor Telepon / HP</td>
	<td>: {{$siswa->telpon}} / {{$siswa->seluler}}</td>
</tr>
<tr>
	<td></td>
	<td>14.</td>
	<td>Tinggal dengan Orang Tua/Saudara/Di Asrama/Kos</td>
	<td>: {{$bukuinduk->tempat_tinggal}}</td>
</tr>
<tr>
	<td></td>
	<td>15.</td>
	<td>Jarak tempat tinggal ke sekolah	</td>
	<td>: {!!$siswa->jarak_rmh_sekolah_km!!}</td>
</tr>