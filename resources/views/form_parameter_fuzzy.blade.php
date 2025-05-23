@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Parameter Fuzzy</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lokasi.form') }}">Home</a></li>
    <li class="breadcrumb-item active">Parameter Fuzzy</li>
@endsection

@section('content')
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

            <form action="{{ route('parameter-fuzzy.simpan') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_parameter">Nama Parameter:</label>
                    <select name="nama_parameter" id="nama_parameter" class="form-control" required>
                        <option value="aksesibilitas">Aksesibilitas</option>
                        <option value="visibilitas">Visibilitas</option>
                        <option value="daya_beli">Daya Beli</option>
                        <option value="persaingan">Persaingan</option>
                        <option value="infrastruktur">Infrastruktur</option>
                        <option value="lingkungan_sekitar">Lingkungan Sekitar</option>
                        <option value="parkir">Parkir</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nilai_fuzzy">Nilai Linguistik:</label>
                    <select name="nilai_fuzzy" id="nilai_fuzzy" class="form-control" required>
                        <!-- Options will be updated by JavaScript based on selected parameter -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="nilai_crisp">Nilai Crisp:</label>
                    <input type="number" name="nilai_crisp" id="nilai_crisp" class="form-control" required>
                    <small class="form-text text-muted">Nilai crisp adalah nilai numerik yang mewakili nilai linguistik
                        (biasanya
                        skala 0-10)</small>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Parameter Fuzzy</button>
            </form>

            <script>
                // Mapping nilai linguistik untuk setiap parameter
                const parameterOptions = {
                    'aksesibilitas': [{
                            text: 'Sangat Mudah',
                            value: 8
                        },
                        {
                            text: 'Sedang',
                            value: 5
                        },
                        {
                            text: 'Tidak Mudah',
                            value: 2
                        }
                    ],
                    'visibilitas': [{
                            text: 'Sangat Terlihat',
                            value: 8
                        },
                        {
                            text: 'Terlihat Sebagian',
                            value: 5
                        },
                        {
                            text: 'Tidak Terlihat',
                            value: 2
                        }
                    ],
                    'daya_beli': [{
                            text: 'Tinggi',
                            value: 8
                        },
                        {
                            text: 'Menengah',
                            value: 5
                        },
                        {
                            text: 'Rendah',
                            value: 2
                        }
                    ],
                    'persaingan': [{
                            text: 'Rendah',
                            value: 8
                        },
                        {
                            text: 'Sedang',
                            value: 5
                        },
                        {
                            text: 'Tinggi',
                            value: 2
                        }
                    ],
                    'infrastruktur': [{
                            text: 'Lengkap',
                            value: 8
                        },
                        {
                            text: 'Cukup',
                            value: 5
                        },
                        {
                            text: 'Tidak Lengkap',
                            value: 2
                        }
                    ],
                    'lingkungan_sekitar': [{
                            text: 'Sangat Mendukung',
                            value: 8
                        },
                        {
                            text: 'Netral',
                            value: 5
                        },
                        {
                            text: 'Tidak Mendukung',
                            value: 2
                        }
                    ],
                    'parkir': [{
                            text: 'Luas',
                            value: 8
                        },
                        {
                            text: 'Sedang',
                            value: 5
                        },
                        {
                            text: 'Sempit',
                            value: 2
                        }
                    ]
                };

                // Update nilai linguistik options berdasarkan parameter yang dipilih
                function updateNilaiFuzzyOptions() {
                    const parameterSelect = document.getElementById('nama_parameter');
                    const nilaiFuzzySelect = document.getElementById('nilai_fuzzy');
                    const nilaiCrispInput = document.getElementById('nilai_crisp');

                    // Hapus semua opsi saat ini
                    nilaiFuzzySelect.innerHTML = '';

                    // Ambil parameter yang dipilih
                    const selectedParameter = parameterSelect.value;

                    // Tambahkan opsi baru berdasarkan parameter
                    if (parameterOptions[selectedParameter]) {
                        parameterOptions[selectedParameter].forEach(option => {
                            const optElement = document.createElement('option');
                            optElement.value = option.text;
                            optElement.textContent = option.text;
                            optElement.dataset.value = option.value;
                            nilaiFuzzySelect.appendChild(optElement);
                        });

                        // Set nilai crisp default berdasarkan opsi pertama
                        if (parameterOptions[selectedParameter].length > 0) {
                            nilaiCrispInput.value = parameterOptions[selectedParameter][0].value;
                        }
                    }
                }

                // Update nilai crisp berdasarkan nilai linguistik yang dipilih
                function updateNilaiCrisp() {
                    const nilaiFuzzySelect = document.getElementById('nilai_fuzzy');
                    const nilaiCrispInput = document.getElementById('nilai_crisp');

                    // Ambil opsi yang dipilih
                    const selectedOption = nilaiFuzzySelect.options[nilaiFuzzySelect.selectedIndex];
                    if (selectedOption && selectedOption.dataset.value) {
                        nilaiCrispInput.value = selectedOption.dataset.value;
                    }
                }

                // Event listeners
                document.getElementById('nama_parameter').addEventListener('change', updateNilaiFuzzyOptions);
                document.getElementById('nilai_fuzzy').addEventListener('change', updateNilaiCrisp);

                // Inisialisasi saat halaman dimuat
                window.onload = function() {
                    updateNilaiFuzzyOptions();
                    updateNilaiCrisp();
                };
            </script>
        </div>
    </div>
@endsection
