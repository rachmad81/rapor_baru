<table border="1" style="width: 100%;border-collapse: collapse;">
	<tr>
		<th class="headcol">No</th>
		<th class="headcol">Nama</th>
		<th>UTS</th>
		<th>UAS</th>
		<th>#</th>
	</tr>
	@if($siswa->count()!=0)
	@foreach($siswa as $k=>$s)
	<tr style="@if($k%2==0) background: #eee !important @endif">
		<td class="headcol">{{($k+1)}}</td>
		<td class="headcol" style="white-space: nowrap;">{!!$s->nama!!}</td>
		<td>
			<input type="number" name="uts_{{$s->id_siswa}}" value="{{$s->uts}}">
		</td>
		<td>
			<input type="number" name="uas_{{$s->id_siswa}}" value="{{$s->uas}}">
		</td>
		<td><a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="simpan_uts('{{$s->id_siswa}}','nph','3')">Simpan</a></td>
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