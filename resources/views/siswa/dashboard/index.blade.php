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
    <!-- Main row -->
    <div class="row">
      <div class="col-lg 6 col-md-6"> 
        <div class="card card-primary card-outline">
          <div class="card-header">
            Anda masuk di halaman wali murid
          </div>
          <div class="card-body">
            <div id="accordion">
              <div class="card card-primary">
                <div class="card-header">
                  <h4 class="card-title w-100">
                    <a class="d-block w-100" data-toggle="collapse" href="#collapseOne" aria-expanded="true">
                      Hak AKses
                    </a>
                  </h4>
                </div>
                <div id="collapseOne" class="collapse show" data-parent="#accordion" style="">
                  <div class="card-body">
                    <div class="callout callout-success m-1 p-1">Melihat Rapor Akhir Siswa</div>
                    <div class="callout callout-danger m-1 p-1">Melihat Nilai-Nilai Setiap Mata Pelajaran</div>
                  </div>
                </div>
              </div>
              <div class="card card-danger">
                <div class="card-header">
                  <h4 class="card-title w-100">
                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false">
                      Catatan
                    </a>
                  </h4>
                </div>
                <div id="collapseTwo" class="collapse" data-parent="#accordion" style="">
                  <div class="card-body">
                    <div class="callout callout-success m-1 p-1">Data Siswa diambil dari PROFIL SEKOLAH, jika ada kesalahan data Siswa, dilakukan perbaikan di PROFIL SEKOLAH. Silakan hubungi admin PROFIL SEKOLAH di sekolah masing-masing.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg 6 col-md-6">
        <div class="card card-primary card-outline">
          <div class="card-header">
            Perhitungan Nilai
          </div>
          <div class="card-body">
            <div class="callout callout-success color-palette m-3 p-1 bg-teal">Penilaian (KI-3 dan KI-4) mempunyai range nilai 1 - 100, Nilai 0 tidak akan dihitung sebagai Nilai, dan TIDAK akan dijadikan PEMBAGI.</div>
            <div class="callout callout-warning color-palette m-3 p-1 bg-orange">Penilaian Sikap (KI-1 dan KI-2) mempunyai range nilai 1 - 4 dan diambil nilai Modus (Nilai yang terbanyak muncul)</div>
            <div class="callout callout-info color-palette m-3 p-1 bg-lightblue">
              Perhitungan nilai Tengah Semester pada KI-3 adalah ( ( 2 * Nilai Rata-rata Ulangan Harian dan Tugas) + Nilai UTS) /3
              <br>
              Perhitungan nilai Akhir Semester pada KI-3 adalah ( ( 2 * Nilai Rata-rata Ulangan Harian dan Tugas) + Nilai UTS + Nilai UAS) / 4
            </div>
            <div class="callout callout-danger color-palette m-3 p-1 bg-maroon">Nilai Akhir untuk ranah Keterampilan (KI-4) diambil dari Nilai RERATA. Penilaian KI-4 harus meliputi Praktik/Portofolio/Proyek/Produk.</div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.row (main row) -->
    <div class="row">
      <div class="col-lg 6 col-md-6">
        <div class="card card-primary card-outline">
          <div class="card-header">
            Kriteria Kenaikan Kelas
          </div>
          <div class="card-body">
            <div class="callout callout-warning color-palette m-3 p-1 bg-orange">Menyelesaikan seluruh program pembelajaran dalam dua semester pada tahun pelajaran yang diikuti.</div>
            <div class="callout callout-info color-palette m-3 p-1 bg-lightblue">Mencapai tingkat kompetensi yang dipersyaratkan, minimal sama dengan KKM.</div>
            <div class="callout callout-danger color-palette m-3 p-1 bg-maroon">Tidak terdapat nilai kurang dari KKM maksimal pada tiga mata pelajaran.</div>
            <div class="callout callout-success color-palette m-3 p-1 bg-teal">Mendapatkan nilai memuaskan pada kegiatan ekstrakurikuler wajib (Pramuka) pada setiap semester.</div>
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