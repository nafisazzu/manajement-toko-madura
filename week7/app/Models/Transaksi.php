<?php

/**
 * Class Transaksi — satu catatan barang masuk atau barang keluar.
 */
class Transaksi
{
    public const MASUK = "masuk";
    public const KELUAR = "keluar";

    private string $id;
    private string $tanggal;
    private string $jenis;
    private Barang $barang;
    private int $jumlah;
    private string $keterangan; // supplier (masuk) atau alasan keluar
    private int $harga;         // harga beli per satuan (masuk) / harga jual (keluar)

    public function __construct(string $id, string $tanggal, string $jenis, Barang $barang, int $jumlah, string $keterangan, int $harga = 0)
    {
        if (!in_array($jenis, [self::MASUK, self::KELUAR], true)) {
            throw new InvalidArgumentException("Jenis transaksi harus 'masuk' atau 'keluar'.");
        }
        if ($jumlah <= 0) {
            throw new InvalidArgumentException("Jumlah harus lebih dari 0.");
        }
        $this->id = $id;
        $this->tanggal = $tanggal;
        $this->jenis = $jenis;
        $this->barang = $barang;
        $this->jumlah = $jumlah;
        $this->keterangan = $keterangan;
        $this->harga = $harga;
    }

    public function getId(): string { return $this->id; }
    public function getTanggal(): string { return $this->tanggal; }
    public function getJenis(): string { return $this->jenis; }
    public function getBarang(): Barang { return $this->barang; }
    public function getJumlah(): int { return $this->jumlah; }
    public function getKeterangan(): string { return $this->keterangan; }
    public function getHarga(): int { return $this->harga; }

    public function isMasuk(): bool
    {
        return $this->jenis === self::MASUK;
    }

    public function getNilai(): int
    {
        return $this->jumlah * $this->harga;
    }

    public function getTanggalFormat(): string
    {
        $bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
        [$tahun, $bln, $hari] = explode("-", $this->tanggal);
        return (int) $hari . " " . $bulan[(int) $bln - 1] . " " . $tahun;
    }

    // contoh: "+10 karung" atau "−4 karung"
    public function getJumlahFormat(): string
    {
        return ($this->isMasuk() ? "+" : "−") . $this->jumlah . " " . $this->barang->getSatuan();
    }
}
