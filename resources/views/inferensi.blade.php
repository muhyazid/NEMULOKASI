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

        <!-- Penjelasan Proses Inferensi -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fa fa-info-circle"></i> Penjelasan Proses Inferensi Fuzzy</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>1. Pembentukan Aturan Fuzzy</h5>
                        <p>Aturan fuzzy dibentuk dalam format IF-THEN:</p>
                        <div class="bg-light p-3 mb-3">
                            <p>IF (parameter 1 is kategori X) AND (parameter 2 is kategori Y) AND ... THEN (output is
                                kategori
                                Z)</p>
                        </div>
                        <p>Contoh aturan:</p>
                        <div class="bg-light p-3">
                            <p>IF (Aksesibilitas is Tinggi) AND (Visibilitas is Tinggi) AND (Daya Beli is Tinggi) THEN
                                (Lokasi
                                is Sangat Layak)</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>2. Perhitungan Nilai α-predikat</h5>
                        <p>Nilai α-predikat dihitung dengan menggunakan operator AND (mengambil nilai minimum):</p>
                        <div class="bg-light p-3 mb-3">
                            <p>α-predikat = min(μ parameter 1, μ parameter 2, ...)</p>
                        </div>
                        <p>Contoh perhitungan:</p>
                        <div class="bg-light p-3">
                            <p>α-predikat = min(0.85, 0.75, 0.60) = 0.60</p>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <h5><i class="fa fa-lightbulb"></i> Catatan Penting:</h5>
                            <p>Nilai α-predikat yang didapatkan dari proses inferensi akan digunakan untuk menghitung nilai
                                Z
                                pada setiap aturan. Semakin tinggi nilai α-predikat, semakin besar kontribusi aturan
                                tersebut
                                terhadap nilai akhir.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Visualisasi Alpha Predikat -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa fa-chart-bar"></i> Visualisasi Nilai α-predikat</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="alphaPredikatChart"></canvas>
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

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('alphaPredikatChart').getContext('2d');

                // Ambil data dari PHP
                const aturanData = @json($hasilInferensi['aturan']);

                // Persiapkan data untuk chart
                const labels = aturanData.map(aturan => 'Aturan ' + aturan.nomor_aturan);
                const alphaValues = aturanData.map(aturan => aturan.alpha);

                // Tentukan warna berdasarkan nilai alpha
                const backgroundColors = alphaValues.map(alpha => {
                    if (alpha > 0.7) return 'rgba(40, 167, 69, 0.7)'; // Hijau untuk nilai tinggi
                    else if (alpha > 0.4) return 'rgba(255, 193, 7, 0.7)'; // Kuning untuk nilai sedang
                    else return 'rgba(220, 53, 69, 0.7)'; // Merah untuk nilai rendah
                });

                // Buat chart
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Nilai α-predikat',
                            data: alphaValues,
                            backgroundColor: backgroundColors,
                            borderColor: backgroundColors.map(color => color.replace('0.7', '1')),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 1,
                                title: {
                                    display: true,
                                    text: 'Nilai α-predikat'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Aturan'
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    afterLabel: function(context) {
                                        const aturanIndex = context.dataIndex;
                                        const aturan = aturanData[aturanIndex];
                                        let result = '';
                                        result += 'Hasil: ' + aturan.hasil_then.replace('_', ' ');
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
