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
        <div class="card-header"> {{-- Tombol "Analisis Lokasi Baru" dihapus dari sini sesuai kode Anda --}}
            <h3 class="card-title">Hasil Perhitungan: <strong>{{ $lokasi->nama }}</strong></h3>
        </div>

        <div class="card-body">
            @php
                $alertClass = $kelayakan == 'Layak' ? 'alert-success' : 'alert-danger';
                $iconKelayakan = $kelayakan == 'Layak' ? 'fas fa-thumbs-up' : 'fas fa-thumbs-down'; // Menggunakan Font Awesome
            @endphp
            <div class="alert {{ $alertClass }}">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3>Skor Akhir Lokasi: <strong>{{ number_format($skor, 2) }}</strong></h3>
                        <h4>Kelayakan Lokasi: <strong>{{ $kelayakan }}</strong></h4>
                        <p class="mb-0">(Threshold Kelayakan >= {{ $threshold }})</p>
                    </div>
                    <div class="col-md-4 text-md-right text-center mt-3 mt-md-0">
                        <div style="font-size: 3.5rem; font-weight: bold;"> {{-- Ukuran ikon disesuaikan --}}
                            <i class="{{ $iconKelayakan }}"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-lightblue">
                    <h4 class="mb-0 card-title"><i class="fas fa-info-circle mr-2"></i>Detail Lokasi & Input</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Lokasi:</strong> {{ $lokasi->nama }}</p>
                            <p><strong>Tanggal Analisis:</strong> {{ $lokasi->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Input Parameter:</strong></p>
                            <ul class="list-group list-group-flush">
                                @php
                                    // Ambil parameter yang relevan dari model Lokasi
                                    $parameterFields = array_diff((new App\Models\Lokasi)->getFillable(), ['nama', 'skor_lokasi', 'kelayakan']);
                                @endphp
                                @foreach ($parameterFields as $p)
                                    @if(isset($lokasi->$p) && !is_null($lokasi->$p))
                                    <li class="list-group-item px-0 py-1"> {{-- Padding lebih kecil --}}
                                        {{ Str::ucfirst(str_replace('_', ' ', $p)) }}: <strong>{{ $lokasi->$p }}</strong>
                                    </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-info">
                    <h4 class="mb-0 card-title"><i class="fas fa-shoe-prints mr-2"></i>Detail Langkah Perhitungan</h4>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('lokasi.fuzzifikasi', $lokasi->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-wave-square mr-2"></i>Step 1: Fuzzifikasi
                        </a>
                        <a href="{{ route('lokasi.inferensi', $lokasi->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-cogs mr-2"></i>Step 2: Inferensi (Aturan Fuzzy)
                        </a>
                        <a href="{{ route('lokasi.nilai-z', $lokasi->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calculator mr-2"></i>Step 3: Defuzzifikasi (Nilai Z & Skor Akhir)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer dengan tombol aksi yang dikelompokkan --}}
        <div class="card-footer card-footer-actions">
            <a href="#" onclick="window.print(); return false;" class="btn btn-outline-secondary">
                <i class="fas fa-print"></i> Cetak Hasil
            </a>
            <a href="{{ route('lokasi.form') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Analisis Lokasi Baru
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Jika ada script khusus untuk halaman ini --}}
@endpush
