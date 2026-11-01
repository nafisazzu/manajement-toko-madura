// =========================================================
// Toko Madura — Sprint 02: JavaScript + DOM + Event
// Data awal ada di js/data.js (daftarBarang, barangMasuk, barangKeluar)
// =========================================================

// ---------- 1. SELECT: ambil elemen yang dibutuhkan ----------
const sapaan = document.querySelector("#sapaan");
const tanggalHariIni = document.querySelector("#tanggal-hari-ini");

const tombolMenu = document.querySelector("#tombol-menu");
const menuUtama = document.querySelector("#menu-utama");
const ikonBuka = document.querySelector("#ikon-buka");
const ikonTutup = document.querySelector("#ikon-tutup");

const ringkasanJenis = document.querySelector("#ringkasan-jenis");
const ringkasanMasuk = document.querySelector("#ringkasan-masuk");
const ringkasanKeluar = document.querySelector("#ringkasan-keluar");
const ringkasanPerlu = document.querySelector("#ringkasan-perlu");

const inputCari = document.querySelector("#cari-barang");
const tabelStok = document.querySelector("#tabel-stok");
const stokKosong = document.querySelector("#stok-kosong");

const tombolTab = document.querySelectorAll(".tab-btn");
const panelMasuk = document.querySelector("#panel-masuk");
const panelKeluar = document.querySelector("#panel-keluar");
const tabelMasuk = document.querySelector("#tabel-masuk");
const tabelKeluar = document.querySelector("#tabel-keluar");

const form = document.querySelector("#form-transaksi");
const pilihBarang = document.querySelector("#pilih-barang");
const infoStok = document.querySelector("#info-stok");
const inputJumlah = document.querySelector("#input-jumlah");
const labelKeterangan = document.querySelector("#label-keterangan");
const inputKeterangan = document.querySelector("#input-keterangan");
const pesanForm = document.querySelector("#pesan-form");

// ---------- 2. Fungsi bantu ----------
const BULAN = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

function formatTanggal(iso) {
  const [tahun, bulan, hari] = iso.split("-");
  return `${Number(hari)} ${BULAN[Number(bulan) - 1]} ${tahun}`;
}

function formatRupiah(angka) {
  return "Rp" + angka.toLocaleString("id-ID");
}

