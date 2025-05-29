@extends('layouts.guest-lte')

@section('title', 'Login')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}">
            {{-- Tampilkan Logo Anda --}}
            <img src="{{ asset('images/logo-fuzzy.png') }}" alt="Logo Fuzzy Lokasi"
                 style="width: 200px; height: auto; max-width: 100%;">
                 {{-- Anda bisa mengatur 'width' sesuai keinginan, misal 150px, 200px, dll. --}}
        </a>
        <h4 class="mt-2"><b>NEMU</b>LOKASI</h4>
    </div>
    
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Silakan login untuk memulai sesi Anda</p>

            @if (session('status'))
                <div class="alert alert-success mb-3" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="username">
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
                           placeholder="Password" required autocomplete="current-password">
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

                <div class="row">
                    {{-- Remember Me --}}
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                    </div>
                    {{-- Tombol Login --}}
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Log in') }}</button>
                    </div>
                    </div>
            </form>

            <p class="mb-1 mt-3">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                @endif
            </p>
            <p class="mb-0">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-center">{{ __('Register a new membership') }}</a>
                @endif
            </p>
        </div>
        </div>
</div>
@endsection