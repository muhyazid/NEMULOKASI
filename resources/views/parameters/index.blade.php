@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Kelola Parameter Fuzzy</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li> {{-- Diarahkan ke dashboard --}}
    <li class="breadcrumb-item active">Kelola Parameter</li>
@endsection

@section('content')
<div class="card card-outline card-primary"> {{-- Tambahkan card-outline --}}
    <div class="card-header">
        <h3 class="card-title">Daftar Parameter dan Konfigurasi Himpunan Fuzzy</h3>
        <div class="card-tools"> {{-- Pindahkan tombol ke card-tools untuk alignment kanan --}}
            <a href="{{ route('parameters.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Parameter Baru
            </a>
        </div>
    </div>
    <div class="card-body p-0"> {{-- Hapus padding default agar tabel lebih rapat ke card --}}

        @include('layouts.partials.alerts') {{-- Pindahkan alert ke luar tabel jika lebih umum --}}

        <div class="table-responsive">
            <table class="table table-hover table-striped"> {{-- Tambahkan table-hover --}}
                <thead class="bg-lightblue">
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th>Nama Parameter</th>
                        <th>Detail Himpunan Fuzzy</th>
                        <th style="width: 120px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parameters as $index => $param)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="font-weight-bold">
                            {{ Str::ucfirst(str_replace('_', ' ', $param->nama_parameter)) }}
                        </td>
                        <td>
                            @if($param->himpunanFuzzies->isNotEmpty())
                                <div class="list-group list-group-flush">
                                @foreach($param->himpunanFuzzies as $hf)
                                    <div class="list-group-item py-1 px-0 border-0"> {{-- Lebih rapat --}}
                                        <span class="badge bg-purple mr-2" style="min-width: 80px; text-align:left;">{{ $hf->nama_himpunan }}</span>
                                        <span class="text-muted small">View:</span> <strong class="mr-2">{{ $hf->nilai_linguistik_view }}</strong>
                                        <span class="text-muted small">Crisp:</span> <strong class="mr-2">{{ $hf->nilai_crisp_input }}</strong>
                                        <span class="text-muted small">MF:</span> <strong class="text-monospace">[{{ $hf->mf_a }}, {{ $hf->mf_b }}, {{ $hf->mf_c }}]</strong>
                                    </div>
                                @endforeach
                                </div>
                            @else
                                <em class="text-muted">Belum ada himpunan fuzzy.</em>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('parameters.edit', $param->id) }}" class="btn btn-warning btn-xs" title="Edit"> {{-- btn-xs untuk lebih kecil --}}
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('parameters.destroy', $param->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus parameter \'{{ $param->nama_parameter }}\' beserta semua himpunan fuzzynya?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs" title="Hapus"> {{-- btn-xs --}}
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <p class="text-muted">Belum ada parameter yang ditambahkan.</p>
                            <a href="{{ route('parameters.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Buat Parameter Pertama Anda
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- Jika ada paginasi, tambahkan di sini --}}
    {{-- <div class="card-footer clearfix">
        {{ $parameters->links() }}
    </div> --}}
</div>
@endsection