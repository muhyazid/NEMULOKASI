<section>
    {{-- Card untuk Update Informasi Profil --}}
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ __('Informasi Profil') }}</h3>
        </div>
        <div class="card-body">
            <p class="mb-4 text-muted">
                {{ __("Perbarui informasi profil dan alamat email akun Anda.") }}
            </p>

            {{-- Menampilkan Pesan Sukses Jika Ada --}}
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="icon fas fa-check"></i>
                    {{ __('Profil berhasil diperbarui.') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Form untuk mengirim ulang verifikasi (biasanya tidak terlihat) --}}
            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            {{-- Form Utama untuk Update Profil --}}
            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                {{-- Input Nama --}}
                <div class="form-group">
                    <label for="name">{{ __('Nama') }}</label>
                    <input id="name" name="name" type="text"
                           class="form-control @error('name', 'updateProfileInformation') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    {{-- Menampilkan Error Validasi Nama --}}
                    @error('name', 'updateProfileInformation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Input Email --}}
                <div class="form-group">
                    <label for="email">{{ __('Email') }}</label>
                    <input id="email" name="email" type="email"
                           class="form-control @error('email', 'updateProfileInformation') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required autocomplete="username">
                    {{-- Menampilkan Error Validasi Email --}}
                    @error('email', 'updateProfileInformation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    {{-- Bagian Verifikasi Email --}}
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-warning">
                                {{ __('Alamat email Anda belum diverifikasi.') }}
                                {{-- Tombol Kirim Ulang Verifikasi --}}
                                <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline text-primary" style="text-decoration: none; vertical-align: baseline;">
                                    {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                                </button>
                            </p>
                            {{-- Pesan Sukses Kirim Ulang --}}
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-success">
                                    {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Tombol Simpan --}}
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">{{ __('Simpan Perubahan') }}</button>
                </div>

            </form>
            {{-- Akhir Form Utama --}}

        </div>
        </div>
    </section>