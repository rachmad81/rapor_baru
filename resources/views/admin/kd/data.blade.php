@if($ki3->count()!=0)
@foreach($ki3 as $k=>$v)
<tr>
	@if($k==0)
	<td rowspan="{{$ki3->count()}}">KI-3</td>
	@else
	@endif
	<td>{{($k+1)}}</td>
	<td><input type="text" name="uraian_k3{{$k}}" value="{{$v->isi}}" class="form-control"></td>
	<td>
		<a href="javascript:void(0)" class="btn btn-sm btn-warning" onclick="update('{{$v->id_kd}}','uraian_k3{{$k}}','{{$kelas}}','3')">Update</a>
		{{-- <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="hapus('{{$v->kd_id}}','{{$kelas}}','3')">Hapus</a> --}}
	</td>
</tr>
@endforeach
@endif
<tr>
	<td>KI-3</td>
	<td></td>
	<td><input type="text" name="uraian_k3" class="form-control"></td>
	<td>
		<a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="simpan('uraian_k3','3','{{$kelas}}')">Simpan</a>
	</td>
</tr>
<tr>
	<td colspan="4">&nbsp;</td>
</tr>
@if($ki4->count()!=0)
@foreach($ki4 as $k=>$v)
<tr>
	@if($k==0)
	<td rowspan="{{$ki4->count()}}">KI-4</td>
	@else
	@endif
	<td>{{($k+1)}}</td>
	<td><input type="text" name="uraian_k4{{$k}}" value="{{$v->isi}}" class="form-control"></td>
	<td>
		<a href="javascript:void(0)" class="btn btn-sm btn-warning" onclick="update('{{$v->id_kd}}','uraian_k4{{$k}}','{{$kelas}}','4')">Update</a>
		{{-- <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="hapus('{{$v->kd_id}}','{{$kelas}}','4')">Hapus</a> --}}
	</td>
</tr>
@endforeach
@endif
<tr>
	<td>KI-4</td>
	<td></td>
	<td><input type="text" name="uraian_k4" class="form-control"></td>
	<td>
		<a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="simpan('uraian_k4','4','{{$kelas}}')">Simpan</a>
	</td>
</tr>