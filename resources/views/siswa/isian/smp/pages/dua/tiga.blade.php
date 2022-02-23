{{-- <table border="1" style="width: 100%;border-collapse: collapse;">
	<tr>
		<th>No</th>
		<th>Nama</th>
		@if($kd->count()!=0)
		@foreach($kd as $k=>$v)
		<th style="cursor: pointer;" onclick="nama_kolom('bg-lime','KD {{$k+1}}','{{$v->kd_isi}}','proyek_{{$k+1}}')">
			<i class="fa fa-comment"></i> KD {{($k+1)}}
			<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123" id="proyek_{{$k+1}}">
				{{$v->kd_isi}}
			</div>
		</th>
		@endforeach
		<th>#</th>
		@else
		<th>KD</th>
		@endif
	</tr>
	<tr>
		<td>1</td>
		<td style="white-space: nowrap;">{{Session::get('nama')}}</td>
		@if($kd->count()!=0)
		@foreach($kd as $k=>$v)
		<?php $kolom = 'proyek_'.($k+1);?>
		<td><input type="number" name="kd1" value="{{$mengajar->$kolom}}"></td>
		@endforeach
		<th><a href="javascript:void(0)" class="btn btn-sm btn-primary">Simpan</a></th>
		@else
		<td>..:: KD tidak disetting ::..</td>
		@endif
	</tr>
</table> --}}

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="row">
			@if($kd->count()!=0)
			@foreach($kd as $k=>$v)
			<?php $kolom = 'proyek_'.($k+1);?>
			<div class="col-lg-3 col-md-3 col-sm-12 xol-xs-12">
				<div class="card card-info">
					<div class="card-header" style="cursor: pointer;" onclick="nama_kolom('bg-lime','KD {{$k+1}}','{{$v->kd_isi}}','proyek_{{$k+1}}')">
						<i class="fa fa-comment"></i> KD {{($k+1)}}
						<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123 bg-dark" id="proyek_{{$k+1}}">
							{{$v->kd_isi}}
						</div>
					</div>
					<div class="card-body" style="background: #ccc">
						<input type="number" class="form-control" name="proyek[]" value="{{(!empty($mengajar)) ? $mengajar->$kolom : ''}}" @if($mengajar->islock_uts==true) readonly @endif>
					</div>
				</div>
			</div>
			@endforeach
			@else
			@endif
		</div>
	</div>

	<div style="width: 100%">
		<a style="width: 100%" href="javascript:void(0)" class="btn btn-primary btn-lg" @if($mengajar->islock_uts==true) onclick="alert('Pengisian nilai sudah dikunci')" @else onclick="simpankd('proyek')" @endif>Simpan</a>
	</div>
</div>