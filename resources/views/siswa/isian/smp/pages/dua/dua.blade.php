{{-- <table border="1" style="width: 100%;border-collapse: collapse;">
	<tr>
		<th>No</th>
		<th>Nama</th>
		@if($kd->count()!=0)
		@foreach($kd as $k=>$v)
		<th style="cursor: pointer;" onclick="nama_kolom('bg-lime','KD {{$k+1}}','{{$v->kd_isi}}','uh_{{$k+1}}')">
			<i class="fa fa-comment"></i> KD {{($k+1)}}
			<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123" id="uh_{{$k+1}}">
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
		<?php $kolom = 'uh_'.($k+1);?>
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
			<?php $kolom = 'uh_'.($k+1);?>
			<div class="col-lg-3 col-md-3 col-sm-12 xol-xs-12">
				<div class="card card-info">
					<div class="card-header" style="cursor: pointer;" onclick="nama_kolom('bg-lime','KD {{$k+1}}','{{$v->isi}}','uh_{{$k+1}}')">
						<i class="fa fa-comment"></i> KD {{($k+1)}}
						<div style="display: none;border: 1px solid black;margin: 5px;padding: 5px;font-size: 12px;" class="tooltip123 bg-dark" id="uh_{{$k+1}}">
							{{$v->isi}}
						</div>
					</div>
					<div class="card-body" style="background: #ccc">
						<input type="hidden" class="form-control" name="id_kd[]" value="{{$v->id_kd}}" @if($mengajar->is_kunci==true) readonly @endif>
						@php
						$nilai = DB::connection($conn)->table($schema.'.nilai_mapel as nm')
						->join($schema.'.detail_nilai_mapel as dm','dm.nilai_mapel_id','nm.id_nilai_mapel')
						->whereRaw("mapel_id='".Session::get('id_mapel')."' AND anggota_rombel_id='".Session::get('id_anggota_rombel')."' AND kd_id='$v->id_kd'")->first();						
						@endphp
						@if(!empty($nilai))
						@if($nilai->kd_id==$v->id_kd)
						<input type="number" class="form-control" name="uh[]" value="{{$nilai->portofolio}}" @if($nilai->is_kunci==true) readonly @endif>
						@else
						<input type="number" class="form-control" name="uh[]" value="" @if($nilai->is_kunci==true) readonly @endif>
						@endif
						@else
						<input type="number" class="form-control" name="uh[]" value="" @if($nilai->is_kunci==true) readonly @endif>
						@endif
					</div>
				</div>
			</div>
			@endforeach
			@else
			@endif
		</div>
	</div>

	<div style="width: 100%">
		<a style="width: 100%" href="javascript:void(0)" class="btn btn-primary btn-lg" @if($mengajar->is_kunci==true) onclick="alert('Pengisian nilai sudah dikunci')" @else onclick="simpankd('uh','portofolio')" @endif>Simpan</a>
	</div>
</div>