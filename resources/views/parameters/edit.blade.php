@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Edit Parameter Fuzzy</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('parameters.index') }}">Kelola Parameter</a></li>
    <li class="breadcrumb-item active">Edit Parameter</li>
@endsection

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Form Edit Parameter</h3>
    </div>
    <form action="{{ route('parameters.update', $parameter->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">

             @include('layouts.partials.alerts')

            <div class="form-group">
                <label for="nama_parameter">Nama Parameter</label>
                <input type="text" name="nama_parameter" id="nama_parameter" class="form-control @error('nama_parameter') is-invalid @enderror" value="{{ old('nama_parameter', $parameter->nama_parameter) }}" required>
                 @error('nama_parameter') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr>
            <h5>Himpunan Fuzzy:</h5>
            <table class="table table-bordered" id="himpunan_table">
                <thead class="bg-light">
                    <tr>
                        <th>Nama Himpunan (untuk Aturan)</th>
                        <th>Nilai Linguistik (untuk Form)</th>
                        <th>Nilai Crisp (Input)</th>
                        <th>MF (a)</th>
                        <th>MF (b)</th>
                        <th>MF (c)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                     @forelse($parameter->himpunanFuzzies as $index => $hf)
                     <tr>
                        <td><input type="text" name="himpunan[{{ $index }}][nama_himpunan]" class="form-control" value="{{ $hf->nama_himpunan }}" required></td>
                        <td><input type="text" name="himpunan[{{ $index }}][nilai_linguistik_view]" class="form-control" value="{{ $hf->nilai_linguistik_view }}" required></td>
                        <td><input type="number" step="0.01" name="himpunan[{{ $index }}][nilai_crisp_input]" class="form-control" value="{{ $hf->nilai_crisp_input }}" required></td>
                        <td><input type="number" step="0.01" name="himpunan[{{ $index }}][mf_a]" class="form-control" value="{{ $hf->mf_a }}" required></td>
                        <td><input type="number" step="0.01" name="himpunan[{{ $index }}][mf_b]" class="form-control" value="{{ $hf->mf_b }}" required></td>
                        <td><input type="number" step="0.01" name="himpunan[{{ $index }}][mf_c]" class="form-control" value="{{ $hf->mf_c }}" required></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                    </tr>
                     @empty
                     {{-- Akan ditambahkan jika kosong --}}
                     @endforelse
                </tbody>
            </table>
            <button type="button" id="add_himpunan_row" class="btn btn-success mt-2">
                <i class="fas fa-plus"></i> Tambah Himpunan
            </button>
             @error('himpunan') <div class="text-danger mt-1">{{ $message }}</div> @enderror

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-warning">Update Parameter</button>
            <a href="{{ route('parameters.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let rowIndex = {{ $parameter->himpunanFuzzies->count() }}; // Mulai dari index setelah data yg ada
        const tableBody = document.querySelector('#himpunan_table tbody');

        function addRow() {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" name="himpunan[${rowIndex}][nama_himpunan]" class="form-control" required></td>
                <td><input type="text" name="himpunan[${rowIndex}][nilai_linguistik_view]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="himpunan[${rowIndex}][nilai_crisp_input]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="himpunan[${rowIndex}][mf_a]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="himpunan[${rowIndex}][mf_b]" class="form-control" required></td>
                <td><input type="number" step="0.01" name="himpunan[${rowIndex}][mf_c]" class="form-control" required></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
            `;
            tableBody.appendChild(row);
            rowIndex++;
        }

        document.getElementById('add_himpunan_row').addEventListener('click', addRow);

        tableBody.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
            }
        });

        // Jika tidak ada himpunan sama sekali, tambahkan satu baris
        if (rowIndex === 0) {
            addRow();
        }
    });
</script>
@endsection