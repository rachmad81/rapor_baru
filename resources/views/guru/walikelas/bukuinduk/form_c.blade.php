{{-- DATA C --}}
<tr>
	<td colspan="4" style="font-size: 12pt;">C. KETERANGAN KESEHATAN : </td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px">16.</td>
	<td>Golongan Darah</td>
	<td>: 
		<?php
		$data_gol_darah = [
			'A','B','AB','O'
		];
		$nilai = (!empty($bukuinduk)) ? $bukuinduk->gol_darah : ''
		?>
		<select name="gol_darah">
			<option value="">- PILIH -</option>
			@for($i=0;$i<count($data_gol_darah);$i++)
			<option value="{{$data_gol_darah[$i]}}" @if($data_gol_darah[$i]==$nilai) selected @endif>{{$data_gol_darah[$i]}}</option>
			@endfor
		</select>
	</td>
</tr>
<tr>
	<td></td>
	<td>17.</td>
	<td>Penyakit yang pernah diderita</td>
	<td>: <input type="text" name="penyakit" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->penyakit) : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td>18.</td>
	<td>Kelainan Jasmani</td>
	<td>: <input type="text" name="kelainan" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->kelainan) : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td>19.</td>
	<td>Tinggi dan Berat Badan</td>
	<td>: <input type="number" name="tinggi" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->tinggi) : '' !!}"> Cm</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>(saat diterima sekolah ini)</td>
	<td>: <input type="number" name="berat" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->berat) : '' !!}"> Kg</td>
</tr>