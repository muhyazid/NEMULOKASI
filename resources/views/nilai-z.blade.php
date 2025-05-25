@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Perhitungan Nilai Z</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lokasi.form') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('lokasi.hasil', $lokasi->id) }}">Hasil Analisis</a></li>
    <li class="breadcrumb-item"><a href="{{ route('lokasi.fuzzifikasi', $lokasi->id) }}">Fuzzifikasi</a></li>
    <li class="breadcrumb-item"><a href="{{ route('lokasi.inferensi', $lokasi->id) }}">Inferensi</a></li>
    <li class="breadcrumb-item active">Perhitungan Nilai Z</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Step 3: Perhitungan Nilai Z</h3>
            <div>
                <a href="{{ route('lokasi.inferensi', $lokasi->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left"></i> Step 2: Inferensi
                </a>
                <a href="{{ route('lokasi.hasil', $lokasi->id) }}" class="btn btn-primary btn-sm">
                    Lihat Hasil <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="alert alert-info mb-4">
            <i class="fa fa-info-circle"></i> Nilai Z adalah hasil defuzzifikasi individu untuk setiap aturan.
            Dalam metode Tsukamoto, nilai Z diperoleh dengan memasukkan nilai α-predikat ke fungsi keanggotaan output.
            Kemudian hasil akhir dihitung menggunakan metode weighted average.
        </div>

        <!-- Tampilkan Informasi Lokasi -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Detail Lokasi</h4>
                <p><strong>Nama Lokasi:</strong> {{ $lokasi->nama }}</p>
                <p><strong>Alamat:</strong> {{ $lokasi->alamat }}</p>
            </div>
        </div>

        <!-- Tabel perhitungan nilai Z -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Perhitungan Nilai Z untuk Setiap Aturan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>No</th>
                                <th>α-predikat</th>
                                <th>Kategori Hasil</th>
                                <th>Rumus Z</th>
                                <th>Perhitungan</th>
                                <th>Nilai Z</th>
                                <th>α-predikat * Z</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hasilInferensi['aturan'] as $index => $aturan)
                                <tr>
                                    <td>{{ $aturan['nomor_aturan'] }}</td>
                                    <td>{{ number_format($aturan['alpha'], 2) }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $aturan['hasil_then'])) }}</td>
                                    <td>
                                        @switch($aturan['hasil_then'])
                                            @case('tidak_layak')
                                                Z = 50 - (50 * α)
                                            @break

                                            @case('layak')
                                                Z = 40 + (30 * α)
                                            @break

                                            @case('sangat_layak')
                                                Z = 60 + (40 * α)
                                            @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($aturan['hasil_then'])
                                            @case('tidak_layak')
                                                Z = 50 - (50 * {{ number_format($aturan['alpha'], 2) }})
                                            @break

                                            @case('layak')
                                                Z = 40 + (30 * {{ number_format($aturan['alpha'], 2) }})
                                            @break

                                            @case('sangat_layak')
                                                Z = 60 + (40 * {{ number_format($aturan['alpha'], 2) }})
                                            @break
                                        @endswitch
                                    </td>
                                    <td>{{ number_format($aturan['z'], 2) }}</td>
                                    <td>{{ number_format($aturan['alpha_z'], 4) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="5" class="text-right">Total:</th>
                                <th>Σ α = {{ number_format($hasilInferensi['total_alpha'], 2) }}</th>
                                <th>Σ (α*Z) = {{ number_format($hasilInferensi['total_alpha_z'], 4) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Penjelasan Rumus Weighted Average -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Perhitungan Skor Akhir dengan Metode Weighted Average</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Rumus Weighted Average:</h5>
                        <div class="border p-3 bg-light mb-3 text-center">
                            <h4>Z = Σ(α<sub>i</sub> × z<sub>i</sub>) / Σα<sub>i</sub></h4>
                        </div>
                        <p>Dimana:</p>
                        <ul>
                            <li>Z = Nilai output akhir (skor lokasi)</li>
                            <li>α<sub>i</sub> = Nilai alpha pada aturan ke-i</li>
                            <li>z<sub>i</sub> = Nilai z pada aturan ke-i</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Perhitungan:</h5>
                        <div class="border p-3 bg-light text-center">
                            <p>Z = {{ number_format($hasilInferensi['total_alpha_z'], 4) }} /
                                {{ number_format($hasilInferensi['total_alpha'], 2) }}</p>
                            <h4>Z = {{ number_format($skor, 2) }}</h4>
                        </div>
                        <div class="mt-3">
                            <p><strong>Hasil Interpretasi:</strong></p>
                            <div
                                class="alert {{ $skor >= 0.7 ? 'alert-success' : ($skor >= 0.5 ? 'alert-warning' : 'alert-danger') }}">
                                <strong>
                                    @if ($skor >= 0.7)
                                        Sangat Cocok
                                    @elseif ($skor >= 0.5)
                                        Cukup Cocok
                                    @else
                                        Kurang Cocok
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('lokasi.hasil', $lokasi->id) }}" class="btn btn-secondary">
                <i class="fa fa-home"></i> Kembali ke Hasil
            </a>
            <div>
                <a href="{{ route('lokasi.inferensi', $lokasi->id) }}" class="btn btn-outline-primary">
                    <i class="fa fa-arrow-left"></i> Step 2: Inferensi
                </a>
                <a href="{{ route('lokasi.form') }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> Analisis Lokasi Baru
                </a>
            </div>
        </div>
@endsection
