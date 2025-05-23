@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Step 1: Proses Fuzzifikasi</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lokasi.form') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('lokasi.hasil', $lokasi->id) }}">Hasil Perhitungan</a></li>
    <li class="breadcrumb-item active">Fuzzifikasi</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Step 1: Proses Fuzzifikasi</h3>
            <div>
                <a href="{{ route('lokasi.hasil', $lokasi->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('lokasi.inferensi', $lokasi->id) }}" class="btn btn-primary btn-sm">
                    Step 2: Inferensi <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Informasi Lokasi -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Detail Lokasi</h4>
                <p><strong>Nama Lokasi:</strong> {{ $lokasi->nama }}</p>
                <p><strong>Alamat:</strong> {{ $lokasi->alamat }}</p>
            </div>
        </div>

        <!-- Penjelasan Fuzzifikasi -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> <strong>Fuzzifikasi</strong> adalah proses mengubah nilai crisp
                    (tegas)
                    menjadi derajat keanggotaan pada himpunan fuzzy. Setiap parameter input akan dikonversi menjadi nilai
                    keanggotaan pada kategori rendah, sedang, dan tinggi.
                </div>
            </div>
        </div>

        <!-- Parameter Utama (3 kolom) -->
        <div class="row">
            <!-- Aksesibilitas -->
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Aksesibilitas: {{ $lokasi->aksesibilitas }}</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nilai Crisp:</strong> {{ $derajatAksesibilitas['nilai_crisp'] }}</p>
                        <p><strong>Derajat Keanggotaan:</strong> {{ number_format($derajatAksesibilitas['derajat'], 2) }}
                        </p>

                        <div class="progress mb-3" style="height: 25px;">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $derajatAksesibilitas['derajat'] * 100 }}%"
                                aria-valuenow="{{ $derajatAksesibilitas['derajat'] * 100 }}" aria-valuemin="0"
                                aria-valuemax="100">
                                {{ number_format($derajatAksesibilitas['derajat'] * 100, 1) }}%
                            </div>
                        </div>

                        <h6>Derajat Keanggotaan pada Setiap Himpunan Fuzzy:</h6>
                        @foreach ($derajatAksesibilitas['semua_derajat'] as $kategori => $nilai)
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span><strong>{{ ucfirst($kategori) }}:</strong></span>
                                    <span>{{ number_format($nilai, 2) }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar {{ $kategori == 'rendah' ? 'bg-danger' : ($kategori == 'sedang' ? 'bg-warning' : 'bg-success') }}"
                                        role="progressbar" style="width: {{ $nilai * 100 }}%"
                                        aria-valuenow="{{ $nilai * 100 }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-3">
                            <span
                                class="badge badge-{{ $derajatAksesibilitas['kategori'] == 'rendah' ? 'danger' : ($derajatAksesibilitas['kategori'] == 'sedang' ? 'warning' : 'success') }} badge-lg">
                                Kategori Dominan: {{ ucfirst($derajatAksesibilitas['kategori']) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visibilitas -->
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Visibilitas: {{ $lokasi->visibilitas }}</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nilai Crisp:</strong> {{ $derajatVisibilitas['nilai_crisp'] }}</p>
                        <p><strong>Derajat Keanggotaan:</strong> {{ number_format($derajatVisibilitas['derajat'], 2) }}</p>

                        <div class="progress mb-3" style="height: 25px;">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $derajatVisibilitas['derajat'] * 100 }}%"
                                aria-valuenow="{{ $derajatVisibilitas['derajat'] * 100 }}" aria-valuemin="0"
                                aria-valuemax="100">
                                {{ number_format($derajatVisibilitas['derajat'] * 100, 1) }}%
                            </div>
                        </div>

                        <h6>Derajat Keanggotaan pada Setiap Himpunan Fuzzy:</h6>
                        @foreach ($derajatVisibilitas['semua_derajat'] as $kategori => $nilai)
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span><strong>{{ ucfirst($kategori) }}:</strong></span>
                                    <span>{{ number_format($nilai, 2) }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar {{ $kategori == 'rendah' ? 'bg-danger' : ($kategori == 'sedang' ? 'bg-warning' : 'bg-success') }}"
                                        role="progressbar" style="width: {{ $nilai * 100 }}%"
                                        aria-valuenow="{{ $nilai * 100 }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-3">
                            <span
                                class="badge badge-{{ $derajatVisibilitas['kategori'] == 'rendah' ? 'danger' : ($derajatVisibilitas['kategori'] == 'sedang' ? 'warning' : 'success') }} badge-lg">
                                Kategori Dominan: {{ ucfirst($derajatVisibilitas['kategori']) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daya Beli -->
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Daya Beli: {{ $lokasi->daya_beli }}</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nilai Crisp:</strong> {{ $derajatDayaBeli['nilai_crisp'] }}</p>
                        <p><strong>Derajat Keanggotaan:</strong> {{ number_format($derajatDayaBeli['derajat'], 2) }}</p>

                        <div class="progress mb-3" style="height: 25px;">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $derajatDayaBeli['derajat'] * 100 }}%"
                                aria-valuenow="{{ $derajatDayaBeli['derajat'] * 100 }}" aria-valuemin="0"
                                aria-valuemax="100">
                                {{ number_format($derajatDayaBeli['derajat'] * 100, 1) }}%
                            </div>
                        </div>

                        <h6>Derajat Keanggotaan pada Setiap Himpunan Fuzzy:</h6>
                        @foreach ($derajatDayaBeli['semua_derajat'] as $kategori => $nilai)
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span><strong>{{ ucfirst($kategori) }}:</strong></span>
                                    <span>{{ number_format($nilai, 2) }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar {{ $kategori == 'rendah' ? 'bg-danger' : ($kategori == 'sedang' ? 'bg-warning' : 'bg-success') }}"
                                        role="progressbar" style="width: {{ $nilai * 100 }}%"
                                        aria-valuenow="{{ $nilai * 100 }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-3">
                            <span
                                class="badge badge-{{ $derajatDayaBeli['kategori'] == 'rendah' ? 'danger' : ($derajatDayaBeli['kategori'] == 'sedang' ? 'warning' : 'success') }} badge-lg">
                                Kategori Dominan: {{ ucfirst($derajatDayaBeli['kategori']) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parameter Sekunder (4 kolom) -->
        <div class="row">
            <!-- Persaingan -->
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Persaingan: {{ $lokasi->persaingan }}</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Nilai Crisp:</strong> {{ $derajatPersaingan['nilai_crisp'] }}</p>
                        <p><strong>Derajat:</strong> {{ number_format($derajatPersaingan['derajat'], 2) }}</p>

                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $derajatPersaingan['derajat'] * 100 }}%"
                                aria-valuenow="{{ $derajatPersaingan['derajat'] * 100 }}" aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>

                        <span
                            class="badge badge-{{ $derajatPersaingan['kategori'] == 'rendah' ? 'danger' : ($derajatPersaingan['kategori'] == 'sedang' ? 'warning' : 'success') }}">
                            {{ ucfirst($derajatPersaingan['kategori']) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Infrastruktur -->
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Infrastruktur: {{ $lokasi->infrastruktur }}</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Nilai Crisp:</strong> {{ $derajatInfrastruktur['nilai_crisp'] }}</p>
                        <p><strong>Derajat:</strong> {{ number_format($derajatInfrastruktur['derajat'], 2) }}</p>

                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $derajatInfrastruktur['derajat'] * 100 }}%"
                                aria-valuenow="{{ $derajatInfrastruktur['derajat'] * 100 }}" aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>

                        <span
                            class="badge badge-{{ $derajatInfrastruktur['kategori'] == 'tinggi'
                                ? 'badge-success'
                                : ($derajatInfrastruktur['kategori'] == 'sedang'
                                    ? 'badge-warning'
                                    : 'badge-danger') }}">
                            {{ ucfirst($derajatInfrastruktur['kategori']) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Lingkungan Sekitar -->
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Lingkungan Sekitar: {{ $lokasi->lingkungan_sekitar }}</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Nilai Crisp:</strong> {{ $derajatLingkunganSekitar['nilai_crisp'] }}</p>
                        <p><strong>Derajat:</strong> {{ number_format($derajatLingkunganSekitar['derajat'], 2) }}</p>

                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $derajatLingkunganSekitar['derajat'] * 100 }}%"
                                aria-valuenow="{{ $derajatLingkunganSekitar['derajat'] * 100 }}" aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>

                        <span
                            class="badge 
                            {{ $derajatLingkunganSekitar['kategori'] == 'tinggi'
                                ? 'badge-success'
                                : ($derajatLingkunganSekitar['kategori'] == 'sedang'
                                    ? 'badge-warning'
                                    : 'badge-danger') }}">
                            {{ ucfirst($derajatLingkunganSekitar['kategori']) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Parkir -->
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Parkir: {{ $lokasi->parkir }}</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Nilai Crisp:</strong> {{ $derajatParkir['nilai_crisp'] }}</p>
                        <p><strong>Derajat:</strong> {{ number_format($derajatParkir['derajat'], 2) }}</p>

                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $derajatParkir['derajat'] * 100 }}%"
                                aria-valuenow="{{ $derajatParkir['derajat'] * 100 }}" aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>

                        <span
                            class="badge 
                            {{ $derajatParkir['kategori'] == 'tinggi'
                                ? 'badge-success'
                                : ($derajatParkir['kategori'] == 'sedang'
                                    ? 'badge-warning'
                                    : 'badge-danger') }}">
                            {{ ucfirst($derajatParkir['kategori']) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigasi -->
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('lokasi.hasil', $lokasi->id) }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali ke Hasil
            </a>
            <a href="{{ route('lokasi.inferensi', $lokasi->id) }}" class="btn btn-primary">
                Lanjut ke Step 2: Inferensi <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
@endsection
