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
        <div class="card card-primary card-outline">
          <div class="card-header">
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>KATEGORI</th>
                  <th>KATEGORI BARU</th>
                  <th>ID MAPEL</th>
                  <th>NAMA MAPEL</th>
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
    get_data();
  });

  function get_data(){
    $('#example2').DataTable({
      "ajax": "{{route('get_data_mapel_ks')}}",
      "columns": [
      {"data":"kategori"},
      {"data":"kategori"},
      {"data":"mapel_id"},
      {"data":"nama"},
      ],
      "autoWidth": false,
      "responsive": true,
    });
  }

  function form_mapel(id){
    $.post("{{route('form_mapel')}}",{id:id},function(data){
      $('.modal_page').html(data.content);
      $('#modal-default').modal('show');
    });
  }

  function simpan(){
    var data = $('form#form_simpan').serialize();
    $.post("{{route('simpan_mapel')}}",data,function(data){

    });
  }
</script>
@endsection