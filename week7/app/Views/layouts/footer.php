  </main>

  <footer class="bg-emerald-950 py-6 text-sm text-emerald-200">
    <div class="mx-auto flex w-[min(1120px,100%-2.5rem)] flex-wrap items-center justify-between gap-3">
      <p>&copy; <?= date("Y") ?> <?= e($toko->getNama()) ?> &middot; Praktikum Pemrograman Web &middot; Struktur MVC</p>
      <form action="<?= url("reset") ?>" method="post" class="flex items-center gap-3">
        <span>Dibuat oleh Nafis &middot; Teknik Informatika UMM</span>
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <button class="rounded-md border border-emerald-700 px-2 py-1 text-xs hover:bg-white/10" title="Kembalikan data ke kondisi awal">Reset data demo</button>
      </form>
    </div>
  </footer>
</body>
</html>
