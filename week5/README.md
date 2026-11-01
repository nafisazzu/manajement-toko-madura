# Sprint 04 — Build Toko Madura Server-Side (Pertemuan 5)

PHP prosedural: syntax, variable, tipe data, operator, conditional, loop, function, output HTML.

## Cara menjalankan
```bash
php -S localhost:8000 -t sprint-04-php
```
Buka http://localhost:8000 (atau taruh folder di `htdocs` XAMPP lalu buka `localhost/sprint-04-php`).

## Struktur
```
sprint-04-php/
├── index.php              ← INPUT (?q=, ?tab=) → PROCESS → OUTPUT HTML
├── includes/data.php      ← variable & array data toko
├── includes/functions.php ← function: formatRupiah, formatTanggal, totalJumlah, hitungStok, statusStok, sapaan, e
└── dist/output.css        ← Tailwind hasil build
```

## Pemetaan task
| Task | Yang dikerjakan |
|---|---|
| Task 01 — Create PHP Page | `index.php` menampilkan info toko dari variable (`$namaToko`, `$pemilik`, `$alamat`, `$jamBuka`), sapaan sesuai jam server, ringkasan stok |
| Task 02 — Process Data with PHP | `foreach` menghitung stok tiap barang; `if/elseif/else` di `statusStok()` menentukan Aman/Menipis/Habis; pencarian `?q=` difilter di server; tab `?tab=masuk/keluar` dipilih oleh PHP |
| Task 03 — Dynamic Server-Side Content | Bagian **Perlu Kulakan** (kartu) dan semua tabel dihasilkan oleh loop PHP, bukan ditulis manual; nilai jual barang keluar dihitung `jumlah × hargaJual` |

## Checklist Sprint 04
- [x] PHP setup &nbsp; - [x] PHP syntax &nbsp; - [x] Variable &nbsp; - [x] Conditional &nbsp; - [x] Loop &nbsp; - [x] Function
- [x] Server-side processing &nbsp; - [x] Dynamic HTML &nbsp; - [x] Test (tidak ada warning/error, input `<script>` di-escape) &nbsp; - [x] Commit & push

## Jawaban pertanyaan demo
1. **Apa yang diproses PHP?** Perhitungan stok, status stok, daftar perlu kulakan, filter pencarian, pilihan tab, format rupiah & tanggal.
2. **Variable apa yang digunakan?** `$namaToko`, `$pemilik`, `$alamat`, `$batasMenipis`, `$daftarBarang`, `$barangMasuk`, `$barangKeluar`, `$kataKunci`, `$tabAktif`, `$daftarStok`, `$perluKulakan`.
3. **Bagaimana conditional/loop digunakan?** `foreach ($daftarBarang ...)` menghitung stok; `if ($stok <= $batasMenipis)` memasukkan barang ke daftar kulakan; `if/elseif/else` untuk status; `foreach` lagi untuk mencetak baris tabel.
4. **Apa yang dihasilkan PHP?** HTML lengkap (tabel, kartu, badge) yang sudah berisi data, dikirim ke browser.
5. **Apa beda PHP dengan JavaScript?** PHP berjalan di **server** sebelum halaman dikirim (browser hanya menerima HTML jadi, kode PHP tidak terlihat). JavaScript berjalan di **browser** setelah halaman diterima dan bisa mengubah tampilan tanpa reload.

## Review
Bagian yang memakai PHP: ringkasan, perlu kulakan, tabel stok + pencarian, riwayat + tab, footer (tahun & versi PHP).
