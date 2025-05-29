<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fuzzy Lokasi | Dashboard</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('LTE/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('LTE/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fuzzy-style.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('images/logo-fuzzy.png') }}" alt="Logo Fuzzy Lokasi"
                height="120" width="120">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('lokasi.form') }}" class="nav-link">Form Analisis</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button" id="toggleFullscreen">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <a href="{{ route('dashboard') }}" class="brand-link">
                    <img src="{{ asset('LTE/dist/img/AdminLTELogo.png') }}" alt="Sistem Fuzzy Logo"
                         class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">Fuzzy Lokasi Bisnis</span>
                </a>

                <div class="sidebar">
                    @auth
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            {{-- Gunakan gambar profil user jika ada, atau default --}}
                            <img src="{{ asset('LTE/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                                 alt="User Image">
                        </div>
                        <div class="info">
                            {{-- Tampilkan nama user (tidak perlu link) --}}
                            <span class="d-block" style="color: #ffffff; font-weight: 600; margin-bottom: 3px;">
                                {{ Auth::user()->name }}
                            </span>
                            {{-- Link Akun & Perangkat --}}
                            <a href="{{ route('profile.edit') }}" class="d-block {{ request()->routeIs('profile.edit') ? 'active-profile-link' : '' }}"
                               style="font-size: 0.8em; color: #c2c7d0; text-decoration: none;">
                               <i class="fas fa-pencil-alt fa-xs" style="margin-right: 4px;"></i> Akun & Perangkat
                            </a>
                        </div>
                    </div>
                    @endauth
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column nav-legacy" data-widget="treeview" role="menu"
                            data-accordion="false">
                            {{-- Link Dashboard --}}
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            {{-- Link Analisis Lokasi --}}
                            <li class="nav-item {{ request()->routeIs('lokasi.*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-map-marker-alt"></i>
                                    <p>
                                        Analisis Lokasi
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    {{-- ... (Isi submenu analisis lokasi tetap sama) ... --}}
                                     <li class="nav-item">
                                        <a href="{{ route('lokasi.form') }}"
                                           class="nav-link {{ request()->routeIs('lokasi.form') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Input / Analisis Baru</p>
                                        </a>
                                    </li>
                                    @php $lokasiIdSaatIni = request()->route('id'); @endphp
                                    @if($lokasiIdSaatIni && (request()->routeIs('lokasi.hasil') || request()->routeIs('lokasi.fuzzifikasi') || request()->routeIs('lokasi.inferensi') || request()->routeIs('lokasi.nilai-z')))
                                        <li class="nav-header" style="padding-left: 25px; font-size: 0.8em;">DETAIL (ID: {{ $lokasiIdSaatIni }})</li>
                                        <li class="nav-item"><a href="{{ route('lokasi.hasil', $lokasiIdSaatIni) }}" class="nav-link {{ request()->routeIs('lokasi.hasil') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i> <p>Hasil</p></a></li>
                                        <li class="nav-item"><a href="{{ route('lokasi.fuzzifikasi', $lokasiIdSaatIni) }}" class="nav-link {{ request()->routeIs('lokasi.fuzzifikasi') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i> <p>Fuzzifikasi</p></a></li>
                                        <li class="nav-item"><a href="{{ route('lokasi.inferensi', $lokasiIdSaatIni) }}" class="nav-link {{ request()->routeIs('lokasi.inferensi') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i> <p>Inferensi</p></a></li>
                                        <li class="nav-item"><a href="{{ route('lokasi.nilai-z', $lokasiIdSaatIni) }}" class="nav-link {{ request()->routeIs('lokasi.nilai-z') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i> <p>Defuzzifikasi</p></a></li>
                                    @endif
                                </ul>
                            </li>

                            {{-- Menu KHUSUS ADMIN --}}
                            @if (Auth::check() && Auth::user()->role == 'admin')
                                {{-- ... (Isi menu admin tetap sama) ... --}}
                                <li class="nav-item">
                                    <a href="{{ route('parameters.index') }}" class="nav-link {{ request()->routeIs('parameters.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-sliders-h"></i>
                                        <p>Data Parameter</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('aturan-fuzzy.index') }}" class="nav-link {{ request()->routeIs('aturan-fuzzy.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-project-diagram"></i>
                                        <p>Data Aturan Fuzzy</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('tempat-bisnis.index') }}" class="nav-link {{ request()->routeIs('tempat-bisnis.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-store"></i>
                                        <p>Data Tempat Bisnis</p>
                                    </a>
                                </li>
                            @endif

                            {{-- Biarkan Link Logout --}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form-sidebar').submit();">
                                    <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                                    <p class="text">{{ __('Log Out') }}</p>
                                </a>
                                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            @yield('page-title', '<h1 class="m-0">Analisis Lokasi Fuzzy</h1>')
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="#">Fuzzy Lokasi</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('LTE/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('LTE/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('LTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('LTE/dist/js/adminlte.js') }}"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/fuzzy-script.js') }}"></script>

    @yield('scripts')
</body>

</html>
