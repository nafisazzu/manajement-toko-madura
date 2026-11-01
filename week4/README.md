# Sprint 03 — Connect Toko Madura to an API (Pertemuan 4)

Fetch API + JSON + Promise / async-await + loading state + error handling.

## Cara menjalankan
`fetch()` **tidak bisa** membaca file JSON lewat `file://`, jadi jalankan lewat local server:
- VS Code **Live Server**, atau
- `npx serve sprint-03-api`, atau
- `php -S localhost:8000 -t sprint-03-api` lalu buka http://localhost:8000

Untuk demo:
- `index.html?lambat=1` → loading terlihat 1,5 detik
- `index.html?error=1` → memanggil endpoint yang salah (HTTP 404) supaya pesan error + tombol **Coba lagi** terlihat

## Endpoint API (REST, GET, JSON)
| Endpoint | Isi |
|---|---|
| `api/barang.json` | 9 barang (kode, nama, kategori, satuan, hargaJual) |
| `api/barang-masuk.json` | riwayat barang masuk (tanggal, kode, jumlah, supplier, hargaBeli) |
| `api/barang-keluar.json` | riwayat barang keluar (tanggal, kode, jumlah, keterangan) |

Format response: `{ "status": "success", "total": 9, "data": [ ... ] }`

## Pemetaan task
| Task | Yang dikerjakan | Lokasi di `js/app.js` |
|---|---|---|
| Task 01 — Connect to API | `ambilData(endpoint)`: `await fetch()` → cek `response.ok` → `await response.json()` → cek format; tiga request dijalankan bersamaan dengan `Promise.all` | bagian 7 & 9 |
| Task 02 — Display API Data | data API (array of objects) dirender ke tabel stok, riwayat, ringkasan, dan pilihan barang di form memakai `map`/`forEach` + template literal | `renderStok()`, `renderMasuk()`, `renderKeluar()` |
| Task 03 — Handle API State | **Loading**: status kuning berkedip, skeleton di tabel, tombol simpan dinonaktifkan. **Sukses**: status hijau + jumlah data + jam. **Error**: status merah, pesan error, tombol "Coba lagi" | bagian 8, `try/catch` di `muatData()` |

Catatan: API ini hanya menyediakan data (GET). Transaksi baru dari form disimpan sementara di browser; penyimpanan permanen dibuat di Sprint 06 (PHP MVC).

## Checklist Sprint 03
- [x] API request &nbsp; - [x] JSON &nbsp; - [x] JavaScript processing &nbsp; - [x] Dynamic data
- [x] Loading &nbsp; - [x] Error handling &nbsp; - [x] Test (normal, `?lambat=1`, `?error=1` → Coba lagi berhasil) &nbsp; - [x] Commit & push

## Jawaban pertanyaan demo
1. **API apa yang digunakan?** REST API JSON milik Toko Madura di folder `api/` (tiga endpoint di atas).
2. **Data apa yang diambil?** Daftar barang, riwayat barang masuk, riwayat barang keluar.
3. **Bagaimana data diproses?** JSON diubah jadi object JavaScript dengan `response.json()`, disimpan ke variabel state, stok dihitung (masuk − keluar), lalu dirender ke DOM.
4. **Apa yang terjadi ketika request gagal?** `response.ok` bernilai false → `throw new Error(...)` → ditangkap `catch` → ringkasan jadi "-", tabel dikosongkan, pesan error dan tombol "Coba lagi" muncul. Halaman tidak rusak.
