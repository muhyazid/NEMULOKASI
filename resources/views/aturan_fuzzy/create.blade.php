@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Tambah Aturan Fuzzy Baru</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('aturan-fuzzy.index') }}">Kelola Aturan</a></li>
    <li class="breadcrumb-item active">Tambah Aturan</li>
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Tambah Aturan Fuzzy</h3>
        </div>
        <form action="{{ route('aturan-fuzzy.store') }}" method="POST">
            @csrf
            <div class="card-body">

                @include('layouts.partials.alerts')

                {{-- Form Group untuk Nama Aturan Dihapus --}}
                {{-- <div class="form-group">
                    <label for="nama_aturan">Nama Aturan</label>
                    <input type="text" name="nama_aturan" id="nama_aturan" class="form-control @error('nama_aturan') is-invalid @enderror" value="{{ old('nama_aturan', $namaAturanOtomatis ?? 'R-Otomatis') }}" required readonly placeholder="Akan terisi otomatis">
                    @error('nama_aturan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div> --}}

                <hr>
                <h5>Kondisi (IF):</h5>
                <p class="text-sm text-muted">Pilih istilah linguistik untuk setiap parameter yang ingin disertakan dalam aturan ini. Biarkan "-- Tidak Digunakan --" jika parameter tidak relevan untuk aturan ini.</p>
                
                <div class="row">
                    @if(empty($allParameters) || $allParameters->isEmpty()) {{-- Periksa jika $allParameters kosong --}}
                        <div class="col-12">
                            <div class="alert alert-warning">
                                Belum ada parameter yang diinputkan. Silakan tambahkan parameter terlebih dahulu melalui menu "Kelola Parameter".
                            </div>
                        </div>
                    @else
                        @foreach($allParameters as $parameter)
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="param_{{ $parameter['nama_parameter'] }}">{{ $parameter['display_name'] }}</label>
                                    <select name="{{ $parameter['nama_parameter'] }}" id="param_{{ $parameter['nama_parameter'] }}" class="form-control @error($parameter['nama_parameter']) is-invalid @enderror">
                                        <option value="">-- Tidak Digunakan --</option> 
                                        @if(!empty($parameter['himpunan_fuzzies']))
                                            @foreach($parameter['himpunan_fuzzies'] as $himpunan_data)
                                                <option value="{{ $himpunan_data['value_to_save'] }}" 
                                                        {{ old($parameter['nama_parameter']) == $himpunan_data['value_to_save'] ? 'selected' : '' }}>
                                                    {{ $himpunan_data['text_to_display'] }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>! Belum ada himpunan fuzzy untuk parameter ini</option>
                                        @endif
                                    </select>
                                    @error($parameter['nama_parameter']) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <hr>
                <h5>Hasil (THEN):</h5>
                <div class="form-group">
                    <label for="hasil">Kelayakan</label>
                    <select name="hasil" id="hasil" class="form-control @error('hasil') is-invalid @enderror" required>
                        <option value="">-- Pilih Hasil Kelayakan --</option>
                        @foreach($outputOptions as $option)
                            <option value="{{ $option }}" {{ old('hasil') == $option ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $option)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('hasil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Form Group untuk Switch Aktif Dihapus --}}
                {{-- <hr>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="aktif" name="aktif" value="1" {{ old('aktif', 1) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="aktif">Aktifkan Aturan Ini</label>
                    </div>
                </div> --}}

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" @if(empty($allParameters) || $allParameters->isEmpty()) disabled @endif>Simpan Aturan</button>
                <a href="{{ route('aturan-fuzzy.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
{{-- Tidak ada JavaScript khusus --}}
@endsection