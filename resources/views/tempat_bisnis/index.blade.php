@extends('layouts.main')

@section('page-title') <h1 class="m-0">Data Tempat Bisnis</h1> @endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Data Tempat Bisnis</li>
@endsection

{{-- @section('css') Hapus jika hanya berisi style untuk thumbnail --}}
{{-- <style> .img-thumbnail-list { ... } </style> --}}
{{-- @endsection --}}

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
    <div class="card-body p-0">
        @include('layouts.partials.alerts')
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="bg-lightblue">
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th>Nama Tempat</th>
                        <th class="text-center">Latitude</th>
                        <th class="text-center">Longitude</th>
                        {{-- <th class="text-center" style="width: 120px;">Gambar</th> --}} {{-- Kolom Gambar Dihapus --}}
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tempatBisnisList as $index => $tempat)
                    <tr>
                        <td class="text-center align-middle">{{ $tempatBisnisList->firstItem() + $index }}</td>
                        <td class="align-middle">
                            <a href="{{ route('tempat-bisnis.show', $tempat->id) }}">{{ $tempat->nama_tempat }}</a>
                        </td>
                        <td class="text-center align-middle">{{ $tempat->latitude ?? '-' }}</td>
                        <td class="text-center align-middle">{{ $tempat->longitude ?? '-' }}</td>
                        {{-- Kolom Gambar Dihapus --}}
                        <td class="text-center align-middle">
                            <a href="{{ route('tempat-bisnis.show', $tempat->id) }}" class="btn btn-info btn-xs" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('tempat-bisnis.edit', $tempat->id) }}" class="btn btn-warning btn-xs" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('tempat-bisnis.destroy', $tempat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data {{ $tempat->nama_tempat }}?');">
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
                        <td colspan="5" class="text-center py-4"> {{-- Colspan disesuaikan --}}
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
    @if($tempatBisnisList->hasPages())
    <div class="card-footer clearfix">
        {{ $tempatBisnisList->links() }}
    </div>
    @endif
</div>
@endsection