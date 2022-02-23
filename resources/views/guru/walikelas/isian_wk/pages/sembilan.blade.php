<div>
	<table style="width: 100%;border-collapse: collapse;" border="1">
		<tr>
			<th style="width: 5%">No</th>
			<th>Nama</th>
			<th>Status</th>
			<th style="width: 10%">Simpan</th>
		</tr>
		@if($siswa->count()!=0)
		@foreach($siswa as $k=>$s)
		<tr>
			<td>{{($k+1)}}</td>
			<td style="white-space: nowrap;">{!!$s->nama!!}</td>
			<td>
				<?php
				$selected = ['selected',''];
				if($s->kenaikan_kelas!=null){
					if($s->kenaikan_kelas=='false'){
					$selected = ['','selected'];
					}else{
					}
				}
				?>
				<select name="naik_{{$k}}" class="form-control">
					<option {{$selected[0]}} value="true">Naik Kelas</option>
					<option {{$selected[1]}} value="false">Tidak Naik Kelas</option>
				</select>
			</td>
			<td><a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="simpan_catatan('{{$s->id_siswa}}','{{$nama_schema}}','naik_{{$k}}','kenaikan_kelas')">Simpan</a></td>
		</tr>
		@endforeach
		@endif
	</table>
</div>