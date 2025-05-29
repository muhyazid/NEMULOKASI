@extends('layouts.main')

@section('page-title') <h1 class="m-0">Data Tempat Bisnis</h1> @endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Data Tempat Bisnis</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Tempat Bisnis</h3>
        <div class="card-tools">
            <a href="{{ route('tempat-bisnis.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Data Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        @include('layouts.partials.alerts')
        <div class="table-responsive">
            <table id="tempatBisnisTable" class="table table-bordered table-striped table-hover" style="width:100%">
                <thead class="bg-lightblue">
                    <tr>
                        <th style="width: 5%;" class="text-center">#</th>
                        <th style="width: 20%;">Nama Tempat</th>
                        <th style="width: 35%;">Deskripsi</th>
                        <th class="text-center" style="width: 10%;">Latitude</th>
                        <th class="text-center" style="width: 10%;">Longitude</th>
                        <th style="width: 15%;" class="text-center no-sort">Aksi</th> {{-- Tambahkan kelas no-sort --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($tempatBisnisList as $tempat)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('tempat-bisnis.show', $tempat->id) }}" title="{{ $tempat->nama_tempat }}">
                                {{ Str::limit($tempat->nama_tempat, 35) }}
                            </a>
                        </td>
                        <td class="description-cell" title="{{ $tempat->deskripsi_lokasi }}">
                            {{ $tempat->deskripsi_lokasi ?? '-' }}
                        </td>
                        <td class="text-center">{{ $tempat->latitude ?? '-' }}</td>
                        <td class="text-center">{{ $tempat->longitude ?? '-' }}</td>
                        <td class="text-center action-buttons">
                            <a href="{{ route('tempat-bisnis.show', $tempat->id) }}" class="btn btn-info btn-xs" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('tempat-bisnis.edit', $tempat->id) }}" class="btn btn-warning btn-xs" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('tempat-bisnis.destroy', $tempat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data \'{{ $tempat->nama_tempat }}\'?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <p class="text-muted">Belum ada data tempat bisnis.</p>
                            <a href="{{ route('tempat-bisnis.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Tambah Data Pertama
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

    {{-- Link ke file JavaScript kustom Anda --}}
    <script src="{{ asset('js/custom-datatables.js') }}"></script>
@endsection
