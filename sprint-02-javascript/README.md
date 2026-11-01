# Sprint 02 — Make Toko Madura Interactive (Pertemuan 3)

JavaScript + DOM + Event. Tampilan dipindah dari CSS manual ke **Tailwind CSS** (slide "Dari CSS Manual ke Tailwind CSS").

## Cara menjalankan
Buka `index.html` di browser. CSS sudah di-build ke `dist/output.css`.
Kalau mengubah class Tailwind, build ulang dari folder root: `npm install` lalu `npm run build:s2`.

## Pemetaan task
| Task | Yang dikerjakan | Lokasi di `js/app.js` |
|---|---|---|
| Task 01 — Select & Change | `querySelector` untuk semua elemen; `textContent` mengubah sapaan sesuai jam (pagi/siang/sore/malam) dan tanggal hari ini | bagian 1 & 3, `tampilkanSapaan()` |
| Task 02 — Handle User Event | `click` tombol menu mobile (buka/tutup + `aria-expanded`), `input` pencarian stok, `click` tab Barang Masuk/Keluar, `change` jenis transaksi & barang | bagian 5 |
| Task 03 — One Complete Interaction | **Form Catat Transaksi** (`submit`): validasi → barang keluar ditolak kalau melebihi stok → data ditambah ke array → ringkasan, tabel stok, riwayat ikut berubah → baris baru disorot → pesan sukses | bagian 6 |

Data awal ada di `js/data.js`. Input pengguna di-escape (`amankan()`) sebelum masuk `innerHTML`.

## Checklist Sprint 02
- [x] JavaScript &nbsp; - [x] DOM &nbsp; - [x] Event handling &nbsp; - [x] User interaction
- [x] Test (cari "minyak" → 1 baris; keluar 10 botol minyak ditolak; keluar 3 botol berhasil, stok jadi 1) &nbsp; - [x] Commit & push

## Jawaban pertanyaan demo
1. **Interaksi apa yang dibuat?** Mencatat barang masuk/keluar lewat form, dan stok langsung berubah tanpa reload.
2. **Event apa yang digunakan?** `submit` (form), `click` (menu, tab), `input` (pencarian), `change` (radio & select).
3. **Apa yang dilakukan JavaScript?** `preventDefault()` agar halaman tidak reload, membaca nilai input, memvalidasi (barang dipilih, jumlah > 0, keluar ≤ stok), `push` ke array `barangMasuk`/`barangKeluar`, lalu memanggil fungsi render.
4. **Elemen apa yang berubah?** Angka ringkasan, isi `<tbody>` stok & riwayat, tab aktif, pesan sukses/error di bawah form, teks info stok.

## Review — apakah interaksinya berguna?
- [x] Mudah dipahami: pilih jenis → barang → jumlah → simpan
- [x] Respon terlihat jelas: pesan hijau/merah + baris baru disorot
- [x] Sesuai kebutuhan toko: mencegah stok minus
- [x] Tidak mengganggu UI: tidak reload, layout tetap rapi di mobile
