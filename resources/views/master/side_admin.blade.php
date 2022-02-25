<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{route('admin-dashboard-main')}}" class="brand-link">
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
          <a href="{{route('admin-dashboard-main')}}" class="nav-link @if(isset($main_menu) && $main_menu=='dashboard') active @endif">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('admin-tahun-ajaran-main')}}" class="nav-link @if(isset($main_menu) && $main_menu=='tahun-ajaran') active @endif">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Tahun Ajaran
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('admin-mapel-main')}}" class="nav-link @if(isset($main_menu) && $main_menu=='mapel') active @endif">
            <i class="nav-icon fas fa-book"></i>
            <p>
              MaPel
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        @if(Session::get('jenjang')=='SD')
        <li class="nav-item">
          <a href="{{route('admin-kd-main',['kelas'=>'1'])}}" class="nav-link @if(isset($main_menu) && $main_menu=='kd-1') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              KD Kelas 1 (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('admin-kd-main',['kelas'=>'2'])}}" class="nav-link @if(isset($main_menu) && $main_menu=='kd-2') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              KD Kelas 2 (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('admin-kd-main',['kelas'=>'3'])}}" class="nav-link @if(isset($main_menu) && $main_menu=='kd-3') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              KD Kelas 3 (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('admin-kd-main',['kelas'=>'4'])}}" class="nav-link @if(isset($main_menu) && $main_menu=='kd-4') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              KD Kelas 4 (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('admin-kd-main',['kelas'=>'5'])}}" class="nav-link @if(isset($main_menu) && $main_menu=='kd-5') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              KD Kelas 5 (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('admin-kd-main',['kelas'=>'6'])}}" class="nav-link @if(isset($main_menu) && $main_menu=='kd-6') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              KD Kelas 6 (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        @else
        <li class="nav-item">
          <a href="{{route('admin-kd-main',['kelas'=>'7'])}}" class="nav-link @if(isset($main_menu) && $main_menu=='kd-6') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              KD Kelas 7 (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('admin-kd-main',['kelas'=>'8'])}}" class="nav-link @if(isset($main_menu) && $main_menu=='kd-6') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              KD Kelas 8 (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('admin-kd-main',['kelas'=>'9'])}}" class="nav-link @if(isset($main_menu) && $main_menu=='kd-6') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              KD Kelas 9 (Kurtilas)
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        @endif
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>