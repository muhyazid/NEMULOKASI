<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fuzzy Lokasi | Dashboard</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('LTE/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('LTE/dist/css/adminlte.min.css') }}">
    <!-- Custom Style -->
    <link rel="stylesheet" href="{{ asset('css/fuzzy-style.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('public/LTE/dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo"
                height="60" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"id="toggleSidebar"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('lokasi.form') }}" class="nav-link">Form Analisis</a>
                </li>
                
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button"id="toggleSidebar">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ route('dashboard') }}" class="brand-link"> <img src="{{ asset('LTE/dist/img/AdminLTELogo.png') }}" alt="Sistem Fuzzy Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Fuzzy Lokasi Bisnis</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('LTE/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                            alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">Administrator</a>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-legacy" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-header">MENU UTAMA</li>

                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                            {{-- MENU ANALISIS LOKASI --}}
                            <li class="nav-item {{ request()->routeIs('lokasi.*') ? 'menu-open' : '' }}">
                                {{-- Link parent # agar hanya berfungsi sebagai toggle dropdown --}}
                                <a href="#" class="nav-link {{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-map-marker-alt"></i> {{-- Icon parent tetap --}}
                                    <p>
                                        Analisis Lokasi
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('lokasi.form') }}"
                                           class="nav-link {{ request()->routeIs('lokasi.form') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i> {{-- Icon diubah --}}
                                            <p>Input / Analisis Baru</p>
                                        </a>
                                    </li>
                                    
                                    {{-- Sub-menu hasil akan muncul jika ada ID di route --}}
                                    @php
                                        $lokasiIdSaatIni = request()->route('id');
                                    @endphp

                                    @if($lokasiIdSaatIni && (request()->routeIs('lokasi.hasil') || request()->routeIs('lokasi.fuzzifikasi') || request()->routeIs('lokasi.inferensi') || request()->routeIs('lokasi.nilai-z')))
                                        {{-- Tambahkan header kecil jika ada hasil --}}
                                        <li class="nav-header" style="padding-left: 25px; font-size: 0.8em;">DETAIL (ID: {{ $lokasiIdSaatIni }})</li>

                                        <li class="nav-item">
                                            <a href="{{ route('lokasi.hasil', $lokasiIdSaatIni) }}"
                                               class="nav-link {{ request()->routeIs('lokasi.hasil') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i> {{-- Icon diubah --}}
                                                <p>Hasil</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('lokasi.fuzzifikasi', $lokasiIdSaatIni) }}"
                                               class="nav-link {{ request()->routeIs('lokasi.fuzzifikasi') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i> {{-- Icon diubah --}}
                                                <p>Fuzzifikasi</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('lokasi.inferensi', $lokasiIdSaatIni) }}"
                                               class="nav-link {{ request()->routeIs('lokasi.inferensi') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i> {{-- Icon diubah --}}
                                                <p>Inferensi</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('lokasi.nilai-z', $lokasiIdSaatIni) }}"
                                               class="nav-link {{ request()->routeIs('lokasi.nilai-z') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i> {{-- Icon diubah --}}
                                                <p>Defuzzifikasi</p>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>

                        <li class="nav-header">PENGATURAN FUZZY</li>

                        <li class="nav-item">
                            <a href="{{ route('parameters.index') }}"
                            class="nav-link {{ request()->routeIs('parameters.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-sliders-h"></i>
                                <p>Kelola Parameter</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('aturan-fuzzy.index') }}"
                            class="nav-link {{ request()->routeIs('aturan-fuzzy.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-project-diagram"></i>
                                <p>Kelola Aturan Fuzzy</p>
                            </a>
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
