@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Form Input Analisis Lokasi</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lokasi.form') }}">Home</a></li>
    <li class="breadcrumb-item active">Form Analisis</li>
@endsection

@section('content')
    <div class="card card-primary card-outline"> {{-- Menggunakan card-outline untuk tampilan yang lebih soft --}}
        <div class="card-header">
            <h3 class="card-title">Masukkan Data Lokasi dan Parameter</h3>
        </div>
        <form action="{{ route('lokasi.hitung') }}" method="POST" id="lokasiForm">
            @csrf
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Bagian Informasi Lokasi Bisnis --}}
                <div class="form-section-card">
                    <div class="card-header">
                        <h5 class="mb-0">1. Informasi Lokasi Bisnis</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tempat_bisnis_id">Pilih Lokasi Bisnis <span class="text-danger">*</span></label>
                            <select name="tempat_bisnis_id" id="tempat_bisnis_id" class="form-control @error('tempat_bisnis_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach ($tempatBisnisOptions as $tempat)
                                    <option value="{{ $tempat->id }}"
                                            data-nama="{{ $tempat->nama_tempat }}"
                                            data-lat="{{ $tempat->latitude }}"
                                            data-long="{{ $tempat->longitude }}"
                                            data-deskripsi="{{ $tempat->deskripsi_lokasi }}"
                                            {{ old('tempat_bisnis_id') == $tempat->id ? 'selected' : '' }}>
                                        {{ $tempat->nama_tempat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tempat_bisnis_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <input type="hidden" name="nama" id="nama" value="{{ old('nama') }}">

                        <div class="form-group">
                            <label for="deskripsi_lokasi_display">Deskripsi Lokasi Terpilih</label>
                            <textarea id="deskripsi_lokasi_display" class="form-control" rows="3" readonly placeholder="Deskripsi akan terisi otomatis"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude">Latitude</label>
                                    <input type="text" id="latitude" name="latitude_display"
                                           class="form-control" value="{{ old('latitude') }}" readonly placeholder="Otomatis">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude">Longitude</label>
                                    <input type="text" id="longitude" name="longitude_display"
                                           class="form-control" value="{{ old('longitude') }}" readonly placeholder="Otomatis">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bagian Parameter Penilaian --}}
                <div class="form-section-card">
                    <div class="card-header">
                        <h5 class="mb-0">2. Parameter Penilaian Fuzzy</h5>
                    </div>
                    <div class="card-body">
                        @if(empty($parameterOptions))
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Parameter penilaian belum diatur. Silakan tambahkan parameter melalui menu 'Kelola Parameter' terlebih dahulu.
                            </div>
                        @else
                            <div class="row">
                                @foreach ($parameterOptions as $param => $options)
                                    <div class="col-md-6">
                                        <div class="form-group parameter-group">
                                            <label for="{{ $param }}">{{ ucfirst(str_replace('_', ' ', $param)) }} <span class="text-danger">*</span></label>
                                            <select name="{{ $param }}" id="{{ $param }}" class="form-control @error($param) is-invalid @enderror" required>
                                                <option value="">-- Pilih {{ ucfirst(str_replace('_', ' ', $param)) }} --</option>
                                                @foreach ($options as $key => $value)
                                                    <option value="{{ $key }}" {{ old($param) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error($param) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div> {{-- End card-body utama --}}

            <div class="card-footer text-right"> {{-- Tombol ke kanan --}}
                <button type="reset" class="btn btn-secondary mr-2" id="resetButton">
                    <i class="fas fa-sync-alt"></i> Reset Form
                </button>
                <button type="submit" class="btn btn-primary" @if(empty($parameterOptions)) disabled @endif>
                    <i class="fas fa-calculator"></i> Hitung Kelayakan
                </button>
            </div>
        </form>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    
<script>
    if (window.jQuery) {
        $(document).ready(function() {
            console.log("Form Lokasi: Dokumen siap dan jQuery ditemukan!");

            function fillData(selectedOption) {
                var lat = selectedOption.attr('data-lat') || '';
                var long = selectedOption.attr('data-long') || '';
                var nama = selectedOption.attr('data-nama') || '';
                var deskripsi = selectedOption.attr('data-deskripsi') || '';

                console.log("Selected:", nama, "| Lat:", lat, "| Long:", long, "| Deskripsi:", deskripsi);

                $('#latitude').val(lat);
                $('#longitude').val(long);
                $('#nama').val(nama);
                $('#deskripsi_lokasi_display').val(deskripsi);
            }

            var initialSelectedId = $('#tempat_bisnis_id').val();
            if (initialSelectedId && initialSelectedId !== '') {
                console.log("Memuat data awal untuk ID:", initialSelectedId);
                var initialSelectedOption = $('#tempat_bisnis_id option:selected');
                if (initialSelectedOption.length > 0 && initialSelectedOption.val() !== '') {
                     fillData(initialSelectedOption);
                } else {
                    console.log("Opsi awal tidak valid atau tidak ditemukan.");
                }
            }

            $('#tempat_bisnis_id').change(function() {
                console.log("Dropdown 'Pilih Lokasi Bisnis' berubah!");
                var selectedOption = $(this).find('option:selected');

                if (selectedOption.val() !== '') {
                    fillData(selectedOption);
                } else {
                    $('#latitude').val('');
                    $('#longitude').val('');
                    $('#nama').val('');
                    $('#deskripsi_lokasi_display').val('');
                    console.log("Dropdown dikosongkan.");
                }
            });

            $('#resetButton').click(function(e) {
               e.preventDefault();
               $('#lokasiForm')[0].reset();
               $('#tempat_bisnis_id').val('').trigger('change'); // Ini akan memanggil handler change untuk mengosongkan field
               // Jika ada select parameter yang ingin direset ke "-- Pilih --" juga
               $('#lokasiForm .parameter-group select').val('');
               console.log("Form direset.");
            });
        });
    } else {
        console.error("jQuery TIDAK ditemukan! Script auto-fill di form_lokasi.blade.php tidak akan berjalan.");
    }
</script>