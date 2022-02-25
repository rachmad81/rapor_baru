<table border="1" style="width: 100%;border-collapse: collapse;">
	<tr>
		<th class="headcol">No</th>
		<th class="headcol">Nama</th>
		@if($kd->count()!=0)
		@foreach($kd as $k=>$v)
		<th onclick="nama_kolom('bg-lime','KD {{$k+1}}','{{$v->isi}}','nph_{{$k+1}}')">
			<i class="fa fa-comment"></i> KD {{($k+1)}}
			<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123 bg-dark" id="nph_{{$k+1}}">
				{{$v->isi}}
			</div>
		</th>
		@endforeach
		@else
		<th>KD</th>
		@endif
		<th>#</th>
	</tr>
	@if($siswa->count()!=0)
	@foreach($siswa as $k=>$s)
	<tr style="@if($k%2==0) background: #eee !important @endif">
		<td class="headcol">{{($k+1)}}</td>
		<td class="headcol" style="white-space: nowrap;">{!!$s->nama!!}</td>
		@if($kd->count()!=0)
		@if(count($s->nilai)!=0)
		@foreach($kd as $k=>$v)
		<td>
			<input type="hidden" name="id_kd_{{$s->id_siswa}}[]" value="{{$v->id_kd}}">
			<input type="number" name="nph_{{$s->id_siswa}}[]" value="{{$s->nilai[$k]}}">
		</td>
		@endforeach
		@else
		@foreach($kd as $k=>$v)
		<td>
			<input type="hidden" name="id_kd_{{$s->id_siswa}}[]" value="{{$v->id_kd}}">
			<input type="number" name="nph_{{$s->id_siswa}}[]" value="">
		</td>
		@endforeach
		@endif
		@else
		<td>..:: KD tidak disetting ::..</td>
		@endif
		<td><a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="simpan_nilai('{{$s->id_siswa}}','nph','3')">Simpan</a></td>
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