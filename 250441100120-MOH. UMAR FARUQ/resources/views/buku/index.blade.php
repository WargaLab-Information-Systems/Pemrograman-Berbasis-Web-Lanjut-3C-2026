<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Buku Perpustakaan</title>
    <!-- Menggunakan Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen p-6">

    <div class="max-w-6xl mx-auto p-6 bg-white shadow-lg rounded-xl mt-8">
        
        <!-- Judul Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b pb-3 gap-4">
            <h2 class="text-2xl font-bold text-blue-600">📚 Manajemen Data Buku Perpustakaan</h2>
            <!-- Tombol Toggle Tampilan Kartu Semua Buku -->
            <button onclick="toggleCardView()" id="btn-toggle-card" 
                class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition text-sm shadow-sm flex items-center gap-2">
                <span>📖 Lihat Semua Kartu Buku</span>
            </button>
        </div>

        <!-- KELOMPOK KARTU SEMUA BUKU (Hidden secara default) -->
        <div id="section-card-view" class="mb-8 hidden">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Galeri Kartu Buku</h3>
            <div id="grid-kartu-buku" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Kartu buku akan dimuat otomatis di sini oleh JavaScript -->
            </div>
        </div>

        <!-- Form Tambah / Ubah Buku -->
        <form id="form-buku" class="bg-gray-50 p-5 rounded-lg border border-gray-200 mb-8 shadow-sm">
            <h3 id="form-title" class="text-lg font-semibold text-gray-700 mb-4">Tambah Buku Baru</h3>
            <input type="hidden" id="buku-id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">ID Kategori:</label>
                    <input type="number" id="kategori_id" required placeholder="Contoh: 1" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Judul Buku:</label>
                    <input type="text" id="judul" required placeholder="Contoh: Pemrograman Web" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Penulis:</label>
                    <input type="text" id="penulis" required placeholder="Contoh: John Doe" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Tahun Terbit:</label>
                    <input type="number" id="tahun_terbit" required placeholder="Contoh: 2025" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-4 flex space-x-2">
                <button type="submit" id="btn-simpan" 
                    class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    Simpan Buku
                </button>
                <button type="button" onclick="resetForm()" 
                    class="px-4 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition">
                    Batal
                </button>
            </div>
        </form>

        <!-- Tabel Daftar Buku -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse bg-white rounded-lg overflow-hidden shadow-sm">
                <thead>
                    <tr class="bg-blue-50 text-left text-gray-700 text-sm uppercase font-semibold">
                        <th class="py-3 px-4 border-b">Kategori</th>
                        <th class="py-3 px-4 border-b">Judul</th>
                        <th class="py-3 px-4 border-b">Penulis</th>
                        <th class="py-3 px-4 border-b">Tahun Terbit</th>
                        <th class="py-3 px-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-buku" class="divide-y divide-gray-200 text-sm">
                    <!-- Data akan dimuat otomatis oleh JavaScript -->
                </tbody>
            </table>
        </div>

    </div>

    <!-- Script JavaScript -->
    <script>
        async function loadBuku() {
            try {
                const response = await fetch('/api/buku');
                if (!response.ok) throw new Error('Gagal mengambil data buku dari server.');
                
                const json = await response.json();
                const tbody = document.querySelector('#tabel-buku');
                const gridCards = document.querySelector('#grid-kartu-buku');
                
                tbody.innerHTML = '';
                gridCards.innerHTML = '';

                // Menyesuaikan struktur data API Laravel Resource + Pagination (json.data bisa berupa array atau object paginasi)
                let listBuku = [];
                if (Array.isArray(json)) {
                    listBuku = json;
                } else if (json.data) {
                    // Jika menggunakan paginate(), datanya berada di json.data.data
                    // Jika tidak paginate, datanya langsung di json.data
                    listBuku = Array.isArray(json.data) ? json.data : (json.data.data || []);
                }

                if (listBuku.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" class="py-4 text-center text-gray-500">Belum ada data buku tersedia.</td></tr>`;
                    gridCards.innerHTML = `<p class="text-gray-500 col-span-3 text-center">Belum ada kartu buku.</p>`;
                    return;
                }

                listBuku.forEach(buku => {
                    // Mengambil nama kategori dengan aman dari relasi objek atau properti teks
                    const namaKategori = buku.kategori?.nama || buku.kategori?.nama_kategori || buku.kategori_buku || buku.kategori || '-';
                    const judulBuku = buku.judul || buku.judul_buku || buku.nama_judul || '-';
                    const penulisBuku = buku.penulis || '-';
                    const tahunBuku = buku.tahun_terbit || buku.tahun || '-';

                    // 1. Render baris tabel
                    tbody.innerHTML += `
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-4">${namaKategori}</td>
                            <td class="py-3 px-4 font-medium text-gray-900">${judulBuku}</td>
                            <td class="py-3 px-4">${penulisBuku}</td>
                            <td class="py-3 px-4">${tahunBuku}</td>
                            <td class="py-3 px-4 text-center space-x-1">
                                <button onclick="editBuku(${buku.id})" 
                                    class="px-2.5 py-1 bg-amber-500 text-white rounded hover:bg-amber-600 transition text-xs font-semibold">
                                    Ubah
                                </button>
                                <button onclick="hapusBuku(${buku.id})" 
                                    class="px-2.5 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition text-xs font-semibold">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    `;

                    // 2. Render kartu untuk galeri card view
                    gridCards.innerHTML += `
                        <div class="bg-white p-5 rounded-xl shadow-md border border-gray-200 flex flex-col justify-between hover:shadow-lg transition">
                            <div>
                                <h4 class="text-base font-bold text-blue-900 mb-2">${judulBuku}</h4>
                                <p class="text-xs text-gray-600 mb-1"><strong>Penulis:</strong> ${penulisBuku}</p>
                                <p class="text-xs text-gray-600 mb-3"><strong>Tahun Terbit:</strong> ${tahunBuku}</p>
                            </div>
                            <div>
                                <span class="inline-block bg-blue-100 text-blue-800 text-[11px] px-2.5 py-1 rounded-full font-semibold">
                                    Kategori: ${namaKategori}
                                </span>
                            </div>
                        </div>
                    `;
                });
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Fungsi untuk menampilkan/menyembunyikan tampilan kartu semua buku
        function toggleCardView() {
            const sectionCard = document.getElementById('section-card-view');
            const btnToggle = document.getElementById('btn-toggle-card');

            if (sectionCard.classList.contains('hidden')) {
                sectionCard.classList.remove('hidden');
                btnToggle.innerHTML = '<span>❌ Sembunyikan Kartu Buku</span>';
                btnToggle.classList.replace('bg-indigo-600', 'bg-gray-600');
                btnToggle.classList.replace('hover:bg-indigo-700', 'hover:bg-gray-700');
            } else {
                sectionCard.classList.add('hidden');
                btnToggle.innerHTML = '<span>📖 Lihat Semua Kartu Buku</span>';
                btnToggle.classList.replace('bg-gray-600', 'bg-indigo-600');
                btnToggle.classList.replace('hover:bg-gray-700', 'hover:bg-indigo-700');
            }
        }

        document.getElementById('form-buku').addEventListener('submit', async function(e) {
            e.preventDefault();

            const id = document.getElementById('buku-id').value;
            const data = {
                kategori_id: document.getElementById('kategori_id').value,
                judul: document.getElementById('judul').value,
                penulis: document.getElementById('penulis').value,
                tahun_terbit: document.getElementById('tahun_terbit').value
            };

            const url = id ? `/api/buku/${id}` : '/api/buku';
            const method = id ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    const errData = await response.json();
                    let errorMsg = 'Gagal menyimpan data.';
                    if (errData.errors) {
                        errorMsg = Object.values(errData.errors).flat().join('\n');
                    } else if (errData.message) {
                        errorMsg = errData.message;
                    }
                    throw new Error(errorMsg);
                }

                resetForm();
                loadBuku(); 
                alert(id ? 'Data buku berhasil diubah!' : 'Buku baru berhasil ditambahkan!');
            } catch (error) {
                alert('Kesalahan:\n' + error.message); 
            }
        });

        async function editBuku(id) {
            try {
                const response = await fetch(`/api/buku/${id}`);
                if (!response.ok) throw new Error('Gagal mengambil data buku.');
                
                const result = await response.json();
                const buku = result.data || result;

                document.getElementById('buku-id').value = buku.id;
                document.getElementById('kategori_id').value = buku.kategori_id || ''; 
                document.getElementById('judul').value = buku.judul || buku.judul_buku || '';
                document.getElementById('penulis').value = buku.penulis || '';
                document.getElementById('tahun_terbit').value = buku.tahun_terbit || buku.tahun || '';
                
                document.getElementById('form-title').innerText = 'Ubah Data Buku';
                document.getElementById('btn-simpan').innerText = 'Perbarui';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } catch (error) {
                alert(error.message);
            }
        }

        async function hapusBuku(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
                return; 
            }

            try {
                const response = await fetch(`/api/buku/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) throw new Error('Gagal menghapus buku.');

                loadBuku(); 
                alert('Buku berhasil dihapus.');
            } catch (error) {
                alert('Kesalahan: ' + error.message);
            }
        }

        function resetForm() {
            document.getElementById('form-buku').reset();
            document.getElementById('buku-id').value = '';
            document.getElementById('form-title').innerText = 'Tambah Buku Baru';
            document.getElementById('btn-simpan').innerText = 'Simpan Buku';
        }

        // Pastikan fungsi loadBuku dipanggil saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', loadBuku);
    </script>

</body>
</html>