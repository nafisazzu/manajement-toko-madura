<?php
/**
 * VIEW detail barang — data dikirim oleh TokoController::show().
 * @var TokoMadura $toko  @var Barang $barang  @var int $stok
 * @var array $status  @var Transaksi[] $riwayat  @var ?array $flash
 */
$totalMasuk = 0;
$totalKeluar = 0;
foreach ($riwayat as $t) {
    if ($t->isMasuk()) {
        $totalMasuk += $t->getJumlah();
    } else {
        $totalKeluar += $t->getJumlah();
    }
}
?>
<section class="bg-linear-to-br from-emerald-950 to-emerald-700 py-10 text-white">
  <div class="mx-auto w-[min(1120px,100%-2.5rem)]">
    <a href="<?= url("home") ?>#stok" class="text-sm font-semibold text-emerald-200 hover:text-white">&larr; Kembali ke daftar stok</a>
    <div class="mt-4 flex flex-wrap items-end justify-between gap-6">
      <div>
        <p class="text-sm text-emerald-200"><?= $barang->getKode() ?> &middot; <?= e($barang->getKategori()) ?></p>
        <h1 class="mt-1 text-3xl font-bold md:text-4xl"><?= e($barang->getNama()) ?></h1>
        <p class="mt-2 text-emerald-100">Harga jual <?= $barang->getHargaFormat() ?> / <?= e($barang->getSatuan()) ?></p>
      </div>
      <div class="rounded-2xl bg-white px-6 py-4 text-slate-900 shadow-xl">
        <p class="text-sm text-slate-500">Stok sekarang</p>
        <p class="flex items-center gap-3">
          <span class="text-4xl font-extrabold"><?= $stok ?></span>
          <span class="text-slate-500"><?= e($barang->getSatuan()) ?></span>
          <span class="badge <?= $status["kelas"] ?>"><?= $status["label"] ?></span>
        </p>
      </div>
    </div>
  </div>
</section>

<section class="py-12">
  <div class="mx-auto grid w-[min(1120px,100%-2.5rem)] gap-8 lg:grid-cols-[1.4fr_1fr]">
    <div>
      <h2 class="text-xl font-bold">Riwayat <?= e($barang->getNama()) ?></h2>
      <p class="mt-1 mb-4 text-slate-600">
        Total masuk <strong class="text-emerald-700"><?= $totalMasuk ?></strong>,
        total keluar <strong class="text-red-600"><?= $totalKeluar ?></strong>
        &rarr; stok <strong><?= $stok ?></strong> <?= e($barang->getSatuan()) ?>.
      </p>

      <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <?php if (count($riwayat) === 0): ?>
          <p class="p-6 text-center text-slate-500">Belum ada transaksi untuk barang ini.</p>
        <?php else: ?>
          <table class="w-full border-collapse">
            <thead class="hidden bg-emerald-50 text-left text-xs tracking-wide text-emerald-950 uppercase md:table-header-group">
              <tr>
                <th class="px-4 py-3">ID</th><th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Jumlah</th>
                <th class="px-4 py-3">Supplier / Keterangan</th>
              </tr>
            </thead>
            <tbody class="block md:table-row-group">
              <?php foreach ($riwayat as $t): ?>
                <tr class="baris">
                  <td class="sel text-slate-500" data-label="ID"><?= $t->getId() ?></td>
                  <td class="sel" data-label="Tanggal"><?= $t->getTanggalFormat() ?></td>
                  <td class="sel tabular-nums font-semibold <?= $t->isMasuk() ? "text-emerald-700" : "text-red-600" ?>" data-label="Jumlah"><?= e($t->getJumlahFormat()) ?></td>
                  <td class="sel" data-label="Keterangan"><?= e($t->getKeterangan()) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <div>
      <h2 class="mb-4 text-xl font-bold">Catat transaksi barang ini</h2>
      <?php $kembali = "barang"; $kodeTerpilih = $barang->getKode(); require __DIR__ . "/partials/form-transaksi.php"; ?>
    </div>
  </div>
</section>
