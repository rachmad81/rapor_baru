@extends('master.index')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Guru Mengajar</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Guru Mengajar</li>
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
            <a href="javascript:void(0)" class="btn btn-primary" onclick="form('0')"><i class="fa fa-plus"></i> Tambah</a>
            <div class="row">
              <div class="col-lg-6">
                <label>Tahun Ajaran</label>
                <div class="input-group mb-3">
                  <select class="form-control" name="tahun_ajaran" onchange="set_ta(this)">
                    @if(count($tahun_ajaran)!=0)
                    <option value="">..:: Pilih Tahun Ajaran ::..</option>
                    @foreach($tahun_ajaran as $ta)
                    <option value="{{$ta['nilai']}}">{{$ta['nama']}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>  
            </div>
            <table id="data_pengajar" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Nama Mapel</th>
                  <th>Kelas Rombel</th>
                  <th>Pengajar</th>
                  <th style="width: 10%">#</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
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
  @if(Session::has('title'))
  swal('{{Session::get('title')}}','{{Session::get('message')}}','{{Session::get('type')}}');
  @endif

  var dt_table = '';
  $(document).ready(function () {
    data_pengajar();
    set_ta();
  });

  function form(id){
    $.post("{{route('form_guru_mengajar_ks')}}",{id:id},function(data){
      $('.modal_page').html(data.content);

      var ini = {
        value:$('select[name=tahun_ajaran]').val()
      };

      set_ta(ini);

      $('#modal-default').modal('show');
    });
  }

  function simpan(){
    var data = $('form#form_simpan').serialize();
    $.post("{{route('simpan_guru_mengajar_ks')}}",data,function(data){
      if(data.code=='200'){
        location.reload();
      }else{
        swal(data.title,data.message,data.type);
      }

    }).fail(function(){
      swal('Whooops','Terjadi kesalahan pad aplikasi','error');
    });
  }

  function set_ta(ini=null){
    var ta = ini.value;
    $.post("{{route('set_ta')}}",{ta:ta},function(data){
      var selectnya = '<option value="">..:: Pilih Kelas Rombel ::..</option>';
      if(data.length!=0){
        $.each(data,function(k,v){
          selectnya += '<option value="'+v.kelas+'|||'+v.rombel+'">'+v.kelas+' '+v.rombel+'</option>';
        });
      }
      $('#rombel').html(selectnya);
    });

    dt_table.ajax.url( "{{route('get_data_guru_mengajar_ks')}}?tahun_ajaran="+ta ).load();
  }

  function data_pengajar(){
    var tahun_ajaran = $('select[name=tahun_ajaran]').val();

    dt_table = $('#data_pengajar').DataTable( {
      "ajax": "{{route('get_data_guru_mengajar_ks')}}?tahun_ajaran="+tahun_ajaran,
      "columns": [
      { "data": "nama_mapel" },
      { "data": "kelas_rombel" },
      { "data": "nama_guru" },
      { "data": "aksi" },
      ],
      "autoWidth": false,
      "responsive": true,
      "processing": true,
    });
  }

  function set_mapel(ini=null){
    var kategori = ini.value;
    $.post("{{route('get_mapel_by_kategori')}}",{kategori:kategori},function(data){
      var selectnya = '<option value="">..:: Pilih Mapel ::..</option>';
      if(data.length!=0){
        $.each(data,function(k,v){
          selectnya += '<option value="'+v.mapel_id+'">'+v.nama+'</option>';
        });
      }
      $('#select_mapel').html(selectnya);
    });
  }

  function hapus_data(nip,mapel_id,kelas,rombel){
    swal({
      title: "Apakah anda yakin?",
      text: "Data tidak bisa dikembalikan!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      var tahun_ajaran = $('select[name=tahun_ajaran]').val();
      var data = {
        nip:nip,
        mapel_id:mapel_id,
        kelas:kelas,
        rombel:rombel,
        tahun_ajaran:tahun_ajaran,
      };
      if (willDelete) {
        $.post("{{route('hapus_guru_mengajar_ks')}}",data,function(data){
          if(data.code=='200'){
            location.reload();
          }else{
            swal(data.title,data.message,data.type);
          }

        }).fail(function(){
          swal('Whooops','Terjadi kesalahan dengan aplikasi','error');
        });
      }
    });
  }
</script>
@endsection