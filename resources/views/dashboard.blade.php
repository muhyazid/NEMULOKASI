@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Dashboard</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-map-marker-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Lokasi</span>
                    <span class="info-box-number">{{ $totalLokasi ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Lokasi Layak</span>
                    <span class="info-box-number">{{ $lokasiLayak ?? 0 }}</span>
                </div>
            </div>
        </div>

        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Lokasi Kurang Layak</span>
                    <span class="info-box-number">{{ $lokasiKurangLayak ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Lokasi Tidak Layak</span>
                    <span class="info-box-number">{{ $lokasiTidakLayak ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main row -->
    <div class="row">
        <!-- Left col -->
        <div class="col-md-8">
            <!-- TABLE: LATEST LOCATIONS -->
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Lokasi Terbaru</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Lokasi</th>
                                    <th>Alamat</th>
                                    <th>Skor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lokasiTerbaru ?? [] as $lokasi)
                                    <tr>
                                        <td><a href="{{ route('lokasi.hasil', $lokasi->id) }}">{{ $lokasi->id }}</a></td>
                                        <td>{{ $lokasi->nama }}</td>
                                        <td>{{ $lokasi->alamat }}</td>
                                        <td>{{ number_format($lokasi->skor_lokasi, 2) }}</td>
                                        <td>
                                            @if ($lokasi->skor_lokasi >= 70)
                                                <span class="badge badge-success">Layak</span>
                                            @elseif($lokasi->skor_lokasi >= 50)
                                                <span class="badge badge-warning">Kurang Layak</span>
                                            @else
                                                <span class="badge badge-danger">Tidak Layak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data lokasi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('lokasi.form') }}" class="btn btn-sm btn-primary float-right">Tambah Lokasi Baru</a>
                </div>
            </div>

            <!-- PARAMETER CARD -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Parameter Fuzzy Terdaftar</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Parameter</th>
                                    <th>Nilai Fuzzy</th>
                                    <th>Nilai Crisp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parameterFuzzy ?? [] as $parameter)
                                    <tr>
                                        <td>{{ $parameter->nama_parameter }}</td>
                                        <td>{{ $parameter->nilai_fuzzy }}</td>
                                        <td>{{ $parameter->nilai_crisp }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada parameter fuzzy</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right col -->
        <div class="col-md-4">
            <!-- AKSES CEPAT -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Akses Cepat</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('lokasi.form') }}" class="nav-link">
                                <i class="fas fa-plus mr-2"></i> Input Data Lokasi Baru
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('parameter-fuzzy.form') }}" class="nav-link">
                                <i class="fas fa-sliders-h mr-2"></i> Atur Parameter Fuzzy
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- INFORMATION CARD -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kriteria Penilaian Lokasi</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <b>Aksesibilitas</b>
                            <p class="text-muted small mb-0">Kemudahan akses ke lokasi</p>
                        </li>
                        <li class="list-group-item">
                            <b>Visibilitas</b>
                            <p class="text-muted small mb-0">Kemudahan lokasi untuk terlihat</p>
                        </li>
                        <li class="list-group-item">
                            <b>Daya Beli</b>
                            <p class="text-muted small mb-0">Daya beli masyarakat sekitar</p>
                        </li>
                        <li class="list-group-item">
                            <b>Persaingan</b>
                            <p class="text-muted small mb-0">Tingkat persaingan bisnis sekitar</p>
                        </li>
                        <li class="list-group-item">
                            <b>Infrastruktur</b>
                            <p class="text-muted small mb-0">Kondisi infrastruktur pendukung</p>
                        </li>
                        <li class="list-group-item">
                            <b>Lingkungan Sekitar</b>
                            <p class="text-muted small mb-0">Kondisi lingkungan disekitar lokasi</p>
                        </li>
                        <li class="list-group-item">
                            <b>Parkir</b>
                            <p class="text-muted small mb-0">Ketersediaan lahan parkir</p>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- INFO APLIKASI -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Aplikasi</h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-book mr-1"></i> Nama Aplikasi</strong>
                    <p class="text-muted">Fuzzy Lokasi - Sistem Analisis Kelayakan Lokasi</p>
                    <hr>
                    <strong><i class="fas fa-pencil-alt mr-1"></i> Metode</strong>
                    <p class="text-muted">Fuzzy Tsukamoto</p>
                    <hr>
                    <strong><i class="fas fa-file-code mr-1"></i> Versi</strong>
                    <p class="text-muted">1.0.0</p>
                </div>
            </div>
        </div>
    </div>
@endsection
