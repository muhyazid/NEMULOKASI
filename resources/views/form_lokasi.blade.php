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
        <form action="{{ route('lokasi.hitung') }}" method="POST">
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

                <div class="form-group">
                    <label for="nama">Nama Lokasi</label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required placeholder="Contoh: Toko Roti Enak">
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat Lokasi</label>
                    <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2" placeholder="Contoh: Jl. Merdeka No. 10">{{ old('alamat') }}</textarea>
                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                 <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i> Reset
                </button>
            </div>
        </form>
    </div>
@endsection