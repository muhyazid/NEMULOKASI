@extends('layouts.main')

@section('page-title') <h1 class="m-0">Edit Data Tempat Bisnis</h1> @endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tempat-bisnis.index') }}">Data Tempat Bisnis</a></li>
    <li class="breadcrumb-item active">Edit: {{ $tempat_bisnis->nama_tempat }}</li>
@endsection

{{-- @section('css') ... Hapus jika hanya untuk gambar ... @endsection --}}

@section('content')
<div class="card card-warning">
    <div class="card-header"><h3 class="card-title">Form Edit Tempat Bisnis</h3></div>
    <form action="{{ route('tempat-bisnis.update', $tempat_bisnis->id) }}" method="POST"> {{-- enctype dihapus --}}
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('layouts.partials.alerts')
            <div class="form-group">
                <label for="nama_tempat">Nama Tempat Bisnis <span class="text-danger">*</span></label>
                <input type="text" name="nama_tempat" id="nama_tempat" class="form-control @error('nama_tempat') is-invalid @enderror" value="{{ old('nama_tempat', $tempat_bisnis->nama_tempat) }}" required>
                @error('nama_tempat') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $tempat_bisnis->latitude) }}" placeholder="Contoh: -7.257472">
                        @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $tempat_bisnis->longitude) }}" placeholder="Contoh: 112.752088">
                        @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-warning">Update Data Tempat</button>
            <a href="{{ route('tempat-bisnis.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection