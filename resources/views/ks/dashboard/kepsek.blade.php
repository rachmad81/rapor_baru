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
            Fitur Rapor Online
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
                    <div class="callout callout-success m-1 p-1">Mereset Password Guru</div>
                    <div class="callout callout-danger m-1 p-1">Mengganti Username Guru</div>
                    <div class="callout callout-success m-1 p-1">Mengunci Nilai RAPOR Online</div>
                    <div class="callout callout-danger m-1 p-1">Mensetting Wali Kelas dan Guru Mengajar</div>
                    <div class="callout callout-success m-1 p-1">Melihat Daftar Guru, Siswa dan Mata Pelajaran</div>
                    <div class="callout callout-danger m-1 p-1">Mensetting Username Guru bagi Guru yang Tidak Mempunyai Username</div>
                  </div>
                </div>
              </div>
              <div class="card card-danger">
                <div class="card-header">
                  <h4 class="card-title w-100">
                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false">
                      Wali Kelas
                    </a>
                  </h4>
                </div>
                <div id="collapseTwo" class="collapse" data-parent="#accordion" style="">
                  <div class="card-body">
                    <div class="callout callout-success m-1 p-1">Mencetak RAPOR Siswa</div>
                    <div class="callout callout-danger m-1 p-1">Mencetak Cover BUKU INDUK Siswa</div>
                    <div class="callout callout-success m-1 p-1">Mengisi Nilai Sikap Spritual (KI-1) dan Sosial (KI-2)</div>
                    <div class="callout callout-danger m-1 p-1">Mengisi Nilai Ekstrakurikuler, Absensi dan Catatan Siswa</div>
                  </div>
                </div>
              </div>
              <div class="card card-warning">
                <div class="card-header">
                  <h4 class="card-title w-100">
                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false">
                      Guru
                    </a>
                  </h4>
                </div>
                <div id="collapseThree" class="collapse" data-parent="#accordion" style="">
                  <div class="card-body">
                    <div class="callout callout-success m-1 p-1">Mengisi Nilai-Nilai Mata Pelajaran yang diasuhnya</div>
                    <div class="callout callout-danger m-1 p-1">Mengisi Nilai Pengetahuan (KI-3) dan Ketrampilan (KI-4)</div>
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
                    <div class="callout callout-success m-1 p-1">Data Siswa berasal dari PROFIL SEKOLAH, jika ada kesalahan data Siswa, dilakukan perbaikannya di PROFIL SEKOLAH.</div>
                    <div class="callout callout-danger m-1 p-1">Data Guru, Username dan Password diambil dari PROFIL SEKOLAH.</div>
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
            <div class="callout callout-danger color-palette m-3 p-1 bg-maroon">Kurikulum 2013</div>
            <div class="callout callout-success color-palette m-3 p-1 bg-teal">Penilaian (KI-3 dan KI-4) mempunyai range nilai 1 - 100, Nilai 0 tidak akan dihitung sebagai Nilai, dan TIDAK akan dijadikan PEMBAGI.</div>
            <div class="callout callout-warning color-palette m-3 p-1 bg-orange">Penilaian Sikap (KI-1 dan KI-2) mempunyai range nilai 1 - 4 dan diambil nilai Modus (Nilai yang terbanyak muncul)</div>
            <div class="callout callout-info color-palette m-3 p-1 bg-lightblue">
              Perhitungan nilai Tengah Semester pada KI-3 adalah ( ( 2 * Nilai Rata-rata Ulangan Harian dan Tugas) + Nilai UTS) /3
              <br>
              Perhitungan nilai Akhir Semester pada KI-3 adalah ( ( 2 * Nilai Rata-rata Ulangan Harian dan Tugas) + Nilai UTS + Nilai UAS) / 4
            </div>
            <div class="callout callout-danger color-palette m-3 p-1 bg-maroon">Nilai Akhir untuk ranah Keterampilan (KI-4) diambil dari Nilai RERATA. Penilaian KI-4 harus meliputi Praktik/Portofolio/Proyek/Produk.</div>
            <div class="callout callout-success color-palette m-3 p-1 bg-teal">Kurikulum KTSP</div>
            <div class="callout callout-warning color-palette m-3 p-1 bg-orange">
              Perhitungan nilai Tengah Semester adalah ( ( 2 * Nilai Rata-rata Ulangan Harian dan Tugas) + Nilai UTS) /3
              <br>
              Perhitungan nilai Akhir Semester adalah ( ( 2 * Nilai Rata-rata Ulangan Harian dan Tugas) + Nilai UTS + Nilai UAS) / 4
            </div>
            <div class="callout callout-info color-palette m-3 p-1 bg-lightblue">Semua nilai mata pelajaran dinyatakan dengan angka skala 0 - 100.</div>
            <div class="callout callout-danger color-palette m-3 p-1 bg-maroon">Nilai akhlak dan kepribadian dinyatakan secara kualitatif dengan kategori (ungkapan) sangat baik (4), baik (3), cukup (2), atau kurang baik (1) sesuai kondisi peserta didik yang bersangkutan</div>
            <div class="callout callout-success color-palette m-3 p-1 bg-teal">Nilai ekstra kurikuler dinyatakan secara kualitatif dengan nilai A (sangat baik), B (baik), C (cukup), D (kurang)</div>
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
            <div class="callout callout-danger color-palette m-3 p-1 bg-maroon">Kurikulum 2013</div>
            <div class="callout callout-success color-palette m-3 p-1 bg-teal">Peserta didik dinyatakan naik kelas apabila memenuhi persyaratan sebagai berikut:</div>
            <div class="callout callout-warning color-palette m-3 p-1 bg-orange">1. Menyelesaikan seluruh program pembelajaran dalam dua semester pada tahun pelajaran yang diikuti.</div>
            <div class="callout callout-info color-palette m-3 p-1 bg-lightblue">2. Deskripsi sikap sekurang-kurangnya minimal BAIK yaitu memenuhi indikator kompetensi sesuai dengan kriteria yang ditetapkan oleh satuan pendidikan.</div>
            <div class="callout callout-danger color-palette m-3 p-1 bg-maroon">3. Deskripsi kegiatan ekstrakurikuler pendidikan kepramukaan minimal BAIK sesuai dengan kriteria yang ditetapkan oleh satuan pendidikan.</div>
            <div class="callout callout-success color-palette m-3 p-1 bg-teal">4. Tidak memiliki lebih dari 2 (dua) mata pelajaran yang masing-masing nilai pengetahuan dan/atau keterampilan di bawah KKM. Apabila ada mata pelajaran yang tidak mencapai ketuntasan belajar pada semester ganjil dan/atau semester genap, nilai akhir diambil dari rerata semester ganjil dan genap pada mata pelajaran yang sama pada tahun pelajaran tersebut.</div>
            <div class="callout callout-warning color-palette m-3 p-1 bg-orange">5. Satuan pendidikan dapat menambahkan kriteria lain sesuai dengan kebutuhan masing-masing (contoh kehadiran, tata tertib, dll.)</div>
            <div class="callout callout-info color-palette m-3 p-1 bg-lightblue">Kurikulum KTSP</div>
            <div class="callout callout-danger color-palette m-3 p-1 bg-maroon">Dilaksanakan pada setiap akhir tahun pelajaran atau setiap semester genap</div>
            <div class="callout callout-success color-palette m-3 p-1 bg-teal">Kenaikan kelas didasarkan pada penilaian hasil belajar pada semerter genap, dengan pertimbangan seluruh SK/KD yang belum tuntas pada semester ganjil, harus dituntaskan sampai mencapai KKM yang ditetapkan, sebelum akhir semester genap. Hal ini sesuai dengan prinsip belajar tuntas (mastery learning), dimana peserta yang belum mencapai ketuntasan belajar sesuai dengan KKM yang ditetapkan, maka yang bersangkutan harus mengikuti pembelajaran remidi sampai yang bersangkutan mampu mencapai KKM dimaksud. Artinya, nilai kenaikan kelas harus tetap memperhitungkan hasil belajar peserta didik selama satu tahun pelajaran yang sedang berlangsung.</div>
            <div class="callout callout-danger color-palette m-3 p-1 bg-maroon">Peserta didik dinyatakan tidak naik ke kelas XI, apabila yang bersangkutan tidak mencapai ketuntasan belajar minimal, lebih dari 3 (tiga) mata pelajaran.</div>
            <div class="callout callout-success color-palette m-3 p-1 bg-teal">
              Peserta didik dinyatakan tidak naik ke kelas XII, apabila yang bersangkutan tidak mencapai ketuntasan belajar minimal, lebih dari 3 (tiga) mata pelajaran yang bukan mata pelajaran ciri khas program, atau yang bersangkutan tidak mencapai ketuntasan belajar minimal pada salah satu atau lebih mata pelajaran ciri khas program.
              Sebagai contoh: Bagi Peserta didik Kelas XI :<br>
              a. Program IPA, tidak boleh memiliki nilai yang tidak tuntas pada mata pelajaran Fisika, Kimia, dan Biologi.<br>
              b. Program IPS, tidak boleh memiliki nilai yang tidak tuntas pada mata pelajaran Geografi, Ekonomi, dan Sosiologi.<br>
              c. Program Bahasa, tidak boleh memiliki nilai yang tidak tuntas (kurang) pada mata pelajaran Antropologi, Sastra Indonesia, dan Bahasa Asing lainnya yang menjadi pilihan.
            </div>
            <div class="callout callout-warning color-palette m-3 p-1 bg-orange">Satuan pendidikan dapat menambah kriteria kenaikan kelas sesuai dengan karakteristik dan kebutuhan setiap satuan pendidikan, melalui rapat dewan pendidik.</div>
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