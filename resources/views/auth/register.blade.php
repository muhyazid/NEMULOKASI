@extends('layouts.guest-lte')

@section('title', 'Register')

@section('content')
<div class="register-box">
    <div class="register-logo">
        <a href="{{ url('/') }}">
            {{-- Tampilkan Logo Anda --}}
            <img src="{{ asset('images/logo-fuzzy.png') }}" alt="Logo Fuzzy Lokasi"
                 style="width: 200px; height: auto; max-width: 100%;">
                 {{-- Pastikan ukurannya sama dengan di halaman login --}}
        </a>
        <h4 class="mt-2"><b>NEMU</b>LOKASI</h4>
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Daftar akun baru</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Nama --}}
                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus autocomplete="name">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           placeholder="Email" value="{{ old('email') }}" required autocomplete="username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                     @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Password" required autocomplete="new-password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="Konfirmasi Password" required autocomplete="new-password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        {{-- Anda bisa menambahkan checkbox agreement di sini jika perlu --}}
                        {{-- <div class="icheck-primary"> ... </div> --}}
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Register') }}</button>
                    </div>
                    </div>
            </form>

            <a href="{{ route('login') }}" class="text-center mt-3 d-block">{{ __('I already have a membership') }}</a>
        </div>
        </div></div>
@endsection