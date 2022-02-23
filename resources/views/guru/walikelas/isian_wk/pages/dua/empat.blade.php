<label>Toleransi Beribadah</label>
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
			'text'=>'0'
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
		<td class="headcol" style="white-space: nowrap;">{!!$s->nama!!}</td>
		@for($j=1;$j<=6;$j++)
		<?php $kolom = 'toleransi_'.$j;?>
		<td>
			<select class="form-control" name="{{$kolom}}" onblur="simpan_nilai('{{$kolom}}','{{$s->id_siswa}}',this)">
				@for($i=0;$i<count($options);$i++)
				<option value="{{$options[$i]['value']}}" @if($options[$i]['value']==$s->$kolom) selected @endif>{{$options[$i]['text']}}</option>
				@endfor
			</select>
		</td>
		@endfor
	</tr>
	@endforeach
	@endif
</table>