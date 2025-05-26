@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Step 3: Defuzzifikasi (Nilai Z & Skor Akhir)</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lokasi.form') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('lokasi.hasil', $lokasi->id) }}">Hasil Analisis</a></li>
    <li class="breadcrumb-item"><a href="{{ route('lokasi.inferensi', $lokasi->id) }}">Inferensi</a></li>
    <li class="breadcrumb-item active">Defuzzifikasi</li>
@endsection

@section('content')
    <div class="card">
         <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Step 3: Defuzzifikasi - {{ $lokasi->nama }}</h3>
            <a href="{{ route('lokasi.hasil', $lokasi->id) }}" class="btn btn-success btn-sm">
                Lihat Hasil Akhir <i class="fa fa-check"></i>
            </a>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> Defuzzifikasi mengubah hasil fuzzy kembali menjadi nilai crisp (Skor Akhir). Metode Tsukamoto menggunakan **Weighted Average (Rata-rata Terbobot)**. Pertama, hitung nilai **Z** untuk setiap aturan, lalu hitung skor akhir.
            </div>

            <div class="card mb-4">
                 <div class="card-header bg-lightblue">
                     <h5 class="mb-0 card-title"><i class="fas fa-stream"></i> Perhitungan Nilai Z per Aturan</h5>
                 </div>
                 <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Hasil (THEN)</th>
                                    <th>α-predikat</th>
                                    <th>Nilai Z</th>
                                    <th>α * Z</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($hasilInferensi['aturan'] as $aturan)
                                    <tr>
                                        <td class="text-center">{{ $aturan['nomor_aturan'] }}</td>
                                        <td>{{ ucwords(str_replace('_', ' ', $aturan['hasil_then'])) }}</td>
                                        <td class="text-center">{{ number_format($aturan['alpha'], 4) }}</td>
                                        <td class="text-center">{{ number_format($aturan['z'], 2) }}</td>
                                        <td class="text-center">{{ number_format($aturan['alpha_z'], 4) }}</td>
                                    </tr>
                                @empty
                                     <tr><td colspan="5" class="text-center alert alert-warning">Tidak ada aturan terpicu.</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-light font-weight-bold">
                                <tr>
                                    <td colspan="2" class="text-right">Total:</td>
                                    <td class="text-center">Σ α = {{ number_format($hasilInferensi['total_alpha'], 4) }}</td>
                                    <td></td>
                                    <td class="text-center">Σ (α*Z) = {{ number_format($hasilInferensi['total_alpha_z'], 4) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
             </div>

            <div class="card">
                <div class="card-header bg-success">
                    <h5 class="mb-0 card-title text-white"><i class="fas fa-bullseye"></i> Perhitungan Skor Akhir (Weighted Average)</h5>
                </div>
                 <div class="card-body text-center">
                     <h5>Rumus: Skor Akhir = Σ(α<sub>i</sub> × Z<sub>i</sub>) / Σα<sub>i</sub></h5>
                     <hr>
                     @if($hasilInferensi['total_alpha'] > 0)
                         <h4>Skor Akhir = {{ number_format($hasilInferensi['total_alpha_z'], 4) }} / {{ number_format($hasilInferensi['total_alpha'], 4) }}</h4>
                         <h2 class="font-weight-bold display-4 text-success">{{ number_format($skor, 2) }}</h2>
                     @else
                         <h2 class="font-weight-bold display-4 text-danger">0.00</h2>
                         <p class="text-danger">(Tidak ada aturan terpicu, skor akhir 0).</p>
                     @endif
                 </div>
            </div>
        </div>
         <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('lokasi.inferensi', $lokasi->id) }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali ke Step 2
            </a>
            <a href="{{ route('lokasi.hasil', $lokasi->id) }}" class="btn btn-success">
                Lihat Hasil Akhir <i class="fa fa-check"></i>
            </a>
        </div>
    </div>
@endsection