<?php
/**
 * Form catat transaksi — dipakai di dashboard dan halaman detail barang.
 * @var TokoMadura $toko  @var ?array $flash
 * @var string $kembali   ("home" atau "barang")
 * @var ?string $kodeTerpilih
 */
$lama = ($flash["jenis"] ?? "") === "error" ? ($flash["input"] ?? []) : [];
$jenisLama = $lama["jenis"] ?? "masuk";
$kodeLama = $lama["kode"] ?? ($kodeTerpilih ?? "");
?>
<form action="<?= url("transaksi") ?>" method="post" class="group grid gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
  <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
  <input type="hidden" name="kembali" value="<?= e($kembali) ?>">

  <fieldset>
    <legend class="mb-2 font-semibold">Jenis transaksi</legend>
    <div class="grid grid-cols-2 gap-3">
      <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-300 p-3 has-checked:border-emerald-600 has-checked:bg-emerald-50">
        <input type="radio" name="jenis" value="masuk" class="accent-emerald-600" <?= $jenisLama === "masuk" ? "checked" : "" ?>> Barang Masuk
      </label>
      <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-300 p-3 has-checked:border-red-600 has-checked:bg-red-50">
        <input type="radio" name="jenis" value="keluar" class="accent-red-600" <?= $jenisLama === "keluar" ? "checked" : "" ?>> Barang Keluar
      </label>
    </div>
  </fieldset>

  <div class="grid gap-4 sm:grid-cols-2">
    <?php if (!empty($kodeTerpilih)): ?>
      <input type="hidden" name="kode" value="<?= e($kodeTerpilih) ?>">
    <?php else: ?>
      <label class="grid gap-1.5">
        <span class="font-semibold">Barang</span>
        <select name="kode" class="input" required>
          <option value="">— Pilih barang —</option>
          <?php foreach ($toko->getDaftarBarang() as $b): ?>
            <option value="<?= $b->getKode() ?>" <?= $kodeLama === $b->getKode() ? "selected" : "" ?>>
              <?= e($b->getNama()) ?> (stok <?= $toko->getStok($b->getKode()) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </label>
    <?php endif; ?>
    <label class="grid gap-1.5">
      <span class="font-semibold">Jumlah</span>
      <input name="jumlah" type="number" min="1" step="1" placeholder="0" value="<?= e((string) ($lama["jumlah"] ?? "")) ?>" class="input" required>
    </label>
    <label class="grid gap-1.5 group-has-[input[value=keluar]:checked]:hidden">
      <span class="font-semibold">Harga beli / satuan</span>
      <input name="harga_beli" type="number" min="0" step="100" placeholder="Contoh: 15500" value="<?= e((string) ($lama["harga_beli"] ?? "")) ?>" class="input">
    </label>
  </div>

  <label class="grid gap-1.5">
    <span class="font-semibold">
      <span class="group-has-[input[value=keluar]:checked]:hidden">Supplier</span>
      <span class="hidden group-has-[input[value=keluar]:checked]:inline">Keterangan (mis. Penjualan, Barang rusak)</span>
    </span>
    <input name="keterangan" type="text" maxlength="60" placeholder="Opsional" value="<?= e((string) ($lama["keterangan"] ?? "")) ?>" class="input">
  </label>

  <button type="submit" class="rounded-[10px] bg-emerald-700 px-5 py-3 font-bold text-white transition hover:-translate-y-0.5 hover:bg-emerald-800 hover:shadow-lg">
    Simpan Transaksi
  </button>
</form>
