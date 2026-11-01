# Toko Madura Web — Praktikum Pemrograman Web (Week 2–7)

Pengerjaan semua task modul Pertemuan 2–7. Contoh **PresidenKu** di slide diganti tema **Toko Madura**: pencatatan **barang masuk** dan **barang keluar** (stok = masuk − keluar).

| Folder | Pertemuan | Materi | Jalankan |
|---|---|---|---|
| `week2/` | 2 | HTML semantic + CSS responsive | buka `index.html` |
| `week3/` | 3 | JavaScript, DOM, Event, Tailwind CSS | buka `index.html` |
| `week4/` | 4 | Fetch API, JSON, async/await, loading & error | `php -S localhost:8000 -t week4` (atau Live Server) |
| `week5/` | 5 | PHP prosedural | `php -S localhost:8000 -t week5` |
| `week6/` | 6 | PHP OOP | `php -S localhost:8000 -t week6` |
| `week7/` | 7 | MVC | `php -S localhost:8000 -t week7` |

Setiap folder punya `README.md` berisi pemetaan task, checklist, dan jawaban pertanyaan demo/review.

## Data contoh (sama di semua week)
9 barang (beras, minyak goreng, gula, telur, mi instan, kopi sachet, air mineral, sabun, gas LPG 3 kg), 9 transaksi masuk (total 247), 9 transaksi keluar (total 125). Stok menipis (≤ 5): minyak goreng, telur, kopi sachet; habis: gas LPG.

## Tailwind CSS (Week 3–7)
File CSS hasil build sudah disertakan, jadi tidak wajib build. Kalau mengubah class:
```bash
npm install
npm run build          # semua week
npm run build:week7    # satu week saja
```

## Kebutuhan
- Browser modern
- PHP 8.1+ untuk Week 4–7
- Node.js (hanya untuk build ulang Tailwind)

## Repository
https://github.com/nafisazzu/manajement-toko-madura