# Sprint 01 — Build the Toko Madura Web Interface (Pertemuan 2)

HTML semantic + CSS manual. Tema PresidenKu dari slide diganti **Toko Madura** (barang masuk & barang keluar).

## Cara menjalankan
Buka `index.html` langsung di browser (double-click) atau pakai Live Server.

## Pemetaan task
| Task | Yang dikerjakan | Lokasi |
|---|---|---|
| Task 01 — HTML Structure | `<header>` (logo + `<nav>`), `<main>` berisi 5 `<section>` (beranda, stok, barang masuk, barang keluar, tentang), `<footer>`. Tag semantic: `<aside>`, `<article>`, `<dl>`, `<table>` + `<caption>` + `<th scope>`, `<time datetime>` | `index.html` |
| Task 02 — Style & Responsive | Variabel CSS (warna, radius, shadow), layout grid/flex, breakpoint 900px & 640px, `overflow-x: hidden` | `css/style.css` |
| Task 03 — Refine | Tabel berubah jadi kartu di mobile (`td::before { content: attr(data-label) }`) sehingga tidak ada horizontal overflow; badge status stok; hover tombol | `css/style.css` bagian RESPONSIVE |

## Checklist Sprint 01
- [x] HTML structure &nbsp; - [x] Semantic HTML &nbsp; - [x] CSS styling &nbsp; - [x] Responsive layout
- [x] Test (desktop 1280px & mobile 390px, scroll horizontal = 0) &nbsp; - [x] Commit & push

## Jawaban pertanyaan demo
1. **Apa yang dibuat?** Halaman Toko Madura untuk melihat stok, riwayat barang masuk (kulakan dari supplier), dan barang keluar (terjual/rusak).
2. **Bagian HTML mana yang dibuat?** Header + navigasi, hero dengan ringkasan (`<aside>` + `<dl>`), tiga tabel data, section tentang, footer.
3. **Bagaimana CSS mengatur tampilannya?** Warna dan ukuran disimpan di variabel `:root`, hero dan ringkasan memakai CSS Grid 2 kolom, navigasi memakai Flexbox, badge status punya warna berbeda (aman/menipis/habis).
4. **Bagaimana menyesuaikan mobile?** Di ≤900px grid jadi 1 kolom; di ≤640px header tabel disembunyikan dan setiap baris jadi kartu dengan label dari atribut `data-label`, menu bisa digeser horizontal, tombol jadi lebar penuh.

## Review — bagian yang diperbaiki di sprint berikutnya
Data tabel masih ditulis manual di HTML dan belum ada interaksi. Di Sprint 02 data dipindah ke JavaScript, ditambah pencarian, tab, menu mobile, dan form catat transaksi.
