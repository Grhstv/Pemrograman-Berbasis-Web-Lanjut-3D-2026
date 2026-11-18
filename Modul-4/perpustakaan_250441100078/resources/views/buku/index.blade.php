@extends('layouts.app')

@section('judul', 'Koleksi Buku - PustakaDigital')

@section('konten')
<div class="page-header">
    <h2>Kelola Koleksi Buku</h2>
    <p>Manajemen data buku perpustakaan secara real-time.</p>
</div>

<!-- Area Pesan Notifikasi & Error Handling -->
<div id="pesan-error" style="color: #dc3545; font-weight: bold; margin-bottom: 10px;"></div>
<div id="pesan-sukses" style="color: #198754; font-weight: bold; margin-bottom: 10px;"></div>

<!-- Formulir Tambah & Ubah Buku -->
<div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #dee2e6;">
    <h3 id="form-title">Tambah Buku Baru</h3>
    <form id="form-buku" onsubmit="simpanData(event)">
        <input type="hidden" id="buku_id">
        
        <div style="margin-bottom: 12px;">
            <label>Judul Buku:</label><br>
            <input type="text" id="judul" required style="width: 100%; padding: 8px; margin-top: 4px;">
        </div>

        <div style="margin-bottom: 12px;">
            <label>Penulis:</label><br>
            <input type="text" id="penulis" required style="width: 100%; padding: 8px; margin-top: 4px;">
        </div>

        <div style="margin-bottom: 12px;">
            <label>Tahun Terbit:</label><br>
            <input type="number" id="tahun_terbit" required style="width: 100%; padding: 8px; margin-top: 4px;">
        </div>

        <div style="margin-bottom: 12px;">
            <label>Kategori Buku:</label><br>
            <select id="kategori_id" required style="width: 100%; padding: 8px; margin-top: 4px;">
                <option value="">-- Pilih Kategori --</option>
                <option value="1">Novel / Fiksi</option>
                <option value="2">Sastra / Sejarah</option>
                <option value="3">Pengembangan Diri</option>
                <option value="4">Teknologi Informasi</option>
            </select>
        </div>

        <button type="submit" id="btn-simpan" style="padding: 8px 16px; cursor: pointer;">Simpan Buku</button>
        <button type="button" onclick="resetForm()" style="padding: 8px 16px; cursor: pointer;">Batal</button>
    </form>
</div>

<!-- Tabel Data Buku -->
<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left;">
    <thead>
        <tr style="background-color: #e9ecef;">
            <th>Kategori</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tahun</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="tabel-buku">
        <tr><td colspan="5" style="text-align:center;">Memuat data dari API...</td></tr>
    </tbody>
</table>

<script>
    let bukuList = [];

    async function loadData() {
        try {
            const response = await fetch('/api/buku', {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                throw new Error(`Gagal mengambil data dari server. (Status: ${response.status})`);
            }

            const result = await response.json();
            bukuList = result.data;

            let html = '';
            if (bukuList.length === 0) {
                html = '<tr><td colspan="5" style="text-align:center;">Tidak ada data buku.</td></tr>';
            } else {
                bukuList.forEach(buku => {
                    html += `
                        <tr>
                            <td><span class="badge">${buku.kategori ?? 'Umum'}</span></td>
                            <td>${buku.judul}</td>
                            <td>${buku.penulis}</td>
                            <td>${buku.tahun}</td>
                            <td>
                                <button onclick="editData(${buku.id})">Ubah</button>
                                <button onclick="hapusData(${buku.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }

            document.getElementById('tabel-buku').innerHTML = html;
            tampilkanPesan('', '');
        } catch (error) {
            tampilkanPesan(error.message, '');
        }
    }

    async function simpanData(event) {
        event.preventDefault();
        const id = document.getElementById('buku_id').value;
        const isEdit = Boolean(id);
        const url = isEdit ? `/api/buku/${id}` : '/api/buku';
        const method = isEdit ? 'PUT' : 'POST';

        const payload = {
            judul: document.getElementById('judul').value,
            penulis: document.getElementById('penulis').value,
            tahun_terbit: document.getElementById('tahun_terbit').value,
            kategori_id: document.getElementById('kategori_id').value
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
                let errMsg = result.message || 'Gagal menyimpan data.';
                if (result.errors) {
                    errMsg = Object.values(result.errors).flat().join(', ');
                }
                throw new Error(errMsg);
            }

            tampilkanPesan('', result.message || 'Data berhasil disimpan!');
            resetForm();
            loadData();
        } catch (error) {
            tampilkanPesan(error.message, '');
        }
    }

    function editData(id) {
        const buku = bukuList.find(b => b.id === id);
        if (buku) {
            document.getElementById('buku_id').value = buku.id;
            document.getElementById('judul').value = buku.judul;
            document.getElementById('penulis').value = buku.penulis;
            document.getElementById('tahun_terbit').value = buku.tahun;
            document.getElementById('kategori_id').value = buku.kategori_id || '';

            document.getElementById('form-title').innerText = 'Ubah Data Buku';
            document.getElementById('btn-simpan').innerText = 'Perbarui Buku';
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    async function hapusData(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) return;

        try {
            const response = await fetch(`/api/buku/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' }
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Gagal menghapus data.');
            }

            tampilkanPesan('', result.message || 'Buku berhasil dihapus!');
            loadData();
        } catch (error) {
            tampilkanPesan(error.message, '');
        }
    }

    function resetForm() {
        document.getElementById('form-buku').reset();
        document.getElementById('buku_id').value = '';
        document.getElementById('kategori_id').value = '';
        document.getElementById('form-title').innerText = 'Tambah Buku Baru';
        document.getElementById('btn-simpan').innerText = 'Simpan Buku';
    }

    function tampilkanPesan(errText, successText) {
        document.getElementById('pesan-error').innerText = errText;
        document.getElementById('pesan-sukses').innerText = successText;
    }

    document.addEventListener('DOMContentLoaded', loadData);
</script>
@endsection