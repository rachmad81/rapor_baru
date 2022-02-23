<style type="text/css">
	
</style>
<h1 class="sikap_title" style="text-align: center;font-weight: bold;">RAPOR PESERTA DIDIK DAN PROFIL PESERTA DIDIK</h1>
<table class="label_rapor" style="width: 80%;">
	<tr>
		<td>Nama</td>
		<td>: {!!$siswa->nama_siswa!!}</td>
		<td>Kelas</td>
		<td>: {{$kelas->kelas}}.{{$kelas->rombel}}</td>
	</tr>
	<tr>
		<td>NISN/NIS</td>
		<td>: {{$siswa->nisn}}/{{$siswa->nis}}</td>
		<td>Semester</td>
		<td>: {{$semester}}</td>
	</tr>
	<tr>
		<td>Nama Sekolah</td>
		<td>: {{$siswa->nama_sekolah}}</td>
		<td>Tahun Pelajaran</td>
		<td>: {{$tahun_ajaran}}</td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td>: {{$siswa->alamat_sekolah}}</td>
		<td></td>
		<td></td>
	</tr>
</table>

<label class="label_rapor">A. Sikap</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<th style="text-align: center;">Aspek</th>
		<th style="text-align: center;">Predikat</th>
		<th style="text-align: center;">Deskripsi</th>
	</tr>
	<tr>
		<td style="width: 20%">Spiritual</td>
		<td>{{(!empty($sikap)) ? $sikap['huruf_ki1'] : ''}}</td>
		<td>{{(!empty($sikap)) ? $sikap['catatan_1'] : ''}}</td>
	</tr>
	<tr>
		<td>Sosial</td>
		<td>{{(!empty($sikap)) ? $sikap['huruf_ki2'] : ''}}</td>
		<td>{{(!empty($sikap)) ? $sikap['catatan_2'] : ''}}</td>
	</tr>
