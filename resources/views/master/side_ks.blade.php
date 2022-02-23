<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{route('dashboard_guru')}}" class="brand-link">
    <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Rapor Online</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{Session::get('foto')}}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{Session::get('nama')}}</a>
        <a href="#" class="d-block">{{Session::get('nama_sekolah')}}</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <!-- <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div> -->

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="{{route('dashboard_ks')}}" class="nav-link @if(isset($main_menu) && $main_menu=='dashboard') active @endif">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('data_master_guru')}}" class="nav-link @if(isset($main_menu) && $main_menu=='master_guru') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Daftar Guru
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('data_master_siswa')}}" class="nav-link @if(isset($main_menu) && $main_menu=='master_siswa') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Daftar Siswa
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('mapel_ks')}}" class="nav-link @if(isset($main_menu) && $main_menu=='mapel') active @endif">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Daftar MaPel (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('walikelas_ks')}}" class="nav-link @if(isset($main_menu) && $main_menu=='walikelas') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Wali Kelas (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('guru_mengajar_ks')}}" class="nav-link @if(isset($main_menu) && $main_menu=='guru_mengajar') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Guru Mengajar (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('kunci_rapor')}}" class="nav-link @if(isset($main_menu) && $main_menu=='kunci_rapor') active @endif">
            <i class="nav-icon fas fa-lock"></i>
            <p>
              Kunci Pengisian RAPOR (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('buka_rapor')}}" class="nav-link @if(isset($main_menu) && $main_menu=='buka_rapor') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Buka Pengisian RAPOR (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('reset_password_guru')}}" class="nav-link @if(isset($main_menu) && $main_menu=='reset_password') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Reset Password Guru
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('user_ks')}}" class="nav-link @if(isset($main_menu) && $main_menu=='user_ks') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Setting Username guru
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>