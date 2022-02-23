{{-- DATA I --}}
<tr>
	<td colspan="4" style="font-size: 12pt;">I. KETERANGAN PERKEMBANGAN SISWA : </td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px">53.</td>
	<td>Menerima Bea Siswa</td>
	<td>: Tahun ......... Kls ........ dari .........</td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px"></td>
	<td></td>
	<td>&nbsp; Tahun ......... Kls ........ dari .........</td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px"></td>
	<td></td>
	<td>&nbsp; Tahun ......... Kls ........ dari .........</td>
</tr>

<tr>
	<td></td>
	<td>54.</td>
	<td>Meninggalkan sekolah ini</td>
	<td></td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px"></td>
	<td>a. Tanggal meninggalkan sekolah</td>
	<td>: {!! $bukuinduk->tgl_keluar !!}</td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px"></td>
	<td>b. Alasan</td>
	<td>: {!! strtoupper($bukuinduk->alasan_keluar) !!}</td>
</tr>

<tr>
	<td></td>
	<td>55.</td>
	<td>Akhir Pendidikan</td>
	<td></td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px"></td>
	<td>a. Tamat belajar/Lulus</td>
	<td>: {!! strtoupper($bukuinduk->tamat) !!}</td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px"></td>
	<td>b. Ijazah</td>
	<td>: {!! $bukuinduk->no_ijazah !!}</td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px"></td>
	<td>c. Nomor Surat Tanda Lulus/STL</td>
	<td>: {!! $bukuinduk->no_stl !!}</td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px"></td>
	<td>d. Nilai rata-rata yang dicapai</td>
	<td>: {!! strtoupper($bukuinduk->nilai) !!}</td>
</tr>