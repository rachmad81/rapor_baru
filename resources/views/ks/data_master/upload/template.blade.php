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
					<td>
						Mapel / Guru
					</td>
					<td>
						: <a href="javascript:void(0)" onclick="ExportToExcel('excel_nilai')">{{$mengajar->nama_mapel}} - {{$mengajar->nama_pengajar}}</a>
					</td>
				</tr>
			</table>
		</div>
		<div>
			<table style="border-collapse: collapse;width: 100%" border="1">
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2">Nama</th>
					<th rowspan="2">ID Siswa</th>
					<th rowspan="2">ID Anggota Rombel</th>
					<th rowspan="2">Kelas</th>
					<th rowspan="2">Rombel</th>
					<th rowspan="2">Mapel_id</th>
					@if($mapel_id=='spiritual')
					<th colspan="4">Penilaian</th>
					<th rowspan="2">Catatan Siswa</th>
					<th colspan="3">Absensi</th>
					<th colspan="6">Kesehatan</th>
					@else
					<th colspan="6">Penilaian</th>
					@endif
				</tr>
				<tr>
					@if($mapel_id=='spiritual')
					<th>Predikat KI1</th>
					<th>Deskripsi KI1</th>
					<th>Predikat KI2</th>
					<th>Deskripsi KI2</th>
					<th>Sakit</th>
					<th>Izin</th>
					<th>Tanpa keterangan</th>
					<th>tinggi badan</th>
					<th>berat badan</th>
					<th>pendengaran</th>
					<th>penglihatan</th>
					<th>gigi</th>
					<th>lainnya</th>
					@else
					<th>Nilai KI3</th>
					<th>Predikat KI3</th>
					<th>Deskripsi KI3</th>
					<th>Nilai KI4</th>
					<th>Predikat KI4</th>
					<th>Deskripsi KI4</th>
					@endif
				</tr>
				@if($siswa->count()!=0)
				@foreach($siswa as $k=>$s)
				<tr>
					<td>{{($k+1)}}</td>
					<td>{{$s->nama}}</td>
					<td>{{$s->id_siswa}}</td>
					<td>{{$s->id_anggota_rombel}}</td>
					<td>{{$s->kelas}}</td>
					<td>{{$s->rombel}}</td>
					<td>{{$mapel_id}}</td>
					@if($mapel_id=='spiritual')
					<td>{{$s->predikat_ki1}}</td>
					<td>{{$s->deskripsi_ki1}}</td>
					<td>{{$s->predikat_ki2}}</td>
					<td>{{$s->deskripsi_ki2}}</td>
					<td>{{$s->sakit}}</td>
					<td>{{$s->izin}}</td>
					<td>{{$s->tanpa_keterangan}}</td>
					<td>{{$s->tinggi_badan}}</td>
					<td>{{$s->berat_badan}}</td>
					<td>{{$s->penglihatan}}</td>
					<td>{{$s->pendengaran}}</td>
					<td>{{$s->gizi}}</td>
					<td>{{$s->lainnya}}</td>
					@else
					<td>{{$s->nilai_ki3}}</td>
					<td>{{$s->predikat_ki3}}</td>
					<td>{{$s->deskripsi_ki3}}</td>
					<td>{{$s->nilai_ki4}}</td>
					<td>{{$s->predikat_ki4}}</td>
					<td>{{$s->deskripsi_ki4}}</td>
					@endif
				</tr>
				@endforeach
				@endif
			</table>
		</div>
		<br>
		<div>
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