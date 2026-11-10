<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Buku</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .form-group { margin-bottom: 12px; }
        label { display: inline-block; width: 130px; }
        input, select { padding: 6px; width: 250px; }
        button { padding: 8px 15px; cursor: pointer; }
        .error { color: red; margin-top: 10px; }
        .success { color: green; margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Kelola Data Buku</h2>

    <div id="error-message" class="error"></div>
    <div id="success-message" class="success"></div>

    <form id="bukuForm">
        <input type="hidden" id="bukuId">
        <div class="form-group">
            <label for="kategori_id">Kategori:</label>
            <select id="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
            </select>
        </div>
        <div class="form-group">
            <label for="judul">Judul:</label>
            <input type="text" id="judul" required>
        </div>
        <div class="form-group">
            <label for="penulis">Penulis:</label>
            <input type="text" id="penulis" required>
        </div>
        <div class="form-group">
            <label for="tahun_terbit">Tahun Terbit:</label>
            <input type="number" id="tahun_terbit" required>
        </div>
        <button type="submit" id="btnSimpan">Simpan Buku</button>
        <button type="button" onclick="resetForm()" style="display:none;" id="btnBatal">Batal</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="bukuTableBody">
        </tbody>
    </table>

    <script>
        const API_URL = 'http://127.0.0.1:8000/api/buku';

        // menampilkan data buku
        async function fetchBuku() {
            try {
                const response = await fetch('http://127.0.0.1:8000/api/buku', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const rawText = await response.text();
                
                if (!response.ok) {
                    throw new Error(`Server merespons dengan status ${response.status}. Isi respons:\n<pre style="white-space: pre-wrap; font-size: 11px;">${rawText}</pre>`);
                }

                const result = JSON.parse(rawText);
                const dataBuku = result.data || result;

                const tbody = document.getElementById('bukuTableBody');
                tbody.innerHTML = ''; 

                if (!dataBuku || dataBuku.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" style="text-align: center;">Belum ada data buku.</td></tr>`;
                    return;
                }

                dataBuku.forEach(buku => {
                tbody.innerHTML += `
                    <tr>
                        <!-- 1. Ubah dari buku.kategori?.nama menjadi buku.kategori saja -->
                        <td>${buku.kategori ?? '-'}</td>
                        <td>${buku.judul ?? '-'}</td>
                        <td>${buku.penulis ?? '-'}</td>
                        
                        <!-- 2. Ubah dari buku.tahun_terbit menjadi buku.tahun -->
                        <td>${buku.tahun ?? '-'}</td>
                        
                        <td>
                            <!-- Pastikan parameter tahun menggunakan buku.tahun -->
                            <button type="button" onclick="editBuku(${buku.id}, ${buku.kategori_id}, '${buku.judul}', '${buku.penulis}', ${buku.tahun})">Ubah</button>
                            <button type="button" onclick="deleteBuku(${buku.id})">Hapus</button>
                        </td>
                    </tr>
                `;
                });

            } catch (error) {
                console.error("GAGAL TOTAL:", error);
                document.getElementById('error-message').innerHTML = `<b>TERNYATA INI ISI ERRORNYA DARI SERVER:</b><br>${error.message}`;
            }
        }

        // mengambil data kategori
        async function fetchKategori() {
            try {
                const response = await fetch('http://127.0.0.1:8000/api/buku-kategori', {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) throw new Error('Gagal memuat data kategori.');

                const result = await response.json();
                const dataKategori = result.data || result;

                const selectKategori = document.getElementById('kategori_id');
                selectKategori.innerHTML = '<option value="">-- Pilih Kategori --</option>';

                dataKategori.forEach(kat => {
                    selectKategori.innerHTML += `
                        <option value="${kat.id}">${kat.nama}</option>
                    `;
                });

            } catch (error) {
                console.error("Kesalahan memuat kategori:", error);
            }
        }

        // update data
        document.getElementById('bukuForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            clearError();
            clearSuccess();

            const id = document.getElementById('bukuId').value;
            const data = {
                kategori_id: document.getElementById('kategori_id').value,
                judul: document.getElementById('judul').value,
                penulis: document.getElementById('penulis').value,
                tahun_terbit: document.getElementById('tahun_terbit').value
            };

            const url = id ? `${API_URL}/${id}` : API_URL;
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
                    let errorMsg = errData.message || 'Terjadi kesalahan saat menyimpan data.';
                    if (errData.errors) {
                        errorMsg = Object.values(errData.errors).flat().join('<br>');
                    }
                    throw new Error(errorMsg);
                }

                const actionMessage = id ? 'Data buku berhasil diperbarui!' : 'Data buku berhasil ditambahkan!';
                resetForm();
                fetchBuku();
                showSuccess(actionMessage);

            } catch (error) {
                showError(error.message);
            }
        });

        function editBuku(id, kategori_id, judul, penulis, tahun) {
            document.getElementById('bukuId').value = id;
            document.getElementById('kategori_id').value = kategori_id;
            document.getElementById('judul').value = judul;
            document.getElementById('penulis').value = penulis;
            
            // Masukkan nilai 'tahun' ke input 'tahun_terbit' yang ada di form
            document.getElementById('tahun_terbit').value = tahun; 
            
            document.getElementById('btnSimpan').innerText = 'Perbarui Buku';
            document.getElementById('btnBatal').style.display = 'inline-block';
            clearError();
            clearSuccess();
        }
        // hapus data
        async function deleteBuku(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) return;
            clearError();
            clearSuccess();

            try {
                const response = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) throw new Error('Gagal menghapus data buku.');

                fetchBuku();
                showSuccess('Data buku berhasil dihapus!');
            } catch (error) {
                showError(error.message);
            }
        }

        function resetForm() {
            document.getElementById('bukuForm').reset();
            document.getElementById('bukuId').value = '';
            document.getElementById('btnSimpan').innerText = 'Simpan Buku';
            document.getElementById('btnBatal').style.display = 'none';
        }

        function showError(message) {
            document.getElementById('error-message').innerHTML = message;
        }

        function clearError() {
            document.getElementById('error-message').innerHTML = '';
        }

        function showSuccess(message) {
            const successDiv = document.getElementById('success-message');
            successDiv.innerHTML = message;
            setTimeout(() => {
                successDiv.innerHTML = '';
            }, 3000);
        }

        function clearSuccess() {
            document.getElementById('success-message').innerHTML = '';
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchBuku();
            fetchKategori();
        });
    </script>
</body>
</html>