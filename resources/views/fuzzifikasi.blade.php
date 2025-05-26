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
            <h3 class="card-title">Step 1: Fuzzifikasi - {{ $lokasi->nama }}</h3>
            <a href="{{ route('lokasi.inferensi', $lokasi->id) }}" class="btn btn-primary btn-sm">
                Lanjut ke Step 2: Inferensi <i class="fa fa-arrow-right"></i>
            </a>
        </div>

        <div class="card-body">
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> Fuzzifikasi adalah proses mengubah nilai crisp (Input) menjadi derajat keanggotaan (μ) pada setiap himpunan fuzzy (Rendah, Sedang, Tinggi, dll) menggunakan fungsi keanggotaan segitiga.
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-lightblue">
                        <tr>
                            <th>Parameter</th>
                            <th>Nilai Linguistik (Input)</th>
                            <th>Nilai Crisp (Input)</th>
                            <th>Derajat Keanggotaan (μ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $parameters = ['aksesibilitas', 'visibilitas', 'daya_beli', 'persaingan', 'infrastruktur', 'lingkungan_sekitar', 'parkir']; @endphp
                        @foreach ($parameters as $param)
                            @php
                                $linguistikVar = $param;
                                $crispVar = 'crisp' . ucfirst($param);
                                $derajatVar = 'derajat' . ucfirst($param);
                            @endphp
                             @if(isset($$crispVar) && isset($$derajatVar))
                            <tr>
                                <td class="font-weight-bold">{{ ucfirst(str_replace('_', ' ', $param)) }}</td>
                                <td>{{ $lokasi->$linguistikVar }}</td>
                                <td class="text-center">{{ $$crispVar ?? 'N/A' }}</td>
                                <td>
                                    @if (is_array($$derajatVar))
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($$derajatVar as $himpunan => $derajat)
                                                @if($derajat > 0) {{-- Hanya tampilkan yg > 0 --}}
                                                <li>
                                                    μ({{ $himpunan }}): <strong>{{ number_format($derajat, 4) }}</strong>
                                                    <div class="progress" style="height: 10px;">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                             style="width: {{ $derajat * 100 }}%"></div>
                                                    </div>
                                                </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-muted mt-2">Catatan: Hanya derajat keanggotaan > 0 yang ditampilkan. Nilai μ berkisar antara 0 hingga 1.</p>
        </div>
         <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('lokasi.hasil', $lokasi->id) }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali ke Hasil
            </a>
            <a href="{{ route('lokasi.inferensi', $lokasi->id) }}" class="btn btn-primary">
                Lanjut ke Step 2 <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
@endsection