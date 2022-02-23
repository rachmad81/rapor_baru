<div>
	<table style="width: 100%;border-collapse: collapse;" border="1">
		<tr>
			<th class="headcol" style="width: 5%;">No</th>
			<th class="headcol">Nama</th>
			<th colspan="3">Ekstrakurikuler</th>
			<th style="width: 5%">#</th>
		</tr>
		@if($siswa->count()!=0)
		@foreach($siswa as $k=>$s)
		<?php
		$options = [
			[
				'value'=>'',
				'text'=>'- Pilih Nilai -'
			],
			[
				'value'=>'Baik Sekali',
				'text'=>'Baik Sekali'
			],
			[
				'value'=>'Baik',
				'text'=>'Baik'
			],
			[
				'value'=>'Cukup',
				'text'=>'Cukup'
			],
			[
				'value'=>'Kurang',
				'text'=>'Kurang'
			],
			[
				'value'=>'Sangat Kurang',
				'text'=>'Sangat Kurang'
			],
		];
		?>
		<tr>
			<td class="headcol">{{($k+1)}}</td>
			<td class="headcol" style="white-space: nowrap;">{!!$s->nama!!}</td>
			<td>
				<select name="ekskul_{{$k+1}}[]">
					@foreach($ekskul as $e)
					<option value="{{$e->nama_ekskul}}" @if($e->nama_ekskul==$s->ekskul_1) selected @endif>{{$e->nama_ekskul}}</option>
					@endforeach
				</select>
				<br>
				<select name="nilai_ekskul_{{$k+1}}[]">
					@for($i=0;$i<count($options);$i++)
					<option value="{{$options[$i]['value']}}" @if($options[$i]['value']==$s->nilai_ekskul_1) selected @endif>{{$options[$i]['text']}}</option>
					@endfor
				</select>
			</td>
			<td>
				<select name="ekskul_{{$k+1}}[]">
					@foreach($ekskul as $e)
					<option value="{{$e->nama_ekskul}}" @if($e->nama_ekskul==$s->ekskul_2) selected @endif>{{$e->nama_ekskul}}</option>
					@endforeach
				</select>
				<br>
				<select name="nilai_ekskul_{{$k+1}}[]">
					@for($i=0;$i<count($options);$i++)
					<option value="{{$options[$i]['value']}}" @if($options[$i]['value']==$s->nilai_ekskul_2) selected @endif>{{$options[$i]['text']}}</option>
					@endfor
				</select>
			</td>
			<td>
				<select name="ekskul_{{$k+1}}[]">
					@foreach($ekskul as $e)
					<option value="{{$e->nama_ekskul}}" @if($e->nama_ekskul==$s->ekskul_3) selected @endif>{{$e->nama_ekskul}}</option>
					@endforeach
				</select>
				<br>
				<select name="nilai_ekskul_{{$k+1}}[]">
					@for($i=0;$i<count($options);$i++)
					<option value="{{$options[$i]['value']}}" @if($options[$i]['value']==$s->nilai_ekskul_3) selected @endif>{{$options[$i]['text']}}</option>
					@endfor
				</select>
			</td>
			<td>
				<a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="simpan_ekskul('{{$s->id_siswa}}','{{$nama_schema}}','ekskul_{{$k+1}}','nilai_ekskul_{{$k+1}}')">Simpan</a>
			</td>
		</tr>
		@endforeach
		@endif
	</table>
</div>