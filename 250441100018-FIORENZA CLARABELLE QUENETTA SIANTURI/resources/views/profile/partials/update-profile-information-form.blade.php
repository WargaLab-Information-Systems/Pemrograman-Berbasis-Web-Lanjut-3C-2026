<h3>Informasi Profil</h3>

@if (session('status') === 'profile-updated')
    <div class="alert alert-success">Profil berhasil diperbarui.</div>
@endif

@can('ubah profil sendiri')
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')
@endcan

        <div class="form-group">
            <label for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" @cannot('ubah profil sendiri') disabled @endcannot>
            @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" @cannot('ubah profil sendiri') disabled @endcannot>
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="no_telepon">No. Telepon</label>
            <input id="no_telepon" type="text" name="no_telepon" value="{{ old('no_telepon', $anggota->no_telepon === '-' ? '' : $anggota->no_telepon) }}" @cannot('ubah profil sendiri') disabled @endcannot>
            @error('no_telepon') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Role</label>
            @foreach ($user->getRoleNames() as $r)
                <span class="badge">{{ ucfirst($r) }}</span>
            @endforeach
            <div class="form-hint">Role hanya dapat diubah oleh Admin.</div>
        </div>

@can('ubah profil sendiri')
        <button type="submit" class="btn">Simpan</button>
    </form>
@endcan
