<section class="space-y-6">
    <header>
        <h4 class="text-lg font-medium text-gray-900 text-danger">
            {{ __('Hapus Akun') }}
        </h4>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    <button class="btn btn-danger" data-toggle="modal" data-target="#confirm-user-deletion">
        {{ __('Hapus Akun') }}
    </button>

    {{-- Modal Konfirmasi --}}
    <div class="modal fade" id="confirm-user-deletion" tabindex="-1" role="dialog" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}" class="p-0">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionLabel">{{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>
                            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Harap masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
                        </p>
                        <div class="form-group">
                            <label for="password_delete" class="sr-only">{{ __('Password') }}</label>
                            <input id="password_delete" name="password" type="password" class="form-control" placeholder="{{ __('Password') }}" required>
                             @if ($errors->userDeletion->get('password'))
                                <div class="text-danger mt-1">
                                    @foreach ((array) $errors->userDeletion->get('password') as $message)
                                        <span>{{ $message }}</span><br>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Batal') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Hapus Akun') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>