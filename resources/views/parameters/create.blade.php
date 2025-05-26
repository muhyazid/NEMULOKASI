@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Tambah Parameter Fuzzy Baru</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('parameters.index') }}">Kelola Parameter</a></li>
    <li class="breadcrumb-item active">Tambah Parameter</li>
@endsection

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Form Tambah Parameter</h3>
    </div>
    <form action="{{ route('parameters.store') }}" method="POST">
        @csrf
        <div class="card-body">

            @include('layouts.partials.alerts')

            <div class="form-group">
                <label for="nama_parameter">Nama Parameter</label>
                <input type="text" name="nama_parameter" id="nama_parameter" class="form-control @error('nama_parameter') is-invalid @enderror" value="{{ old('nama_parameter') }}" required placeholder="Contoh: Aksesibilitas">
                @error('nama_parameter') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr>
            <h5>Himpunan Fuzzy (Default: Rendah, Sedang, Tinggi):</h5>
            <p class="text-sm text-muted">Nilai default untuk Crisp dan MF (a,b,c) dapat Anda sesuaikan jika parameter ini memiliki karakteristik khusus.</p>
            <table class="table table-bordered" id="himpunan_table">
                <thead class="bg-light">
                    <tr>
                        <th>Nama Himpunan (untuk Aturan)</th>
                        <th>Nilai Linguistik (untuk Form)</th>
                        <th>Nilai Crisp (Input)</th>
                        <th>MF (a)</th>
                        <th>MF (b)</th>
                        <th>MF (c)</th>
                        {{-- Kolom Aksi dihapus --}}
                    </tr>
                </thead>
                <tbody>
                    {{-- Baris akan ditambahkan oleh JavaScript dengan nilai default --}}
                </tbody>
            </table>
            {{-- Tombol Tambah Himpunan dihapus --}}
            @error('himpunan') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            @error('himpunan.*') <div class="text-danger mt-1">Pastikan semua field himpunan fuzzy terisi.</div> @enderror


        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan Parameter</button>
            <a href="{{ route('parameters.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#himpunan_table tbody');
        // Data default untuk setiap baris himpunan fuzzy
        const defaultHimpunanData = [
            { nama: "Rendah", crisp: "2.5", mfa: "0", mfb: "2", mfc: "5", linguistikPlaceholder: "e.g., Sangat Sulit, Tidak Terlihat, Rendah" },
            { nama: "Sedang", crisp: "4.5", mfa: "2", mfb: "5", mfc: "8", linguistikPlaceholder: "e.g., Cukup Mudah, Terlihat Sebagian, Menengah" },
            { nama: "Tinggi", crisp: "7.5", mfa: "5", mfb: "8", mfc: "10", linguistikPlaceholder: "e.g., Sangat Mudah, Sangat Terlihat, Tinggi" }
        ];
        let globalRowIndex = 0; // Untuk menjaga konsistensi index array yang dikirim

        function addPredefinedRow(data) {
            const row = document.createElement('tr');
            // Perhatikan penggunaan globalRowIndex untuk name attribute
            row.innerHTML = `
                <td><input type="text" name="himpunan[${globalRowIndex}][nama_himpunan]" class="form-control" value="${data.nama}" readonly required></td>
                <td><input type="text" name="himpunan[${globalRowIndex}][nilai_linguistik_view]" class="form-control" placeholder="${data.linguistikPlaceholder}" required></td>
                <td><input type="number" step="any" name="himpunan[${globalRowIndex}][nilai_crisp_input]" class="form-control" value="${data.crisp}" placeholder="e.g., ${data.crisp}" required></td>
                <td><input type="number" step="any" name="himpunan[${globalRowIndex}][mf_a]" class="form-control" value="${data.mfa}" placeholder="e.g., ${data.mfa}" required></td>
                <td><input type="number" step="any" name="himpunan[${globalRowIndex}][mf_b]" class="form-control" value="${data.mfb}" placeholder="e.g., ${data.mfb}" required></td>
                <td><input type="number" step="any" name="himpunan[${globalRowIndex}][mf_c]" class="form-control" value="${data.mfc}" placeholder="e.g., ${data.mfc}" required></td>
                {{-- Tombol hapus per baris dihilangkan --}}
            `;
            tableBody.appendChild(row);
            globalRowIndex++; // Naikkan index untuk baris berikutnya
        }

        // Tambah 3 baris default dengan nama himpunan dan nilai-nilai default yang sudah terisi
        defaultHimpunanData.forEach(data => {
            addPredefinedRow(data);
        });
    });
</script>
@endsection