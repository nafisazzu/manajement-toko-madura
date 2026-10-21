<?php
// Halaman utama: Barang Masuk & Barang Keluar (object -> PHP -> HTML)
date_default_timezone_set('Asia/Jakarta');
session_start();
require_once 'TokoMadura.php';

$toko = new Tokomadura('Toko Madura Berkah');

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// Proses form (POST) lalu redirect agar tidak terkirim ulang saat refresh
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $aksi = $_POST['aksi'] ?? '';
        $catatan = trim($_POST['catatan'] ?? '');
        if ($aksi === 'masuk') {
            $toko->barangMasuk(
                $_POST['nama'] ?? '',
                (int) ($_POST['jumlah'] ?? 0),
                $_POST['satuan'] ?? 'pcs',
                $catatan
            );
            $_SESSION['pesan'] = ['ok', 'Barang masuk berhasil dicatat.'];
        } elseif ($aksi === 'keluar') {
            $toko->barangKeluar($_POST['nama'] ?? '', (int) ($_POST['jumlah'] ?? 0), $catatan);
            $_SESSION['pesan'] = ['ok', 'Barang keluar berhasil dicatat.'];
        }
    } catch (InvalidArgumentException $ex) {
        $_SESSION['pesan'] = ['error', $ex->getMessage()];
    }
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$pesan = $_SESSION['pesan'] ?? null;
unset($_SESSION['pesan']);
$daftarBarang = $toko->getDaftarBarang();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($toko->getNama()) ?> - Barang Masuk &amp; Keluar</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: system-ui, sans-serif; background:#F2F5F3; color:#16241C; margin:0; }
    header { background:#0F3D28; color:#fff; padding:24px 32px; }
    header h1 { margin:0 0 4px; }
    main { max-width:1000px; margin:24px auto; padding:0 16px 40px; }
    h2 { margin:28px 0 12px; font-size:1.1rem; }
    .grid { display:grid; gap:12px; }
    .ringkasan { grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); }
    .form-wrap { grid-template-columns:repeat(auto-fit,minmax(320px,1fr)); }
    .kartu { background:#fff; border:1px solid #CBD6CF; border-radius:12px; padding:14px 18px; }
    .kartu small { color:#5C6B62; }
    .kartu strong { display:block; font-size:1.4rem; }
    label { display:block; font-size:.85rem; color:#5C6B62; margin:10px 0 4px; }
    input, select { width:100%; padding:9px 10px; border:1px solid #CBD6CF; border-radius:8px; font:inherit; background:#fff; }
    .baris { display:flex; gap:10px; } .baris > div { flex:1; }
    button { margin-top:14px; width:100%; padding:11px; border:0; border-radius:8px; font:inherit; font-weight:600; color:#fff; cursor:pointer; }
    .btn-masuk { background:#0F3D28; } .btn-keluar { background:#E6A122; color:#16241C; }
    .pesan { padding:12px 16px; border-radius:10px; margin-bottom:16px; }
    .pesan.ok { background:#E3ECE6; color:#0F3D28; } .pesan.error { background:#FBEAE8; color:#B3261E; }
    table { width:100%; border-collapse:collapse; background:#fff; border:1px solid #CBD6CF; border-radius:12px; overflow:hidden; }
    th, td { padding:10px 14px; text-align:left; border-bottom:1px solid #E3ECE6; font-size:.92rem; }
    th { background:#E3ECE6; }
    .tabel-scroll { overflow-x:auto; }
    .badge { padding:2px 10px; border-radius:999px; font-size:.8rem; white-space:nowrap; }
    .aman, .masuk { background:#E3ECE6; color:#0F3D28; }
    .menipis, .keluar { background:#FCF1DC; color:#9a6200; }
    .habis { background:#FBEAE8; color:#B3261E; }
    .kosong { color:#5C6B62; padding:16px; text-align:center; }
  </style>
</head>
<body>
  <header>
    <h1><?= e($toko->getNama()) ?></h1>
    <div>Pencatatan Barang Masuk &amp; Barang Keluar</div>
  </header>

  <main>
    <?php if ($pesan): ?>
      <div class="pesan <?= e($pesan[0]) ?>"><?= e($pesan[1]) ?></div>
    <?php endif; ?>

    <section class="grid ringkasan">
      <div class="kartu"><small>Jenis Barang</small><strong><?= $toko->getTotalJenisBarang() ?></strong></div>
      <div class="kartu"><small>Total Masuk</small><strong><?= $toko->getTotalMasuk() ?></strong></div>
      <div class="kartu"><small>Total Keluar</small><strong><?= $toko->getTotalKeluar() ?></strong></div>
      <div class="kartu"><small>Perlu Restock</small><strong><?= $toko->getJumlahMenipis() ?></strong></div>
    </section>

    <section class="grid form-wrap" style="margin-top:20px">
      <form method="post" class="kartu">
        <strong>Barang Masuk</strong>
        <input type="hidden" name="aksi" value="masuk">
        <label for="m-nama">Nama barang</label>
        <input id="m-nama" name="nama" list="daftar-barang" required placeholder="Contoh: Gula Pasir 1 kg">
        <datalist id="daftar-barang">
          <?php foreach ($daftarBarang as $b): ?><option value="<?= e($b->getNama()) ?>"><?php endforeach; ?>
        </datalist>
        <div class="baris">
          <div><label for="m-jumlah">Jumlah</label>
            <input id="m-jumlah" type="number" name="jumlah" min="1" required></div>
          <div><label for="m-satuan">Satuan (barang baru)</label>
            <select id="m-satuan" name="satuan">
              <?php foreach (['pcs','dus','karung','botol','kg','liter'] as $s): ?>
                <option><?= e($s) ?></option>
              <?php endforeach; ?>
            </select></div>
        </div>
        <label for="m-catatan">Catatan (opsional)</label>
        <input id="m-catatan" name="catatan" placeholder="Contoh: Beli dari supplier">
        <button class="btn-masuk" type="submit">Catat Barang Masuk</button>
      </form>

      <form method="post" class="kartu">
        <strong>Barang Keluar</strong>
        <input type="hidden" name="aksi" value="keluar">
        <label for="k-nama">Pilih barang</label>
        <select id="k-nama" name="nama" required>
          <option value="">-- pilih barang --</option>
          <?php foreach ($daftarBarang as $b): ?>
            <option value="<?= e($b->getNama()) ?>">
              <?= e($b->getNama()) ?> (stok <?= $b->getStok() ?> <?= e($b->getSatuan()) ?>)
            </option>
          <?php endforeach; ?>
        </select>
        <label for="k-jumlah">Jumlah</label>
        <input id="k-jumlah" type="number" name="jumlah" min="1" required>
        <label for="k-catatan">Catatan (opsional)</label>
        <input id="k-catatan" name="catatan" placeholder="Contoh: Terjual / rusak">
        <button class="btn-keluar" type="submit">Catat Barang Keluar</button>
      </form>
    </section>

    <h2>Stok Barang</h2>
    <div class="tabel-scroll">
      <table>
        <thead><tr><th>Barang</th><th>Stok</th><th>Status</th></tr></thead>
        <tbody>
          <?php foreach ($daftarBarang as $b): ?>
            <?php $s = $b->getStatusStok(); $kelas = $s === 'Habis' ? 'habis' : ($s === 'Stok menipis' ? 'menipis' : 'aman'); ?>
            <tr>
              <td><?= e($b->getNama()) ?></td>
              <td><?= $b->getStok() ?> <?= e($b->getSatuan()) ?></td>
              <td><span class="badge <?= $kelas ?>"><?= e($s) ?></span></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$daftarBarang): ?><tr><td colspan="3" class="kosong">Belum ada barang.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>

    <h2>Riwayat Transaksi</h2>
    <div class="tabel-scroll">
      <table>
        <thead><tr><th>Waktu</th><th>Jenis</th><th>Barang</th><th>Jumlah</th><th>Catatan</th></tr></thead>
        <tbody>
          <?php foreach ($toko->getRiwayat() as $t): ?>
            <tr>
              <td><?= e($t->getWaktu()) ?></td>
              <td><span class="badge <?= e($t->getJenis()) ?>"><?= e($t->getLabel()) ?></span></td>
              <td><?= e($t->getNamaBarang()) ?></td>
              <td><?= e($t->getJumlahBertanda()) ?></td>
              <td><?= e($t->getCatatan()) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$toko->getRiwayat()): ?><tr><td colspan="5" class="kosong">Belum ada transaksi.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>