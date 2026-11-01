# Sprint 05 — Build Toko Madura with OOP (Pertemuan 6)

PHP OOP: class, object, property, method, constructor, encapsulation.

## Cara menjalankan
```bash
php -S localhost:8000 -t sprint-05-php-oop
```

## Struktur
```
sprint-05-php-oop/
├── index.php               ← membuat object toko, memanggil method, menghasilkan HTML
├── classes/Barang.php      ← class Barang
├── classes/Transaksi.php   ← class Transaksi (masuk/keluar)
├── classes/TokoMadura.php  ← class utama yang mengelola Barang & Transaksi
├── includes/seed.php       ← buatTokoMadura(): isi data awal lewat method object
└── includes/helpers.php    ← e(), formatRupiah()
```

## Desain class
| Class | Property (private) | Method penting |
|---|---|---|
| `Barang` | kode, nama, kategori, satuan, hargaJual | `__construct`, getter, `setHargaJual()` (validasi tidak negatif), `getHargaFormat()` |
| `Transaksi` | id, tanggal, jenis, barang (object Barang), jumlah, keterangan, harga | `isMasuk()`, `getNilai()`, `getTanggalFormat()`, `getJumlahFormat()` |
| `TokoMadura` | nama, pemilik, alamat, batasMenipis, daftarBarang[], daftarTransaksi[] | `getProfile()`, `tambahBarang()`, `catatMasuk()`, `catatKeluar()`, `getStok()`, `getStatusStok()`, `getPerluKulakan()`, `cariBarang()`, `getBarangMasuk()`, `getBarangKeluar()`, `getTotalMasuk()`, `getTotalKeluar()` |

**Encapsulation:** semua property `private`. Transaksi hanya bisa ditambah lewat `catatMasuk()` / `catatKeluar()`, dan `catatKeluar()` melempar `RuntimeException` kalau jumlah melebihi stok, jadi stok tidak mungkin minus. Constructor `Transaksi` menolak jumlah ≤ 0 dan jenis selain masuk/keluar.

## Pemetaan task
| Task | Yang dikerjakan |
|---|---|
| Task 01 — Create a Class | Tiga class di folder `classes/`; object dibuat dengan `new TokoMadura(...)`, `new Barang(...)` |
| Task 02 — Add Properties & Methods | Property & method di tabel atas; dipilih sesuai kebutuhan halaman (stok, status, kulakan, riwayat) |
| Task 03 — Dynamic Content with Object | `index.php` hanya memanggil method object (`$toko->getPerluKulakan()`, `$barang->getHargaFormat()`, `$t->getJumlahFormat()`, …) untuk membuat semua bagian halaman |

## Checklist Sprint 05
- [x] Class &nbsp; - [x] Object &nbsp; - [x] Property &nbsp; - [x] Method &nbsp; - [x] Constructor &nbsp; - [x] Encapsulation
- [x] OOP data processing &nbsp; - [x] Dynamic HTML &nbsp; - [x] Test (stok sama dengan Sprint 04; `catatKeluar` gas LPG saat stok 0 ditolak) &nbsp; - [x] Commit & push

## Jawaban pertanyaan demo
1. **Class apa yang dibuat?** `Barang`, `Transaksi`, `TokoMadura`.
2. **Object apa yang digunakan?** `$toko` (TokoMadura) yang berisi 9 object `Barang` dan 18 object `Transaksi`.
3. **Property apa yang dimiliki object?** Lihat tabel desain class.
4. **Method apa yang dibuat?** Lihat tabel desain class.
5. **Bagaimana object menghasilkan data di halaman?** `index.php` memanggil method (mis. `$toko->cariBarang($kataKunci)`), hasilnya di-loop dan setiap method getter mengisi sel tabel.

## Review — apa yang lebih mudah dikelola dibanding prosedural?
Perhitungan stok dan aturan "stok tidak boleh minus" sekarang ada di satu tempat (`TokoMadura`). Di versi prosedural data array bisa diubah dari mana saja tanpa validasi; di versi OOP perubahan wajib lewat method.
