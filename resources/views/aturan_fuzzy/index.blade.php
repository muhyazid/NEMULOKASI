@extends('layouts.main')

@section('title', 'Daftar Aturan Fuzzy')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar Aturan Fuzzy</h3>
                    <a href="{{ route('aturan-fuzzy.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Aturan Baru
                    </a>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($aturanFuzzy->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Nama Aturan</th>
                                        <th width="40%">Kondisi IF</th>
                                        <th width="15%">Hasil THEN</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($aturanFuzzy as $index => $aturan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $aturan->nama_aturan }}</strong>
                                            </td>
                                            <td>
                                                <small>
                                                    @if(is_array($aturan->kondisi))
                                                        @foreach($aturan->kondisi as $parameter => $kategori)
                                                            <span class="badge badge-info mr-1 mb-1">
                                                                {{ ucfirst(str_replace('_', ' ', $parameter)) }}: {{ ucfirst($kategori) }}
                                                            </span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">Kondisi tidak valid</span>
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($aturan->hasil == 'sangat_layak') badge-success
                                                    @elseif($aturan->hasil == 'layak') badge-warning
                                                    @else badge-danger
                                                    @endif
                                                ">
                                                    {{ ucfirst(str_replace('_', ' ', $aturan->hasil)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($aturan->aktif)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('aturan-fuzzy.show', $aturan) }}" 
                                                       class="btn btn-info btn-sm" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('aturan-fuzzy.edit', $aturan) }}" 
                                                       class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('aturan-fuzzy.destroy', $aturan) }}" 
                                                          method="POST" 
                                                          style="display: inline-block;"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus aturan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-database fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada aturan fuzzy</h5>
                            <p class="text-muted">Silakan tambah aturan fuzzy untuk mulai menggunakan sistem inferensi.</p>
                            <a href="{{ route('aturan-fuzzy.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Aturan Pertama
                            </a>
                        </div>
                    @endif
                </div>
                
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="text-muted mb-0">
                                Total: {{ $aturanFuzzy->count() }} aturan 
                                ({{ $aturanFuzzy->where('aktif', true)->count() }} aktif)
                            </p>
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .badge {
            font-size: 0.85em;
        }
        .btn-group .btn {
            margin-right: 2px;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
@endpush