<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Koleksi Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Form Tambah/Ubah Buku HANYA TAMPIL JIKA BUKAN SISWA -->
            @unlessrole('siswa')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4" id="formTitle">Tambah Buku</h3>
                    
                    <div id="alertMessage" class="hidden mb-4 p-4 rounded-md"></div>
                    
                    <form id="formBuku">
                        <input type="hidden" id="buku_id">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                                <input type="text" id="judul" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                                <input type="text" id="penulis" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit</label>
                                <input type="number" id="tahun_terbit" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <select id="kategori_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="1">Sains</option>
                                    <option value="2">Fiksi</option>
                                    <option value="3">Nonfiksi</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Simpan Data</button>
                            <button type="button" id="btnBatal" class="hidden bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition" onclick="resetForm()">Batal Ubah</button>
                        </div>
                    </form>
                </div>
            </div>
            @endunlessrole

            <!-- Tabel Data Buku (Dilihat oleh Semua Role) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    
                    @role('siswa')
                        <h3 class="text-lg font-bold mb-4">Katalog Buku Perpustakaan</h3>
                    @else
                        <h3 class="text-lg font-bold mb-4">Daftar Data Buku</h3>
                    @endrole

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-gray-50">
                                <th class="p-3 font-semibold">Judul Buku</th>
                                <th class="p-3 font-semibold">Penulis</th>
                                <th class="p-3 font-semibold">Kategori</th>
                                <th class="p-3 font-semibold">Tahun</th>
                                <th class="p-3 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabelBukuBody">
                            <!-- Dimuat JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Pengecekan Role untuk JavaScript -->
    <script>
        const isSiswa = "{{ auth()->user()->hasRole('siswa') ? 'true' : 'false' }}" === "true";
        const isAdmin = "{{ auth()->user()->hasRole('admin') ? 'true' : 'false' }}" === "true";
        const isPetugas = "{{ auth()->user()->hasRole('petugas') ? 'true' : 'false' }}" === "true";
        const apiUrl = '/api/buku';

        document.addEventListener("DOMContentLoaded", () => {
            loadBuku();
        });

        function showMessage(pesan, isError = false) {
            const alertBox = document.getElementById('alertMessage');
            if(!alertBox) return; 
            alertBox.className = isError 
                ? 'mb-4 p-4 rounded-md bg-red-100 text-red-700 border border-red-400 block' 
                : 'mb-4 p-4 rounded-md bg-green-100 text-green-700 border border-green-400 block';
            alertBox.innerText = pesan;
            setTimeout(() => { alertBox.classList.replace('block', 'hidden'); }, 4000);
        }

        async function loadBuku() {
            try {
                const response = await fetch(apiUrl, { headers: { 'Accept': 'application/json' }});
                if (!response.ok) throw new Error("Gagal mengambil data dari API");
                
                const result = await response.json();
                
                let dataArray = Array.isArray(result.data) ? result.data : (result.data?.data || result || []);
                let html = '';
                
                if (dataArray.length === 0) {
                    html = '<tr><td colspan="5" class="text-center p-6 text-gray-500">Belum ada data buku.</td></tr>';
                } else {
                    dataArray.forEach(buku => {
                        const judul = buku.judul_buku || buku.judul || '-';
                        const penulis = buku.nama_penulis || buku.penulis || '-';
                        const kategori = buku.nama_kategori || (buku.kategori ? buku.kategori.nama : 'Tanpa Kategori');
                        const tahun = buku.tahun || buku.tahun_terbit || '-';

                        // 1. Semua role (Admin, Petugas, Siswa) mendapatkan tombol Detail dan Pinjam
                        let btnPinjam = buku.sedang_dipinjam
                            ? `<button type="button" disabled class="bg-gray-400 text-white px-3 py-1 rounded text-sm cursor-not-allowed mb-1 mr-2">Sedang Dipinjam</button>`
                            : `<button class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 mb-1 mr-2" onclick="ajukanPinjam(${buku.id}, '${judul}')">Ajukan Pinjam</button>`;
                        let actionButtons = `
                            <a href="/buku/${buku.id}" class="inline-block bg-indigo-500 text-white px-3 py-1 rounded text-sm hover:bg-indigo-600 mb-1 mr-1">Detail Buku</a>
                            ${btnPinjam}
                        `;
                        // 2. Khusus Admin dan Petugas, tambahkan tombol Ubah dan Hapus
                        if (isAdmin || isPetugas) {
                            actionButtons += `
                                <button class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 mb-1 mr-1" onclick="editBuku(${buku.id}, '${judul}', '${penulis}', ${tahun}, ${buku.kategori_id})">Ubah</button>
                                <button class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 mb-1" onclick="hapusBuku(${buku.id})">Hapus</button>
                            `;
                        }

                        html += `
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="p-3 font-medium">${judul}</td>
                                <td class="p-3">${penulis}</td>
                                <td class="p-3"><span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs">${kategori}</span></td>
                                <td class="p-3">${tahun}</td>
                                <td class="p-3 text-center">${actionButtons}</td>
                            </tr>
                        `;
                    });
                }
                document.getElementById('tabelBukuBody').innerHTML = html;
            } catch (error) {
                console.error("Error load data:", error);
            }
        }

        if(!isSiswa) {
            document.getElementById('formBuku').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const id = document.getElementById('buku_id').value;
                const url = id ? `${apiUrl}/${id}` : apiUrl;
                const method = id ? 'PUT' : 'POST';
                
                const dataBody = {
                    judul: document.getElementById('judul').value,
                    penulis: document.getElementById('penulis').value,
                    tahun_terbit: document.getElementById('tahun_terbit').value,
                    kategori_id: document.getElementById('kategori_id').value
                };

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(dataBody)
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        let errorMsg = result.message || "Gagal menyimpan data";
                        if (result.errors) errorMsg = Object.values(result.errors).flat().join('\n');
                        throw new Error(errorMsg);
                    }

                    showMessage("Data berhasil disimpan!", false);
                    resetForm();
                    loadBuku();
                } catch (error) {
                    showMessage(error.message, true);
                }
            });
        }
        
        function editBuku(id, judul, penulis, tahun, kategori_id) {
            document.getElementById('formTitle').innerText = "Ubah Buku";
            document.getElementById('buku_id').value = id;
            document.getElementById('judul').value = judul;
            document.getElementById('penulis').value = penulis;
            document.getElementById('tahun_terbit').value = tahun;
            document.getElementById('kategori_id').value = kategori_id;
            document.getElementById('btnBatal').classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetForm() {
            document.getElementById('formTitle').innerText = "Tambah Buku";
            document.getElementById('formBuku').reset();
            document.getElementById('buku_id').value = '';
            document.getElementById('btnBatal').classList.add('hidden');
        }

        async function hapusBuku(id) {
            if (confirm("Apakah Anda yakin ingin menghapus buku ini?")) {
                try {
                    const response = await fetch(`${apiUrl}/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
                    if (!response.ok) throw new Error("Gagal menghapus data");

                    showMessage("Data berhasil dihapus!", false);
                    loadBuku();
                } catch (error) {
                    showMessage(error.message, true);
                }
            }
        }
        
        // Fungsi untuk mengirim pengajuan pinjaman ke database
        async function ajukanPinjam(bukuId, judul) {
            if (confirm(`Apakah Anda yakin ingin mengajukan peminjaman untuk buku "${judul}"?`)) {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    // URL diubah menjadi rute umum (/ajukan-pinjam)
                    const response = await fetch('/ajukan-pinjam', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken 
                        },
                        body: JSON.stringify({ buku_id: bukuId })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || "Gagal mengajukan pinjaman");
                    }

                    alert(result.message);
                    
                    // Redirect diubah mengarah ke rute umum (/riwayat)
                    window.location.href = '/riwayat';
                    
                } catch (error) {
                    alert("Terjadi kesalahan: " + error.message);
                }
            }
        }
    </script>
</x-app-layout>