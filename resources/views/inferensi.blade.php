@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Proses Inferensi</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lokasi.form') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('lokasi.hasil', $lokasi->id) }}">Hasil Analisis</a></li>
    <li class="breadcrumb-item"><a href="{{ route('lokasi.fuzzifikasi', $lokasi->id) }}">Fuzzifikasi</a></li>
    <li class="breadcrumb-item active">Inferensi</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Step 2: Proses Inferensi</h3>
            <div>
                <a href="{{ route('lokasi.fuzzifikasi', $lokasi->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left"></i> Step 1: Fuzzifikasi
                </a>
                <a href="{{ route('lokasi.nilai-z', $lokasi->id) }}" class="btn btn-primary btn-sm">
                    Step 3: Nilai Z <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="alert alert-info mb-4">
            <i class="fa fa-info-circle"></i> Inferensi adalah proses penerapan aturan fuzzy untuk menentukan output
            berdasarkan kondisi input. Dalam metode Tsukamoto, setiap aturan akan menghasilkan nilai α-predikat (nilai
            alpha)
            yang selanjutnya digunakan untuk menghitung nilai Z.
        </div>

        <!-- Tampilkan Informasi Lokasi -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Detail Lokasi</h4>
                <p><strong>Nama Lokasi:</strong> {{ $lokasi->nama }}</p>
                <p><strong>Alamat:</strong> {{ $lokasi->alamat }}</p>
            </div>
        </div>

        <!-- Tabel Inferensi -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tabel Inferensi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>No</th>
                                <th>Kondisi (IF)</th>
                                <th>Nilai α-predikat</th>
                                <th>Hasil (THEN)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hasilInferensi['aturan'] as $index => $aturan)
                                <tr>
                                    <td class="text-center">{{ $aturan['nomor_aturan'] }}</td>
                                    <td>
                                        <p>IF</p>
                                        <ul class="list-unstyled">
                                            @foreach ($aturan['kondisi_if'] as $kondisi)
                                                <li>
                                                    <strong>{{ ucfirst($kondisi['parameter']) }}</strong> is
                                                    <strong>{{ ucfirst($kondisi['kategori']) }}</strong>
                                                    <span
                                                        class="badge {{ $kondisi['derajat'] > 0.5 ? 'badge-success' : 'badge-secondary' }}">
                                                        μ = {{ number_format($kondisi['derajat'], 2) }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span
                                            class="display-4 font-weight-bold {{ $aturan['alpha'] > 0.5 ? 'text-success' : 'text-muted' }}">
                                            {{ number_format($aturan['alpha'], 2) }}
                                        </span>
                                        <div class="progress mt-2" style="height: 20px;">
                                            <div class="progress-bar {{ $aturan['alpha'] > 0.5 ? 'bg-success' : 'bg-secondary' }}"
                                                role="progressbar" style="width: {{ $aturan['alpha'] * 100 }}%"
                                                aria-valuenow="{{ $aturan['alpha'] * 100 }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ number_format($aturan['alpha'] * 100, 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <strong>THEN Lokasi
                                            {{ ucwords(str_replace('_', ' ', $aturan['hasil_then'])) }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('lokasi.hasil', $lokasi->id) }}" class="btn btn-secondary">
                <i class="fa fa-home"></i> Kembali ke Hasil
            </a>
            <div>
                <a href="{{ route('lokasi.fuzzifikasi', $lokasi->id) }}" class="btn btn-outline-primary">
                    <i class="fa fa-arrow-left"></i> Step 1: Fuzzifikasi
                </a>
                <a href="{{ route('lokasi.nilai-z', $lokasi->id) }}" class="btn btn-primary">
                    Step 3: Nilai Z <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
    @endsection

 