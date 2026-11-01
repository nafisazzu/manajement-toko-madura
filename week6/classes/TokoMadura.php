<?php

require_once __DIR__ . "/Barang.php";
require_once __DIR__ . "/Transaksi.php";

/**
 * Class TokoMadura — object utama yang mengelola barang dan transaksi.
 * Data hanya bisa diubah lewat method (catatMasuk / catatKeluar),
 * sehingga aturan "stok tidak boleh minus" selalu terjaga.
 */
class TokoMadura
{
    private string $nama;
    private string $pemilik;
    private string $alamat;
    private int $batasMenipis;

    /** @var Barang[] kode => Barang */
    private array $daftarBarang = [];

    /** @var Transaksi[] */
    private array $daftarTransaksi = [];

    public function __construct(string $nama, string $pemilik, string $alamat, int $batasMenipis = 5)
    {
        $this->nama = $nama;
        $this->pemilik = $pemilik;
        $this->alamat = $alamat;
        $this->batasMenipis = $batasMenipis;
    }

    // ---------- profil ----------
    public function getNama(): string { return $this->nama; }
    public function getPemilik(): string { return $this->pemilik; }
    public function getAlamat(): string { return $this->alamat; }
    public function getBatasMenipis(): int { return $this->batasMenipis; }

    public function getProfile(): string
    {
        return "{$this->nama} — milik {$this->pemilik}, {$this->alamat}";
    }

    // ---------- barang ----------
    public function tambahBarang(Barang $barang): void
    {
        $this->daftarBarang[$barang->getKode()] = $barang;
    }

    public function getBarang(string $kode): Barang
    {
        if (!isset($this->daftarBarang[$kode])) {
            throw new InvalidArgumentException("Barang dengan kode $kode tidak ditemukan.");
        }
        return $this->daftarBarang[$kode];
    }

    /** @return Barang[] */
    public function getDaftarBarang(): array
    {
        return array_values($this->daftarBarang);
    }

    // ---------- transaksi ----------
    public function catatMasuk(string $kode, int $jumlah, string $tanggal, string $supplier, int $hargaBeli): Transaksi
    {
        $id = $this->buatId("BM", Transaksi::MASUK);
        $transaksi = new Transaksi($id, $tanggal, Transaksi::MASUK, $this->getBarang($kode), $jumlah, $supplier, $hargaBeli);
        $this->daftarTransaksi[] = $transaksi;
        return $transaksi;
    }

    public function catatKeluar(string $kode, int $jumlah, string $tanggal, string $keterangan = "Penjualan"): Transaksi
    {
        $barang = $this->getBarang($kode);
        $stok = $this->getStok($kode);
        if ($jumlah > $stok) {
            throw new RuntimeException("Stok {$barang->getNama()} hanya $stok {$barang->getSatuan()}.");
        }
        $id = $this->buatId("BK", Transaksi::KELUAR);
        $transaksi = new Transaksi($id, $tanggal, Transaksi::KELUAR, $barang, $jumlah, $keterangan, $barang->getHargaJual());
        $this->daftarTransaksi[] = $transaksi;
        return $transaksi;
    }

    /** @return Transaksi[] terbaru di atas */
    public function getBarangMasuk(): array
    {
        return $this->filterTransaksi(Transaksi::MASUK);
    }

    /** @return Transaksi[] terbaru di atas */
    public function getBarangKeluar(): array
    {
        return $this->filterTransaksi(Transaksi::KELUAR);
    }

    // ---------- stok ----------
    public function getStok(string $kode): int
    {
        $stok = 0;
        foreach ($this->daftarTransaksi as $t) {
            if ($t->getBarang()->getKode() === $kode) {
                $stok += $t->isMasuk() ? $t->getJumlah() : -$t->getJumlah();
            }
        }
        return $stok;
    }

    /** @return array{label: string, kelas: string} */
    public function getStatusStok(string $kode): array
    {
        $stok = $this->getStok($kode);
        if ($stok <= 0) {
            return ["label" => "Habis", "kelas" => "badge-habis"];
        }
        if ($stok <= $this->batasMenipis) {
            return ["label" => "Menipis", "kelas" => "badge-menipis"];
        }
        return ["label" => "Aman", "kelas" => "badge-aman"];
    }

    /** @return Barang[] barang yang stoknya <= batas, paling sedikit di depan */
    public function getPerluKulakan(): array
    {
        $hasil = array_filter($this->daftarBarang, fn(Barang $b) => $this->getStok($b->getKode()) <= $this->batasMenipis);
        usort($hasil, fn(Barang $a, Barang $b) => $this->getStok($a->getKode()) <=> $this->getStok($b->getKode()));
        return $hasil;
    }

    /** @return Barang[] */
    public function cariBarang(string $kataKunci): array
    {
        $kataKunci = strtolower(trim($kataKunci));
        if ($kataKunci === "") {
            return $this->getDaftarBarang();
        }
        return array_values(array_filter(
            $this->daftarBarang,
            fn(Barang $b) => str_contains(strtolower("{$b->getKode()} {$b->getNama()} {$b->getKategori()}"), $kataKunci)
        ));
    }

    public function getTotalMasuk(): int
    {
        return array_sum(array_map(fn(Transaksi $t) => $t->getJumlah(), $this->getBarangMasuk()));
    }

    public function getTotalKeluar(): int
    {
        return array_sum(array_map(fn(Transaksi $t) => $t->getJumlah(), $this->getBarangKeluar()));
    }

    // ---------- method private (hanya dipakai di dalam class) ----------
    private function filterTransaksi(string $jenis): array
    {
        $hasil = array_filter($this->daftarTransaksi, fn(Transaksi $t) => $t->getJenis() === $jenis);
        return array_reverse(array_values($hasil));
    }

    private function buatId(string $prefix, string $jenis): string
    {
        $jumlah = count($this->filterTransaksi($jenis)) + 1;
        return $prefix . "-" . str_pad((string) $jumlah, 3, "0", STR_PAD_LEFT);
    }
}
