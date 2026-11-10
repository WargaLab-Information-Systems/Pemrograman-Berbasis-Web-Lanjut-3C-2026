<style>
    .book-form { margin-bottom: 25px; padding: 20px; background: #fff7fb; border: 1px solid #ead7e5; border-radius: 12px; }
    .book-form label { display: block; margin-top: 12px; margin-bottom: 5px; font-weight: 600; }
    .book-form input, .book-form select { width: 100%; padding: 10px; border: 1px solid #d8c5d3; border-radius: 7px; box-sizing: border-box; }
    .book-form button, .book-form a.btn-cancel { display: inline-block; padding: 8px 12px; margin-top: 12px; margin-right: 5px; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 14px; }
    .btn-save { background: #a65c89; color: white; }
    .btn-cancel { background: #e7dce4; color: #333; }
    .field-error { color: #a12645; margin: 4px 0 0; font-size: 14px; }
</style>

<div class="book-form">
    <form method="POST" action="{{ $action }}">
        @csrf
        @isset($buku)
            @method('PUT')
        @endisset

        <label for="kategori_id">Kategori</label>
        <select id="kategori_id" name="kategori_id" required>
            <option value="">Pilih kategori</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}" @selected(old('kategori_id', $buku->kategori_id ?? null) == $kategori->id)>
                    {{ $kategori->nama }}
                </option>
            @endforeach
        </select>
        @error('kategori_id') <p class="field-error">{{ $message }}</p> @enderror

        <label for="judul">Judul Buku</label>
        <input type="text" id="judul" name="judul" minlength="3" maxlength="150" required
               value="{{ old('judul', $buku->judul ?? '') }}">
        @error('judul') <p class="field-error">{{ $message }}</p> @enderror

        <label for="penulis">Penulis</label>
        <input type="text" id="penulis" name="penulis" minlength="3" maxlength="100" required
               value="{{ old('penulis', $buku->penulis ?? '') }}">
        @error('penulis') <p class="field-error">{{ $message }}</p> @enderror

        <label for="tahun_terbit">Tahun Terbit</label>
        <input type="number" id="tahun_terbit" name="tahun_terbit" min="1900" max="{{ date('Y') }}" required
               value="{{ old('tahun_terbit', $buku->tahun_terbit ?? '') }}">
        @error('tahun_terbit') <p class="field-error">{{ $message }}</p> @enderror

        <button type="submit" class="btn-save">{{ $tombol }}</button>
        <a href="{{ route('buku.index') }}" class="btn-cancel">Batal</a>
    </form>
</div>
