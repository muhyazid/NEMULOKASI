@extends('layouts.main') {{-- Sesuaikan dengan nama layout utama Anda --}}

@section('page-title')
    <h1 class="m-0">Dashboard Sistem Pendukung Keputusan Lokasi Bisnis</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-map-marked-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Lokasi Dianalisis</span>
                    <span class="info-box-number">{{ $jumlahLokasi }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-sliders-h"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Parameter Fuzzy</span>
                    <span class="info-box-number">{{ $jumlahParameter }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-project-diagram"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Aturan Fuzzy</span>
                    <span class="info-box-number">{{ $jumlahAturan }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bullhorn"></i>
                        Selamat Datang di Sistem Pendukung Keputusan Pemilihan Lokasi Bisnis
                    </h3>
                </div>
                <div class="card-body">
                    <p class="lead">Sistem ini membantu Anda menganalisis dan menentukan kelayakan suatu lokasi untuk bisnis menggunakan metode Fuzzy Tsukamoto.</p>
                    <h5>Bagaimana Memulai?</h5>
                    <ol>
                        {{-- Modifikasi untuk Admin --}}
                        @if(Auth::check() && Auth::user()->role == 'admin')
                        <li>
                            <strong>Kelola Parameter:</strong> Pastikan semua parameter penilaian (seperti Aksesibilitas, Visibilitas, dll.) beserta himpunan fuzzynya (Rendah, Sedang, Tinggi) telah diatur dengan benar. Anda bisa mengatur nilai crisp input dan batasan fungsi keanggotaan (MF a,b,c) untuk setiap himpunan fuzzy.
                            <a href="{{ route('parameters.index') }}" class="btn btn-sm btn-outline-primary ml-2">Ke Kelola Parameter <i class="fas fa-arrow-circle-right"></i></a>
                        </li>
                        <li class="mt-2">
                            <strong>Kelola Aturan Fuzzy:</strong> Buat atau perbarui aturan IF-THEN yang akan digunakan sistem untuk melakukan inferensi. Aturan ini adalah "otak" dari sistem.
                            <a href="{{ route('aturan-fuzzy.index') }}" class="btn btn-sm btn-outline-primary ml-2">Ke Kelola Aturan <i class="fas fa-arrow-circle-right"></i></a>
                        </li>
                        @endif
                        <li class="mt-2">
                            <strong>Analisis Lokasi Baru:</strong> Setelah parameter dan aturan siap, Anda bisa mulai menganalisis lokasi baru dengan menginput data lokasi dan memilih nilai linguistik untuk setiap parameter.
                            <a href="{{ route('lokasi.form') }}" class="btn btn-sm btn-primary ml-2">Mulai Analisis Baru <i class="fas fa-map-marker-alt"></i></a>
                        </li>
                    </ol>
                    <p class="mt-3">Hasil analisis akan memberikan skor kelayakan dan detail perhitungan langkah demi langkah.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history"></i> Riwayat Analisis Lokasi Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Lokasi</th>
                                    <th>Alamat</th>
                                    <th>Skor</th>
                                    <th>Kelayakan</th>
                                    <th>Tanggal Analisis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lokasiTerbaru as $lokasi)
                                <tr>
                                    <td>{{ $lokasi->nama }}</td>
                                    <td>{{ Str::limit($lokasi->alamat, 50) ?: '-' }}</td>
                                    <td><span class="badge badge-{{ $lokasi->skor_lokasi >= (isset($threshold) ? $threshold : 60) ? 'success' : 'danger' }}">{{ number_format($lokasi->skor_lokasi, 2) }}</span></td>
                                    <td>
                                        @if($lokasi->kelayakan == 'Layak')
                                            <span class="badge badge-success">Layak</span>
                                        @else
                                            <span class="badge badge-danger">Kurang Layak</span>
                                        @endif
                                    </td>
                                    <td>{{ $lokasi->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <a href="{{ route('lokasi.hasil', $lokasi->id) }}" class="btn btn-xs btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada riwayat analisis.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($jumlahLokasi > 5)
                <div class="card-footer text-center">
                    {{-- Jika Anda punya halaman daftar semua lokasi, link ke sana --}}
                    {{-- <a href="#">Lihat Semua Riwayat Lokasi</a> --}}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Jika ada script khusus untuk dashboard --}}
@endsection