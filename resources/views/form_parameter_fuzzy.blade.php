@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Parameter Fuzzy</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lokasi.form') }}">Home</a></li>
    <li class="breadcrumb-item active">Parameter Fuzzy</li>
@endsection

@section('content')
    <div class="row">
        <!-- Form Input -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Input Parameter Fuzzy</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
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

                    <form action="{{ route('parameter-fuzzy.simpan') }}" method="POST" id="parameterForm">
                        @csrf
                        
                        <!-- Nama Parameter -->
                        <div class="form-group">
                            <label for="nama_parameter">Nama Parameter:<span class="text-danger">*</span></label>
                            <select name="nama_parameter" id="nama_parameter" class="form-control" required>
                                <option value="">-- Pilih Parameter --</option>
                                @if(!empty($availableParameters))
                                    @foreach($availableParameters as $param)
                                        <option value="{{ $param }}" {{ old('nama_parameter') == $param ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('_', ' ', $param)) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                       

                        <!-- Nilai Linguistik -->
                        <div class="form-group">
                            <label for="nilai_fuzzy">Nilai Linguistik:<span class="text-danger">*</span></label>
                            <select name="nilai_fuzzy" id="nilai_fuzzy" class="form-control" required>
                                <option value="">-- Pilih Nilai Linguistik --</option>
                            </select>
                            <small class="form-text text-muted">Nilai linguistik seperti "rendah", "sedang", "tinggi"</small>
                        </div>

                        <!-- Input Custom Nilai Linguistik -->
                        <div class="form-group">
                            <label for="custom_nilai_fuzzy">Atau Masukkan Nilai Linguistik Baru:</label>
                            <input type="text" id="custom_nilai_fuzzy" class="form-control" 
                                   placeholder="Contoh: sangat rendah, cukup baik, dll">
                            <small class="form-text text-muted">Kosongkan jika menggunakan pilihan di atas</small>
                        </div>

                        <!-- Nilai Crisp -->
                        <div class="form-group">
                            <label for="nilai_crisp">Nilai Crisp:<span class="text-danger">*</span></label>
                            <input type="number" name="nilai_crisp" id="nilai_crisp" class="form-control" 
                                   min="0" max="10" step="0.1" required value="{{ old('nilai_crisp') }}">
                            <small class="form-text text-muted">
                                Nilai numerik 0-10. 
                                Batas fuzzy saat ini: Rendah (0-2), Sedang (2-5), Tinggi (5-8)
                            </small>
                        </div>

                        <!-- Preview Kategori -->
                        <div class="form-group">
                            <label>Preview Kategori:</label>
                            <div id="kategoriPreview" class="alert alert-secondary">
                                Masukkan nilai crisp untuk melihat kategori fuzzy
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan Parameter Fuzzy
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaParameterSelect = document.getElementById('nama_parameter');
            const nilaiFuzzySelect = document.getElementById('nilai_fuzzy');
            const customParameterGroup = document.getElementById('customParameterGroup');
            const customParameterInput = document.getElementById('custom_parameter');
            const customNilaiFuzzyInput = document.getElementById('custom_nilai_fuzzy');
            const nilaiCrispInput = document.getElementById('nilai_crisp');
            const kategoriPreview = document.getElementById('kategoriPreview');
            const form = document.getElementById('parameterForm');

            // Data parameter dari server
            const parameterData = @json($availableParameters ?? []);
            // Handle custom parameter input
            customParameterInput.addEventListener('input', function() {
                loadNilaiLinguistik('');
            });

            // Handle custom nilai fuzzy input
            customNilaiFuzzyInput.addEventListener('input', function() {
                if (this.value.trim()) {
                    nilaiFuzzySelect.disabled = true;
                } else {
                    nilaiFuzzySelect.disabled = false;
                }
            });

            // Handle nilai crisp change for preview
            nilaiCrispInput.addEventListener('input', function() {
                if (this.value) {
                    updateKategoriPreview(parseFloat(this.value));
                }
            });
                // Handle custom nilai fuzzy
            if (customNilaiFuzzyInput.value.trim()) {
                    nilaiFuzzySelect.value = customNilaiFuzzyInput.value.trim().toLowerCase();
                }

                // Validate required fields
                if (!nilaiFuzzySelect.value && !customNilaiFuzzyInput.value.trim()) {
                    e.preventDefault();
                    alert('Silakan pilih atau masukkan nilai linguistik!');
                    return;
                }
             });
            // Load nilai linguistik berdasarkan parameter
            function loadNilaiLinguistik(parameter) {
                // Clear existing options
                nilaiFuzzySelect.innerHTML = '<option value="">-- Pilih Nilai Linguistik --</option>';

                if (!parameter) {
                    // Add common linguistic values
                    const commonValues = [
                        'sangat rendah', 'rendah', 'sedang', 'tinggi', 'sangat tinggi',
                        'buruk', 'cukup', 'baik', 'sangat baik',
                        'tidak ada', 'sedikit', 'banyak',
                        'sempit', 'luas', 'sangat luas'
                    ];

                    commonValues.forEach(value => {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = value.charAt(0).toUpperCase() + value.slice(1);
                        nilaiFuzzySelect.appendChild(option);
                    });
                    return;
                }
            }

            // Update nilai crisp berdasarkan pilihan nilai fuzzy
            nilaiFuzzySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.dataset.crisp) {
                    nilaiCrispInput.value = selectedOption.dataset.crisp;
                    updateKategoriPreview(parseFloat(selectedOption.dataset.crisp));
                }
            });
        });
    </script>
@endsection