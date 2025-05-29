<section>
    <header>
        <h4 class="text-lg font-medium text-gray-900">
            {{ __('Ubah Password') }}
        </h4>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password">{{ __('Password Saat Ini') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
            @if ($errors->updatePassword->get('current_password'))
                <div class="text-danger mt-1">
                    @foreach ((array) $errors->updatePassword->get('current_password') as $message)
                        <span>{{ $message }}</span><br>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="update_password_password">{{ __('Password Baru') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password">
             @if ($errors->updatePassword->get('password'))
                <div class="text-danger mt-1">
                    @foreach ((array) $errors->updatePassword->get('password') as $message)
                        <span>{{ $message }}</span><br>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation">{{ __('Konfirmasi Password Baru') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
            @if ($errors->updatePassword->get('password_confirmation'))
                <div class="text-danger mt-1">
                     @foreach ((array) $errors->updatePassword->get('password_confirmation') as $message)
                        <span>{{ $message }}</span><br>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-success ml-3">{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>