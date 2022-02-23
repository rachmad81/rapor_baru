
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="card card-warning card-outline">
		<div class="card-header">Walikelas</div>
		<div class="card-body">
			<div class="row">
				@if($walikelas->count()!=0)
				@foreach($walikelas as $w)
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<div class="small-box bg-lime">
						<div class="inner">
							<h3>{{$w->kelas}} - {{$w->rombel}}</h3>
							{{-- <p>World</p> --}}
						</div>
						<div class="icon">
							<i class="fa fa-building"></i>
						</div>
						<a href="{{route('isian_wk',['kelas'=>$w->kelas,'rombel'=>$w->rombel])}}" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				@endforeach
				@else
				<h3>Tidak disetting sebagai wali kelas</h3>
				@endif
			</div>
		</div>
	</div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="card card-danger card-outline">
		<div class="card-header">Mengajar</div>
		<div class="card-body">
			<div class="row">
				@if($mengajar->count()!=0)
				@foreach($mengajar as $w)
				<div class="col-lg-3 col-md-3">
					<div class="small-box bg-orange">
						<div class="inner">
							<h3>{{$w->kelas}} - {{$w->rombel}}</h3>
							<p>{{$w->nama_mapel}}</p>
						</div>
						<div class="icon">
							<i class="fa fa-building"></i>
						</div>
						<a href="{{route('isian_nilai',['kelas'=>$w->kelas,'rombel'=>$w->rombel,'mapel_id'=>$w->mapel_id])}}" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				@endforeach
				@else
				<h3>Tidak disetting sebagai guru mengajar</h3>
				@endif
			</div>
		</div>
	</div>
</div>