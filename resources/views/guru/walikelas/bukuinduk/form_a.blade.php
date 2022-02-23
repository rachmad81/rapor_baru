{{-- DATA A --}}
<tr>
	<td colspan="4" style="font-size: 12pt;">A. KETERANGAN TENTANG DIRI PESERTA DIDIK : </td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px">1.</td>
	<td>Nama Lengkap</td>
	<td>: {!!strtoupper($siswa->nama)!!}</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>Nama Panggilan</td>
	<td>: <input type="text" name="nama_panggilan" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->nama_panggilan) : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td>2.</td>
	<td>Jenis Kelamin</td>
	<td>: {{($siswa->kelamin=='L') ? 'LAKI-LAKI' : 'PEREMPUAN'}}</td>
</tr>
<tr>
	<td></td>
	<td>3.</td>
	<td>Temap Tanggal Lahir</td>
	<td>: {!!strtoupper($siswa->tempat_lahir)!!}, {{date('d-m-Y',strtotime($siswa->tgl_lahir))}}</td>
</tr>
<tr>
	<td></td>
	<td>4.</td>
	<td>Agama</td>
	<td>: {!!strtoupper($siswa->aga_nama)!!}</td>
</tr>
<tr>
	<td></td>
	<td>5.</td>
	<td>Kewarganegaraan</td>
	<td>: {!!strtoupper($siswa->country_name)!!}</td>
</tr>
<tr>
	<td></td>
	<td>6.</td>
	<td>Anak keberapa</td>
	<td>: {!!$siswa->anakke!!}</td>
</tr>
<tr>
	<td></td>
	<td>7.</td>
	<td>Jumlah Saudara Kandung</td>
	<td>: <input type="number" name="saudara_kandung" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->saudara_kandung) : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td>8.</td>
	<td>Jumlah Saudara Tiri</td>
	<td>: <input type="number" name="saudara_tiri" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->saudara_tiri) : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td>9.</td>
	<td>Jumlah Saudara Angkat</td>
	<td>: <input type="number" name="saudara_angkat" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->saudara_angkat) : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td>10.</td>
	<td>Status Anak</td>
	<td>: 
		<?php
		$data_yatim = [
			'Yatim','Piatu','Yatim Piatu','Kandung','Tiri'
		];
		$nilai = (!empty($bukuinduk)) ? $bukuinduk->yatim_piatu : '';
		?>
		<select name="yatim_piatu">
			<option value="">- PILIH -</option>
			@for($i=0;$i<count($data_yatim);$i++)
			<option value="{{$data_yatim[$i]}}" @if($data_yatim[$i]==$nilai) selected @endif>{{$data_yatim[$i]}}</option>
			@endfor
		</select>
	</td>
</tr>
<tr>
	<td></td>
	<td>11.</td>
	<td>Bahasa sehari-hari di rumah</td>
	<td>: <input type="text" name="bahasa" value="{{(!empty($bukuinduk)) ? strtoupper($bukuinduk->bahasa) : ''}}"></td>
</tr>