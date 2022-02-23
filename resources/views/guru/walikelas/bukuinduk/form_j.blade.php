{{-- DATA J --}}
<tr>
	<td colspan="4" style="font-size: 12pt;">J. KETERANGAN SETELAH SELESAI PENDIDIKAN : </td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px">56.</td>
	<td>Akan melanjutkan ke</td>
	<td>: <input type="text" name="melajutkan" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->melajutkan) : '' !!}"></td>
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
	<td>: <input type="text" name="tgl_bekerja" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->tgl_bekerja) : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>b. Nama Perusahaan/lembaga Dan Lain-lain</td>
	<td>: <input type="text" name="perusahaan" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->perusahaan) : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>c. Penghasilan</td>
	<td>: 
		<?php
		$pengeluaran = [
			'Tidak ada penghasilan','Rp.0 - Rp. 500 Ribu','Rp. 500 Ribu - Rp. 1 Juta','Rp. 1.5 Juta - Rp. 2.5 Juta','Rp. 3 Juta - Rp. 5 Juta','Diatas Rp. 5 Juta'
		];
		$nilai = (!empty($bukuinduk)) ? $bukuinduk->penghasilan : ''
		?>
		<select name="penghasilan">
			<option value="">- PILIH -</option>
			@for($i=0;$i<count($pengeluaran);$i++)
			<option value="{{$pengeluaran[$i]}}" @if($pengeluaran[$i]==$nilai) selected @endif>{{$pengeluaran[$i]}}</option>
			@endfor
		</select>
	</td>
</tr>