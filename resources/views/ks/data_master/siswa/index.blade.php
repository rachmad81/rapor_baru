@extends('master.index')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Data Siswa</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Data Siswa</li>
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
            <div class="row">
              <div class="col-lg-6">
                <label>Tahun Ajaran</label>
                <div class="input-group mb-3">
                  <select class="form-control" name="tahun_ajaran" onchange="set_ta(this)">
                    <option value="">..:: Pilih Tahun Ajaran ::..</option>
                    @if(count($tahun_ajaran)!=0)
                    @foreach($tahun_ajaran as $ta)
                    <option value="{{$ta['nilai']}}">{{$ta['nama']}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>  
            </div>
            <div class="row">
              <div class="col-lg-6">
                <label>Pilih Kelas</label>
                <div class="input-group mb-3">
                  <select name="rombel" class="form-control" id="rombel" onchange="get_siswa()">
                    <option value="">..:: Pilih Kelas Rombel ::..</option>
                  </select>
                </div>
              </div>  
            </div>
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>NISN</th>
                  <th>NIK</th>
                  <th>Nama</th>
                  <th>JK</th>
                  <th>Tgl lahir</th>
                  <th>Alamat Domisili</th>
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
    var tahun_ajaran = $('select[name=tahun_ajaran]').val();
    var rombel = $('select[name=rombel]').val();

    dt_table = $('#example2').DataTable( {
      "ajax": "{{route('get_data_data_master_siswa')}}?tahun_ajaran="+tahun_ajaran+'&kelas='+rombel,
      "columns": [
      { "data": "nis" },
      { "data": "nik" },
      { "data": "nama" },
      { "data": "kelamin" },
      { "data": "tgl_lahir" },
      { "data": "alamat_domisili" },
      ],
      "autoWidth": false,
      "responsive": true,
      "processing": true,
      "order": [[ 2, 'asc' ]],
    });
  });

  function set_ta(ini=null){
    var ta = $('select[name=tahun_ajaran]').val();
    var rombel = $('select[name=rombel]').val();
    $.post("{{route('set_ta')}}",{ta:ta},function(data){
      var selectnya = '<option value="">..:: Pilih Kelas Rombel ::..</option>';
      if(data.length!=0){
        $.each(data,function(k,v){
          selectnya += '<option value="'+v.kelas+'|||'+v.rombel+'">'+v.kelas+' '+v.rombel+'</option>';
        });
      }
      $('#rombel').html(selectnya);
    });

    // dt_table.ajax.url("{{route('get_data_data_master_siswa')}}?tahun_ajaran="+ta+'&kelas='+rombel).load();
    setTimeout(function(){
      get_siswa();
    },1000)
  }

  function get_siswa(){
    var tahun_ajaran = $('select[name=tahun_ajaran]').val();
    var rombel = $('select[name=rombel]').val();
    
    dt_table.ajax.url("{{route('get_data_data_master_siswa')}}?tahun_ajaran="+tahun_ajaran+'&kelas='+rombel).load();
  }
</script>
@endsection