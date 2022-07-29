<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style type="text/css">
  .row{
    margin: 0px !important;
  }
  @media (max-width: 576px){
    .login-box, .register-box{
      width:  100% !important;
    }
    .login-page, .register-page{
      justify-content: normal !important;
      margin-bottom: 10px;
    }
  }
</style>
<body class="hold-transition login-page" style="background-image:url('{{asset('bg/bg.jpg')}}');background-size: cover;background-repeat: no-repeat;">

  <div class="login-box" style="background: #ccc">
    <div class="login-logo">
      <a href="{{url('/')}}"><b>Rapor</b>Online</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Halaman Login</p>

        <form action="{{route('do_login')}}" method="post">
          {{csrf_field()}}
          <div class="input-group mb-3">
            <select class="form-control" name="sebagai" required onchange="set_status()">
              <option value="">..:: Sebagai ::..</option>
              <option value="guru">Guru dan Kasek</option>
              <option value="siswa">Wali Murid</option>
            </select>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <select class="form-control" name="jenjang" required>
              <option value="">..:: Jenjang ::..</option>
              <option value="SD">SD</option>
              <option value="SMP">SMP</option>
            </select>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-university"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="user_rapor" placeholder="Username" value="{{ old('user_rapor') }}">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" autocomplete="off" autofocus="off" name="password" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          @php
          if(Session::has('sekolah') && Session::get('sekolah')!=''){
            foreach(Session::get('sekolah') as $s){
              echo '<label><input type="radio" name="npsn" value="'.$s->npsn.'"> '.$s->nama_sekolah.'</label><br>';
            }
          }
          @endphp
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-success btn-block">Login</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <!-- <p class="mb-1">
          <a href="forgot-password.html">I forgot my password</a>
        </p>
        <p class="mb-0">
          <a href="register.html" class="text-center">Register a new membership</a>
        </p> -->
      </div>
      <!-- /.login-card-body -->
      <div style="font-size: 10pt;padding: 10px;display: none;" id="guru">
        <ol>
          <li>Bagi PTK silahkan LOGIN menggunakan USERNAME RAPOR ONLINE di PROFIL SEKOLAH</li>
          <li>
            Bagi yang Tidak Memiliki USERNAME RAPOR ONLINE :
            <ul>
              <li>Untuk Kepala Sekolah, Harap Meminta Username Ke Admin Dinas</li>
              <li>Untuk Guru, Bisa Meminta Dibuatkan Username Kepada Kepala Sekolah Melalui RAPOR ONLINE ini</li>
            </ul>
          </li>
        </ol>
      </div>

      <div style="font-size: 10pt;padding: 10px;display: none;" id="siswa">
        <ol>
          <li>Username menggunakan NIK dan Tanggal Lahir SISWA di PROFIL SEKOLAH</li>
          <li>Apabila Anda tidak dapat login, silahkan hubungi Admin/Operator PROFIL SEKOLAH anak Anda</li>
          <li>Jika ada kendala bisa menggunakan alamat alternatif Rapor Online raporku.net</li>
        </ol>
      </div>

      <div style="text-align: center;font-size: 8pt;margin-top: 8px;">
        Support by <i>Dinas Pendidika Kota Surabaya</i>
      </div>
    </div>
  </div>
  <!-- /.login-box -->


  <!-- jQuery -->
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
  <!-- SWAL -->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script type="text/javascript">
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  </script>
  <script type="text/javascript">
    @if(Session::has('message'))
    swal('{{Session::get('message')}}');
    @endif

    $(document).ready(function(){
      set_status();
    });

    function set_status(){
      var sebagai = $('select[name=sebagai]').val();
      console.log(sebagai);
      if(sebagai=='siswa'){
        $('#guru').hide();
        $('#siswa').show();

        $('input[name=password]').datetimepicker({
          format: 'Y-m-d',
          timepicker: false,
        });
      }else{
        $('#guru').show();
        $('#siswa').hide();
        $('input[name=password]').datetimepicker("destroy");
      }
    }
  </script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-GLL3T93PDW"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-GLL3T93PDW');
  </script>
</body>
</html>
