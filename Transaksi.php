<?php
// Class Transaksi: satu catatan barang masuk atau barang keluar
class Transaksi
{
    public const MASUK = 'masuk';
    public const KELUAR = 'keluar';

    private string $jenis;
    private string $namaBarang;
    private int $jumlah;
    private string $satuan;
    private string $catatan;
    private string $waktu;

    public function __construct(
        string $jenis,
        string $namaBarang,
        int $jumlah,
        string $satuan,
        string $catatan = '',
        ?string $waktu = null
    ) {
        if (!in_array($jenis, [self::MASUK, self::KELUAR], true)) {
            throw new InvalidArgumentException('Jenis transaksi tidak valid.');
        }
        $this->jenis = $jenis;
        $this->namaBarang = $namaBarang;
        $this->jumlah = $jumlah;
        $this->satuan = $satuan;
        $this->catatan = $catatan;
        $this->waktu = $waktu ?? date('Y-m-d H:i');
    }

    public function getJenis(): string      { return $this->jenis; }
    public function getNamaBarang(): string { return $this->namaBarang; }
    public function getJumlah(): int        { return $this->jumlah; }
    public function getSatuan(): string     { return $this->satuan; }
    public function getCatatan(): string    { return $this->catatan; }
    public function getWaktu(): string      { return $this->waktu; }

    public function getLabel(): string
    {
        return $this->jenis === self::MASUK ? 'Barang Masuk' : 'Barang Keluar';
    }

    public function getJumlahBertanda(): string
    {
        $tanda = $this->jenis === self::MASUK ? '+' : '-';
        return $tanda . $this->jumlah . ' ' . $this->satuan;
    }

    public function toArray(): array
    {
        return [
            'jenis' => $this->jenis, 'nama' => $this->namaBarang, 'jumlah' => $this->jumlah,
            'satuan' => $this->satuan, 'catatan' => $this->catatan, 'waktu' => $this->waktu,
        ];
    }

    public static function fromArray(array $d): self
    {
        return new self($d['jenis'], $d['nama'], (int) $d['jumlah'], $d['satuan'], $d['catatan'] ?? '', $d['waktu']);
    }
}