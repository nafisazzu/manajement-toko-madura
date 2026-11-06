<?php

/**
 * Class Barang — blueprint untuk setiap barang yang dijual Toko Madura.
 * Property dibuat private (encapsulation), dibaca lewat getter.
 */
class Barang
{
    private string $kode;
    private string $nama;
    private string $kategori;
    private string $satuan;
    private int $hargaJual;

    public function __construct(string $kode, string $nama, string $kategori, string $satuan, int $hargaJual)
    {
        if ($hargaJual < 0) {
            throw new InvalidArgumentException("Harga jual tidak boleh negatif.");
        }
        $this->kode = $kode;
        $this->nama = $nama;
        $this->kategori = $kategori;
        $this->satuan = $satuan;
        $this->hargaJual = $hargaJual;
    }

    public function getKode(): string { return $this->kode; }
    public function getNama(): string { return $this->nama; }
    public function getKategori(): string { return $this->kategori; }
    public function getSatuan(): string { return $this->satuan; }
    public function getHargaJual(): int { return $this->hargaJual; }

    // setter dengan validasi: harga hanya bisa diubah lewat method ini
    public function setHargaJual(int $hargaBaru): void
    {
        if ($hargaBaru < 0) {
            throw new InvalidArgumentException("Harga jual tidak boleh negatif.");
        }
        $this->hargaJual = $hargaBaru;
    }

    public function getHargaFormat(): string
    {
        return "Rp" . number_format($this->hargaJual, 0, ',', '.');
    }
}
