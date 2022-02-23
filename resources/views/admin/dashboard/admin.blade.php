@extends('master.index')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Dashboard</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Dashboard v1</li>
				</ol>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-3 col-6">
				<!-- small box -->
				<div class="small-box bg-info">
					<div class="inner">
						<h3>150</h3>

						<p>New Orders</p>
					</div>
					<div class="icon">
						<i class="ion ion-bag"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-3 col-6">
				<!-- small box -->
				<div class="small-box bg-maroon">
					<div class="inner">
						<h3>150</h3>

						<p>New Orders</p>
					</div>
					<div class="icon">
						<i class="ion ion-bag"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-3 col-6">
				<!-- small box -->
				<div class="small-box bg-orange">
					<div class="inner">
						<h3>150</h3>

						<p>New Orders</p>
					</div>
					<div class="icon">
						<i class="ion ion-bag"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-3 col-6">
				<!-- small box -->
				<div class="small-box bg-olive">
					<div class="inner">
						<h3>150</h3>

						<p>New Orders</p>
					</div>
					<div class="icon">
						<i class="ion ion-bag"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>
		</div>
		<!-- Main row -->
		<div class="row">
			<div class="col-lg 6 col-md-6"> 
				<div class="card card-warning card-outline">
					<div class="card-header">
						Proses Pengisian Rapor
					</div>
					<div class="card-body">
						<div id="accordion">
							<div class="card card-primary">
								<div class="card-header">
									<h4 class="card-title w-100">
										<a class="d-block w-100" data-toggle="collapse" href="#collapseOne" aria-expanded="true">
											Kepala Sekolah
										</a>
									</h4>
								</div>
								<div id="collapseOne" class="collapse show" data-parent="#accordion" style="">
									<div class="card-body">
										<div class="callout callout-success m-1 p-1">Melakukan setting Pengajaran (Guru mengajar apa di kelas apa)</div>
										<div class="callout callout-danger m-1 p-1">Melakukan setting Wali Kelas</div>
									</div>
								</div>
							</div>
							<div class="card card-danger">
								<div class="card-header">
									<h4 class="card-title w-100">
										<a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false">
											Guru
										</a>
									</h4>
								</div>
								<div id="collapseTwo" class="collapse" data-parent="#accordion" style="">
									<div class="card-body">
										<div class="callout callout-success m-1 p-1">Sebagai guru Mapel, mengisikan Nilai-nilai Mapel yang diasuhnya</div>
										<div class="callout callout-danger m-1 p-1">Sebagai Wali kelas, mengisikan data ekstrakurikuler dan absensi</div>
										<div class="callout callout-success m-1 p-1">Sebagai Wali kelas, mencetak rapor</div>
									</div>
								</div>
							</div>
							<div class="card card-success">
								<div class="card-header">
									<h4 class="card-title w-100">
										<a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseFour" aria-expanded="false">
											Catatan
										</a>
									</h4>
								</div>
								<div id="collapseFour" class="collapse" data-parent="#accordion" style="">
									<div class="card-body">
										<div class="callout callout-success m-1 p-1">Data Siswa diambil dari PROFIL SEKOLAH, jadi jika ada kesalahan data Siswa, dilakukan perbaikannya di website profil sekolah.</div>
										<div class="callout callout-danger m-1 p-1">Data Guru dan Login+password diambil dari KINERJA.</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg 6 col-md-6">
				<div class="card card-warning card-outline">
					<div class="card-header">
						Perhitungan Nilai
					</div>
					<div class="card-body">
						<div class="callout callout-danger color-palette m-3 p-1 bg-maroon">Seluruh Penilaian mempunyai range nilai 1.0 - 4.0, Nilai 0 tidak akan dimasukkan sebagai nilai, dan TIDAK akan dijadikan PEMBAGI. Jadi semisal jika ada yang siswa tidak mengikuti ujian, harus tetap dimasukkan nilai-nya, JANGAN dibiarkan bernilai 0.</div>
						<div class="callout callout-success color-palette m-3 p-1 bg-teal">Nilai yang diperoleh ranah sikap (KI-1 dan KI-2) diambil dari nilai Modus (Nilai yang terbanyak muncul). Pada aspek sikap yang sama, dinilai menggunakan 4 teknik (Observasi, Penilaian Diri, Antarteman, dan Jurnal Guru).</div>
						<div class="callout callout-info color-palette m-3 p-1 bg-lightblue">
							Perhitungan nilai Tengah Semester pada KI-3 adalah ( ( 2 * Nilai Rata-rata Ulangan Harian dan Tugas) + Nilai UTS) /3
							<br>
							Perhitungan nilai Akhir Semester pada KI-3 adalah ( ( 2 * Nilai Rata-rata Ulangan Harian dan Tugas) + Nilai UTS + Nilai UAS) / 4
						</div>
						<div class="callout callout-danger color-palette m-3 p-1 bg-maroon">Nilai Akhir untuk ranah Keterampilan (KI-4) diambil dari nilai Optimal (Nilai Tertinggi). Penilaian KI-4 harus meliputi Praktik, Portofolio, Proyek, dan Produk.</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /.row (main row) -->
		<div class="row">
			<div class="col-lg 6 col-md-6">
				<div class="card card-warning card-outline">
					<div class="card-header">
						Kriteria Kenaikan Kelas
					</div>
					<div class="card-body">
						<div class="callout callout-danger color-palette m-3 p-1 bg-maroon">Menyelesaikan seluruh program pembelajaran dalam dua semester pada tahun pelajaran yang diikuti.</div>
						<div class="callout callout-success color-palette m-3 p-1 bg-teal">Mencapai tingkat kompetensi yang dipersyaratkan, minimal sama dengan KKM.</div>
						<div class="callout callout-warning color-palette m-3 p-1 bg-orange">Tidak terdapat nilai kurang dari KKM maksimal pada tiga mata pelajaran.</div>
						<div class="callout callout-info color-palette m-3 p-1 bg-lightblue">Mendapatkan nilai memuaskan pada kegiatan ekstrakurikuler wajib (Pramuka) pada setiap semester.</div>
					</div>
				</div>
			</div>
			<div class="col-lg col-md-6">
			</div>
		</div>
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('extend_js')
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('dist/js/pages/dashboard.js')}}"></script>
@endsection