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
		{!! strtoupper($bukuinduk->tmp_ibu) !!}, 
		{!! date('d-m-Y',strtotime($bukuinduk->tgl_ibu)) !!}
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
	<td>: {{$bukuinduk->pengeluaran_ibu}}</td>
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
	<td>: {{$bukuinduk->status_ibu}}</td>
</tr>