// mencegah teks input pengguna dibaca sebagai HTML
function amankan(teks) {
  return String(teks).replace(/[&<>"']/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));
}

function cariBarang(kode) {
  return daftarBarang.find((barang) => barang.kode === kode);
}

function hitungStok(kode) {
  const masuk = barangMasuk.filter((t) => t.kode === kode).reduce((total, t) => total + t.jumlah, 0);
  const keluar = barangKeluar.filter((t) => t.kode === kode).reduce((total, t) => total + t.jumlah, 0);
  return masuk - keluar;
}

function statusStok(stok) {
  if (stok === 0) return { label: "Habis", kelas: "badge-habis" };
  if (stok <= BATAS_MENIPIS) return { label: "Menipis", kelas: "badge-menipis" };
  return { label: "Aman", kelas: "badge-aman" };
}

function hariIniISO() {
  const d = new Date();
  const pad = (n) => String(n).padStart(2, "0");
  return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
}

function idBaru(prefix, daftar) {
  return `${prefix}-${String(daftar.length + 1).padStart(3, "0")}`;
}

// ---------- 3. CHANGE: ubah isi elemen (Task 01) ----------
function tampilkanSapaan() {
  const jam = new Date().getHours();
  let waktu = "malam";
  if (jam >= 4 && jam < 11) waktu = "pagi";
  else if (jam >= 11 && jam < 15) waktu = "siang";
  else if (jam >= 15 && jam < 18) waktu = "sore";

  sapaan.textContent = `Selamat ${waktu}! Toko buka 24 jam, stok tetap terkendali`;
  tanggalHariIni.textContent = new Date().toLocaleDateString("id-ID", {
    weekday: "long", day: "numeric", month: "long", year: "numeric",
  });
}

// ---------- 4. Render data ke DOM ----------
function renderRingkasan() {
  const totalMasuk = barangMasuk.reduce((total, t) => total + t.jumlah, 0);
  const totalKeluar = barangKeluar.reduce((total, t) => total + t.jumlah, 0);
  const perluKulakan = daftarBarang.filter((b) => hitungStok(b.kode) <= BATAS_MENIPIS).length;

  ringkasanJenis.textContent = daftarBarang.length;
  ringkasanMasuk.textContent = totalMasuk;
  ringkasanKeluar.textContent = totalKeluar;
  ringkasanPerlu.textContent = perluKulakan;

  document.querySelector("#jumlah-tab-masuk").textContent = barangMasuk.length;
  document.querySelector("#jumlah-tab-keluar").textContent = barangKeluar.length;
}

function renderStok() {
  const kataKunci = inputCari.value.trim().toLowerCase();
  const hasil = daftarBarang.filter((b) =>
    `${b.kode} ${b.nama} ${b.kategori}`.toLowerCase().includes(kataKunci)
  );

  tabelStok.innerHTML = hasil.map((b) => {
    const stok = hitungStok(b.kode);
    const status = statusStok(stok);
    return `
      <tr class="baris" data-kode="${b.kode}">
        <td class="sel" data-label="Kode">${b.kode}</td>
        <td class="sel font-medium" data-label="Nama Barang">${b.nama}</td>
        <td class="sel" data-label="Kategori">${b.kategori}</td>
        <td class="sel tabular-nums" data-label="Stok">${stok} ${b.satuan}</td>
        <td class="sel" data-label="Status"><span class="badge ${status.kelas}">${status.label}</span></td>
      </tr>`;
  }).join("");

  stokKosong.classList.toggle("hidden", hasil.length > 0);
}

function renderMasuk(idSorot = null) {
  // urutkan terbaru di atas tanpa mengubah array asli
  const urut = [...barangMasuk].reverse();
  tabelMasuk.innerHTML = urut.map((t) => {
    const b = cariBarang(t.kode);
    const sorot = t.id === idSorot ? "bg-emerald-50" : "";
    return `
      <tr class="baris ${sorot}">
        <td class="sel" data-label="Tanggal"><time datetime="${t.tanggal}">${formatTanggal(t.tanggal)}</time></td>
        <td class="sel font-medium" data-label="Barang">${b.nama}</td>
        <td class="sel tabular-nums font-semibold text-emerald-700" data-label="Jumlah">+${t.jumlah} ${b.satuan}</td>
        <td class="sel" data-label="Supplier">${amankan(t.supplier)}</td>
        <td class="sel tabular-nums" data-label="Harga Beli">${t.hargaBeli ? formatRupiah(t.hargaBeli) : "-"}</td>
      </tr>`;
  }).join("");
}

function renderKeluar(idSorot = null) {
  const urut = [...barangKeluar].reverse();
  tabelKeluar.innerHTML = urut.map((t) => {
    const b = cariBarang(t.kode);
    const sorot = t.id === idSorot ? "bg-red-50" : "";
    return `
      <tr class="baris ${sorot}">
        <td class="sel" data-label="Tanggal"><time datetime="${t.tanggal}">${formatTanggal(t.tanggal)}</time></td>
        <td class="sel font-medium" data-label="Barang">${b.nama}</td>
        <td class="sel tabular-nums font-semibold text-red-600" data-label="Jumlah">&minus;${t.jumlah} ${b.satuan}</td>
        <td class="sel" data-label="Keterangan">${amankan(t.keterangan)}</td>
        <td class="sel tabular-nums" data-label="Harga Jual">${formatRupiah(b.hargaJual)}</td>
      </tr>`;
  }).join("");
}

function isiPilihanBarang() {
  daftarBarang.forEach((b) => {
    const opsi = document.createElement("option");
    opsi.value = b.kode;
    opsi.textContent = `${b.nama} (${b.kode})`;
    pilihBarang.appendChild(opsi);
  });
}

// ---------- 5. EVENT HANDLING (Task 02) ----------

// a) Klik menu (mobile): buka/tutup navigasi
tombolMenu.addEventListener("click", () => {
  const terbuka = tombolMenu.getAttribute("aria-expanded") === "true";
  tombolMenu.setAttribute("aria-expanded", String(!terbuka));
  tombolMenu.setAttribute("aria-label", terbuka ? "Buka menu" : "Tutup menu");
  menuUtama.classList.toggle("hidden");
  ikonBuka.classList.toggle("hidden");
  ikonTutup.classList.toggle("hidden");
});

// tutup menu setelah salah satu link diklik (mobile)
menuUtama.querySelectorAll("a").forEach((link) => {
  link.addEventListener("click", () => {
    if (window.innerWidth < 768 && tombolMenu.getAttribute("aria-expanded") === "true") {
      tombolMenu.click();
    }
  });
});

// b) Input pencarian: filter tabel stok secara langsung
inputCari.addEventListener("input", renderStok);

// c) Klik tab: tampilkan barang masuk / keluar
function pilihTab(nama) {
  tombolTab.forEach((tombol) => {
    const aktif = tombol.dataset.tab === nama;
    tombol.setAttribute("aria-selected", String(aktif));
    tombol.classList.toggle("bg-white", aktif);
    tombol.classList.toggle("text-emerald-900", aktif);
    tombol.classList.toggle("shadow-sm", aktif);
    tombol.classList.toggle("text-slate-500", !aktif);
  });
  panelMasuk.classList.toggle("hidden", nama !== "masuk");
  panelKeluar.classList.toggle("hidden", nama !== "keluar");
}

tombolTab.forEach((tombol) => {
  tombol.addEventListener("click", () => pilihTab(tombol.dataset.tab));
});

// d) Perubahan jenis transaksi & barang: sesuaikan label dan info stok
function jenisTerpilih() {
  return form.querySelector('input[name="jenis"]:checked').value;
}

function perbaruiForm() {
  const jenis = jenisTerpilih();
  labelKeterangan.textContent = jenis === "masuk" ? "Supplier" : "Keterangan";
  inputKeterangan.placeholder = jenis === "masuk" ? "Contoh: Grosir Barokah" : "Contoh: Penjualan";

  const barang = cariBarang(pilihBarang.value);
  infoStok.textContent = barang ? `Stok sekarang: ${hitungStok(barang.kode)} ${barang.satuan}` : "";
}

form.querySelectorAll('input[name="jenis"]').forEach((radio) => radio.addEventListener("change", perbaruiForm));
pilihBarang.addEventListener("change", perbaruiForm);

// ---------- 6. SATU INTERAKSI LENGKAP (Task 03): submit form ----------
function tampilkanPesan(teks, jenis) {
  pesanForm.textContent = teks;
  pesanForm.classList.remove("hidden", "bg-emerald-50", "text-emerald-800", "bg-red-50", "text-red-700");
  pesanForm.classList.add(...(jenis === "sukses" ? ["bg-emerald-50", "text-emerald-800"] : ["bg-red-50", "text-red-700"]));
}

form.addEventListener("submit", (event) => {
  event.preventDefault(); // jangan reload halaman

  const jenis = jenisTerpilih();
  const barang = cariBarang(pilihBarang.value);
  const jumlah = Number(inputJumlah.value);
  const keterangan = inputKeterangan.value.trim();

  // validasi
  if (!barang) return tampilkanPesan("Pilih barang terlebih dahulu.", "error");
  if (!Number.isInteger(jumlah) || jumlah <= 0) return tampilkanPesan("Jumlah harus bilangan bulat lebih dari 0.", "error");

  const stokSekarang = hitungStok(barang.kode);
  if (jenis === "keluar" && jumlah > stokSekarang) {
    return tampilkanPesan(`Stok ${barang.nama} hanya ${stokSekarang} ${barang.satuan}. Jumlah keluar tidak boleh melebihi stok.`, "error");
  }

  // simpan ke array data
  let transaksi;
  if (jenis === "masuk") {
    transaksi = { id: idBaru("BM", barangMasuk), tanggal: hariIniISO(), kode: barang.kode, jumlah, supplier: keterangan || "-", hargaBeli: 0 };
    barangMasuk.push(transaksi);
  } else {
    transaksi = { id: idBaru("BK", barangKeluar), tanggal: hariIniISO(), kode: barang.kode, jumlah, keterangan: keterangan || "Penjualan" };
    barangKeluar.push(transaksi);
  }

  // perbarui semua bagian halaman yang terpengaruh
  renderRingkasan();
  renderStok();
  renderMasuk(jenis === "masuk" ? transaksi.id : null);
  renderKeluar(jenis === "keluar" ? transaksi.id : null);
  pilihTab(jenis);

  const stokBaru = hitungStok(barang.kode);
  tampilkanPesan(
    `Berhasil! ${jenis === "masuk" ? "+" : "−"}${jumlah} ${barang.satuan} ${barang.nama} dicatat. Stok sekarang ${stokBaru} ${barang.satuan}.`,
    "sukses"
  );

  form.reset();
  perbaruiForm();
});

// ---------- 7. Jalankan saat halaman dimuat ----------
tampilkanSapaan();
isiPilihanBarang();
renderRingkasan();
renderStok();
renderMasuk();
renderKeluar();
pilihTab("masuk");
