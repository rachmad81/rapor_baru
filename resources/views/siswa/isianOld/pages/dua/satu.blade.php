<table border="1" style="width: 100%;border-collapse: collapse;">
	<tr>
		<th>No</th>
		<th>Nama</th>
		@if($kd->count()!=0)
		@foreach($kd as $k=>$v)
		<th onclick="nama_kolom('bg-maroon','KD {{$k+1}}','{{$v->kd_isi}}')">KD {{($k+1)}}</th>
		@endforeach
		@else
		<th>KD</th>
		@endif
	</tr>
	<tr>
		<td>1</td>
		<td>{{Session::get('nama')}}</td>
		@if($kd->count()!=0)
		@foreach($kd as $k=>$v)
		<?php $kolom = 'praktek_'.($k+1);?>
		<td><input type="number" name="kd1" value="{{$mengajar->$kolom}}"></td>
		@endforeach
		@else
		<td>..:: KD tidak disetting ::..</td>
		@endif
	</tr>
</table>