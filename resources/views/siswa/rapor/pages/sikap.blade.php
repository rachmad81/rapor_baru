<style type="text/css">
	
</style>
<h1 class="sikap_title" style="text-align: center;font-weight: bold;">RAPOR PESERTA DIDIK DAN PROFIL PESERTA DIDIK</h1>
<table class="label_rapor" style="width: 80%;">
	<tr>
		<td>Nama</td>
		<td>: {!!$siswa->nama_siswa!!}</td>
		<td>Kelas</td>
		<td>: {{$siswa->kelas}}.{{$siswa->rombel}}</td>
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
		<th colspan="2" style="text-align: center;">Deskripsi</th>
	</tr>
	<tr>
		<td style="width: 20%">1. Sikap Spiritual</td>
		<td>{{(!empty($sikap)) ? $sikap->deskripsi_ki1 : ''}}</td>
	</tr>
	<tr>
		<td>2. Sikap Sosial</td>
		<td>{{(!empty($sikap)) ? $sikap->deskripsi_ki2 : ''}}</td>
	</tr>
</table>
<?php
$nomor = 1;
?>
<label class="label_rapor">B. Pengetahuan dan Keterampilan</label><br>
<label class="label_rapor">KKM Satuan Pendidikan : {{$siswa->kkm}}</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;">
		<th rowspan="2" style="width: 5%">No</th>
		<th rowspan="2" style="text-align: center;">Mata Pelajaran</th>
		<th colspan="3" style="text-align: center;">Pengetahuan</th>
		<th colspan="3" style="text-align: center;">Keterampilan</th>
	</tr>
	<tr style="background: #ddd;">
		<th style="text-align: center;">Angka</th>
		<th style="text-align: center;">Predikat</th>
		<th style="text-align: center;">Deskripsi</th>
		<th style="text-align: center;">Angka</th>
		<th style="text-align: center;">Predikat</th>
		<th style="text-align: center;">Deskripsi</th>
	</tr>
	<tr>
		<th style="text-align: left;width: 5%">A.</th>
		<th style="text-align: left;" colspan="7">Kelompok A</th>
	</tr>
	@if($nilaia->count()!=0)
	@foreach($nilaia as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}<br><i style="font-size: 10px">{{$v->guru_mengajar}}</i></td>
		<td style="text-align: center">{{number_format($v->nilai_ki3,0,'','')}}</td>
		<td style="text-align: center">{{$v->predikat_ki3}}</td>
		<td>{{$v->deskripsi_ki3}}</td>
		<td style="text-align: center">{{number_format($v->nilai_ki4,0,'','')}}</td>
		<td style="text-align: center">{{$v->predikat_ki4}}</td>
		<td>{{$v->deskripsi_ki4}}</td>
	</tr>
	<?php $nomor++;?>
	@endforeach
	@endif
	<tr>
		<th style="text-align: left;width: 5%">B.</th>
		<th style="text-align: left;" colspan="7">Kelompok B</th>
	</tr>
	@if($nilaib->count()!=0)
	@foreach($nilaib as $k=>$v)
	<tr>
		<td>{{$nomor}}</td>
		<td>{{$v->mapel}}<br><i style="font-size: 10px">{{$v->guru_mengajar}}</i></td>
		<td style="text-align: center">{{number_format($v->nilai_ki3,0,'','')}}</td>
		<td style="text-align: center">{{$v->predikat_ki3}}</td>
		<td>{{$v->deskripsi_ki3}}</td>
		<td style="text-align: center">{{number_format($v->nilai_ki4,0,'','')}}</td>
		<td style="text-align: center">{{$v->predikat_ki4}}</td>
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
<label class="label_rapor">D. Saran-Saran</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr>
		<td style="height: 100px;padding: 10px;vertical-align: top;">
			{!!$siswa->nama_siswa!!} @if(!empty($sikap)) {{$sikap->catatan_siswa}} @endif
		</td>
	</tr>
</table>
<label class="label_rapor">E. Tinggi dan Berat Badan</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;text-align: center;">
		<th rowspan="2">No</th>
		<th rowspan="2">Aspek yang dinilai</th>
		<th>Semester</th>
		<th>Semester</th>
	</tr>
	<tr style="background: #ddd;text-align: center;">
		<th>1</th>
		<th>2</th>
	</tr>
	<tr>
		<td>1</td>
		<td>Tinggi</td>
		<td>{{(!empty($kesehatan1)) ? $kesehatan1->tinggi_badan : ''}} Cm</td>
		<td>{{(!empty($kesehatan2)) ? $kesehatan2->tinggi_badan : ''}} Cm</td>
	</tr>
	<tr>
		<td>2</td>
		<td>Berat Badan</td>
		<td>{{(!empty($kesehatan1)) ? $kesehatan1->berat_badan : ''}} Kg</td>
		<td>{{(!empty($kesehatan2)) ? $kesehatan2->berat_badan : ''}} Kg</td>
	</tr>
</table>
<label class="label_rapor">F. Kondisi Kesehatan</label>
<table class="kontent_rapor" style="width: 100%;border-collapse: collapse;" border="1">
	<tr style="background: #ddd;text-align: center;">
		<th rowspan="2">No</th>
		<th rowspan="2">Aspek yang dinilai</th>
		<th>Semester</th>
		<th>Semester</th>
	</tr>
	<tr style="background: #ddd;text-align: center;">
		<th>1</th>
		<th>2</th>
	</tr>
	<tr>
		<td>1</td>
		<td>Pendengaran</td>
		<td>@if(!empty($kesehatan1)) {{$kesehatan1->pendengaran}} @endif</td>
		<td>@if(!empty($kesehatan2)) {{$kesehatan2->pendengaran}} @endif</td>
	</tr>
	<tr>
		<td>2</td>
		<td>Penglihatan</td>
		<td>@if(!empty($kesehatan1)) {{$kesehatan1->penglihatan}} @endif</td>
		<td>@if(!empty($kesehatan2)) {{$kesehatan2->penglihatan}} @endif</td>
	</tr>
	<tr>
		<td>3</td>
		<td>Gigi</td>
		<td>@if(!empty($kesehatan1)) {{$kesehatan1->gizi}} @endif</td>
		<td>@if(!empty($kesehatan2)) {{$kesehatan2->gizi}} @endif</td>
	</tr>
	<tr>
		<td>4</td>
		<td>Lainnya</td>
		<td>@if(!empty($kesehatan1)) {{$kesehatan1->lainnya}} @endif</td>
		<td>@if(!empty($kesehatan2)) {{$kesehatan2->lainnya}} @endif</td>
	</tr>
</table>
<label class="label_rapor">G. Catatan Prestasi</label>
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
<label class="label_rapor">H. Ketidakhadiran</label>
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
								<td>: @if(!empty($sikap)) {{$sikap->sakit}} @endif</td>
								<td>Hari</td>
							</tr>
							<tr>
								<td>Izin</td>
								<td>: @if(!empty($sikap)) {{$sikap->izin}} @endif</td>
								<td>Hari</td>
							</tr>
							<tr>
								<td>Tanpa Keterangan</td>
								<td>: @if(!empty($sikap)) {{$sikap->tanpa_keterangan}} @endif</td>
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
			@if($semester=='Genap')
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
@include('siswa.rapor.umum.ttd')