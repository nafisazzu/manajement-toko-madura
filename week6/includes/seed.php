<?php

require_once __DIR__ . "/../classes/TokoMadura.php";

/**
 * Membuat object TokoMadura beserta data awalnya.
 * Semua data masuk lewat method object, bukan diisi langsung ke property.
 */
function buatTokoMadura(): TokoMadura
{
    $toko = new TokoMadura("Toko Madura", "Pak Rofiq", "Jl. Raya Tlogomas, Malang", 5);

    // daftar barang
    $toko->tambahBarang(new Barang("BRG-001", "Beras Premium 5 kg", "Sembako", "karung", 78000));
    $toko->tambahBarang(new Barang("BRG-002", "Minyak Goreng 1 L", "Sembako", "botol", 18000));
    $toko->tambahBarang(new Barang("BRG-003", "Gula Pasir 1 kg", "Sembako", "bungkus", 17500));
    $toko->tambahBarang(new Barang("BRG-004", "Telur Ayam", "Sembako", "kg", 29000));
    $toko->tambahBarang(new Barang("BRG-005", "Mi Instan Goreng", "Makanan", "bungkus", 3500));
    $toko->tambahBarang(new Barang("BRG-006", "Kopi Sachet", "Minuman", "renceng", 15000));
    $toko->tambahBarang(new Barang("BRG-007", "Air Mineral 600 ml", "Minuman", "botol", 4000));
    $toko->tambahBarang(new Barang("BRG-008", "Sabun Mandi", "Kebutuhan Rumah", "batang", 4500));
    $toko->tambahBarang(new Barang("BRG-009", "Gas LPG 3 kg", "Kebutuhan Rumah", "tabung", 22000));

    // transaksi berurutan sesuai tanggal (masuk dulu, baru keluar)
    $toko->catatMasuk("BRG-001", 10, "2026-10-26", "CV Sumber Pangan", 72000);
    $toko->catatMasuk("BRG-002", 24, "2026-10-26", "CV Sumber Pangan", 16000);
    $toko->catatMasuk("BRG-003", 20, "2026-10-27", "Grosir Barokah", 15500);
    $toko->catatMasuk("BRG-005", 80, "2026-10-27", "Agen Mi Sejahtera", 2800);
    $toko->catatKeluar("BRG-001", 4, "2026-10-27", "Penjualan");
    $toko->catatKeluar("BRG-005", 35, "2026-10-27", "Penjualan");
    $toko->catatMasuk("BRG-006", 15, "2026-10-28", "Grosir Barokah", 12500);
    $toko->catatMasuk("BRG-007", 48, "2026-10-28", "Agen Air Segar", 2800);
    $toko->catatKeluar("BRG-002", 20, "2026-10-28", "Penjualan");
    $toko->catatKeluar("BRG-007", 30, "2026-10-28", "Penjualan");
    $toko->catatMasuk("BRG-009", 12, "2026-10-29", "Pangkalan LPG Pak Halim", 18000);
    $toko->catatKeluar("BRG-009", 12, "2026-10-29", "Penjualan");
    $toko->catatKeluar("BRG-003", 6, "2026-10-29", "Penjualan");
    $toko->catatMasuk("BRG-004", 8, "2026-10-30", "Peternak Telur Lestari", 26000);
    $toko->catatMasuk("BRG-008", 30, "2026-10-30", "Grosir Barokah", 3500);
    $toko->catatKeluar("BRG-006", 11, "2026-10-30", "Penjualan");
    $toko->catatKeluar("BRG-004", 5, "2026-10-31", "Penjualan");
    $toko->catatKeluar("BRG-008", 2, "2026-10-31", "Barang rusak");

    return $toko;
}
