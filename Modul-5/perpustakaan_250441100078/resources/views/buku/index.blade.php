<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Koleksi Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Area Pesan Notifikasi & Error Handling -->
                <x-flash />
                <div id="pesan-error" style="color: #dc3545; font-weight: bold; margin-bottom: 10px;"></div>
                <div id="pesan-sukses" style="color: #198754; font-weight: bold; margin-bottom: 10px;"></div>

                <!-- Formulir Tambah & Ubah Buku (HANYA UNTUK ADMIN & PETUGAS) -->
                @can('kelola data buku')
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #dee2e6;">
                    <h3 id="form-title" style="margin-top:0; font-size: 1.25rem; font-weight: bold; margin-bottom: 15px;">Tambah Buku Baru</h3>
                    <form id="form-buku" onsubmit="simpanData(event)">
                        <input type="hidden" id="buku_id">
                        
                        <div style="margin-bottom: 12px;">
                            <label>Judul Buku:</label><br>
                            <input type="text" id="judul" required style="width: 100%; padding: 8px; margin-top: 4px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>

                        <div style="margin-bottom: 12px;">
                            <label>Penulis:</label><br>
                            <input type="text" id="penulis" required style="width: 100%; padding: 8px; margin-top: 4px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>

                        <div style="margin-bottom: 12px;">
                            <label>Tahun Terbit:</label><br>
                            <input type="number" id="tahun_terbit" required style="width: 100%; padding: 8px; margin-top: 4px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>

                        <div style="margin-bottom: 12px;">
                            <label>Kategori Buku:</label><br>
                            <select id="kategori_id" required style="width: 100%; padding: 8px; margin-top: 4px; border: 1px solid #ccc; border-radius: 4px;">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="1">Novel / Fiksi</option>
                                <option value="2">Sastra / Sejarah</option>
                                <option value="3">Pengembangan Diri</option>
                                <option value="4">Teknologi Informasi</option>
                            </select>
                        </div>

                        <button type="submit" id="btn-simpan" style="padding: 8px 16px; cursor: pointer; background: #2563eb; color: white; border: none; border-radius: 4px;">Simpan Buku</button>
                        <button type="button" onclick="resetForm()" style="padding: 8px 16px; cursor: pointer; background: #6b7280; color: white; border: none; border-radius: 4px;">Batal</button>
                    </form>
                </div>
                @endcan

                <!-- Tabel Data Buku -->
                <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: left; border: 1px solid #dee2e6;">
                    <thead>
                        <tr style="background-color: #f3f4f6;">
                            <th style="border: 1px solid #dee2e6;">Kategori</th>
                            <th style="border: 1px solid #dee2e6;">Judul</th>
                            <th style="border: 1px solid #dee2e6;">Penulis</th>
                            <th style="border: 1px solid #dee2e6;">Tahun</th>
                            @can('kelola data buku')
                            <th style="border: 1px solid #dee2e6;">Aksi</th>
                            @endcan
                            @can('mengajukan peminjaman buku')
                            <th style="border: 1px solid #dee2e6;">Peminjaman</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody id="tabel-buku">
                        <tr><td colspan="5" style="text-align:center; border: 1px solid #dee2e6;">Memuat data dari API...</td></tr>
                    </tbody>
                </table>

                <script>
                    let bukuList = [];
                    // Mengambil status izin pengguna dari Spatie Laravel Permission
                    const userCanKelola = @json(auth()->user()->can('kelola data buku'));
                    const userCanAjukan = @json(auth()->user()->can('mengajukan peminjaman buku'));
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    const pinjamUrl = @json(route('peminjaman.store'));

                    async function loadData() {
                        try {
                            const response = await fetch('/api/buku', {
                                headers: { 'Accept': 'application/json' }
                            });

                            if (!response.ok) throw new Error(`Gagal mengambil data. Status: ${response.status}`);
                            
                            const result = await response.json();
                            bukuList = result.data;

                            let html = '';
                            const colSpanCount = (userCanKelola || userCanAjukan) ? 5 : 4;

                            if (bukuList.length === 0) {
                                html = `<tr><td colspan="${colSpanCount}" style="text-align:center; border: 1px solid #dee2e6;">Tidak ada data buku.</td></tr>`;
                            } else {
                                bukuList.forEach(buku => {
                                    html += `
                                        <tr>
                                            <td style="border: 1px solid #dee2e6;">${buku.kategori ?? 'Umum'}</td>
                                            <td style="border: 1px solid #dee2e6;">${buku.judul}</td>
                                            <td style="border: 1px solid #dee2e6;">${buku.penulis}</td>
                                            <td style="border: 1px solid #dee2e6;">${buku.tahun}</td>
                                            ${userCanKelola ? `
                                            <td style="border: 1px solid #dee2e6;">
                                                <button onclick="editData(${buku.id})" style="padding: 4px 8px; background: #eab308; color: white; border: none; border-radius: 4px; cursor: pointer;">Ubah</button>
                                                <button onclick="hapusData(${buku.id})" style="padding: 4px 8px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer;">Hapus</button>
                                            </td>
                                            ` : ''}
                                            ${userCanAjukan ? `
                                            <td style="border: 1px solid #dee2e6;">
                                                <form method="POST" action="${pinjamUrl}">
                                                    <input type="hidden" name="_token" value="${csrfToken}">
                                                    <input type="hidden" name="buku_id" value="${buku.id}">
                                                    <button type="submit" style="padding: 4px 8px; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer;">Ajukan Pinjam</button>
                                                </form>
                                            </td>
                                            ` : ''}
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
                                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                                body: JSON.stringify(payload)
                            });

                            const result = await response.json();

                            if (!response.ok) {
                                let errMsg = result.message || 'Gagal menyimpan data.';
                                if (result.errors) errMsg = Object.values(result.errors).flat().join(', ');
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
                                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                            });
                            if (!response.ok) throw new Error('Gagal menghapus data.');
                            tampilkanPesan('', 'Buku berhasil dihapus!');
                            loadData();
                        } catch (error) {
                            tampilkanPesan(error.message, '');
                        }
                    }

                    function resetForm() {
                        if (document.getElementById('form-buku')) {
                            document.getElementById('form-buku').reset();
                            document.getElementById('buku_id').value = '';
                            document.getElementById('form-title').innerText = 'Tambah Buku Baru';
                            document.getElementById('btn-simpan').innerText = 'Simpan Buku';
                        }
                    }

                    function tampilkanPesan(errText, successText) {
                        document.getElementById('pesan-error').innerText = errText;
                        document.getElementById('pesan-sukses').innerText = successText;
                    }

                    document.addEventListener('DOMContentLoaded', loadData);
                </script>
                
            </div>
        </div>
    </div>
</x-app-layout>