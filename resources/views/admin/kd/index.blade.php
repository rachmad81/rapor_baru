@extends('master.index')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Mata Pelajaran</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Mata Pelajaran</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card card-warning card-outline">
          <div class="card-header">
            Pilih Mata Pelajaran
          </div>
          <!-- /.card-header -->
          <div class="card-body" id="data_mapel">
            
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('extend_js')
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

  $(document).ready(function(){
    get_mapel_kd();
  });

  function get_mapel_kd(){
    $.post("{{route('get_mapel_kd')}}",{kelas:'{{$kelas}}'},function(data){
      $('#data_mapel').html(data.content);
    });
  }

  function form_setting(id,kelas){
    $.post("{{route('setting_kd')}}",{id:id,kelas:kelas},function(data){
      $('.modal_page').html(data.content);
      $('#modal-default').modal('show');
    });

    get_kd(id,kelas);
    get_mapel_kd();
  }

  function simpan(isi,kd,kelas){
    var uraian = $('input[name='+isi+']').val();
    var mapel = $('input[name=mapel_id]').val();

    var data = {
      uraian:uraian,
      mapel:mapel,
      kd:kd,
      kelas:kelas,
    };

    $.post("{{route('simpan_kd')}}",data,function(data){
      get_kd(mapel,kelas);
      get_mapel_kd();
    }).fail(function(){
      swal('Whooops','Terjadi kesalahan pada aplikasi','error');
    });
  }

  function get_kd(id,kelas){
    $.post("{{route('get_kd')}}",{id:id,kelas:kelas},function(data){
      $('#tempat_3').html(data.content);
    });
  }

  function hapus(id,kelas,no_kd){
    var mapel = $('input[name=mapel_id]').val();

    var data = {
      id:id,
      kelas:kelas,
      no_kd:no_kd,
      mapel:mapel,
    }

    swal({
      title: "Apakah anda yakin?",
      text: "Data tidak bisa dikembalikan!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.post("{{route('hapus_kd')}}",data,function(data){
          if(data.code=='200'){
            get_kd(mapel,kelas);
            get_mapel_kd();
          }else{
            swal(data.title,data.message,data.type);
          }

        }).fail(function(){
          swal('Whooops','Terjadi kesalahan dengan aplikasi','error');
        });
      }
    });
  }

  function update(id,isi,kelas,no_kd){
    var uraian = $('input[name='+isi+']').val();
    var mapel = $('input[name=mapel_id]').val();

    var data = {
      uraian:uraian,
      mapel:mapel,
      id:id,
      kelas:kelas,
      no_kd:no_kd,
    };

    $.post("{{route('update_kd')}}",data,function(data){
      swal(data.title,data.message,data.type);
      get_kd(mapel,kelas);
      get_mapel_kd();
    });
  }
</script>
@endsection