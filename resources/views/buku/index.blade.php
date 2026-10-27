@extends('layouts.app')

@section('konten')
<div class="container bagian-buku">
    <h2 class="judul" style="font-size: 1.8rem; margin-bottom: 20px; font-weight: 900;">Pengelolaan Data Buku</h2>

    <div id="alertSuccess" class="alert-notif alert-sukses" style="display:none;"></div>
    <div id="alertError" class="alert-notif alert-bahaya" style="display:none;"></div>

    <div class="form-container-buku">
        <h3 id="formTitle">Tambah Buku Baru</h3>
        <form id="formBuku">
            <input type="hidden" id="bukuId" value="">
            
            <div class="form-grid">
                <div class="form-group">
                    <label>ID Kategori:</label>
                    <input type="number" id="kategori_id" placeholder="Contoh: 1" required>
                </div>
                <div class="form-group">
                    <label>Tahun Terbit:</label>
                    <input type="number" id="tahun_terbit" placeholder="Contoh: 2024" required>
                </div>
            </div>

            <div class="form-group">
                <label>Judul Buku:</label>
                <input type="text" id="judul" placeholder="Masukkan Judul Buku" required>
            </div>

            <div class="form-group">
                <label>Penulis:</label>
                <input type="text" id="penulis" placeholder="Nama Penulis" required>
            </div>

            <div class="form-aksi">
                <button type="submit" id="btnSimpan" class="btn btn-primary">Simpan Data</button>
                <button type="button" id="btnBatal" class="btn btn-kembali" onclick="resetForm()" style="display:none;">Batal</button>
            </div>
        </form>
    </div>

    <div class="tabel-buku-wrapper">
        <table class="table-detail">
            <thead>
                <tr>
                    <th style="width: 80px;">Kategori</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th style="width: 70px;">Tahun</th>
                    <th style="text-align: center; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelBukuBody">
              
            </tbody>
        </table>
    </div>
</div>

<script>
    const API_URL = '/api/buku';
    let dataBukuGlobal = []; 

    document.addEventListener('DOMContentLoaded', loadBuku);

    function showAlert(message, isError = false) {
        const successDiv = document.getElementById('alertSuccess');
        const errorDiv = document.getElementById('alertError');
        
        if (isError) {
            errorDiv.innerText = message;
            errorDiv.style.display = 'block';
            successDiv.style.display = 'none';
        } else {
            successDiv.innerText = message;
            successDiv.style.display = 'block';
            errorDiv.style.display = 'none';
        }
        setTimeout(() => {
            successDiv.style.display = 'none';
            errorDiv.style.display = 'none';
        }, 5000);
    }

    async function loadBuku() {
        try {
            const response = await fetch(API_URL);
            
            if (!response.ok) throw new Error('Gagal mengambil data dari server');
            
            const result = await response.json();
            dataBukuGlobal = result.data || result;

            const tbody = document.getElementById('tabelBukuBody');
            tbody.innerHTML = '';

            dataBukuGlobal.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.kategori}</td>
                        <td style="font-weight: 700; color: #0f172a;">${item.judul}</td>
                        <td>${item.penulis}</td>
                        <td>${item.tahun_terbit}</td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <button type="button" class="btn-aksi-edit" onclick="editBukuById(${item.id})">Ubah</button>
                                
                                <button type="button" class="btn-aksi-hapus" onclick="deleteBuku(${item.id})">Hapus</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        } catch (error) {
            showAlert(error.message, true);
        }
    }

    function editBukuById(id) {
        const item = dataBukuGlobal.find(buku => buku.id === id);
        if (!item) return;

        document.getElementById('bukuId').value = item.id;
        document.getElementById('kategori_id').value = item.kategori_id || item.kategori;
        document.getElementById('judul').value = item.judul;
        document.getElementById('penulis').value = item.penulis;
        document.getElementById('tahun_terbit').value = item.tahun_terbit;

        document.getElementById('formTitle').innerText = 'Edit Data Buku';
        document.getElementById('btnSimpan').innerText = 'Perbarui Data';
        
        const btnBatal = document.getElementById('btnBatal');
        btnBatal.innerText = 'Batal';
        btnBatal.style.display = 'inline-flex';

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.getElementById('formBuku').addEventListener('submit', async function(e) {
        e.preventDefault();

        const id = document.getElementById('bukuId').value;
        const isEdit = id !== '';
        const url = isEdit ? `${API_URL}/${id}` : API_URL;
        const method = isEdit ? 'PUT' : 'POST';

        const payload = {
            kategori_id: document.getElementById('kategori_id').value,
            judul: document.getElementById('judul').value,
            penulis: document.getElementById('penulis').value,
            tahun_terbit: document.getElementById('tahun_terbit').value
        };

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (!response.ok) {
                let errorMsg = result.message || 'Terjadi kesalahan saat menyimpan data.';
                if (result.errors) {
                    errorMsg = Object.values(result.errors).flat().join('\n');
                }
                throw new Error(errorMsg);
            }

            showAlert(isEdit ? 'Buku berhasil diperbarui!' : 'Buku berhasil ditambahkan!');
            resetForm(); 
            await loadBuku(); 
        } catch (error) {
            showAlert(error.message, true);
        }
    });

    function resetForm() {
        document.getElementById('bukuId').value = '';
        document.getElementById('formBuku').reset();
        document.getElementById('formTitle').innerText = 'Tambah Buku Baru';
        document.getElementById('btnSimpan').innerText = 'Simpan Data';
        document.getElementById('btnBatal').style.display = 'none';
    }

    async function deleteBuku(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) return;

        try {
            const response = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Gagal menghapus data.');

            showAlert('Buku berhasil dihapus!');
            loadBuku(); 
        } catch (error) {
            showAlert(error.message, true);
        }
    }
</script>
@endsection