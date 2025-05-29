@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Kelola Parameter Fuzzy</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Kelola Parameter</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Parameter dan Konfigurasi Himpunan Fuzzy</h3>
        <div class="card-tools">
            <a href="{{ route('parameters.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Parameter Baru
            </a>
        </div>
    </div>
    <div class="card-body">

        @include('layouts.partials.alerts')

        <div class="table-responsive">
            <table id="parametersTable" class="table table-bordered table-striped table-hover" style="width:100%">
                <thead class="bg-lightblue">
                    <tr>
                        <th style="width: 5%;" class="text-center">#</th>
                        <th style="width: 20%;">Nama Parameter</th>
                        <th>Detail Himpunan Fuzzy</th>
                        <th style="width: 15%;" class="text-center no-sort">Aksi</th> {{-- Tambahkan kelas no-sort --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($parameters as $param)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="font-weight-bold">
                            {{ Str::ucfirst(str_replace('_', ' ', $param->nama_parameter)) }}
                        </td>
                        <td>
                            @if($param->himpunanFuzzies->isNotEmpty())
                                <ul class="himpunan-fuzzy-list">
                                @foreach($param->himpunanFuzzies as $hf)
                                    <li>
                                        <span class="badge bg-purple">{{ $hf->nama_himpunan }}</span>
                                        <span class="detail-item"><span class="detail-label">View:</span> <strong>{{ $hf->nilai_linguistik_view }}</strong></span>
                                        <span class="detail-item"><span class="detail-label">Crisp:</span> <strong>{{ $hf->nilai_crisp_input }}</strong></span>
                                        <span class="detail-item"><span class="detail-label">MF:</span> <strong class="text-monospace">[{{ $hf->mf_a }}, {{ $hf->mf_b }}, {{ $hf->mf_c }}]</strong></span>
                                    </li>
                                @endforeach
                                </ul>
                            @else
                                <em class="text-muted">Belum ada himpunan fuzzy.</em>
                            @endif
                        </td>
                        <td class="text-center action-buttons">
                            <a href="{{ route('parameters.edit', $param->id) }}" class="btn btn-warning btn-xs" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('parameters.destroy', $param->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus parameter \'{{ Str::ucfirst(str_replace('_', ' ', $param->nama_parameter)) }}\' beserta semua himpunan fuzzynya?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs" title="Hapus">
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
</div>
@endsection

@section('scripts')
    {{-- Library DataTables (CDN) --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('js/custom-datatables.js') }}"></script>
@endsection
