<?php
// =========================================================
// Toko Madura — Sprint 05: PHP OOP
// CLASS (Barang, Transaksi, TokoMadura) -> OBJECT -> METHOD -> HTML
// =========================================================

date_default_timezone_set("Asia/Jakarta");

require __DIR__ . "/includes/seed.php";
require __DIR__ . "/includes/helpers.php";

// membuat object toko (berisi object Barang & Transaksi)
$toko = buatTokoMadura();

// input dari URL
$kataKunci = trim($_GET["q"] ?? "");
$tabAktif  = ($_GET["tab"] ?? "masuk") === "keluar" ? "keluar" : "masuk";

// data untuk halaman diambil lewat method object
$daftarStok   = $toko->cariBarang($kataKunci);
$perluKulakan = $toko->getPerluKulakan();
$riwayat      = $tabAktif === "masuk" ? $toko->getBarangMasuk() : $toko->getBarangKeluar();

$jam = (int) date("G");
$sapaan = match (true) {
    $jam >= 4 && $jam < 11  => "Selamat pagi",
    $jam >= 11 && $jam < 15 => "Selamat siang",
    $jam >= 15 && $jam < 18 => "Selamat sore",
    default                 => "Selamat malam",
};

$navItems = ["#beranda" => "Beranda", "#kulakan" => "Perlu Kulakan", "#stok" => "Stok", "#riwayat" => "Riwayat"];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($toko->getNama()) ?> — Catat Barang Masuk &amp; Keluar</title>
  <link rel="stylesheet" href="dist/output.css">
