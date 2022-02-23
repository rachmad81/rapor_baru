@extends('master.index')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Data Guru</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Data Guru</li>
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
        <div class="card card-primary card-outline">
          <div class="card-header"></div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>User Rapor</th>
                  <th>NIP</th>
                  <th>NUPTK</th>
                  <th>Jen. Kel.</th>
                  <th>Alamat</th>
                  <th>Telp</th>
                  <th>Pangkat</th>
                  <th>Keahlian</th>
                  <th>Keterangan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
            </table>
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
  $(document).ready(function () {
    $('#example2').DataTable({
      "ajax": "{{route('get_data_data_master_guru')}}",
      "columns": [
      {"data": "nama"},
      {"data": "user_rapor"},
      {"data": "nip"},
      {"data": "nuptk"},
      {"data": "jk"},
      {"data": "alamat"},
      {"data": "telp"},
      {"data": "nama_golongan"},
      {"data": "sertifikasi_bidang_studi"},
      {"data": "keterangan"},
      {"data": "aksi"},
      ],
      "autoWidth": false,
      "responsive": true,
    });
  });

  function reset_password(guru){
    var data = {guru:guru};
    $.post("{{route('proses_reset_password_guru')}}",data,function(data){
      if(data.code=='200'){
        swal('Success',data.message,'success');
      }else{
        swal('Whooops',data.message,'error');
      }
    });
  }

  function form(id){
    $.post("{{route('form_master_guru')}}",{id:id},function(data){
      $('.modal_page').html(data.content);

      $('#modal-default').modal('show');
    });
  }

  function simpan(){
    var data = $('form#form_simpan').serialize();
    $.post("{{route('simpan_master_guru')}}",data,function(data){
      if(data.code=='200'){
        swal('Success',data.message,'success');
        location.reload();
      }else{
        swal('Whooops',data.message,'error');
      }
    });    
  }
</script>
@endsection