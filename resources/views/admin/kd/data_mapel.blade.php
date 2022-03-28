<div class="row">
	@if($mapel->count()!=0)
	@foreach($mapel as $m)
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<a href="javascript:void(0)" onclick="form_setting('{{$m->mapel_id}}','{{$kelas}}')">
			<div class="callout callout-success m-1 p-1 bg-navy">
				{{$m->kategori_baru}}<br>
				{{$m->nama}}
				<div>
					KD = {{$m->kd}}
				</div>
			</div>
		</a>
	</div>
	@endforeach
	@endif
</div>