@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Form Input Analisis Lokasi</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lokasi.form') }}">Home</a></li>
    <li class="breadcrumb-item active">Form Analisis</li>
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Masukkan Data Lokasi dan Parameter</h3>
        </div>
        <form action="{{ route('lokasi.hitung') }}" method="POST" id="lokasiForm">
            @csrf
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Dropdown Pilih Lokasi Bisnis --}}
                <div class="form-group">
                    <label for="tempat_bisnis_id">Pilih Lokasi Bisnis <span class="text-danger">*</span></label>
                    <select name="tempat_bisnis_id" id="tempat_bisnis_id" class="form-control @error('tempat_bisnis_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach ($tempatBisnisOptions as $tempat)
                            <option value="{{ $tempat->id }}" 
                                    data-nama="{{ $tempat->nama_tempat }}"
                                    data-lat="{{ $tempat->latitude }}" 
                                    data-long="{{ $tempat->longitude }}"
                                    {{ old('tempat_bisnis_id') == $tempat->id ? 'selected' : '' }}>
                                {{ $tempat->nama_tempat }}
                            </option>
                        @endforeach
                    </select>
                    @error('tempat_bisnis_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Hidden input untuk nama --}}
                <input type="hidden" name="nama" id="nama" value="{{ old('nama') }}">

                {{-- Field Latitude & Longitude --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" id="latitude" class="form-control" value="{{ old('latitude') }}" readonly placeholder="Akan terisi otomatis">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" id="longitude" class="form-control" value="{{ old('longitude') }}" readonly placeholder="Akan terisi otomatis">
                        </div>
                    </div>
                </div>

                <hr>
                <h5>Parameter Penilaian:</h5>

                 @forelse ($parameterOptions as $param => $options)
                    <div class="form-group">
                        <label for="{{ $param }}">{{ ucfirst(str_replace('_', ' ', $param)) }}</label>
                        <select name="{{ $param }}" id="{{ $param }}" class="form-control @error($param) is-invalid @enderror" required>
                            <option value="">-- Pilih {{ ucfirst(str_replace('_', ' ', $param)) }} --</option>
                            @foreach ($options as $key => $value)
                                <option value="{{ $key }}" {{ old($param) == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                         @error($param) <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                 @empty
                    <div class="alert alert-warning">
                        Parameter belum diatur. Silakan tambahkan parameter melalui menu 'Kelola Parameter'.
                    </div>
                 @endforelse

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" @if(empty($parameterOptions)) disabled @endif>
                    <i class="fas fa-calculator"></i> Hitung Kelayakan
                </button>
                <button type="reset" class="btn btn-secondary" id="resetButton">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </form>
    </div>
@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> {{-- Pastikan jQuery sudah ada --}}
<script>
$(document).ready(function() {
    
    function fillData(selectedOption) {
        // Menggunakan .attr() sebagai alternatif .data()
        var lat = selectedOption.attr('data-lat') || ''; 
        var long = selectedOption.attr('data-long') || '';
        var nama = selectedOption.attr('data-nama') || '';

        console.log("Selected:", nama, "Lat:", lat, "Long:", long); // Tambahkan ini untuk debugging di console

        $('#latitude').val(lat);
        $('#longitude').val(long);
        $('#nama').val(nama); 
    }

    // Panggil saat halaman dimuat jika ada old value
    var selectedId = $('#tempat_bisnis_id').val();
    if (selectedId) {
        fillData($('#tempat_bisnis_id option:selected'));
    }

    $('#tempat_bisnis_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        
        if (selectedOption.val() !== '') {
            fillData(selectedOption);
        } else {
            $('#latitude').val('');
            $('#longitude').val('');
            $('#nama').val('');
            console.log("Dropdown dikosongkan."); // Debugging
        }
    });

    // Handle Reset Button
    $('#resetButton').click(function(e) {
       e.preventDefault(); 
       $('#lokasiForm')[0].reset(); 
       $('#tempat_bisnis_id').val('').trigger('change'); 
    });

});
</script>
