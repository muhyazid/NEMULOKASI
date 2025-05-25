@extends('layouts.main')

@section('title', 'Tambah Aturan Fuzzy')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Aturan Fuzzy Baru</h3>
                </div>
                <form action="{{ route('aturan-fuzzy.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:</h6>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Nama Aturan -->
                        <div class="form-group">
                            <label for="nama_aturan" class="font-weight-bold">
                                Nama Aturan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama_aturan') is-invalid @enderror" 
                                   id="nama_aturan" 
                                   name="nama_aturan" 
                                   value="{{ old('nama_aturan') }}"
                                   placeholder="Contoh: Aturan 1 - Lokasi Sangat Layak"
                                   required>
                            @error('nama_aturan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Berikan nama yang deskriptif untuk aturan ini</small>
                        </div>

                        <!-- Kondisi IF -->
                        <div class="form-group">
                            <label class="font-weight-bold">
                                Kondisi IF <span class="text-danger">*</span>
                            </label>
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-muted mb-3">
                                        <i class="fas fa-info-circle"></i> 
                                        Pilih kategori untuk setiap parameter yang akan digunakan dalam aturan ini
                                    </p>
                                    
                                    <div class="row">
                                        @foreach($parameters as $parameter)
                                            <div class="col-md-6 mb-3">
                                                <label for="kondisi_{{ $parameter }}" class="font-weight-semibold">
                                                    {{ ucfirst(str_replace('_', ' ', $parameter)) }}
                                                </label>
                                                <select name="kondisi[{{ $parameter }}]" 
                                                        id="kondisi_{{ $parameter }}"
                                                        class="form-control @error('kondisi.'.$parameter) is-invalid @enderror">
                                                    <option value="">-- Pilih Kategori --</option>
                                                    @if(isset($parameterOptions[$parameter]))
                                                        @foreach($parameterOptions[$parameter] as $key => $value)
                                                            <option value="{{ $key }}" {{ old('kondisi.'.$parameter) == $key ? 'selected' : '' }}>
                                                                {{ ucfirst($value) }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option value="rendah" {{ old('kondisi.'.$parameter) == 'rendah' ? 'selected' : '' }}>
                                                            Rendah
                                                        </option>
                                                        <option value="sedang" {{ old('kondisi.'.$parameter) == 'sedang' ? 'selected' : '' }}>
                                                            Sedang
                                                        </option>
                                                        <option value="tinggi" {{ old('kondisi.'.$parameter) == 'tinggi' ? 'selected' : '' }}>
                                                            Tinggi
                                                        </option>
                                                    @endif
                                                </select>
                                                @error('kondisi.'.$parameter)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="alert alert-info mt-3">
                                        <i class="fas fa-lightbulb"></i>
                                        <strong>Tips:</strong> Anda tidak perlu mengisi semua parameter. 
                                        Kosongkan parameter yang tidak relevan untuk aturan ini.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hasil THEN -->
                        <div class="form-group">
                            <label for="hasil" class="font-weight-bold">
                                Hasil THEN <span class="text-danger">*</span>
                            </label>
                            <select name="hasil" 
                                    id="hasil" 
                                    class="form-control @error('hasil') is-invalid @enderror" 
                                    required>
                                <option value="">-- Pilih Hasil --</option>
                                <option value="tidak_layak" {{ old('hasil') == 'tidak_layak' ? 'selected' : '' }}>
                                    Tidak Layak
                                </option>
                                <option value="layak" {{ old('hasil') == 'layak' ? 'selected' : '' }}>
                                    Layak
                                </option>
                                <option value="sangat_layak" {{ old('hasil') == 'sangat_layak' ? 'selected' : '' }}>
                                    Sangat Layak
                                </option>
                            </select>
                            @error('hasil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Pilih kesimpulan yang akan dihasilkan jika kondisi IF terpenuhi</small>
                        </div>
                        
                    </div>
                    
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Aturan
                                </button>
                                <button type="reset" class="btn btn-secondary ml-2">
                                    <i class="fas fa-undo"></i> Reset Form
                                </button>
                            </div>
                            <div class="col-sm-6 text-right">
                                <a href="{{ route('aturan-fuzzy.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .font-weight-semibold {
            font-weight: 600;
        }
        .custom-control-label {
            padding-top: 2px;
        }
        .card .card-body {
            background-color: #f8f9fa;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        #preview-aturan {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            white-space: pre-wrap;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Preview kondisi yang dipilih
            $('select[name^="kondisi"], select[name="hasil"]').change(function() {
                updatePreview();
            });
            
            // Update preview saat halaman dimuat
            updatePreview();
            
            function updatePreview() {
                let kondisiArray = [];
                $('select[name^="kondisi"]').each(function() {
                    if ($(this).val()) {
                        let parameter = $(this).attr('name').match(/\[(.*?)\]/)[1];
                        let kategori = $(this).val();
                        kondisiArray.push(parameter.replace(/_/g, ' ') + ' = ' + kategori.toUpperCase());
                    }
                });
                
                let hasil = $('select[name="hasil"]').val();
                let preview = '';
                
                if (kondisiArray.length > 0) {
                    preview = 'IF ' + kondisiArray.join(' AND ') + 
                            (hasil ? '\nTHEN lokasi = ' + hasil.replace('_', ' ').toUpperCase() : '\nTHEN ...');
                } else {
                    preview = 'Silakan isi kondisi dan hasil untuk melihat preview aturan';
                }
                
                $('#preview-aturan').text(preview);
            }
            
            // Reset form
            $('button[type="reset"]').click(function() {
                setTimeout(function() {
                    updatePreview();
                }, 100);
            });
        });
    </script>
@endpush