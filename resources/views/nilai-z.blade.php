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

        <!-- Penjelasan Fungsi Keanggotaan Output -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Fungsi Keanggotaan Output</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">Tidak Layak (0-50)</h5>
                            </div>
                            <div class="card-body text-center">
                                <p>Monoton Turun</p>
                                <div class="border p-2 bg-light">
                                    <strong>Z = 50 - (50 * α)</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">Layak (40-70)</h5>
                            </div>
                            <div class="card-body text-center">
                                <p>Linear/Segitiga</p>
                                <div class="border p-2 bg-light">
                                    <strong>Z = 40 + (30 * α)</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Sangat Layak (60-100)</h5>
                            </div>
                            <div class="card-body text-center">
                                <p>Monoton Naik</p>
                                <div class="border p-2 bg-light">
                                    <strong>Z = 60 + (40 * α)</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="position-relative mb-3" style="height: 150px;">
                    <!-- Visualisasi fungsi keanggotaan output -->
                    <div class="position-absolute w-100 h-100">
                        <!-- Fungsi keanggotaan Tidak Layak -->
                        <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <!-- Koordinat: (0,100), (50,0), (100,0) -->
                            <polygon points="0,100 50,0 100,0 100,0 0,0" fill="rgba(220, 53, 69, 0.3)" />
                            <polyline points="0,100 50,0" stroke="#dc3545" stroke-width="2" fill="none" />
                        </svg>

                        <!-- Fungsi keanggotaan Layak -->
                        <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <!-- Koordinat segitiga: (40,0), (55,100), (70,0) -->
                            <polygon points="40,0 55,100 70,0" fill="rgba(255, 193, 7, 0.3)" />
                            <polyline points="40,0 55,100 70,0" stroke="#ffc107" stroke-width="2" fill="none" />
                        </svg>

                        <!-- Fungsi keanggotaan Sangat Layak -->
                        <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <!-- Koordinat: (60,0), (100,100), (100,0) -->
                            <polygon points="60,0 100,100 100,0" fill="rgba(40, 167, 69, 0.3)" />
                            <polyline points="60,0 100,100" stroke="#28a745" stroke-width="2" fill="none" />
                        </svg>
                    </div>

                    <!-- Keterangan sumbu X -->
                    <div class="position-absolute w-100" style="bottom: -25px;">
                        <div class="d-flex justify-content-between">
                            <span>0</span>
                            <span>10</span>
                            <span>20</span>
                            <span>30</span>
                            <span>40</span>
                            <span>50</span>
                            <span>60</span>
                            <span>70</span>
                            <span>80</span>
                            <span>90</span>
                            <span>100</span>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <p><strong>Keterangan:</strong> Sumbu X menunjukkan nilai output (0-100), sumbu Y menunjukkan
                        derajat keanggotaan (0-1)</p>
                </div>
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

        <!-- Visualisasi posisi nilai Z -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Visualisasi Nilai Z untuk Setiap Aturan</h5>
            </div>
            <div class="card-body">
                <div class="position-relative" style="height: 120px;">
                    <div class="position-absolute w-100 h-100"
                        style="background: linear-gradient(to right, #dc3545, #ffc107, #28a745);">
                        <!-- Marker untuk posisi Z setiap aturan -->
                        @foreach ($hasilInferensi['aturan'] as $index => $aturan)
                            <div class="position-absolute"
                                style="left: {{ $aturan['z'] }}%; top: 0; transform: translateX(-50%);">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fa fa-caret-down text-dark" style="font-size: 20px;"></i>
                                    <span class="badge badge-dark">R{{ $aturan['nomor_aturan'] }}:
                                        {{ number_format($aturan['z'], 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-2">
                    <div class="d-flex justify-content-between">
                        <span>0</span>
                        <span>10</span>
                        <span>20</span>
                        <span>30</span>
                        <span>40</span>
                        <span>50</span>
                        <span>60</span>
                        <span>70</span>
                        <span>80</span>
                        <span>90</span>
                        <span>100</span>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <p>Posisi nilai Z dari masing-masing aturan pada spektrum kelayakan</p>
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

        <!-- Chart Visualisasi -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Visualisasi Perhitungan Skor Akhir</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:350px;">
                    <canvas id="finalScoreChart"></canvas>
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

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('finalScoreChart').getContext('2d');

                // Ambil data dari PHP
                const aturanData = @json($hasilInferensi['aturan']);
                const finalScore = {{ $skor }};

                // Persiapkan data untuk chart
                const labels = aturanData.map(aturan => 'R' + aturan.nomor_aturan);
                const alphaValues = aturanData.map(aturan => aturan.alpha);
                const zValues = aturanData.map(aturan => aturan.z);
                const alphaZValues = aturanData.map(aturan => aturan.alpha_z);

                // Buat chart
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Nilai Z',
                                data: zValues,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                                // Tetapkan yAxisID ke 'y1'
                                yAxisID: 'y1'
                            },
                            {
                                label: 'α-predikat',
                                data: alphaValues,
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                // Tetapkan yAxisID ke 'y'
                                yAxisID: 'y'
                            },
                            {
                                label: 'α-predikat × Z',
                                data: alphaZValues,
                                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                // Tetapkan yAxisID ke 'y2'
                                yAxisID: 'y2'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 1,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'α-predikat'
                                }
                            },
                            y1: {
                                beginAtZero: true,
                                max: 100,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Nilai Z'
                                },
                                grid: {
                                    drawOnChartArea: false
                                }
                            },
                            y2: {
                                beginAtZero: true,
                                display: false,
                                grid: {
                                    drawOnChartArea: false
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Skor Akhir: ' + finalScore.toFixed(2),
                                font: {
                                    size: 18
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    afterLabel: function(context) {
                                        const aturanIndex = context.dataIndex;
                                        const aturan = aturanData[aturanIndex];
                                        let result = '';
                                        result += 'Kategori: ' + aturan.hasil_
                                        result += 'Kategori: ' + aturan.hasil_then.replace(/_/g, ' ');
                                        return result;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    </div>
@endsection
