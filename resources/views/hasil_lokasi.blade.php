@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Hasil Analisis Lokasi</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lokasi.form') }}">Home</a></li>
    <li class="breadcrumb-item active">Hasil Analisis</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Hasil Perhitungan: {{ $lokasi->nama }}</h3>
            <a href="{{ route('lokasi.form') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Analisis Lokasi Baru
            </a>
        </div>

        <div class="card-body">
            @php
                $alertClass = $kelayakan == 'Layak' ? 'alert-success' : 'alert-danger';
            @endphp
            <div class="alert {{ $alertClass }}">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3>Skor Akhir Lokasi: <strong>{{ number_format($skor, 2) }}</strong></h3>
                        <h4>Kelayakan Lokasi: <strong>{{ $kelayakan }}</strong></h4>
                         <p class="mb-0">(Threshold Kelayakan >= {{ $threshold }})</p>
                    </div>
                    <div class="col-md-4 text-md-right text-center mt-3 mt-md-0">
                        <div style="font-size: 4rem; font-weight: bold;">
                             {{ $kelayakan == 'Layak' ? '👍' : '👎' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-lightblue">
                    <h4 class="mb-0 card-title"><i class="fas fa-info-circle"></i> Detail Lokasi & Input</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Lokasi:</strong> {{ $lokasi->nama }}</p>
                            <p><strong>Alamat:</strong> {{ $lokasi->alamat ?: '-' }}</p>
                            <p><strong>Tanggal Analisis:</strong> {{ $lokasi->created_at->format('d F Y H:i') }}</p>
                        </div>
                         <div class="col-md-6">
                            <p><strong>Input Parameter:</strong></p>
                             <ul class="list-group list-group-flush">
                                 @foreach (['aksesibilitas', 'visibilitas', 'daya_beli', 'persaingan', 'infrastruktur', 'lingkungan_sekitar', 'parkir'] as $p)
                                     @if(isset($lokasi->$p))
                                     <li class="list-group-item">{{ ucfirst(str_replace('_', ' ', $p)) }}: <strong>{{ $lokasi->$p }}</strong></li>
                                     @endif
                                 @endforeach
                             </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-info">
                    <h4 class="mb-0 card-title"><i class="fas fa-shoe-prints"></i> Detail Langkah Perhitungan</h4>
                </div>
                <div class="card-body">
                    <div class="list-group">
                         <a href="{{ route('lokasi.fuzzifikasi', $lokasi->id) }}" class="list-group-item list-group-item-action">
                             <i class="fa fa-wave-square"></i> Step 1: Fuzzifikasi
                         </a>
                          <a href="{{ route('lokasi.inferensi', $lokasi->id) }}" class="list-group-item list-group-item-action">
                             <i class="fa fa-cogs"></i> Step 2: Inferensi (Aturan Fuzzy)
                         </a>
                         <a href="{{ route('lokasi.nilai-z', $lokasi->id) }}" class="list-group-item list-group-item-action">
                             <i class="fa fa-calculator"></i> Step 3: Defuzzifikasi (Nilai Z & Skor Akhir)
                         </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('lokasi.form') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali ke Form
            </a>
            <a href="#" onclick="window.print()" class="btn btn-outline-dark">
                <i class="fa fa-print"></i> Cetak Hasil
            </a>
        </div>
    </div>
@endsection