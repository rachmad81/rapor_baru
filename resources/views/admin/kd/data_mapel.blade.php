<div class="panel-group" id="accordion">
	<div class="card card-warning">
		<a class="card-header" data-toggle="collapse" data-parent="#accordion" href="javascript:void(0)" onclick="acordion('1')" style="text-decoration: none;color: black">
			<div>
				<h4 class="card-title">
					AGAMA ISLAM
				</h4>
			</div>
		</a>
		<div id="collapse1" class="panel-collapse collapse">
			<div class="card-body">
				<div class="row">
					@php
					if(count($mapel)!=0){
						$agama = $mapel->where($kolom_kategori,'AGAMA ISLAM');
						foreach ($agama as $m) { 
							@endphp
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
							@php
						}
					}
					@endphp
				</div>
			</div>
		</div>
	</div>
	<div class="card card-success">
		<a class="card-header" data-toggle="collapse" data-parent="#accordion" href="javascript:void(0)" onclick="acordion('2')" style="text-decoration: none;color: black">
			<div>
				<h4 class="card-title">
					KELOMPOK A
				</h4>
			</div>
		</a>
		<div id="collapse2" class="panel-collapse collapse">
			<div class="card-body">
				<div class="row">
					@php
					if(count($mapel)!=0){
						$agama = $mapel->where($kolom_kategori,'KELOMPOK A');
						foreach ($agama as $m) { 
							@endphp
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
							@php
						}
					}
					@endphp
				</div>
			</div>
		</div>
	</div>
	<div class="card card-info">
		<a class="card-header" data-toggle="collapse" data-parent="#accordion" href="javascript:void(0)" onclick="acordion('3')" style="text-decoration: none;color: black">
			<div>
				<h4 class="card-title">
					KELOMPOK B
				</h4>
			</div>
		</a>
		<div id="collapse3" class="panel-collapse collapse">
			<div class="card-body">
				<div class="row">
					@php
					if(count($mapel)!=0){
						$agama = $mapel->where($kolom_kategori,'KELOMPOK B');
						foreach ($agama as $m) { 
							@endphp
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
							@php
						}
					}
					@endphp
				</div>
			</div>
		</div>
	</div>
</div>