@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Input Data Lokasi</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lokasi.form') }}">Home</a></li>
    <li class="breadcrumb-item active">Input Data Lokasi</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Penilaian Lokasi Bisnis</h3>
        </div>
        <div class="card-body">
            <p class="text-muted">Form ini digunakan untuk menilai kelayakan lokasi bisnis menggunakan metode Fuzzy Tsukamoto</p>

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('lokasi.hitung') }}" method="POST" id="lokasiForm">
                @csrf

                <!-- Informasi Dasar Lokasi -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama">Nama Lokasi:<span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control" 
                                   value="{{ old('nama') }}" required placeholder="Masukkan nama lokasi">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alamat">Alamat:<span class="text-danger">*</span></label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="3"
                                      placeholder="Masukkan alamat lengkap lokasi">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Parameter Penilaian -->
                <h5 class="mt-4 mb-3">Parameter Penilaian</h5>
                
                @if(empty($parameterOptions))
                    <div class="alert alert-warning">
                        <strong>Perhatian:</strong> Belum ada parameter yang tersedia di database. 
                        <a href="{{ route('parameter-fuzzy.form') }}" class="alert-link">Tambah parameter terlebih dahulu</a>
                    </div>
                @else
                    @foreach($parameterOptions as $parameter => $options)
                        @if(!empty($options))
                            <div class="form-group">
                                <label for="{{ $parameter }}">
                                    {{ ucwords(str_replace('_', ' ', $parameter)) }}:
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="{{ $parameter }}" id="{{ $parameter }}" class="form-control" required>
                                    <option value="" disabled {{ old($parameter) ? '' : 'selected' }}>
                                        -- Pilih {{ ucwords(str_replace('_', ' ', $parameter)) }} --
                                    </option>
                                    @foreach($options as $value => $label)
                                        <option value="{{ $value }}" {{ old($parameter) == $value ? 'selected' : '' }}>
                                            {{ ucwords($label) }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">{{ getParameterDescription($parameter) }}</small>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <strong>{{ ucwords(str_replace('_', ' ', $parameter)) }}:</strong> 
                                Belum ada nilai linguistik untuk parameter ini. 
                                <a href="{{ route('parameter-fuzzy.form') }}" class="alert-link">Tambah nilai linguistik</a>
                            </div>
                        @endif
                    @endforeach
                @endif

                <!-- Info Panel -->
                <div class="alert alert-info mt-4">
                    <h6><i class="fa fa-info-circle"></i> Informasi Penilaian:</h6>
                    <p class="mb-2">Sistem akan melakukan analisis berdasarkan parameter yang Anda pilih dengan menggunakan metode Fuzzy Tsukamoto.</p>
                    <p class="mb-0"><strong>Proses perhitungan:</strong> Fuzzifikasi → Inferensi → Defuzzifikasi → Skor Akhir</p>
                    <p class="mb-0 mt-2"><strong>Batas Fuzzy saat ini:</strong> Rendah (0-2), Sedang (2-5), Tinggi (5-8)</p>
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    @if(!empty($parameterOptions) && count(array_filter($parameterOptions)) > 0)
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-calculator"></i> Hitung Skor Lokasi
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary btn-lg" disabled>
                            <i class="fa fa-exclamation-triangle"></i> Tambah Parameter Dulu
                        </button>
                    @endif
                    <a href="{{ route('lokasi.form') }}" class="btn btn-outline-secondary btn-lg ml-2">
                        <i class="fa fa-refresh"></i> Reset Form
                    </a>
                </div>
            </form>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('parameter-fuzzy.form') }}" class="btn btn-outline-primary btn-block">
                                <i class="fa fa-plus"></i> Tambah Parameter Fuzzy
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('parameter-fuzzy.index') }}" class="btn btn-outline-info btn-block">
                                <i class="fa fa-list"></i> Lihat Semua Parameter
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('aturan-fuzzy.index') }}" class="btn btn-outline-warning btn-block">
                                <i class="fa fa-cogs"></i> Kelola Aturan Fuzzy
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Parameter Values -->
            @if(!empty($parameterOptions))
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Preview Nilai Parameter Tersedia</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Parameter</th>
                                    <th>Nilai Linguistik Tersedia</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parameterOptions as $parameter => $options)
                                    <tr>
                                        <td><strong>{{ ucwords(str_replace('_', ' ', $parameter)) }}</strong></td>
                                        <td>
                                            @if(!empty($options))
                                                @foreach(array_keys($options) as $option)
                                                    <span class="badge badge-secondary mr-1">{{ ucwords($option) }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Belum ada nilai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ count($options) >= 3 ? 'success' : 'warning' }}">
                                                {{ count($options) }} nilai
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <small class="text-muted">
                        <strong>Catatan:</strong> Setiap parameter sebaiknya memiliki minimal 3 nilai linguistik untuk hasil yang optimal.
                    </small>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('lokasiForm');
            const selects = form.querySelectorAll('select[name]');
            
            // Add change event listeners to all select elements
            selects.forEach(function(select) {
                select.addEventListener('change', function() {
                    // Update styling based on selection
                    if (this.value) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                    
                    console.log('Parameter ' + this.name + ' changed to: ' + this.value);
                });
            });
            
            // Form validation
            form.addEventListener('submit', function(e) {
                let valid = true;
                const requiredFields = form.querySelectorAll('[required]');
                
                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        valid = false;
                        field.classList.add('is-invalid');
                        field.classList.remove('is-valid');
                    } else {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                });
                
                if (!valid) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi!');
                    
                    // Scroll to first invalid field
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstInvalid.focus();
                    }
                }
            });
            
            // Real-time validation
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(function(field) {
                field.addEventListener('blur', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
            });
        });
    </script>
@endsection

@php
// Helper function untuk deskripsi parameter
function getParameterDescription($parameter) {
    $descriptions = [
        'aksesibilitas' => 'Kemudahan dalam mencapai lokasi (transportasi, jalan)',
        'visibilitas' => 'Seberapa mudah bisnis terlihat dari jalan utama',
        'daya_beli' => 'Kemampuan masyarakat sekitar untuk membeli produk/jasa',
        'persaingan' => 'Tingkat persaingan bisnis sejenis di area tersebut',
        'infrastruktur' => 'Ketersediaan listrik, air, internet, dll',
        'lingkungan_sekitar' => 'Apakah lingkungan sekitar mendukung jenis bisnis ini',
        'parkir' => 'Ketersediaan lahan parkir untuk pelanggan'
    ];
    
    return $descriptions[$parameter] ?? 'Parameter untuk analisis lokasi bisnis';
}
@endphp