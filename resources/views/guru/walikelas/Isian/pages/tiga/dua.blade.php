<table border="1" style="width: 100%;border-collapse: collapse;">
	<tr>
		<th>No</th>
		<th>Nama</th>
		@if($kd->count()!=0)
		@foreach($kd as $k=>$v)
		<th onclick="nama_kolom('bg-lime','KD {{$k+1}}','{{$v->isi}}','uh_{{$k+1}}')">
			<i class="fa fa-comment"></i> KD {{($k+1)}}
			<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123 bg-dark" id="uh_{{$k+1}}">
				{{$v->isi}}
			</div>
		</th>
		@endforeach
		@else
		<th>KD</th>
		@endif
	</tr>
	@if($siswa->count()!=0)
	@foreach($siswa as $k=>$s)
	<tr style="@if($k%2==0) background: #eee @endif">
		<td>{{($k+1)}}</td>
		<td style="white-space: nowrap;">{!!$s->nama!!}</td>
		@if($kd->count()!=0)
		@foreach($kd as $k=>$v)
		<td><input type="number" name="kd1"></td>
		@endforeach
		@else
		<td>..:: KD tidak disetting ::..</td>
		@endif
	</tr>
	@endforeach
	@else
	<tr>
		<td colspan="6" style="text-align: center">
			-- Data siswa belum di <i>Generate</i> --
		</td>
	</tr>
	@endif
</table>