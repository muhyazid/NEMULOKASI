<h3>Step 2: Proses Inferensi</h3>

<div class="alert alert-info mb-4">
    <i class="fa fa-info-circle"></i> Inferensi adalah proses penerapan aturan fuzzy untuk menentukan output
    berdasarkan kondisi input.
    Dalam metode Tsukamoto, setiap aturan akan menghasilkan nilai α-predikat (nilai alpha) yang selanjutnya
    digunakan untuk menghitung nilai Z.
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="bg-primary text-white">
            <tr>
                <th>No</th>
                <th>Kondisi (IF)</th>
                <th>Nilai α-predikat</th>
                <th>Hasil (THEN)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasilInferensi['aturan'] as $index => $aturan)
                <tr>
                    <td class="text-center">{{ $aturan['nomor_aturan'] }}</td>
                    <td>
                        <p>IF</p>
                        <ul class="list-unstyled">
                            @foreach ($aturan['kondisi_if'] as $kondisi)
                                <li>
                                    <strong>{{ ucfirst($kondisi['parameter']) }}</strong> is
                                    <strong>{{ ucfirst($kondisi['kategori']) }}</strong>
                                    <span
                                        class="badge {{ $kondisi['derajat'] > 0.5 ? 'badge-success' : 'badge-secondary' }}">
                                        μ = {{ number_format($kondisi['derajat'], 2) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="text-center align-middle">
                        <span
                            class="display-4 font-weight-bold {{ $aturan['alpha'] > 0.5 ? 'text-success' : 'text-muted' }}">
                            {{ number_format($aturan['alpha'], 2) }}
                        </span>
                        <div class="progress mt-2" style="height: 20px;">
                            <div class="progress-bar {{ $aturan['alpha'] > 0.5 ? 'bg-success' : 'bg-secondary' }}"
                                role="progressbar" style="width: {{ $aturan['alpha'] * 100 }}%"
                                aria-valuenow="{{ $aturan['alpha'] * 100 }}" aria-valuemin="0" aria-valuemax="100">
                                {{ number_format($aturan['alpha'] * 100, 0) }}%
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <strong>THEN Lokasi
                            {{ ucwords(str_replace('_', ' ', $aturan['hasil_then'])) }}</strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
