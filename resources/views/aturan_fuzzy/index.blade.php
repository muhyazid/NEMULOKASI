@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Kelola Aturan Fuzzy</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Kelola Aturan Fuzzy</li>
@endsection

@section('css')
    {{-- CSS untuk DataTables Bootstrap 4 --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom-styles.css') }}"> 
@endsection

@section('content')
<div class="row mb-3">
    <div class="col-12 text-right">
        <a href="{{ route('aturan-fuzzy.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Aturan Baru
        </a>
    </div>
</div>

@include('layouts.partials.alerts')

@if($aturans->isEmpty())
    <div class="card card-body text-center shadow-sm">
        <i class="fas fa-info-circle fa-3x text-info my-3"></i>
        <p class="text-muted lead">Belum ada aturan fuzzy yang ditambahkan.</p>
        <p>
            <a href="{{ route('aturan-fuzzy.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Buat Aturan Fuzzy Pertama Anda
            </a>
        </p>
    </div>
@else
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Daftar Aturan Fuzzy (IF-THEN)</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="aturanFuzzyTable" class="table table-bordered table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No. Aturan</th>
                            @foreach($parameterDisplayNames as $displayParamName)
                                <th>{{ $displayParamName }}</th>
                            @endforeach
                            <th style="width: 10%;">Hasil (THEN)</th>
                            {{-- Tambahkan kelas 'no-sort' jika ingin menargetkan dengan kelas --}}
                            <th style="width: 12%;" class="text-center no-sort">Aksi</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aturans as $aturan)
                        <tr>
                            <td class="text-center">R{{ $loop->iteration }}</td>
                            @foreach($parameterDisplayNames as $displayParamName)
                                @php
                                    $paramKeyForCondition = str_replace(' ', '_', strtolower($displayParamName));
                                    $namaHimpunanTersimpan = is_array($aturan->kondisi) ? ($aturan->kondisi[$paramKeyForCondition] ?? null) : null;
                                    $tampilanLinguistikDiTabel = '-'; 
                                    if ($namaHimpunanTersimpan && isset($linguisticViewMap[$paramKeyForCondition][$namaHimpunanTersimpan])) {
                                        $tampilanLinguistikDiTabel = $linguisticViewMap[$paramKeyForCondition][$namaHimpunanTersimpan];
                                    }
                                @endphp
                                <td class="text-center">
                                    @if($namaHimpunanTersimpan)
                                        <span class="badge bg-lightblue">{{ $tampilanLinguistikDiTabel }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center">
                                <span class="badge {{ $aturan->hasil == 'Layak' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucwords(str_replace('_', ' ', $aturan->hasil)) }}
                                </span>
                            </td>
                            <td class="text-center action-buttons">
                                <a href="{{ route('aturan-fuzzy.edit', $aturan->id) }}" class="btn btn-warning btn-xs" title="Edit">
                                    <i class="fas fa-edit"></i> 
                                </a>
                                <form action="{{ route('aturan-fuzzy.destroy', $aturan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aturan R{{ $loop->iteration }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" title="Hapus">
                                        <i class="fas fa-trash"></i> 
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
    {{-- Library DataTables (CDN) --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

    {{-- Link ke file JavaScript kustom Anda --}}
    <script src="{{ asset('js/custom-datatables.js') }}"></script>
@endsection
