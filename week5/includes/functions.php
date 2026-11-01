<?php
// =========================================================
// Function bantu Toko Madura (PHP prosedural)
// =========================================================

// mencegah teks dibaca sebagai HTML (keamanan output)
function e(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

function formatRupiah(int $angka): string
{
    return "Rp" . number_format($angka, 0, ',', '.');
}

function formatTanggal(string $tanggal): string
{
    $bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
    [$tahun, $bln, $hari] = explode("-", $tanggal);
    return (int) $hari . " " . $bulan[(int) $bln - 1] . " " . $tahun;
}

// total jumlah barang dari sebuah daftar transaksi (boleh difilter per kode)
function totalJumlah(array $transaksi, ?string $kode = null): int
{
    $total = 0;
    foreach ($transaksi as $t) {
        if ($kode === null || $t["kode"] === $kode) {
            $total += $t["jumlah"];
        }
    }
    return $total;
}

// stok = total masuk - total keluar
function hitungStok(string $kode, array $barangMasuk, array $barangKeluar): int
{
    return totalJumlah($barangMasuk, $kode) - totalJumlah($barangKeluar, $kode);
}

// conditional: menentukan status stok
function statusStok(int $stok, int $batasMenipis): array
{
    if ($stok <= 0) {
        return ["label" => "Habis", "kelas" => "badge-habis"];
    } elseif ($stok <= $batasMenipis) {
        return ["label" => "Menipis", "kelas" => "badge-menipis"];
    } else {
        return ["label" => "Aman", "kelas" => "badge-aman"];
    }
}

// sapaan berdasarkan jam server
function sapaan(int $jam): string
{
    if ($jam >= 4 && $jam < 11) {
        return "Selamat pagi";
    } elseif ($jam >= 11 && $jam < 15) {
        return "Selamat siang";
    } elseif ($jam >= 15 && $jam < 18) {
        return "Selamat sore";
    }
    return "Selamat malam";
}
