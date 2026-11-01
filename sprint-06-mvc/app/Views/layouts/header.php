<?php /** @var TokoMadura $toko @var string $judul @var ?array $flash */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($judul) ?> — <?= e($toko->getNama()) ?></title>
  <link rel="stylesheet" href="public/css/output.css">
</head>
<body class="flex min-h-screen flex-col overflow-x-hidden bg-slate-50 font-sans leading-relaxed text-slate-900">

  <header class="sticky top-0 z-20 bg-emerald-950 text-white shadow-lg">
    <div class="mx-auto flex min-h-16 w-[min(1120px,100%-2.5rem)] flex-wrap items-center justify-between gap-2 py-2 md:py-0">
      <a href="<?= url("home") ?>" class="flex items-center gap-2.5">
        <span class="grid size-[38px] place-items-center rounded-[10px] bg-yellow-400 font-extrabold tracking-tight text-emerald-950" aria-hidden="true">TM</span>
        <span class="text-xl">Toko<strong class="text-yellow-400">Madura</strong></span>
      </a>
      <nav aria-label="Navigasi utama" class="w-full md:w-auto">
        <ul class="flex gap-1 overflow-x-auto">
          <li><a href="<?= url("home") ?>#beranda" class="block rounded-lg px-3 py-2 font-medium whitespace-nowrap text-emerald-100 hover:bg-white/10 hover:text-white">Beranda</a></li>
          <li><a href="<?= url("home") ?>#stok" class="block rounded-lg px-3 py-2 font-medium whitespace-nowrap text-emerald-100 hover:bg-white/10 hover:text-white">Stok</a></li>
          <li><a href="<?= url("home") ?>#riwayat" class="block rounded-lg px-3 py-2 font-medium whitespace-nowrap text-emerald-100 hover:bg-white/10 hover:text-white">Riwayat</a></li>
          <li><a href="<?= url("home") ?>#catat" class="block rounded-lg bg-yellow-400 px-3 py-2 font-bold whitespace-nowrap text-emerald-950 hover:bg-yellow-300">+ Catat Transaksi</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <?php if ($flash): ?>
    <div class="mx-auto mt-4 w-[min(1120px,100%-2.5rem)]">
      <p role="status" class="rounded-xl border px-4 py-3 font-medium <?= $flash["jenis"] === "sukses" ? "border-emerald-200 bg-emerald-50 text-emerald-800" : "border-red-200 bg-red-50 text-red-700" ?>">
        <?= $flash["jenis"] === "sukses" ? "✓" : "✕" ?> <?= e($flash["pesan"]) ?>
      </p>
    </div>
  <?php endif; ?>

  <main class="flex-1">
