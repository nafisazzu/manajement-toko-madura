# Sprint 06 — Structure Toko Madura with MVC (Pertemuan 7)

Model – View – Controller, dengan data tersimpan di file JSON.

## Cara menjalankan
```bash
php -S localhost:8000 -t sprint-06-mvc
```
Buka http://localhost:8000. Folder `data/` harus bisa ditulis (untuk menyimpan transaksi).
Tombol **Reset data demo** di footer mengembalikan data ke kondisi awal (`data/toko.seed.json`).

## Struktur MVC
```
sprint-06-mvc/
├── index.php                          ← front controller + routing (?page=)
├── app/
│   ├── Models/
│   │   ├── Barang.php
│   │   ├── Transaksi.php
│   │   └── TokoMadura.php             ← data + aturan stok + muat()/simpan() JSON
│   ├── Controllers/
│   │   └── TokoController.php         ← index(), show(), process(), reset(), notFound()
│   ├── Views/
│   │   ├── layouts/header.php, footer.php
│   │   ├── toko-page.php              ← dashboard
│   │   ├── barang-detail.php          ← detail satu barang
│   │   ├── partials/form-transaksi.php
│   │   └── 404.php
│   └── Core/helpers.php               ← e(), formatRupiah(), url(), csrfToken()
├── data/toko.json                     ← data aktif
├── data/toko.seed.json                ← data awal untuk reset
└── public/css/output.css
```

## Routing
| Request | Controller | Hasil |
|---|---|---|
| `GET index.php` / `?page=home` | `index()` | dashboard (ringkasan, perlu kulakan, stok + cari, riwayat, form) |
| `GET ?page=barang&kode=BRG-001` | `show($kode)` | detail barang + riwayat + form khusus barang itu |
| `POST ?page=transaksi` | `process()` | validasi → Model mencatat & menyimpan → redirect + pesan |
| `POST ?page=reset` | `reset()` | kembalikan data awal |
| lainnya / kode salah | `notFound()` | halaman 404 |

## Tanggung jawab
- **Model** (`TokoMadura`, `Barang`, `Transaksi`): menyimpan & mengolah data, menghitung stok, menolak barang keluar melebihi stok, membaca/menulis `data/toko.json`. Tidak ada HTML.
- **Controller** (`TokoController`): membaca `$_GET`/`$_POST`, validasi input (jenis, jumlah bilangan bulat > 0, harga, panjang keterangan, token CSRF), memanggil Model, memilih View, redirect setelah POST (pola Post/Redirect/Get) dengan flash message.
- **View**: hanya menampilkan data yang dikirim Controller; semua output di-escape dengan `e()`.

## Pemetaan task
| Task | Yang dikerjakan |
|---|---|
| Task 01 — Design MVC Structure | Folder `Models/`, `Views/`, `Controllers/` seperti di atas |
| Task 02 — Build the Controller | `TokoController` dengan `index()`, `show()`, `process()`; menerima request, memanggil Model, mengirim data ke View lewat `render()` |
| Task 03 — Connect Model, Controller & View | Alur: `index.php` → `TokoController` → `TokoMadura::muat()` → data → `render("toko-page", [...])` → HTML |

## Checklist Sprint 06
- [x] MVC concept &nbsp; - [x] Model &nbsp; - [x] View &nbsp; - [x] Controller &nbsp; - [x] Request flow
- [x] Model–Controller connection &nbsp; - [x] Controller–View connection
- [x] Test: keluar 2 tabung LPG saat stok 0 ditolak; masuk 10 tabung → BM-010, stok 10; keluar 3 dari halaman detail → BK-010, stok 7; data tetap ada setelah reload; kode salah → 404; POST tanpa token ditolak; mobile tanpa overflow; reset kembali ke data awal
- [x] Commit & push

## Jawaban pertanyaan demo
1. **Apa fungsi Controller?** Pengatur alur: menerima request, validasi input, memanggil Model, memilih View yang ditampilkan.
2. **Apa data yang dikelola Model?** Profil toko, daftar barang, transaksi masuk/keluar, stok & statusnya, serta penyimpanan ke JSON.
3. **Apa yang ditampilkan oleh View?** Dashboard (ringkasan, perlu kulakan, tabel stok, riwayat, form) dan halaman detail barang.
4. **Bagaimana Controller berkomunikasi dengan Model?** Lewat method object: `TokoMadura::muat()`, `cariBarang()`, `getPerluKulakan()`, `catatMasuk()`, `catatKeluar()`, `simpan()`.
5. **Bagaimana data sampai ke View?** Controller memanggil `render("nama-view", $data)`; array `$data` di-`extract` jadi variabel, lalu file View di-`require` di antara header & footer.

## Review — apa yang lebih mudah dikelola setelah MVC?
Menambah halaman baru (detail barang) cukup menambah satu method Controller + satu View tanpa menyentuh logika stok. Mengganti penyimpanan JSON ke MySQL nanti cukup mengubah Model.
