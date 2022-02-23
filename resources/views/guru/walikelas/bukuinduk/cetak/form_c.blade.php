{{-- DATA C --}}
<tr>
	<td colspan="4" style="font-size: 12pt;">C. KETERANGAN KESEHATAN : </td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px">16.</td>
	<td>Golongan Darah</td>
	<td>: {{$bukuinduk->gol_darah}}</td>
</tr>
<tr>
	<td></td>
	<td>17.</td>
	<td>Penyakit yang pernah diderita</td>
	<td>: {!! strtoupper($bukuinduk->penyakit)!!}</td>
</tr>
<tr>
	<td></td>
	<td>18.</td>
	<td>Kelainan Jasmani</td>
	<td>: {!! strtoupper($bukuinduk->kelainan)!!}</td>
</tr>
<tr>
	<td></td>
	<td>19.</td>
	<td>Tinggi dan Berat Badan</td>
	<td>: {!! strtoupper($bukuinduk->tinggi)!!} Cm</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>(saat diterima sekolah ini)</td>
	<td>: {!! strtoupper($bukuinduk->berat)!!} Kg</td>
</tr>