</head>
<body class="overflow-x-hidden bg-slate-50 font-sans leading-relaxed text-slate-900">

  <header class="sticky top-0 z-20 bg-emerald-950 text-white shadow-lg">
    <div class="mx-auto flex min-h-16 w-[min(1120px,100%-2.5rem)] flex-wrap items-center justify-between gap-2 py-2 md:py-0">
      <a href="#beranda" class="flex items-center gap-2.5">
        <span class="grid size-[38px] place-items-center rounded-[10px] bg-yellow-400 font-extrabold tracking-tight text-emerald-950" aria-hidden="true">TM</span>
        <span class="text-xl">Toko<strong class="text-yellow-400">Madura</strong></span>
      </a>
      <nav aria-label="Navigasi utama" class="w-full md:w-auto">
        <ul class="flex gap-1 overflow-x-auto">
          <?php foreach ($navItems as $link => $label): ?>
            <li><a href="<?= $link ?>" class="block rounded-lg px-3 py-2 font-medium whitespace-nowrap text-emerald-100 hover:bg-white/10 hover:text-white"><?= $label ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <!-- ===== HERO: profil toko dari variable PHP ===== -->
    <section id="beranda" class="bg-linear-to-br from-emerald-950 to-emerald-700 pt-10 pb-12 text-white md:pt-16 md:pb-[4.5rem]">
      <div class="mx-auto grid w-[min(1120px,100%-2.5rem)] items-center gap-10 lg:grid-cols-[1.3fr_1fr]">
        <div>
          <p class="mb-4 inline-block rounded-full bg-yellow-400/20 px-3 py-1 text-sm font-semibold text-yellow-400">
            <?= $sapaan ?>, <?= e($toko->getPemilik()) ?>! Toko buka 24 jam
          </p>
          <h1 class="text-[clamp(1.9rem,4vw,2.9rem)] leading-tight font-bold tracking-tight">Catat barang masuk &amp; keluar <?= e($toko->getNama()) ?> tanpa ribet</h1>
          <p class="mt-4 max-w-[52ch] text-lg text-emerald-100">
            Semua data di halaman ini berasal dari object <code class="rounded bg-white/10 px-1">TokoMadura</code> yang berisi object <code class="rounded bg-white/10 px-1">Barang</code> dan <code class="rounded bg-white/10 px-1">Transaksi</code>.
          </p>
          <dl class="mt-6 grid max-w-lg grid-cols-[auto_1fr] gap-x-6 gap-y-1 text-sm">
            <dt class="text-emerald-300">Profil</dt><dd><?= e($toko->getProfile()) ?></dd>
            <dt class="text-emerald-300">Batas menipis</dt><dd>&le; <?= $toko->getBatasMenipis() ?> satuan</dd>
            <dt class="text-emerald-300">Diperbarui</dt><dd><?= date("d/m/Y H:i") ?> WIB</dd>
          </dl>
        </div>

        <aside class="rounded-2xl bg-white p-6 text-slate-900 shadow-xl" aria-labelledby="judul-ringkasan">
          <h2 id="judul-ringkasan" class="mb-4 font-semibold text-slate-500">Ringkasan stok</h2>
          <dl class="grid grid-cols-2 gap-3">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
              <dt class="text-sm text-slate-500">Jenis barang</dt>
              <dd class="mt-1 text-3xl font-extrabold"><?= count($toko->getDaftarBarang()) ?></dd>
            </div>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
              <dt class="text-sm text-slate-500">Total masuk</dt>
              <dd class="mt-1 text-3xl font-extrabold text-emerald-700"><?= $toko->getTotalMasuk() ?></dd>
            </div>
            <div class="rounded-xl border border-red-200 bg-red-50 p-4">
              <dt class="text-sm text-slate-500">Total keluar</dt>
              <dd class="mt-1 text-3xl font-extrabold text-red-600"><?= $toko->getTotalKeluar() ?></dd>
            </div>
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
              <dt class="text-sm text-slate-500">Perlu kulakan</dt>
              <dd class="mt-1 text-3xl font-extrabold text-amber-600"><?= count($perluKulakan) ?></dd>
            </div>
          </dl>
        </aside>
      </div>
    </section>

    <!-- ===== PERLU KULAKAN: konten dinamis (Task 03) ===== -->
    <section id="kulakan" class="py-14">
      <div class="mx-auto w-[min(1120px,100%-2.5rem)]">
        <header class="mb-6">
          <h2 class="text-2xl font-bold">Perlu Kulakan</h2>
          <p class="mt-1 text-slate-600">Hasil method <code>$toko->getPerluKulakan()</code>: barang dengan stok &le; <?= $toko->getBatasMenipis() ?>.</p>
        </header>

        <?php if (count($perluKulakan) === 0): ?>
          <p class="rounded-xl bg-emerald-50 p-5 text-emerald-800">Semua stok aman. Belum perlu kulakan.</p>
        <?php else: ?>
          <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <?php foreach ($perluKulakan as $barang): ?>
              <?php $stok = $toko->getStok($barang->getKode()); $status = $toko->getStatusStok($barang->getKode()); ?>
              <article class="rounded-2xl border bg-white p-5 shadow-sm <?= $stok <= 0 ? "border-red-200" : "border-amber-200" ?>">
                <div class="flex items-start justify-between gap-2">
                  <h3 class="font-bold"><?= e($barang->getNama()) ?></h3>
                  <span class="badge <?= $status["kelas"] ?>"><?= $status["label"] ?></span>
                </div>
                <p class="mt-1 text-sm text-slate-500"><?= e($barang->getKategori()) ?> &middot; <?= $barang->getKode() ?></p>
                <p class="mt-4 text-3xl font-extrabold <?= $stok <= 0 ? "text-red-600" : "text-amber-600" ?>">
                  <?= $stok ?> <span class="text-base font-medium text-slate-500"><?= e($barang->getSatuan()) ?></span>
                </p>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- ===== STOK + PENCARIAN (diproses PHP) ===== -->
    <section id="stok" class="bg-white py-14">
      <div class="mx-auto w-[min(1120px,100%-2.5rem)]">
        <header class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
          <div>
            <h2 class="text-2xl font-bold">Stok Barang</h2>
            <p class="mt-1 text-slate-600">Stok = total barang masuk &minus; total barang keluar.</p>
          </div>
          <form action="#stok" method="get" class="flex gap-2 md:w-96">
            <input type="hidden" name="tab" value="<?= $tabAktif ?>">
            <label class="flex-1">
              <span class="sr-only">Cari barang</span>
              <input type="search" name="q" value="<?= e($kataKunci) ?>" placeholder="Cari nama, kode, kategori…" class="input">
            </label>
            <button class="rounded-lg bg-emerald-700 px-4 font-semibold text-white hover:bg-emerald-800">Cari</button>
          </form>
        </header>

        <?php if ($kataKunci !== ""): ?>
          <p class="mb-3 text-sm text-slate-600">
            Hasil pencarian "<strong><?= e($kataKunci) ?></strong>": <?= count($daftarStok) ?> barang.
            <a href="?tab=<?= $tabAktif ?>#stok" class="font-semibold text-emerald-700 underline">Hapus pencarian</a>
          </p>
        <?php endif; ?>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
          <table class="w-full border-collapse">
            <thead class="hidden bg-emerald-50 text-left text-xs tracking-wide text-emerald-950 uppercase md:table-header-group">
              <tr>
                <th class="px-4 py-3">Kode</th><th class="px-4 py-3">Nama Barang</th><th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Stok</th><th class="px-4 py-3">Harga Jual</th><th class="px-4 py-3">Status</th>
              </tr>
            </thead>
            <tbody class="block md:table-row-group">
              <?php foreach ($daftarStok as $barang): ?>
                <?php $status = $toko->getStatusStok($barang->getKode()); ?>
                <tr class="baris">
                  <td class="sel" data-label="Kode"><?= $barang->getKode() ?></td>
                  <td class="sel font-medium" data-label="Nama Barang"><?= e($barang->getNama()) ?></td>
                  <td class="sel" data-label="Kategori"><?= e($barang->getKategori()) ?></td>
                  <td class="sel tabular-nums" data-label="Stok"><?= $toko->getStok($barang->getKode()) ?> <?= e($barang->getSatuan()) ?></td>
                  <td class="sel tabular-nums" data-label="Harga Jual"><?= $barang->getHargaFormat() ?></td>
                  <td class="sel" data-label="Status"><span class="badge <?= $status["kelas"] ?>"><?= $status["label"] ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php if (count($daftarStok) === 0): ?>
            <p class="p-6 text-center text-slate-500">Barang tidak ditemukan.</p>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- ===== RIWAYAT: tab dipilih lewat ?tab= ===== -->
    <section id="riwayat" class="py-14">
      <div class="mx-auto w-[min(1120px,100%-2.5rem)]">
        <header class="mb-6">
          <h2 class="text-2xl font-bold">Riwayat Transaksi</h2>
          <p class="mt-1 text-slate-600">Tab dipilih lewat URL (<code>?tab=masuk</code> / <code>?tab=keluar</code>); datanya dari method <code>getBarangMasuk()</code> / <code>getBarangKeluar()</code>.</p>
        </header>

        <nav class="mb-4 inline-flex rounded-xl bg-slate-200/70 p-1" aria-label="Jenis transaksi">
          <?php foreach (["masuk" => "Barang Masuk", "keluar" => "Barang Keluar"] as $kunci => $label): ?>
            <?php $aktif = $tabAktif === $kunci; ?>
            <a href="?tab=<?= $kunci ?>&amp;q=<?= urlencode($kataKunci) ?>#riwayat"
               class="flex items-center gap-2 rounded-lg px-4 py-2 font-semibold <?= $aktif ? "bg-white text-emerald-900 shadow-sm" : "text-slate-500" ?>"
               <?= $aktif ? 'aria-current="page"' : "" ?>>
              <span class="size-2.5 rounded-full <?= $kunci === "masuk" ? "bg-emerald-600" : "bg-red-600" ?>"></span><?= $label ?>
              <span class="rounded-full bg-slate-200 px-2 text-xs"><?= count($kunci === "masuk" ? $toko->getBarangMasuk() : $toko->getBarangKeluar()) ?></span>
            </a>
          <?php endforeach; ?>
        </nav>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
          <table class="w-full border-collapse">
            <thead class="hidden text-left text-xs tracking-wide uppercase md:table-header-group <?= $tabAktif === "masuk" ? "bg-emerald-50 text-emerald-950" : "bg-red-50 text-red-900" ?>">
              <tr>
                <th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Barang</th><th class="px-4 py-3">Jumlah</th>
                <th class="px-4 py-3"><?= $tabAktif === "masuk" ? "Supplier" : "Keterangan" ?></th>
                <th class="px-4 py-3"><?= $tabAktif === "masuk" ? "Harga Beli" : "Nilai Jual" ?></th>
              </tr>
            </thead>
            <tbody class="block md:table-row-group">
              <?php foreach ($riwayat as $t): ?>
                <tr class="baris">
                  <td class="sel" data-label="Tanggal"><?= $t->getTanggalFormat() ?></td>
                  <td class="sel font-medium" data-label="Barang"><?= e($t->getBarang()->getNama()) ?></td>
                  <td class="sel tabular-nums font-semibold <?= $t->isMasuk() ? "text-emerald-700" : "text-red-600" ?>" data-label="Jumlah"><?= e($t->getJumlahFormat()) ?></td>
                  <td class="sel" data-label="<?= $t->isMasuk() ? "Supplier" : "Keterangan" ?>"><?= e($t->getKeterangan()) ?></td>
                  <td class="sel tabular-nums" data-label="<?= $t->isMasuk() ? "Harga Beli" : "Nilai Jual" ?>"><?= formatRupiah($t->isMasuk() ? $t->getHarga() : $t->getNilai()) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>

  <footer class="bg-emerald-950 py-6 text-sm text-emerald-200">
    <div class="mx-auto flex w-[min(1120px,100%-2.5rem)] flex-wrap justify-between gap-2">
      <p>&copy; <?= date("Y") ?> <?= e($toko->getNama()) ?> &middot; Praktikum Pemrograman Web</p>
      <p>Dibuat oleh Nafis &middot; Teknik Informatika UMM &middot; PHP <?= PHP_VERSION ?></p>
    </div>
  </footer>
</body>
</html>
