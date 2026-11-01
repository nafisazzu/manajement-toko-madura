<?php /** @var string $pesan */ ?>
<section class="mx-auto grid w-[min(1120px,100%-2.5rem)] place-items-center py-24 text-center">
  <p class="text-6xl font-extrabold text-emerald-700">404</p>
  <h1 class="mt-3 text-2xl font-bold">Tidak ditemukan</h1>
  <p class="mt-2 text-slate-600"><?= e($pesan) ?></p>
  <a href="<?= url("home") ?>" class="mt-6 rounded-[10px] bg-emerald-700 px-5 py-3 font-bold text-white hover:bg-emerald-800">Kembali ke dashboard</a>
</section>
