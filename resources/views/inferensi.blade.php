@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Step 2: Proses Inferensi</h1>
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
            <h3 class="card-title">Step 2: Inferensi - {{ $lokasi->nama }}</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> Inferensi adalah proses mengevaluasi aturan fuzzy yang ada berdasarkan derajat keanggotaan (μ) dari Fuzzifikasi. Untuk setiap aturan, dihitung nilai **α-predikat** (alpha), yaitu nilai **MINIMUM** dari derajat keanggotaan semua kondisi dalam aturan tersebut.
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="bg-lightblue">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Aturan</th>
                            <th>Kondisi (IF) & Derajat (μ)</th>
                            <th class="text-center">Nilai α-predikat</th>
                            <th>Hasil (THEN)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hasilInferensi['aturan'] as $aturan)
                            <tr>
                                <td class="text-center">{{ $aturan['nomor_aturan'] }}</td>
                                <td>{{ $aturan['nama_aturan'] }}</td>
                                <td>
                                    <ul class="list-unstyled mb-0 pl-3">
                                        @foreach ($aturan['kondisi_if'] as $kondisi)
                                            <li>
                                                {{ ucfirst($kondisi['parameter']) }} IS {{ $kondisi['himpunan'] }}
                                                (μ={{ number_format($kondisi['derajat'], 4) }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-center font-weight-bold" style="font-size: 1.2rem; vertical-align: middle;">
                                    <span class="badge badge-success p-2">
                                        {{ number_format($aturan['alpha'], 4) }}
                                    </span>
                                </td>
                                <td style="vertical-align: middle;">
                                    Kelayakan = <strong>{{ ucwords(str_replace('_', ' ', $aturan['hasil_then'])) }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center alert alert-warning">Tidak ada aturan yang terpicu (Nilai Alpha > 0). Periksa kembali aturan Anda atau input data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p class="text-muted mt-2">Catatan: Hanya aturan dengan α-predikat > 0 yang ditampilkan dan digunakan dalam perhitungan selanjutnya.</p>
        </div>
         <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('lokasi.fuzzifikasi', $lokasi->id) }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali ke Step 1
            </a>
            <a href="{{ route('lokasi.nilai-z', $lokasi->id) }}" class="btn btn-primary">
                Lanjut ke Step 3 <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
@endsection