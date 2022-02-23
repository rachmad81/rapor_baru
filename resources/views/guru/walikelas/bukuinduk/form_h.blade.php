{{-- DATA H --}}
<tr>
	<td colspan="4" style="font-size: 12pt;">H. KEGEMARAN PESERTA DIDIK : </td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px">49.</td>
	<td>Kesenian</td>
	<td>: <input type="text" name="kesenian" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->kesenian) : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td>50.</td>
	<td>Olah Raga</td>
	<td>: <input type="text" name="olahraga" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->olahraga) : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td>51.</td>
	<td>Kemasyarakatan/Organisasi</td>
	<td>: <input type="text" name="organisasi" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->organisasi) : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td>52</td>
	<td>Lain-lain</td>
	<td>: <input type="text" name="lainnya" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->lainnya) : '' !!}"></td>
</tr>