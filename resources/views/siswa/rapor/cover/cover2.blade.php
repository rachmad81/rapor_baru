<div style="width: 100%;">
	<div class="sikap_title" style="font-weight:bold;text-align: center">	
		KETERANGAN TENTANG DIRI PESERTA DIDIK
	</div>
	
	<div class="kontent_rapor" style="width: 100%;">
		<table style="width: 70%;margin: auto">
			<tr style="vertical-align: top;">
				<td style="width: 2%">1</td>
				<td style="width: 49%">Nama Peserta Didik (Lengkap)</td>
				<td style="width: 49%">: {!!$siswa->nama_siswa!!}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>2</td>
				<td>Nomor Induk / NISN</td>
				<td>: {{$siswa->nisn}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>3</td>
				<td>Tempat Tanggal Lahir</td>
				<td>: {{$siswa->tempat_lahir}}, {{date('d-m-Y',strtotime($siswa->tgl_lahir))}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>4</td>
				<td>Jenis Kelamin</td>
				<td>: {{($siswa->kelamin=='L') ? 'LAKI-LAKI' : 'PEREMPUAN'}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>5</td>
				<td>Agama</td>
				<td>: {{strtoupper($siswa->aga_nama)}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>6</td>
				<td>Status Dalam Keluarga</td>
				<td>: {{$siswa->status_anak}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>7</td>
				<td>Anak Ke</td>
				<td>: {{$siswa->anakke}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>8</td>
				<td>Alamat Peserta Didik</td>
				<td>: {{$siswa->alamat_siswa}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>9</td>
				<td>Nomor Telepon Rumah</td>
				<td>: {{$siswa->telpon}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>10</td>
				<td>Sekolah Asal</td>
				<td>: {{$siswa->asal_sekolah}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>11</td>
				<td>Diterima Di Sekolah Ini</td>
				<td>: </td>
			</tr>
			<tr style="vertical-align: top;">
				<td></td>
				<td>Kelas</td>
				<td>: 1 (Satu)</td>
			</tr>
			<tr style="vertical-align: top;">
				<td></td>
				<td>Pada Tanggal</td>
				<td>: 15 Juli {{($siswa->kelas=='1' || $siswa->kelas=='7') ? date('Y',strtotime($siswa->tgl_setting_awal)) : date('Y',strtotime($siswa->tgl_setting_awal))-3 ;}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>12</td>
				<td>Nama Orang Tua</td>
				<td>:</td>
			</tr>
			<tr style="vertical-align: top;">
				<td></td>
				<td>Ayah</td>
				<td>: {{$siswa->ayah}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td></td>
				<td>Ibu</td>
				<td>: {{$siswa->ibu}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td></td>
				<td>Alamat Orang Tua</td>
				<td>: {{$siswa->alamat_rumah}}<br>RT {{$siswa->rt}}, RW {{$siswa->rw}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td></td>
				<td>Nomor Telepon Rumah</td>
				<td>: {{$siswa->telpon}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>13</td>
				<td>Pekerjaan Orang Tua</td>
				<td>: {{$siswa->nisn}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td></td>
				<td>Ayah</td>
				<td>: {{$siswa->pekerjaan_ayah}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td></td>
				<td>Ibu</td>
				<td>: {{$siswa->pekerjaan_ibu}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td>14</td>
				<td>Nama Wali Peserta Didik</td>
				<td>: </td>
			</tr>
			<tr style="vertical-align: top;">
				<td>15</td>
				<td>Alamat Wali Peserta Didik</td>
				<td>: {{$siswa->alamat_rumah}}</td>
			</tr>
			<tr style="vertical-align: top;">
				<td></td>
				<td>Nomor Telepon Rumah</td>
				<td>: </td>
			</tr>
			<tr style="vertical-align: top;">
				<td>16</td>
				<td>Pekerjaan Wali Peserta Didik</td>
				<td>: {{$siswa->pekerjaan_wali}}</td>
			</tr>
		</table>
	</div>
	<div style="width: 100%">
		<table style="width: 100%">
			<tr style="text-align: center;vertical-align: top;">
				<td style="width: 40%">&nbsp;</td>
				<td>
					<div style="width: 2cm;height: 3cm; border: 1px solid black">
						@if(file_exists($foto))
						<img src="{{$foto}}" style="width: 2cm;height: 3cm;" alt="User Image">
						@else
						<div style="height: 3cm;width: 2cm;text-align: center;padding-top: 8mm;">
							Foto Ukuran 2x3 cm
						</div>
						@endif
					</div>
				</td>
				<td>
					Surabaya,
					<?php
					if(!empty($rapor_semester)){
						if($siswa->kelas=='6'){
							echo '15 Desember '.date('Y',strtotime($siswa->tgl_setting_akhir));
						}else{
							echo App\Http\Libraries\Convert::tgl_indo(date('Y-m-d',strtotime($siswa->tgl_setting_akhir)));
						}
					}else{
						echo '15 Desember '.date('Y',strtotime($siswa->tgl_setting_akhir));
					}
				?>
				<br>
				Kepala Sekolah
				<br>
				<br>
				<br>
				<br>
				<u>{{$ks->gelar_depan}} {{$ks->nama_ks}} {{$ks->gelar_belakang}}</u><br>
				{{$ks->nip}} 
			</td>
		</tr>
	</table>
</div>
<div style="text-align:right">
	{!! $qrcode !!}<br>
	{!!$siswa->nama_siswa!!}
</div>
</div>