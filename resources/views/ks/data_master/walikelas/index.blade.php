@extends('master.index')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Walikelas</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Walikelas</li>
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
            {{-- <a href="javascript:void(0)" class="btn btn-primary" onclick="form_mapel('0')"><i class="fa fa-plus"></i> Tambah</a> --}}
            <div class="row">
              <div class="col-lg-6">
                <label>Tahun Ajaran</label>
                <div class="input-group mb-3">
                  <select class="form-control" name="tahun_ajaran" onchange="set_ta()">
                    @if(count($tahun_ajaran)!=0)
                    <option value="">..:: Pilih Tahun Ajaran ::..</option>
                    @foreach($tahun_ajaran as $ta)
                    <option value="{{$ta->id_tahun_ajaran}}">{{$ta->nama_tahun_ajaran}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>  
            </div>
            <div class="row">
              <div class="col-lg-6">
                <label>Semester</label>
                <div class="input-group mb-3">
                  <select class="form-control" name="semester" onchange="set_ta()">
                    <option value="1">Ganjil</option>
                    <option value="2">Genap</option>
                  </select>
                </div>
              </div>  
            </div>
            <table id="data_walkel" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Kelas</th>
                  <th>Rombel</th>
                  <th>Wali Kelas</th>
                  <th>User rapor</th>
                  <th style="width: 10%">#</th>
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
  @if(Session::has('title'))
  swal('{{Session::get('title')}}','{{Session::get('message')}}','{{Session::get('type')}}');
  @endif

  var dt_table = '';

  $(document).ready(function () {
    var tahun_ajaran = $('select[name=tahun_ajaran]').val();
    var semester = $('select[name=semester]').val();
    dt_table = $('#data_walkel').DataTable( {
      "ajax": "{{route('ks-data-master-walikelas-get_data')}}?tahun_ajaran="+tahun_ajaran+"&semester="+semester,
      "columns": [
      { "data": "kelas" },
      { "data": "rombel" },
      { "data": "nama" },
      { "data": "user_rapor" },
      { "data": "aksi" },
      ],
      "autoWidth": false,
      "responsive": true,
      "processing": true,
    });
  });

  function form_mapel(id){
    $.post("{{route('ks-data-master-walikelas-form')}}",{id:id},function(data){
      $('.modal_page').html(data.content);
      $('#modal-default').modal('show');
    });
  }

  function simpan(){
    var data = $('form#form_simpan').serialize();
    $.post("{{route('ks-data-master-walikelas-simpan')}}",data,function(data){
      if(data.code=='200'){
        $('#modal-default').modal('hide');
        get_wakel();
        swal('Success','Berhasil disimpan','success');
      }else{
        swal('Whooops','Gagal disimpan','error');
      }
    }).fail(function(){
      swal('Whooops','Aplikasi mengalami kesalahan','error');
    });
  }

  function set_ta(form=null){
    if(form==null){
      var ta = $('select[name=tahun_ajaran]').val();
    }else{
      var ta = $('#tahun_ajaran').val();
    }
    $.post("{{route('set_ta')}}",{ta:ta},function(data){
      var selectnya = '<option value="">..:: Pilih Kelas Rombel ::..</option>';
      if(data.code=='200'){
        if(data.kelas.length!=0){
          $.each(data.kelas,function(k,v){
            selectnya += '<option value="'+v.kelas+'|||'+v.rombel+'">'+v.kelas+' '+v.rombel+'</option>';
          });
        }
      }else{
        swal('Whooops','Tahun ajaran belum dibuka','warning');
      }
      $('#rombel').html(selectnya);
    });

    get_wakel();
  }

  function get_wakel(){
    var tahun_ajaran = $('select[name=tahun_ajaran]').val();
    var semester = $('select[name=semester]').val();

    $('#data_walkel tbody').html('');

    dt_table.ajax.url("{{route('ks-data-master-walikelas-get_data')}}?tahun_ajaran="+tahun_ajaran+"&semester="+semester).load();
  }

  function hapus(nip,kelas,rombel){
    swal({
      title: "Apakah anda yakin?",
      text: "Data tidak bisa dikembalikan!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      var tahun_ajaran = $('select[name=tahun_ajaran]').val();
      if (willDelete) {
        var data = {
          nip:nip,
          kelas:kelas,
          rombel:rombel,
          tahun_ajaran:tahun_ajaran,
        };
        $.post("{{route('ks-data-master-walikelas-hapus')}}",data,function(data){
          if(data.code=='200'){
            get_wakel();
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