@extends('layouts.main')

@section('page-title')
    <h1 class="m-0">Pengaturan Akun</h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pengaturan Akun</li>
@endsection

@section('content')
<div class="row">
    {{-- Kolom Menu Kiri --}}
    <div class="col-md-3">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="{{ asset('LTE/dist/img/user2-160x160.jpg') }}"
                         alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>
                <p class="text-muted text-center">{{ ucfirst(Auth::user()->role) }}</p>

                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="#update-info" class="nav-link active" data-toggle="tab">
                            <i class="fas fa-user"></i> Ganti Nama & Email
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#update-password" class="nav-link" data-toggle="tab">
                            <i class="fas fa-key"></i> Ubah Password
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="#delete-account" class="nav-link" data-toggle="tab">
                            <i class="fas fa-trash text-danger"></i> <span class="text-danger">Hapus Akun</span>
                        </a>
                    </li>
                    {{-- Tambahkan menu lain jika perlu, sesuai screenshot --}}
                </ul>
            </div>
        </div>
    </div>

    {{-- Kolom Konten Kanan --}}
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">

                    {{-- Tab: Ganti Nama & Email --}}
                    <div class="tab-pane active" id="update-info">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    {{-- Tab: Ubah Password --}}
                    <div class="tab-pane" id="update-password">
                       @include('profile.partials.update-password-form')
                    </div>

                     {{-- Tab: Hapus Akun --}}
                    <div class="tab-pane" id="delete-account">
                        @include('profile.partials.delete-user-form')
                    </div>

                </div>
                </div></div>
        </div>
</div>
@endsection

@section('styles')
{{-- CSS Khusus untuk Tab (jika diperlukan) --}}
<style>
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        background-color: #007bff;
        color: #fff;
    }
    .nav-pills .nav-link:not(.active):hover {
        background-color: #e9ecef;
    }
    .nav-pills .nav-link {
        color: #495057;
        margin-bottom: 5px; /* Memberi sedikit jarak antar menu */
    }
    .nav-pills .nav-link i {
        margin-right: 10px;
        width: 20px; /* Meratakan ikon */
        text-align: center;
    }
</style>
@endsection

@section('scripts')
{{-- JS untuk mengaktifkan Tab --}}
<script>
    $(function () {
        // Handle tab switching
        $('.nav-pills a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // Optional: Handle hash in URL to open specific tab
        var hash = window.location.hash;
        if (hash) {
            $('.nav-pills a[href="' + hash + '"]').tab('show');
        }
    });
</script>
@endsection