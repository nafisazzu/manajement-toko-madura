<?php

require_once __DIR__ . "/../Models/TokoMadura.php";

/**
 * CONTROLLER — menerima request, meminta/memproses data lewat Model,
 * lalu meneruskan data ke View. Controller tidak menulis HTML.
 */
class TokoController
{
    private TokoMadura $toko;
    private string $fileData;
    private string $folderView;

    public function __construct(string $fileData, string $folderView)
    {
        $this->fileData = $fileData;
        $this->folderView = $folderView;
        $this->toko = TokoMadura::muat($fileData); // Controller -> Model
    }

    // GET index.php?page=home  -> halaman utama (dashboard)
    public function index(): void
    {
        $kataKunci = trim($_GET["q"] ?? "");
        $tabAktif = ($_GET["tab"] ?? "masuk") === "keluar" ? "keluar" : "masuk";

        $this->render("toko-page", [
            "judul" => "Dashboard",
            "toko" => $this->toko,
            "kataKunci" => $kataKunci,
            "tabAktif" => $tabAktif,
            "daftarStok" => $this->toko->cariBarang($kataKunci),
            "perluKulakan" => $this->toko->getPerluKulakan(),
            "riwayat" => $tabAktif === "masuk" ? $this->toko->getBarangMasuk() : $this->toko->getBarangKeluar(),
        ]);
    }

    // GET index.php?page=barang&kode=BRG-001 -> detail satu barang
    public function show(string $kode): void
    {
        try {
            $barang = $this->toko->getBarang($kode);
        } catch (InvalidArgumentException $e) {
            $this->notFound("Barang dengan kode \"$kode\" tidak ditemukan.");
            return;
        }

        $this->render("barang-detail", [
            "judul" => $barang->getNama(),
            "toko" => $this->toko,
            "barang" => $barang,
            "stok" => $this->toko->getStok($kode),
            "status" => $this->toko->getStatusStok($kode),
            "riwayat" => $this->toko->getRiwayatBarang($kode),
        ]);
    }

    // POST index.php?page=transaksi -> catat barang masuk / keluar
    public function process(): void
    {
        $kembaliKe = $_POST["kembali"] ?? "home";
        $kodeBarang = (string) ($_POST["kode"] ?? "");
        $urlKembali = $kembaliKe === "barang" ? url("barang", ["kode" => $kodeBarang]) : url("home") . "#catat";

        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !csrfValid($_POST["csrf"] ?? null)) {
            $this->flash("error", "Permintaan tidak valid. Muat ulang halaman lalu coba lagi.");
            $this->redirect($urlKembali);
        }

        // ambil & validasi input
        $jenis = $_POST["jenis"] ?? "";
        $jumlah = filter_var($_POST["jumlah"] ?? "", FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        $keterangan = trim((string) ($_POST["keterangan"] ?? ""));
        $hargaBeli = filter_var($_POST["harga_beli"] ?? 0, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]]);

        $error = null;
        if (!in_array($jenis, [Transaksi::MASUK, Transaksi::KELUAR], true)) {
            $error = "Pilih jenis transaksi.";
        } elseif ($kodeBarang === "") {
            $error = "Pilih barang terlebih dahulu.";
        } elseif ($jumlah === false) {
            $error = "Jumlah harus bilangan bulat lebih dari 0.";
        } elseif ($jenis === Transaksi::MASUK && $hargaBeli === false) {
            $error = "Harga beli tidak valid.";
        } elseif (mb_strlen($keterangan) > 60) {
            $error = "Keterangan maksimal 60 karakter.";
        }

        if ($error !== null) {
            $this->flash("error", $error, $_POST);
            $this->redirect($urlKembali);
        }

        // minta Model memproses data (aturan stok ada di Model)
        try {
            $tanggal = date("Y-m-d");
            if ($jenis === Transaksi::MASUK) {
                $t = $this->toko->catatMasuk($kodeBarang, $jumlah, $tanggal, $keterangan !== "" ? $keterangan : "-", (int) $hargaBeli);
            } else {
                $t = $this->toko->catatKeluar($kodeBarang, $jumlah, $tanggal, $keterangan !== "" ? $keterangan : "Penjualan");
            }
            $this->toko->simpan($this->fileData);
        } catch (InvalidArgumentException | RuntimeException $e) {
            $this->flash("error", $e->getMessage(), $_POST);
            $this->redirect($urlKembali);
        }

        $barang = $t->getBarang();
        $this->flash("sukses", sprintf(
            "%s dicatat: %s %s. Stok sekarang %d %s.",
            $t->getId(), $t->getJumlahFormat(), $barang->getNama(), $this->toko->getStok($barang->getKode()), $barang->getSatuan()
        ));
        $this->redirect($urlKembali);
    }

    // POST index.php?page=reset -> kembalikan data ke kondisi awal (untuk demo)
    public function reset(string $fileSeed): void
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && csrfValid($_POST["csrf"] ?? null)) {
            copy($fileSeed, $this->fileData);
            $this->flash("sukses", "Data dikembalikan ke kondisi awal.");
        }
        $this->redirect(url("home"));
    }

    public function notFound(string $pesan = "Halaman tidak ditemukan."): void
    {
        http_response_code(404);
        $this->render("404", ["judul" => "Tidak ditemukan", "toko" => $this->toko, "pesan" => $pesan]);
    }

    // ---------- bantuan Controller ----------

    // Controller -> View: kirim data, View yang menyusun HTML
    private function render(string $view, array $data): void
    {
        $data["flash"] = $_SESSION["flash"] ?? null;
        unset($_SESSION["flash"]);

        extract($data);
        require $this->folderView . "/layouts/header.php";
        require $this->folderView . "/$view.php";
        require $this->folderView . "/layouts/footer.php";
    }

    private function flash(string $jenis, string $pesan, array $inputLama = []): void
    {
        $_SESSION["flash"] = ["jenis" => $jenis, "pesan" => $pesan, "input" => $inputLama];
    }

    private function redirect(string $url): never
    {
        header("Location: $url");
        exit;
    }
}
