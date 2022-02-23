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
	<td>: <input type="text" name="no_stl_lama" value="{!! (!empty($bukuinduk)) ? $bukuinduk->no_stl_lama : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>d. Lama Belajar</td>
	<td>: 
		<?php
		$kelas = [
			'0','1','2','3','4','5','6','7','8'
		];
		$nilai = (!empty($bukuinduk)) ? $bukuinduk->lama_belajar : ''
		?>
		<select name="lama_belajar">
			<option value="">- PILIH -</option>
			@for($i=0;$i<count($kelas);$i++)
			<option value="{{$kelas[$i]}}" @if($kelas[$i]==$nilai) selected @endif>{{$kelas[$i]}}</option>
			@endfor
		</select>
	</td>
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
	<td>: <input type="text" name="dari_sekolah" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->dari_sekolah) : '' !!}"></td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>b. Alasan</td>
	<td>: <input type="text" name="alasan" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->alasan) : '' !!}"></td>
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
	<td>: 
		<?php
		if(Session::get('jenjang')=='SD'){
			$kelas = [
				'1','2','3','4','5','6'
			];
		}else{
			$kelas = [
				'7','8','9'
			];
		}
		$nilai = (!empty($bukuinduk)) ? $bukuinduk->dikelas : ''
		?>
		<select name="dikelas">
			<option value="">- PILIH -</option>
			@for($i=0;$i<count($kelas);$i++)
			<option value="{{$kelas[$i]}}" @if($kelas[$i]==$nilai) selected @endif>{{$kelas[$i]}}</option>
			@endfor
		</select>
	</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>b. Kelompok</td>
	<td>: 
		<?php
		$rombel = [
			'1','2','3','4','5','6'
		];
		$nilai = (!empty($bukuinduk)) ? $bukuinduk->dirombel : ''
		?>
		<select name="dirombel">
			<option value="">- PILIH -</option>
			@for($i=0;$i<count($rombel);$i++)
			<option value="{{$rombel[$i]}}" @if($rombel[$i]==$nilai) selected @endif>{{$rombel[$i]}}</option>
			@endfor
		</select>
	</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>c. Tanggal</td>
	<td>: <input type="date" name="tgl_mutasi" value="{!! (!empty($bukuinduk)) ? strtoupper($bukuinduk->tgl_mutasi) : '' !!}"></td>
</tr>