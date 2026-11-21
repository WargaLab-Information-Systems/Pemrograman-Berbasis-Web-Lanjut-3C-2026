<h3>Ubah Kata Sandi</h3>

@if (session('status') === 'password-updated')
    <div class="alert alert-success">Kata sandi berhasil diperbarui.</div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="update_password_current_password">Kata Sandi Saat Ini</label>
        <input id="update_password_current_password" type="password" name="current_password" autocomplete="current-password">
        @error('current_password', 'updatePassword') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="update_password_password">Kata Sandi Baru</label>
        <input id="update_password_password" type="password" name="password" autocomplete="new-password">
        @error('password', 'updatePassword') <div class="form-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="update_password_password_confirmation">Konfirmasi Kata Sandi Baru</label>
        <input id="update_password_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
    </div>

    <button type="submit" class="btn">Simpan</button>
</form>
