@extends('layouts.main')

@section('title', 'Daftar Buku')

@section('content')
    <h2>Daftar Koleksi Buku</h2>

    <!-- Form Tambah/Ubah Buku -->
    <div class="card" style="margin-top: 20px; margin-bottom: 20px;">
        <h3 class="card-title" id="formTitle">Tambah Buku</h3>
        <div id="alertMessage" class="alert-error" style="display: none; margin-bottom: 15px;"></div>
        
        <form id="formBuku">
            <input type="hidden" id="buku_id">
            
            <!-- UBAH DISINI: flex-direction: column agar inputan ke bawah -->
            <div style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px;">Judul</label>
                    <input type="text" id="judul" required style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px;">Penulis</label>
                    <input type="text" id="penulis" required style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px;">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" required style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px;">
                </div>
                
                <!-- DROPDOWN KATEGORI -->
                <div>
                    <label style="display: block; margin-bottom: 5px;">Kategori</label>
                    <select id="kategori_id" required style="width: 100%; padding: 8px; border: 1px solid var(--border-color); border-radius: 4px; background-color: white;">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="1">Sains</option>
                        <option value="2">Fiksi</option>
                        <option value="3">Nonfiksi</option>
                    </select>
                </div>
            </div>
            
            <div>
                <button type="submit" class="btn">Simpan Data</button>
                <button type="button" class="btn btn-secondary" id="btnBatal" style="display: none;" onclick="resetForm()">Batal Ubah</button>
            </div>
        </form>
    </div>

    <!-- Tabel Data Buku -->
    <div class="detail-card">
        <table style="width: 100%;">
            <thead style="border-bottom: 2px solid var(--border-color);">
                <tr>
                    <th style="padding: 12px; text-align: left;">Judul Buku</th>
                    <th style="padding: 12px; text-align: left;">Penulis</th>
                    <th style="padding: 12px; text-align: left;">Kategori</th>
                    <th style="padding: 12px; text-align: left;">Tahun</th>
                    <th style="padding: 12px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelBukuBody">
                <!-- Baris tabel (tr) akan dimuat otomatis oleh JavaScript -->
            </tbody>
        </table>
    </div>

<script>
    const apiUrl = '/api/buku';

    document.addEventListener("DOMContentLoaded", () => {
        loadBuku();
    });

    function showMessage(pesan, isError = false) {
        const alertBox = document.getElementById('alertMessage');
        alertBox.style.backgroundColor = isError ? '#f8d7da' : '#d4edda';
        alertBox.style.color = isError ? '#721c24' : '#155724';
        alertBox.style.borderColor = isError ? '#f5c6cb' : '#c3e6cb';
        alertBox.innerText = pesan;
        alertBox.style.display = 'block';
        setTimeout(() => alertBox.style.display = 'none', 4000);
    }

    async function loadBuku() {
        try {
            const response = await fetch(apiUrl, { headers: { 'Accept': 'application/json' }});
            if (!response.ok) throw new Error("Gagal mengambil data dari API");
            
            const result = await response.json();
            
            let dataArray = [];
            if (Array.isArray(result.data)) {
                dataArray = result.data;
            } else if (result.data && Array.isArray(result.data.data)) {
                dataArray = result.data.data;
            } else if (Array.isArray(result)) {
                dataArray = result;
            }

            let html = '';
            
            if (dataArray.length === 0) {
                html = '<tr><td colspan="5" style="text-align: center; padding: 15px;">Belum ada data buku.</td></tr>';
            } else {
                dataArray.forEach(buku => {
                    const judul = buku.judul_buku || buku.judul || '-';
                    const penulis = buku.nama_penulis || buku.penulis || '-';
                    const kategori = buku.nama_kategori || (buku.kategori ? buku.kategori.nama : 'Tanpa Kategori');
                    const tahun = buku.tahun || buku.tahun_terbit || '-';

                    html += `
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 12px;"><strong>${judul}</strong></td>
                            <td style="padding: 12px;">${penulis}</td>
                            <td style="padding: 12px;"><span class="badge">${kategori}</span></td>
                            <td style="padding: 12px;">${tahun}</td>
                            <td style="padding: 12px; text-align: center;">
                                <button class="btn" style="padding: 6px 12px; font-size: 0.8rem; margin-top: 0; margin-right: 5px;" onclick="editBuku(${buku.id}, '${judul}', '${penulis}', ${tahun}, ${buku.kategori_id})">Ubah</button>
                                <button class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem; margin-top: 0; background-color: #e74c3c;" onclick="hapusBuku(${buku.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }
            document.getElementById('tabelBukuBody').innerHTML = html;
        } catch (error) {
            showMessage("Error JavaScript: " + error.message, true);
        }
    }

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

    function editBuku(id, judul, penulis, tahun, kategori_id) {
        document.getElementById('formTitle').innerText = "Ubah Buku";
        document.getElementById('buku_id').value = id;
        document.getElementById('judul').value = judul;
        document.getElementById('penulis').value = penulis;
        document.getElementById('tahun_terbit').value = tahun;
        document.getElementById('kategori_id').value = kategori_id;
        document.getElementById('btnBatal').style.display = 'inline-block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetForm() {
        document.getElementById('formTitle').innerText = "Tambah Buku";
        document.getElementById('formBuku').reset();
        document.getElementById('buku_id').value = '';
        document.getElementById('btnBatal').style.display = 'none';
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
</script>
@endsection
