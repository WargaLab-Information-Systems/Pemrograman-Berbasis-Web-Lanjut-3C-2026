<x-app-layout>
    <style>
        :root {
            --primary-color: #3498db;
            --border-color: #e5e7eb;
            --text-dark: #374151;
        }
        
        .card-container {
            background: white;
            border-radius: 6px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }

        /* --- STYLING KHUSUS POP-UP MODAL --- */
        .modal-overlay {
            display: none; /* Sembunyikan secara default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Latar belakang gelap transparan */
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-box {
            background: white;
            width: 100%;
            max-width: 550px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: slideDown 0.3s ease-out forwards;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header Modal (Bagian Atas) */
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
        }
        .modal-header h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            text-transform: uppercase;
        }
        .btn-close-x {
            background: none;
            border: none;
            font-size: 1.2rem;
            font-weight: bold;
            color: #9ca3af;
            cursor: pointer;
        }
        .btn-close-x:hover { color: #374151; }

        /* Body Modal (Bagian Tengah/Form) */
        .modal-body {
            padding: 20px;
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Footer Modal (Bagian Bawah/Tombol) */
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background-color: #f9fafb;
        }

        /* --- STYLING FORM & TABEL --- */
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 0.95rem;
        }
        .form-control:focus { outline: none; border-color: var(--primary-color); }
        
        .btn-modul4 {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            text-transform: uppercase;
            font-weight: 600;
            color: white;
        }
        .btn-primary { background-color: var(--primary-color); }
        .btn-primary:hover { background-color: #2980b9; }
        .btn-secondary { background-color: #6b7280; }
        .btn-secondary:hover { background-color: #4b5563; }
        
        .table-custom { width: 100%; border-collapse: collapse; text-align: left; }
        .table-custom th { padding: 15px; font-weight: 600; color: #374151; font-size: 0.9rem; border-bottom: 2px solid var(--border-color); background-color: #f9fafb; }
        .table-custom td { padding: 12px; font-size: 0.9rem; border-bottom: 1px solid var(--border-color); color: #4b5563;}
        
        .badge { padding: 6px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; display: inline-block; }
        .badge-admin { background-color: #fde0dd; color: #c0392b; }
        .badge-petugas { background-color: #e1f5fe; color: #0288d1; }
        .badge-siswa { background-color: #e8f5e9; color: #2e7d32; }
    </style>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div id="alert-sukses" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb; transition: opacity 0.5s ease;">
                    {{ session('success') }}
                </div>
                
                <!-- Script untuk menghilangkan alert otomatis -->
                <script>
                    setTimeout(function() {
                        let alertBox = document.getElementById('alert-sukses');
                        if (alertBox) {
                            alertBox.style.opacity = '0'; // Membuatnya transparan perlahan
                            setTimeout(function() {
                                alertBox.style.display = 'none'; // Menghilangkan elemen dari halaman setelah transparan
                            }, 500); // Menunggu transisi opacity selesai (0.5 detik)
                        }
                    }, 3000); // Waktu tampil 3000 milidetik (3 detik)
                </script>
            @endif

            <!-- Tombol Buka Pop-up -->
            <div style="margin-bottom: 20px; text-align: right;">
                <button type="button" class="btn-modul4" style="background-color: #24a6f0;" onclick="bukaFormTambah()">
                    Tambah Pengguna Baru
                </button>
            </div>

            <!-- TABEL DAFTAR PENGGUNA -->
            <div class="card-container" style="padding: 0;">
                <div style="overflow-x: auto;">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td style="font-weight: 600; color: #111827;">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @php $roleName = $user->roles->first()->name ?? 'siswa'; @endphp
                                        @if($roleName == 'admin') 
                                            <span class="badge badge-admin">Admin</span>
                                        @elseif($roleName == 'petugas') 
                                            <span class="badge badge-petugas">Petugas</span>
                                        @else 
                                            <span class="badge badge-siswa">Siswa</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <!-- Perbaikan: ID dibungkus tanda kutip tunggal -->
                                        <button type="button" class="btn-modul4" style="background-color: #f39c12; padding: 6px 12px; font-size: 0.8rem; margin-right: 5px;" 
                                                onclick="editUser('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $roleName }}')">
                                            UBAH
                                        </button>
                                        
                                        @if(auth()->user()->id !== $user->id)
                                        <form action="{{ route('akun.destroy', $user->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-modul4" style="background-color: #e74c3c; padding: 6px 12px; font-size: 0.8rem;" onclick="return confirm('Hapus akun ini?')">HAPUS</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" style="text-align: center; padding: 20px; color: #6b7280;">Belum ada data pengguna.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- STRUKTUR POP-UP (MODAL) SEPERTI GAMBAR REFERENSI -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-box">
            
            <!-- HEADER MODAL -->
            <div class="modal-header">
                <h3 id="formTitle">TAMBAH PENGGUNA BARU</h3>
                <button type="button" class="btn-close-x" onclick="tutupForm()">&#10005;</button>
            </div>
            
            <form action="{{ route('akun.store') }}" method="POST" id="formAkun">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <!-- BODY MODAL -->
                <div class="modal-body">
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; color: #4b5563; font-size: 0.9rem;">Nama Lengkap</label>
                            <input type="text" name="name" id="name" required class="form-control">
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 5px; color: #4b5563; font-size: 0.9rem;">Email</label>
                            <input type="email" name="email" id="email" required class="form-control">
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 5px; color: #4b5563; font-size: 0.9rem;">Password <span id="passwordHelp" style="font-size: 0.8rem; color: #9ca3af;">(Wajib diisi)</span></label>
                            <input type="password" name="password" id="password" required class="form-control">
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 5px; color: #4b5563; font-size: 0.9rem;">Role</label>
                            <select name="role" id="role" required class="form-control" style="background-color: white;">
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="petugas">Petugas</option>
                                <option value="siswa">Siswa</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- FOOTER MODAL (TOMBOL) -->
                <div class="modal-footer">
                    <button type="button" class="btn-modul4 btn-secondary" onclick="tutupForm()">TUTUP</button>
                    <button type="submit" class="btn-modul4 btn-primary" id="btnSubmit">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Kontrol Pop-up -->
    <script>
        function bukaFormTambah() {
            document.getElementById('modalOverlay').style.display = 'flex';
            
            document.getElementById('formTitle').innerText = "TAMBAH PENGGUNA BARU";
            document.getElementById('btnSubmit').innerText = "SIMPAN";
            
            document.getElementById('formAkun').reset();
            document.getElementById('formAkun').action = "{{ route('akun.store') }}";
            document.getElementById('formMethod').value = 'POST';
            
            document.getElementById('password').required = true;
            document.getElementById('passwordHelp').innerText = "(Wajib diisi)";
        }

        function editUser(id, name, email, roleName) {
            document.getElementById('modalOverlay').style.display = 'flex';
            
            document.getElementById('formTitle').innerText = "EDIT PENGGUNA";
            document.getElementById('btnSubmit').innerText = "UPDATE";
            
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('role').value = roleName;
            
            document.getElementById('password').required = false;
            document.getElementById('passwordHelp').innerText = "(Kosongkan jika tidak diubah)";

            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('formAkun').action = `/akun/${id}`;
        }

        function tutupForm() {
            document.getElementById('modalOverlay').style.display = 'none';
        }
    </script>
</x-app-layout>