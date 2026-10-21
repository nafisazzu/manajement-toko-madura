<?php
// Class Barang: data + perilaku satu jenis barang di toko
class Barang
{
    private string $nama;
    private string $satuan;
    private int $stok;

    public function __construct(string $nama, string $satuan, int $stok = 0)
    {
        $this->nama = trim($nama);
        $this->satuan = $satuan;
        $this->stok = max(0, $stok);
    }

    public function getNama(): string   { return $this->nama; }
    public function getSatuan(): string { return $this->satuan; }
    public function getStok(): int      { return $this->stok; }

    // Barang masuk: stok bertambah
    public function tambahStok(int $jumlah): void
    {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException('Jumlah harus lebih dari 0.');
        }
        $this->stok += $jumlah;
    }

    // Barang keluar: stok berkurang, tidak boleh melebihi stok yang ada
    public function kurangiStok(int $jumlah): void
    {
        if ($jumlah <= 0) {
            throw new InvalidArgumentException('Jumlah harus lebih dari 0.');
        }
        if ($jumlah > $this->stok) {
            throw new InvalidArgumentException(
                "Stok {$this->nama} tidak cukup (tersisa {$this->stok} {$this->satuan})."
            );
        }
        $this->stok -= $jumlah;
    }

    public function getStatusStok(): string
    {
        if ($this->stok === 0) return 'Habis';
        if ($this->stok <= 5)  return 'Stok menipis';
        return 'Stok aman';
    }

    public function toArray(): array
    {
        return ['nama' => $this->nama, 'satuan' => $this->satuan, 'stok' => $this->stok];
    }

    public static function fromArray(array $d): self
    {
        return new self($d['nama'], $d['satuan'], (int) $d['stok']);
    }
}