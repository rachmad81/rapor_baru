{{-- DATA E --}}
<tr>
	<td colspan="4" style="font-size: 12pt;">E. KETERANGAN TENTANG IBU KANDUNG : </td>
</tr>
<tr>
	<td></td>
	<td style="width: 5px">23.</td>
	<td>Nama</td>
	<td>: {!! strtoupper($siswa->nama_ibu) !!}</td>
</tr>
<tr>
	<td></td>
	<td>24.</td>
	<td>Tempat dan tanggal lahir</td>
	<td>: 
		<input type="text" name="tmp_ibu" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->tmp_ibu) : '' !!}">
		<input type="date" name="tgl_ibu" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->tgl_ibu) : '' !!}">
	</td>
</tr>
<tr>
	<td></td>
	<td>25.</td>
	<td>Agama</td>
	<td>: {!!strtoupper($siswa->aga_nama)!!}</td>
</tr>
<tr>
	<td></td>
	<td>26</td>
	<td>Kewarganegaraan</td>
	<td>: {!!strtoupper($siswa->country_name)!!}</td>
</tr>
<tr>
	<td></td>
	<td>27.</td>
	<td>Pendidikan</td>
	<td>: {!!$siswa->c_pendidikan_ibu!!}</td>
</tr>

<tr>
	<td></td>
	<td style="width: 5px">28.</td>
	<td>Pekerjaan</td>
	<td>: {!!$siswa->c_pekerjaan_ibu!!}</td>
</tr>
<tr>
	<td></td>
	<td>29.</td>
	<td>Pengeluaran perbulan</td>
	<td>: 
		<?php
		$pengeluaran = [
			'Tidak ada penghasilan','Rp.0 - Rp. 500 Ribu','Rp. 500 Ribu - Rp. 1 Juta','Rp. 1.5 Juta - Rp. 2.5 Juta','Rp. 3 Juta - Rp. 5 Juta','Diatas Rp. 5 Juta'
		];
		$nilai = (!empty($bukuinduk)) ? $bukuinduk->pengeluaran_ibu : ''
		?>
		<select name="pengeluaran_ibu">
			<option value="">- PILIH -</option>
			@for($i=0;$i<count($pengeluaran);$i++)
			<option value="{{$pengeluaran[$i]}}" @if($pengeluaran[$i]==$nilai) selected @endif>{{$pengeluaran[$i]}}</option>
			@endfor
		</select>
	</td>
</tr>
<tr>
	<td></td>
	<td>30.</td>
	<td>Alamat rumah/Nomor Telp./HP</td>
	<td>: 
		{!!strtoupper($siswa->alamat_rumah)!!}
		<br>&nbsp;&nbsp;RT : {{$siswa->rt}}, RW : {{$siswa->rw}}
		<br>&nbsp;&nbsp; {{$siswa->telpon}} / {{$siswa->seluler}}
	</td>
</tr>

<tr>
	<td></td>
	<td style="width: 5px">31.</td>
	<td>Masih hidup/Meninggal dunia	</td>
	<td>:
		<?php
		$status = [
			'Masih hidup','Meninggal dunia'
		];
		$nilai = (!empty($bukuinduk)) ? $bukuinduk->status_ibu : ''
		?>
		<select name="status_ibu">
			<option value="">- PILIH -</option>
			@for($i=0;$i<count($status);$i++)
			<option value="{{$status[$i]}}" @if($status[$i]==$nilai) selected @endif>{{$status[$i]}}</option>
			@endfor
		</select>
	</td>
</tr>