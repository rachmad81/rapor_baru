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
            <i style="color:red;font-size:9pt">
              <b>( I N F O R M A S I )</b><br>
              <b>Tambah</b> untuk menambahkan guru untuk mengajar mapel pada kelas tertentu<br>
              @if(Session::get('jenjang')=='SD')
              <b>Set Guru Kelas</b> untuk setting mapel umum kelas kepada wali kelas<br>
              @endif
            </i>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <a href="javascript:void(0)" class="btn btn-primary" onclick="form('0')"><i class="fa fa-plus"></i> Tambah</a>
            @if(Session::get('jenjang')=='SD')
            <a href="javascript:void(0)" class="btn btn-success" onclick="set_guru_kelas()"><i class="fa fa-cog"></i> Set Guru Kelas</a>
            @endif
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
            <table id="data_pengajar" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Nama Mapel</th>
                  <th>Kelas Rombel</th>
                  <th>Pengajar</th>
                  <th>User rapor</th>
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
    var tahun_ajaran = $('select[name=tahun_ajaran]').val();
    var semester = $('select[name=semester]').val();

    dt_table = $('#data_pengajar').DataTable( {
      "ajax": "{{route('ks-data-master-guru_mengajar-get_data')}}?tahun_ajaran="+tahun_ajaran+"&semester="+semester,
      "columns": [
      { "data": "nama_mapel" },
      { "data": "kelas_rombel" },
      { "data": "nama_guru" },
      { "data": "user_rapor" },
      { "data": "aksi" },
      ],
      "autoWidth": false,
      "responsive": true,
      "processing": true,
    });
    set_ta();
  });

  function form(id){
    $.post("{{route('ks-data-master-guru_mengajar-form')}}",{id:id},function(data){
      $('.modal_page').html(data.content);

      var ini = {
        value:$('select[name=tahun_ajaran]').val()
      };

      set_ta(ini);
    });
  }

  function simpan(){
    var data = $('form#form_simpan').serialize();
    $.post("{{route('ks-data-master-guru_mengajar-simpan')}}",data,function(data){
      if(data.code=='200'){
        data_pengajar();
        swal('Success','Berhasil disimpan','success');
        $('#modal-default').modal('hide');
      }else{
        swal('Whooops','Gagal disimpan','error');
      }

    }).fail(function(){
      swal('Whooops','Terjadi kesalahan pad aplikasi','error');
    });
  }

  function set_ta(form=null){
    if(form==null){
      var ta = $('select[name=tahun_ajaran]').val();
      var semester = $('select[name=semester]').val();
    }else{
      var ta = $('#tahun_ajaran').val();
      var semester = $('#semester').val();
    }
    $.post("{{route('get_rombel')}}",{ta:ta,semester:semester},function(data){
      var selectnya = '<option value="">..:: Pilih Kelas Rombel ::..</option>';
      if(data.code=='200'){
        if(data.kelas.length!=0){
          $.each(data.kelas,function(k,v){
            selectnya += '<option value="'+v.id_rombongan_belajar+'">'+v.kelas+' '+v.rombel+'</option>';
          });
        }
      }else{
        swal('Whooops','Tahun ajaran belum dibuka','warning');
      }
      $('#rombel').html(selectnya);
    });

    data_pengajar();
  }

  function data_pengajar(){
    var ta = $('select[name=tahun_ajaran]').val();
    var semester = $('select[name=semester]').val();

    $('#data_pengajar tbody').html('');

    dt_table.ajax.url( "{{route('ks-data-master-guru_mengajar-get_data')}}?tahun_ajaran="+ta+"&semester="+semester ).load();
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
        $.post("{{route('ks-data-master-guru_mengajar-hapus')}}",data,function(data){
          if(data.code=='200'){
            data_pengajar();
          }else{
            swal(data.title,data.message,data.type);
          }

        }).fail(function(){
          swal('Whooops','Terjadi kesalahan dengan aplikasi','error');
        });
      }
    });
  }

  function set_guru_kelas(){
    var ta = $('select[name=tahun_ajaran]').val();
    var tatext = $('select[name=tahun_ajaran] option:selected').html();
    var semester = $('select[name=semester]').val();

    if(ta==''){
      swal({
        title: 'Whooops',
        text: 'Tahun ajaran harus diisi',
        icon: 'error'
      });
    }else if(tatext=='2021/2022'){
      swal({
        title: 'Whooops',
        text: 'Tahun ajaran harus lebih dari 2021',
        icon: 'error'
      });
    }else{
      swal({
        title: "Apakah anda yakin?",
        text: "Setting guru mengajar tahun ajaran "+tatext,
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        var data = {
          tahun_ajaran:ta,
          semester:semester,
        };
        if (willDelete) {
          $.post("{{route('ks-data-master-guru_mengajar-set_guru_kelas')}}",data,function(data){
            if(data.code=='200'){
              data_pengajar();
            }else{
              swal(data.title,data.message,data.type);
            }

          }).fail(function(){
            swal('Whooops','Terjadi kesalahan dengan aplikasi','error');
          });
        }
      });
    }   
  }
</script>
@endsection