<h3>Step 1: Proses Fuzzifikasi</h3>

<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> Fuzzifikasi adalah proses mengubah nilai crisp (tegas) menjadi
            derajat keanggotaan pada himpunan fuzzy.
        </div>
    </div>
</div>

<div class="row">
    <!-- Aksesibilitas -->
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Aksesibilitas: {{ $lokasi->aksesibilitas }}
            </div>
            <div class="card-body">
                <p><strong>Nilai Crisp:</strong> {{ $derajatAksesibilitas['nilai_crisp'] }}</p>
                <p><strong>Derajat Keanggotaan:</strong>
                    {{ number_format($derajatAksesibilitas['derajat'], 2) }}</p>
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar"
                        style="width: {{ $derajatAksesibilitas['derajat'] * 100 }}%"
                        aria-valuenow="{{ $derajatAksesibilitas['derajat'] * 100 }}" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>

                <h5>Derajat Keanggotaan pada Setiap Himpunan Fuzzy:</h5>
                @foreach ($derajatAksesibilitas['semua_derajat'] as $kategori => $nilai)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>{{ ucfirst($kategori) }}:</span>
                            <span>{{ number_format($nilai, 2) }}</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar {{ $kategori == 'rendah' ? 'bg-danger' : ($kategori == 'sedang' ? 'bg-warning' : 'bg-success') }}"
                                role="progressbar" style="width: {{ $nilai * 100 }}%"
                                aria-valuenow="{{ $nilai * 100 }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                @endforeach

                <p><strong>Kategori Dominan:</strong> {{ ucfirst($derajatAksesibilitas['kategori']) }}</p>
            </div>
        </div>
    </div>

    <!-- Visibilitas -->
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Visibilitas: {{ $lokasi->visibilitas }}
            </div>
            <div class="card-body">
                <p><strong>Nilai Crisp:</strong> {{ $derajatVisibilitas['nilai_crisp'] }}</p>
                <p><strong>Derajat Keanggotaan:</strong> {{ number_format($derajatVisibilitas['derajat'], 2) }}
                </p>
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar"
                        style="width: {{ $derajatVisibilitas['derajat'] * 100 }}%"
                        aria-valuenow="{{ $derajatVisibilitas['derajat'] * 100 }}" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>

                <h5>Derajat Keanggotaan pada Setiap Himpunan Fuzzy:</h5>
                @foreach ($derajatVisibilitas['semua_derajat'] as $kategori => $nilai)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>{{ ucfirst($kategori) }}:</span>
                            <span>{{ number_format($nilai, 2) }}</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar {{ $kategori == 'rendah' ? 'bg-danger' : ($kategori == 'sedang' ? 'bg-warning' : 'bg-success') }}"
                                role="progressbar" style="width: {{ $nilai * 100 }}%"
                                aria-valuenow="{{ $nilai * 100 }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                @endforeach

                <p><strong>Kategori Dominan:</strong> {{ ucfirst($derajatVisibilitas['kategori']) }}</p>
            </div>
        </div>
    </div>

    <!-- Daya Beli -->
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Daya Beli: {{ $lokasi->daya_beli }}
            </div>
            <div class="card-body">
                <p><strong>Nilai Crisp:</strong> {{ $derajatDayaBeli['nilai_crisp'] }}</p>
                <p><strong>Derajat Keanggotaan:</strong> {{ number_format($derajatDayaBeli['derajat'], 2) }}
                </p>
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar"
                        style="width: {{ $derajatDayaBeli['derajat'] * 100 }}%"
                        aria-valuenow="{{ $derajatDayaBeli['derajat'] * 100 }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>

                <h5>Derajat Keanggotaan pada Setiap Himpunan Fuzzy:</h5>
                @foreach ($derajatDayaBeli['semua_derajat'] as $kategori => $nilai)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>{{ ucfirst($kategori) }}:</span>
                            <span>{{ number_format($nilai, 2) }}</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar {{ $kategori == 'rendah' ? 'bg-danger' : ($kategori == 'sedang' ? 'bg-warning' : 'bg-success') }}"
                                role="progressbar" style="width: {{ $nilai * 100 }}%"
                                aria-valuenow="{{ $nilai * 100 }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                @endforeach

                <p><strong>Kategori Dominan:</strong> {{ ucfirst($derajatDayaBeli['kategori']) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Persaingan -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Persaingan: {{ $lokasi->persaingan }}
            </div>
            <div class="card-body">
                <p><strong>Nilai Crisp:</strong> {{ $derajatPersaingan['nilai_crisp'] }}</p>
                <p><strong>Derajat Keanggotaan:</strong> {{ number_format($derajatPersaingan['derajat'], 2) }}
                </p>
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar"
                        style="width: {{ $derajatPersaingan['derajat'] * 100 }}%"
                        aria-valuenow="{{ $derajatPersaingan['derajat'] * 100 }}" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>

                <p><strong>Kategori Dominan:</strong> {{ ucfirst($derajatPersaingan['kategori']) }}</p>
            </div>
        </div>
    </div>

    <!-- Infrastruktur -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Infrastruktur: {{ $lokasi->infrastruktur }}
            </div>
            <div class="card-body">
                <p><strong>Nilai Crisp:</strong> {{ $derajatInfrastruktur['nilai_crisp'] }}</p>
                <p><strong>Derajat Keanggotaan:</strong>
                    {{ number_format($derajatInfrastruktur['derajat'], 2) }}</p>
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar"
                        style="width: {{ $derajatInfrastruktur['derajat'] * 100 }}%"
                        aria-valuenow="{{ $derajatInfrastruktur['derajat'] * 100 }}" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>

                <p><strong>Kategori Dominan:</strong> {{ ucfirst($derajatInfrastruktur['kategori']) }}</p>
            </div>
        </div>
    </div>

    <!-- Lingkungan Sekitar -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Lingkungan Sekitar: {{ $lokasi->lingkungan_sekitar }}
            </div>
            <div class="card-body">
                <p><strong>Nilai Crisp:</strong> {{ $derajatLingkunganSekitar['nilai_crisp'] }}</p>
                <p><strong>Derajat Keanggotaan:</strong>
                    {{ number_format($derajatLingkunganSekitar['derajat'], 2) }}
                </p>
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar"
                        style="width: {{ $derajatLingkunganSekitar['derajat'] * 100 }}%"
                        aria-valuenow="{{ $derajatLingkunganSekitar['derajat'] * 100 }}" aria-valuemin="0"
                        aria-valuemax="100">
                    </div>
                </div>

                <p><strong>Kategori Dominan:</strong> {{ ucfirst($derajatLingkunganSekitar['kategori']) }}</p>
            </div>
        </div>
    </div>

    <!-- Parkir -->
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Parkir: {{ $lokasi->parkir }}
            </div>
            <div class="card-body">
                <p><strong>Nilai Crisp:</strong> {{ $derajatParkir['nilai_crisp'] }}</p>
                <p><strong>Derajat Keanggotaan:</strong> {{ number_format($derajatParkir['derajat'], 2) }}</p>
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar"
                        style="width: {{ $derajatParkir['derajat'] * 100 }}%"
                        aria-valuenow="{{ $derajatParkir['derajat'] * 100 }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>

                <p><strong>Kategori Dominan:</strong> {{ ucfirst($derajatParkir['kategori']) }}</p>
            </div>
        </div>
    </div>
</div>
