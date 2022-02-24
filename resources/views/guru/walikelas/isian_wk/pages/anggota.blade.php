<div>
	<a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="generate_anggota()">Generate Anggota Rombel Kelas </a>
	<br>
	<center><h3>
		Siswa Rombongan Belajar 
		<br>Kelas {{Session::get('kelas_wk')}}.{{Session::get('rombel_wk')}}
		<br>Tahun ajaran {{Session::get('ta_wk')}}
		<br>{{Session::get('semester_wk')}}
	</h3></center>
	<br>
	<table style="width:100%">
		<tr>
			<td style="vertical-align: top;width: 50%">
				<table style="width: 100%;border-collapse: collapse;" border="1">
					<tr>
						<th>Nama</th>
						<th>Kelas</th>
						<th>Rombel</th>
					</tr>
					@if($siswa_ta->count()!=0)
					@foreach($siswa_ta as $st)
					<tr>
						<td>{{$st->nama}}</td>
						<td>{{$st->kelas}}</td>
						<td>{{$st->rombel}}</td>
					</tr>
					@endforeach
					@endif
				</table>
			</td>
			<td style="vertical-align: top;width: 50%">
				<table style="width: 100%;border-collapse: collapse;" border="1">
					<tr>
						<th>Nama</th>
						<th>Kelas</th>
						<th>Rombel</th>
					</tr>
					@if($siswa_skrg->count()!=0)
					@foreach($siswa_skrg as $st)
					<tr>
						<td>{{$st->nama}}</td>
						<td>{{$st->kelas}}</td>
						<td>{{$st->rombel}}</td>
					</tr>
					@endforeach
					@endif
				</table>
			</td>
		</tr>
	</table>
</div>