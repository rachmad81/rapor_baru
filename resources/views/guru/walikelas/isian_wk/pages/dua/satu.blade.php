<label>Ketaatan Beribadah</label>
<table border="1" style="width: 100%;border-collapse: collapse;">
	<tr>
		<th class="headcol">No</th>
		<th class="headcol">Nama</th>
		@for($i=1;$i<=6;$i++)
		<th>Bulan {{$i}}</th>
		@endfor
	</tr>
	<?php
	$options = [
		[
			'value'=>'0',
			'text'=>''
		],
		[
			'value'=>'4',
			'text'=>'SB'
		],
		[
			'value'=>'3',
			'text'=>'B'
		],
		[
			'value'=>'2',
			'text'=>'C'
		],
		[
			'value'=>'1',
			'text'=>'K'
		],
	];
?>
@if($siswa->count()!=0)
@foreach($siswa as $k=>$s)
<tr>
	<td class="headcol">{{($k+1)}}</td>
	<td class="headcol" style="white-space: nowrap;">{!! $s->nama !!}</td>
	@for($j=1;$j<=6;$j++)
	<?php $kolom = 'ibadah_'.$j;?>
	<td>
		@if(count($s->nilai)!=0)
		@php
		$uraian = $s->nilai[0]['uraian'];
		$nilai = $s->nilai[0]['nilai'];
		$key = array_search($j, $uraian);
		if($key){
			$nilai_pakai = $nilai[$key];
		}else{
			$nilai_pakai = 3;
		}
		@endphp
		<select class="form-control" name="{{$kolom}}" onblur="simpan_nilai('{{$kolom}}','{{$s->id_siswa}}',this,'3')">
			@for($i=0;$i<count($options);$i++)
			@if($nilai_pakai==$options[$i]['value'])
			<option value="{{$options[$i]['value']}}" selected>{{$options[$i]['text']}}</option>
			@else
			<option value="{{$options[$i]['value']}}">{{$options[$i]['text']}}</option>
			@endif
			@endfor
		</select>
		@else
		<select class="form-control" name="{{$kolom}}" onblur="simpan_nilai('{{$kolom}}','{{$s->id_siswa}}',this,'3')">
			@for($i=0;$i<count($options);$i++)
			@if('3'==$options[$i]['value'])
			<option value="{{$options[$i]['value']}}" selected>{{$options[$i]['text']}}</option>
			@else
			<option value="{{$options[$i]['value']}}">{{$options[$i]['text']}}</option>
			@endif
			@endfor
		</select>
		@endif
	</td>
	@endfor
</tr>
@endforeach
@else
<tr>
	<td colspan="8" style="text-align: center">
		-- Data siswa belum di <i>Generate</i> --
	</td>
</tr>
@endif
</table>