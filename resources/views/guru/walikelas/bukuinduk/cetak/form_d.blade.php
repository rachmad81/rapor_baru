{{-- DATA D --}}
<tr>
	<td colspan="4" style="font-size: 12pt;">D. KETERANGAN PENDIDIKAN : </td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px">20.</td>
	<td>Pendidikan Sebelumnya</td>
	<td></td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>a. Tamatan dari</td>
	<td>: {{$siswa->asal_sekolah}}</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>b. Tanggal dan Nomor Ijazah</td>
	<td>: {!!strtoupper($siswa->tahun_ijasah)!!} @if($siswa->no_ijasah!='') dan @endif {!!$siswa->no_ijasah!!}</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>c. Tanggal dan Nomor STL</td>
	<td>: {!! $bukuinduk->no_stl_lama !!}
</tr>
<tr>
	<td></td>
	<td></td>
	<td>d. Lama Belajar</td>
	<td>: {{$bukuinduk->lama_belajar}}</td>
</tr>

<tr>
	<td></td>
	<td style="width: 5px">21.</td>
	<td>Pindahan</td>
	<td></td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>a. Dari Sekolah</td>
	<td>: {!! strtoupper($bukuinduk->dari_sekolah)!!}</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>b. Alasan</td>
	<td>: {!! strtoupper($bukuinduk->alasan)!!}</td>
</tr>

<tr>
	<td></td>
	<td style="width: 5px">22.</td>
	<td>Diterima di Sekolah ini</td>
	<td></td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>a. Dikelas</td>
	<td>: {{$bukuinduk->dikelas}}</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>b. Kelompok</td>
	<td>: {{$bukuinduk->dirombel}}</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>c. Tanggal</td>
	<td>: {!! strtoupper($bukuinduk->tgl_mutasi)!!}</td>
</tr>