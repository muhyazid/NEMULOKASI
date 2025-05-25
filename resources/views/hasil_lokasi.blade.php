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
            <h3 class="card-title">Hasil Perhitungan Skor Lokasi</h3>
            <a href="{{ route('lokasi.form') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Analisis Lokasi Baru
            </a>
        </div>

        <!-- Tampilkan Skor Lokasi -->
        <div class="alert {{ $skor >= 70 ? 'alert-success' : ($skor >= 50 ? 'alert-warning' : 'alert-danger') }}">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Skor Lokasi: <strong>{{ number_format($skor, 2) }}</strong></h3>
                    <p class="mb-0">Kelayakan Lokasi:
                        <strong>
                            @if ($skor >= 70)
                                Sangat Cocok
                            @elseif ($skor >= 50)
                                Cukup Cocok
                            @else
                                Kurang Cocok
                            @endif
                        </strong>
                    </p>
                </div>
                <div class="text-right">
                    <h1 class="display-4 mb-0">{{ number_format($skor, 2) }}</h1>
                </div>
            </div>
        </div>

        <!-- Tampilkan Informasi Lokasi -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Detail Lokasi</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama Lokasi:</strong> {{ $lokasi->nama }}</p>
                        <p><strong>Alamat:</strong> {{ $lokasi->alamat }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tanggal Analisis:</strong> {{ date('d F Y', strtotime($lokasi->created_at)) }}</p>
                        <p><strong>Status:</strong>
                            <span
                                class="badge {{ $skor >= 0.7 ? 'badge-success' : ($skor >= 0.5 ? 'badge-warning' : 'badge-danger') }}">
                                @if ($skor >= 0.7)
                                    Sangat Cocok
                                @elseif ($skor >= 0.5)
                                    Cukup Cocok
                                @else
                                    Kurang Cocok
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigasi ke Proses Step by Step -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">Proses Perhitungan Step by Step</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fa fa-calculator fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Step 1: Fuzzifikasi</h5>
                                <p class="card-text">Lihat proses konversi nilai crisp menjadi derajat keanggotaan fuzzy</p>
                                <a href="{{ route('lokasi.fuzzifikasi', $lokasi->id) }}" class="btn btn-primary">
                                    <i class="fa fa-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fa fa-cogs fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Step 2: Inferensi</h5>
                                <p class="card-text">Lihat penerapan aturan fuzzy dan perhitungan α-predikat</p>
                                <a href="{{ route('lokasi.inferensi', $lokasi->id) }}" class="btn btn-info">
                                    <i class="fa fa-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fa fa-chart-line fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Step 3: Perhitungan Nilai Z</h5>
                                <p class="card-text">Lihat perhitungan nilai Z dan proses defuzzifikasi</p>
                                <a href="{{ route('lokasi.nilai-z', $lokasi->id) }}" class="btn btn-success">
                                    <i class="fa fa-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('lokasi.form') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali ke Form
            </a>
            <div>
                <a href="#" onclick="window.print()" class="btn btn-outline-dark mr-2">
                    <i class="fa fa-print"></i> Cetak Hasil
                </a>
                <a href="{{ route('lokasi.form') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Analisis Lokasi Baru
                </a>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('scoreGauge').getContext('2d');

                // Ambil skor dari PHP
                const score = {{ $skor }};

                // Fungsi untuk menentukan gradasi warna berdasarkan skor
                function getGradient(ctx) {
                    const gradient = ctx.createLinearGradient(0, 0, 400, 0);
                    gradient.addColorStop(0, '#dc3545'); // Merah (kurang cocok)
                    gradient.addColorStop(0.5, '#ffc107'); // Kuning (cukup cocok)
                    gradient.addColorStop(1, '#28a745'); // Hijau (sangat cocok)
                    return gradient;
                }

                // Buat chart gauge
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [score, 1 - score],
                            backgroundColor: [
                                getGradient(ctx),
                                '#f1f1f1'
                            ],
                            borderWidth: 0,
                            circumference: 180,
                            rotation: 270
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '80%',
                        plugins: {
                            tooltip: {
                                enabled: false
                            },
                            legend: {
                                display: false
                            }
                        },
                        layout: {
                            padding: {
                                bottom: 30
                            }
                        }
                    },
                    plugins: [{
                        id: 'gaugeText',
                        afterDraw: (chart) => {
                            const {
                                ctx,
                                width,
                                height
                            } = chart;
                            ctx.save();

                            // Tampilkan skor
                            const fontSize = Math.round(width / 10);
                            ctx.font = `bold ${fontSize}px Arial`;
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillStyle = '#333';
                            ctx.fillText(score.toFixed(2), width / 2, height - height / 3);

                            // Tampilkan kategori
                            let category;
                            let textColor;

                            if (score >= 0.7) {
                                category = 'Sangat Cocok';
                                textColor = '#28a745';
                            } else if (score >= 0.5) {
                                category = 'Cukup Cocok';
                                textColor = '#ffc107';
                            } else {
                                category = 'Kurang Cocok';
                                textColor = '#dc3545';
                            }

                            ctx.font = `${fontSize / 2}px Arial`;
                            ctx.fillStyle = textColor;
                            ctx.fillText(category, width / 2, height - height / 5);

                            // Tambahkan label untuk skala
                            ctx.font = `${fontSize / 2.5}px Arial`;
                            ctx.fillStyle = '#999';

                            // Label kiri (0.0)
                            ctx.textAlign = 'left';
                            ctx.fillText('0.0', width * 0.05, height - height / 10);

                            // Label tengah (0.5)
                            ctx.textAlign = 'center';
                            ctx.fillText('0.5', width / 2, height - height / 10);

                            // Label kanan (1.0)
                            ctx.textAlign = 'right';
                            ctx.fillText('1.0', width * 0.95, height - height / 10);

                            ctx.restore();
                        }
                    }]
                });
            });
        </script>
    </div>
@endsection