</table>
<?php
$nomor = 1;
?>
<label class="label_rapor">B. Pengetahuan dan Keterampilan</label><br>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;text-align: center;">
		<th rowspan="2">No</th>
		<th rowspan="2">Mata Pelajaran</th>
		<th colspan="4">Pengetahuan</th>
	</tr>
	<tr style="background: #ddd;text-align: center;">
		<th>KKM</th>
		<th>Angka</th>
		<th>Predikat</th>
		<th>Deskripsi</th>
	</tr>
	<?php $nomor = 1;?>
	@if($nilai_agama->count()!=0)
	@foreach($nilai_agama as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}</td>
		<td>{{$v->kkm}}</td>
		<td>{{$v->nilai_ki3}}</td>
		<td>{{$v->predikat_ki3}}</td>
		<td>{{$v->deskripsi_ki3}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
	<tr>
		<th style="text-align: left;width: 5%">A.</th>
		<th style="text-align: left;" colspan="5">Kelompok A</th>
	</tr>
	@if($nilaia->count()!=0)
	@foreach($nilaia as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}</td>
		<td>{{$v->kkm}}</td>
		<td>{{$v->nilai_ki3}}</td>
		<td>{{$v->predikat_ki3}}</td>
		<td>{{$v->deskripsi_ki3}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
	<tr>
		<th style="text-align: left;width: 5%">B.</th>
		<th style="text-align: left;" colspBn="5">Kelompok B</th>
	</tr>
	@if($nilaib->count()!=0)
	@foreach($nilaib as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}</td>
		<td>{{$v->kkm}}</td>
		<td>{{$v->nilai_ki3}}</td>
		<td>{{$v->predikat_ki3}}</td>
		<td>{{$v->deskripsi_ki3}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
</table><br>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;text-align: center;">
		<th rowspan="2">No</th>
		<th rowspan="2">Mata Pelajaran</th>
		<th colspan="4">Keterampilan</th>
	</tr>
	<tr style="background: #ddd;text-align: center;">
		<th>KKM</th>
		<th>Angka</th>
		<th>Predikat</th>
		<th>Deskripsi</th>
	</tr>
	<?php $nomor = 1;?>
	@if($nilai_agama->count()!=0)
	@foreach($nilai_agama as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}</td>
		<td>{{$v->kkm}}</td>
		<td>{{$v->nilai_ki4}}</td>
		<td>{{$v->predikat_ki4}}</td>
		<td>{{$v->deskripsi_ki4}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
	<tr>
		<th style="width: 5%;text-align: left;">A.</th>
		<th style="text-align: left" colspan="5">Kelompok A</th>
	</tr>
	@if($nilaia->count()!=0)
	@foreach($nilaia as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}</td>
		<td>{{$v->kkm}}</td>
		<td>{{$v->nilai_ki4}}</td>
		<td>{{$v->predikat_ki4}}</td>
		<td>{{$v->deskripsi_ki4}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
	<tr>
		<th style="width: 5%;text-align: left;">B.</th>
		<th style="text-align: left" colspan="5">Kelompok B</th>
	</tr>
	@if($nilaib->count()!=0)
	@foreach($nilaib as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}</td>
		<td>{{$v->kkm}}</td>
		<td>{{$v->nilai_ki4}}</td>
		<td>{{$v->predikat_ki4}}</td>
		<td>{{$v->deskripsi_ki4}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
</table>
<label class="label_rapor">C. Ekstrakurikuler</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<th style="text-align: center;">Kegiatan Ektrakurikuler</th>
		<th style="text-align: center;">Keterangan</th>
	</tr>
	<tr>
		<td style="width: 50%">1 @if(!empty($ekskul)) {{$ekskul->ekskul_1}} @endif</td>
		<td style="text-align: center;">@if(!empty($ekskul)) {{$ekskul->nilai_ekskul_1}} @endif</td>
	</tr>
	<tr>
		<td>2 @if(!empty($ekskul)) {{$ekskul->ekskul_2}} @endif</td>
		<td style="text-align: center;">@if(!empty($ekskul)) {{$ekskul->nilai_ekskul_2}} @endif</td>
	</tr>
	<tr>
		<td>3 @if(!empty($ekskul)) {{$ekskul->ekskul_3}} @endif</td>
		<td style="text-align: center;">@if(!empty($ekskul)) {{$ekskul->nilai_ekskul_3}} @endif</td>
	</tr>
</table>
<label class="label_rapor">D. Ketidakhadiran</label>
<table class="kontent_rapor" style="width: 100%;">
	<tr>
		<td style="width: 30%">
			<table style="width: 100%;border-collapse: collapse;" border="1">
				<tr style="background: #ddd;">
					<th style="text-align: center;">Ketidakhadiran</th>
				</tr>
				<tr>
					<td>
						<table style="width: 100%">
							<tr>
								<td>Sakit</td>
								<td>: @if(!empty($ekskul)) {{$ekskul->sakit}} @endif</td>
								<td>Hari</td>
							</tr>
							<tr>
								<td>Izin</td>
								<td>: @if(!empty($ekskul)) {{$ekskul->ijin}} @endif</td>
								<td>Hari</td>
							</tr>
							<tr>
								<td>Tanpa Keterangan</td>
								<td>: @if(!empty($ekskul)) {{$ekskul->tanpa_keterangan}} @endif</td>
								<td>Hari</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td style="width: 30%">
			&nbsp;
		</td>
		<td style="vertical-align: top">
			@if($semester=='II (Dua)')
			<table style="width: 100%;border-collapse: collapse;" border="1">
				<tr style="padding: 5px;">
					<td>
						Keputusan:<br>
						{!! $kenaikan !!}
					</td>
				</tr>
			</table>
			@endif
		</td>
	</tr>
</table>
<label class="label_rapor">E. Catatan Prestasi</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;text-align: center;">
		<th>No</th>
		<th>Jenis Prestasi</th>
		<th>Keterangan</th>
	</tr>
	@if($prestasi->count()!=0)
	<?php $batas = ($prestasi->count()>3) ? $prestasi->count() : 3;?>
	@for($i=0;$i<$batas;$i++)
	<tr>
		<td>{{($i+1)}}</td>
		<td>
			@if(isset($prestasi[$i]))
			{{$prestasi[$i]->kategori}}
			@endif
		</td>
		<td>
			@if(isset($prestasi[$i]))
			{{$prestasi[$i]->lomba}}, <b>Tingkat</b> {{$prestasi[$i]->tingkat}}, <b>Peringkat</b> {{$prestasi[$i]->peringkat}}, <b>Tahun</b> {{$prestasi[$i]->tahun}}
			@endif
		</td>
	</tr>
	@endfor
	@else
	@for($i=1;$i<=3;$i++)
	<tr>
		<td>{{$i}}</td>
		<td></td>
		<td></td>
	</tr>
	@endfor
	@endif
</table>
<label class="label_rapor">D. Catatan Wali Kelas</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr>
		<td style="height: 100px;padding: 10px;vertical-align: top;">
			{!!$siswa->nama_siswa!!} @if(!empty($ekskul)) {{$ekskul->catatan}} @endif
		</td>
	</tr>
</table>
<label class="label_rapor">E. Tanggapan Orang Tua/ Wali</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr>
		<td style="height: 100px;padding: 10px;vertical-align: top;">
			&nbsp;
		</td>
	</tr>
</table>
@include('siswa.rapor.umum.ttd')