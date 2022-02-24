<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{route('guru-dashboard-main')}}" class="brand-link">
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
          <a href="{{route('guru-dashboard-main')}}" class="nav-link @if(isset($main_menu) && $main_menu=='dashboard') active @endif">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('guru-walikelas-main')}}" class="nav-link @if(isset($main_menu) && $main_menu=='walikelas') active @endif">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Walikelas
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
        {{-- <li class="nav-item">
          <a href="{{route('ks-dashboard-main')}}" class="nav-link @if(isset($main_menu) && $main_menu=='mapelajar') active @endif">
            <i class="nav-icon fas fa-clipboard"></i>
            <p>
              Mapelajar
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li> --}}
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>