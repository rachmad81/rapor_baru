<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{$nama_file}}</title>
</head>
<body>
	<div id="excel_nilai">
		<div style="font-weight: bold;">
			<div style="text-align: center;">
				DATA NILAI SEMESTER {{($semester=='genap') ? 'II' : 'I'}}<br>
				TAHUN PELAJARAN {{$rombongan_belajar->nama_tahun_ajaran}}<br>
				@if(Session::get('jenjang')=='SD')
				SEKOLAH DASAR (SD)
				@else
				SEKOLAH MENENGAH PERTAMA (SMP)
				@endif
			</div>
		</div>
		<br>
		<div>
			<table>
				<tr>
					<td>Nama Sekolah</td>
					<td>: {{$sekolah->npsn}} - {{$sekolah->nama}}</td>
				</tr>
				<tr>
					<td>Alamat Sekolah</td>
					<td>: {{$sekolah->alamat}}</td>
				</tr>
				<tr>
					<td>Kelas / Wali</td>
					<td>: {{$rombongan_belajar->kelas}}.{{$rombongan_belajar->rombel}} - {{$rombongan_belajar->nama}}</td>
				</tr>
				<tr>
					<td>Mapel / Guru</td>
					<td>: <a href="javascript:void(0)" onclick="ExportToExcel('excel_nilai')">{{$mengajar->nama_mapel}} - {{$mengajar->nama_pengajar}}</a></td>
				</tr>
			</table>
		</div>
		<br>
		<div>
			<table border="1" style="border-collapse: collapse;">
				<tr>
					<th rowspan="4">No</th>
					<th rowspan="4">Nama</th>
					<th rowspan="4">mapel_id</th>
					<th rowspan="4">npsn</th>
					<th rowspan="4">kelas</th>
					<th rowspan="4">rombel</th>
					<th rowspan="4">id_siswa</th>
					<th rowspan="4">UTS</th>
					<th rowspan="4">UAS</th>
					<th colspan="{{$kd3->count()*2}}">Pengetahuan</th>
					<th colspan="{{$kd4->count()*4}}">Keterampilan</th>
				</tr>

				<tr>
					@foreach($kd3 as $k=>$k3)
					<th colspan="2">KD {{$k+1}}</th>
					@endforeach
					@foreach($kd4 as $k=>$k4)
					<th colspan="4">KD {{$k+1}}</th>
					@endforeach
				</tr>
				<tr>
					@foreach($kd3 as $k=>$k3)
					<th colspan="2">{{$k3->id_kd}}</th>
					@endforeach
					@foreach($kd4 as $k=>$k4)
					<th colspan="4">{{$k4->id_kd}}</th>
					@endforeach
				</tr>
				<tr>
					@foreach($kd3 as $k=>$k3)
					<th>Ulangan Harian</th>
					<th>Tugas</th>
					@endforeach
					@foreach($kd4 as $k=>$k4)
					<th>Praktek</th>
					<th>Portofolio</th>
					<th>Proyek</th>
					<th>Produk</th>
					@endforeach
				</tr>
				
				@foreach($siswa as $k=>$s)
				<tr>
					<td>{{$k+1}}</td>
					<td>{{$s->nama}}</td>
					<td>{{$mengajar->mapel_id}}</td>
					<td>{{$sekolah->npsn}}</td>
					<td>{{$rombongan_belajar->kelas}}</td>
					<td>{{$rombongan_belajar->rombel}}</td>
					<td>{{$s->id_siswa}}</td>
					<td>{{$s->uts}}</td>
					<td>{{$s->uas}}</td>


					@foreach($kd3 as $k=>$k3)
					@if(!empty($s->nilai_kd3[$k3->id_kd]))
					<td>{{$s->nilai_kd3[$k3->id_kd]->nph}}</td>
					<td>{{$s->nilai_kd3[$k3->id_kd]->npts}}</td>
					@else
					<td></td>
					<td></td>
					@endif
					@endforeach
					
					@foreach($kd4 as $k=>$k4)
					@if(!empty($s->nilai_kd4[$k4->id_kd]))
					<td>{{$s->nilai_kd4[$k4->id_kd]->keterampilan}}</td>
					<td>{{$s->nilai_kd4[$k4->id_kd]->portofolio}}</td>
					<td>{{$s->nilai_kd4[$k4->id_kd]->proyek}}</td>
					<td>{{$s->nilai_kd4[$k4->id_kd]->produk}}</td>
					@else
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					@endif
					@endforeach
				</tr>
				@endforeach
				
			</table>
		</div>
	</div>
</body>

<script type="text/javascript">
	function ExportToExcel(mytblId){
		var a = document.createElement('a');
        //getting data from our div that contains the HTML table
        var data_type = 'data:application/vnd.ms-excel';
        var table_div = document.getElementById(mytblId);
        var table_html = table_div.outerHTML.replace(/ /g, '%20');
        a.href = data_type + ', ' + table_html;
        //setting the file name

        var nama_file = '{{$nama_file}}.xls';

        a.download = nama_file;
        //triggering the function
        a.click();
    }
</script>
</html>