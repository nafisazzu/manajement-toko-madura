<?php
require_once 'Barang.php';
require_once 'Transaksi.php';

// Class TokoMadura: mengelola barang & riwayat, disimpan ke data/toko.json
class Tokomadura
{
    private string $nama;
    private string $berkas;
    /** @var Barang[] */
    private array $barang = [];
    /** @var Transaksi[] */
    private array $riwayat = [];

    public function __construct(string $nama, string $berkas = __DIR__ . '/data/toko.json')
    {
        $this->nama = $nama;
        $this->berkas = $berkas;
        $this->muat();
    }

    public function getNama(): string { return $this->nama; }

    // ---------- Fitur utama ----------

    public function barangMasuk(string $nama, int $jumlah, string $satuan, string $catatan = ''): void
    {
        $nama = trim($nama);
        if ($nama === '') {
            throw new InvalidArgumentException('Nama barang wajib diisi.');
        }
        $kunci = $this->kunci($nama);
        if (!isset($this->barang[$kunci])) {
            $this->barang[$kunci] = new Barang($nama, $satuan);
        }
        $b = $this->barang[$kunci];
        $b->tambahStok($jumlah);
        $this->riwayat[] = new Transaksi(Transaksi::MASUK, $b->getNama(), $jumlah, $b->getSatuan(), $catatan);
        $this->simpan();
    }

    public function barangKeluar(string $nama, int $jumlah, string $catatan = ''): void
    {
        $kunci = $this->kunci($nama);
        if (!isset($this->barang[$kunci])) {
            throw new InvalidArgumentException('Barang tidak ditemukan.');
        }
        $b = $this->barang[$kunci];
        $b->kurangiStok($jumlah);
        $this->riwayat[] = new Transaksi(Transaksi::KELUAR, $b->getNama(), $jumlah, $b->getSatuan(), $catatan);
        $this->simpan();
    }

    // ---------- Data untuk halaman ----------

    public function getDaftarBarang(): array
    {
        $daftar = array_values($this->barang);
        usort($daftar, fn(Barang $a, Barang $b) => strcasecmp($a->getNama(), $b->getNama()));
        return $daftar;
    }

    public function getRiwayat(): array
    {
        return array_reverse($this->riwayat); // terbaru di atas
    }

    public function getTotalJenisBarang(): int { return count($this->barang); }

    public function getTotalMasuk(): int  { return $this->hitung(Transaksi::MASUK); }
    public function getTotalKeluar(): int { return $this->hitung(Transaksi::KELUAR); }

    public function getJumlahMenipis(): int
    {
        return count(array_filter($this->barang, fn(Barang $b) => $b->getStok() <= 5));
    }

    // ---------- Internal ----------

    private function hitung(string $jenis): int
    {
        $total = 0;
        foreach ($this->riwayat as $t) {
            if ($t->getJenis() === $jenis) $total += $t->getJumlah();
        }
        return $total;
    }

    private function kunci(string $nama): string
    {
        return strtolower(trim($nama));
    }

    private function muat(): void
    {
        if (!file_exists($this->berkas)) {
            $this->isiContoh();
            return;
        }
        $data = json_decode((string) file_get_contents($this->berkas), true) ?: [];
        foreach ($data['barang'] ?? [] as $d) {
            $b = Barang::fromArray($d);
            $this->barang[$this->kunci($b->getNama())] = $b;
        }
        foreach ($data['riwayat'] ?? [] as $d) {
            $this->riwayat[] = Transaksi::fromArray($d);
        }
    }

    private function simpan(): void
    {
        $data = [
            'barang' => array_map(fn(Barang $b) => $b->toArray(), array_values($this->barang)),
            'riwayat' => array_map(fn(Transaksi $t) => $t->toArray(), $this->riwayat),
        ];
        $dir = dirname($this->berkas);
        if (!is_dir($dir)) mkdir($dir, 0775, true);
        file_put_contents($this->berkas, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }

    // Data contoh saat pertama kali dijalankan (hapus data/toko.json untuk mengulang)
    private function isiContoh(): void
    {
        $this->barangMasuk('Beras 5 kg', 20, 'karung', 'Stok awal');
        $this->barangMasuk('Indomie Goreng', 100, 'pcs', 'Stok awal');
        $this->barangMasuk('Minyak Goreng 1 L', 12, 'botol', 'Stok awal');
        $this->barangKeluar('Indomie Goreng', 35, 'Terjual hari ini');
        $this->barangKeluar('Minyak Goreng 1 L', 8, 'Terjual hari ini');
    }
}