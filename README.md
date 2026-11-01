# Toko Madura Web — Praktikum Pemrograman Web (Sprint 01–06)

Pengerjaan semua task modul Pertemuan 2–7. Contoh **PresidenKu** di slide diganti tema **Toko Madura**: pencatatan **barang masuk** dan **barang keluar** (stok = masuk − keluar).

| Folder | Pertemuan | Materi | Jalankan |
|---|---|---|---|
| `sprint-01-html-css/` | 2 | HTML semantic + CSS responsive | buka `index.html` |
| `sprint-02-javascript/` | 3 | JavaScript, DOM, Event, Tailwind CSS | buka `index.html` |
| `sprint-03-api/` | 4 | Fetch API, JSON, async/await, loading & error | `php -S localhost:8000 -t sprint-03-api` (atau Live Server) |
| `sprint-04-php/` | 5 | PHP prosedural | `php -S localhost:8000 -t sprint-04-php` |
| `sprint-05-php-oop/` | 6 | PHP OOP | `php -S localhost:8000 -t sprint-05-php-oop` |
| `sprint-06-mvc/` | 7 | MVC | `php -S localhost:8000 -t sprint-06-mvc` |

Setiap folder punya `README.md` berisi pemetaan task, checklist, dan jawaban pertanyaan demo/review.

## Data contoh (sama di semua sprint)
9 barang (beras, minyak goreng, gula, telur, mi instan, kopi sachet, air mineral, sabun, gas LPG 3 kg), 9 transaksi masuk (total 247), 9 transaksi keluar (total 125). Stok menipis (≤ 5): minyak goreng, telur, kopi sachet; habis: gas LPG.

## Tailwind CSS (Sprint 02–06)
File CSS hasil build sudah disertakan, jadi tidak wajib build. Kalau mengubah class:
```bash
npm install
npm run build        # semua sprint
npm run build:s6     # satu sprint saja
```

## Kebutuhan
- Browser modern
- PHP 8.1+ (dites dengan PHP 8.3) untuk Sprint 03–06
- Node.js (hanya untuk build ulang Tailwind)

## Push ke GitHub
Repo ini sudah berisi commit per sprint. Untuk push:
```bash
git remote add origin https://github.com/nafisazzu/<nama-repo>.git
git branch -M main
git push -u origin main
```
