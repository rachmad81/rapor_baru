@extends('master.index')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Tahun Ajaran</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Tahun Ajaran</li>
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
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <a href="javascript:void(0)" class="btn btn-primary" onclick="form_data('0')"><i class="fa fa-plus"></i> Tambah</a>
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Tahun Ajaran</th>
                  <th>Tanggal Awal</th>
                  <th>Tanggal Akhir</th>
                  <th>#</th>
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
  var dt_table = '';

  $(document).ready(function () {
    dt_table = $('#example2').DataTable({
      "ajax": "{{route('admin-tahun-ajaran-get_data')}}",
      "columns": [
      {"data":"nama_tahun_ajaran"},
      {"data":"awal"},
      {"data":"akhir"},
      {"data":"aksi"},
      ],
      "processing": true,
      "responsive": true,
      "autoWidth": false,
    });
  });

  function form_data(id){
    $.post("{{route('admin-tahun-ajaran-form')}}",{id:id},function(data){
      $('.modal_page').html(data.content);
      $('#modal-default').modal('show');
    });
  }

  function simpan(){
    var data = $('form#form_simpan').serialize();
    $.post("{{route('admin-tahun-ajaran-simpan')}}",data,function(data){
      if(data.code=='200'){
        swal('Success','Berhasil disimpan','success');
        $('#modal-default').modal('hide');
        get_data();
      }else{
        swal('Whooops','Gagal disimpan','error');
      }
    }).fail(function(){
      swal('Whoooops','Ada error dengan aplikasi silahkan coba kembali','error');
    });
  }

  function hapus(id){
    swal({
      title: "Apakah anda yakin?",
      text: "Data tidak bisa dikembalikan!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.post("{{route('admin-tahun-ajaran-hapus')}}",{id:id},function(data){
          if(data.code=='200'){
            get_data();
            swal('Success','Berhasil dihapus','success');
          }else{
            swal('Whooops','Gagal dihapus','error');
          }

        }).fail(function(){
          swal('Whooops','Terjadi kesalahan dengan aplikasi','error');
        });
      }
    });
  }

  function get_data(){
    dt_table.ajax.url("{{route('admin-tahun-ajaran-get_data')}}").load();
  }
</script>
@endsection