{{-- DATA J --}}
<tr>
	<td colspan="4" style="font-size: 12pt;">J. KETERANGAN SETELAH SELESAI PENDIDIKAN : </td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px">56.</td>
	<td>Akan melanjutkan ke</td>
	<td>: {!! strtoupper($bukuinduk->melajutkan) !!}</td>
</tr>
<tr>
	<td></td>
	<td>57.</td>
	<td>Akan bekerja</td>
	<td></td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>a. Tanggal mulai bekerja</td>
	<td>: {!! strtoupper($bukuinduk->tgl_bekerja) !!}</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>b. Nama Perusahaan/lembaga Dan Lain-lain</td>
	<td>: {!! strtoupper($bukuinduk->perusahaan) !!}</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>c. Penghasilan</td>
	<td>: {{$bukuinduk->penghasilan}}</td>
</tr>