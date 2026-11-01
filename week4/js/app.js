// =========================================================
// Toko Madura — Sprint 03: Fetch API + JSON + async/await
// Data TIDAK lagi ditulis di file JS, tetapi diambil dari API:
//   GET api/barang.json        -> daftar barang
//   GET api/barang-masuk.json  -> riwayat barang masuk
//   GET api/barang-keluar.json -> riwayat barang keluar
// Jalankan lewat local server (Live Server / `npx serve` / `php -S`),
// karena fetch() tidak bisa membaca file JSON lewat file://
// =========================================================

// ---------- 0. Konfigurasi API ----------
const API_BASE = "api";
const BATAS_MENIPIS = 5;

// Simulasi untuk demo (lewat URL):
//   index.html?lambat=1  -> jeda 1,5 detik supaya loading terlihat jelas
//   index.html?error=1   -> memanggil endpoint yang salah supaya error terlihat
const parameter = new URLSearchParams(location.search);
const JEDA_SIMULASI = parameter.has("lambat") ? 1500 : 400;
const SIMULASI_ERROR = parameter.has("error");

// state aplikasi (diisi dari API)
let daftarBarang = [];
let barangMasuk = [];
let barangKeluar = [];

// ---------- 1. SELECT: ambil elemen yang dibutuhkan ----------
const statusIkon = document.querySelector("#status-ikon");
const statusTeks = document.querySelector("#status-teks");
const tombolMuatUlang = document.querySelector("#tombol-muat-ulang");
const stokError = document.querySelector("#stok-error");
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
  pilihBarang.length = 1; // sisakan opsi "— Pilih barang —"
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

// ---------- 7. FETCH API (Task 01) ----------
const tunggu = (ms) => new Promise((selesai) => setTimeout(selesai, ms));

// Mengambil satu endpoint dan mengembalikan array data
async function ambilData(endpoint) {
  const response = await fetch(`${API_BASE}/${endpoint}.json`);

  if (!response.ok) {
    throw new Error(`Gagal mengambil ${endpoint} (HTTP ${response.status})`);
  }

  const json = await response.json(); // JSON -> object JavaScript
  if (json.status !== "success" || !Array.isArray(json.data)) {
    throw new Error(`Format data ${endpoint} tidak valid`);
  }
  return json.data;
}

// ---------- 8. STATE: loading / sukses / error (Task 03) ----------
function aturStatus(jenis, teks) {
  const warna = { loading: "bg-amber-400 animate-pulse", sukses: "bg-emerald-500", error: "bg-red-500" };
  statusIkon.className = `size-3 shrink-0 rounded-full ${warna[jenis]}`;
  statusTeks.textContent = teks;
  document.querySelector("#status-api").classList.toggle("border-red-300", jenis === "error");
  tombolMuatUlang.disabled = jenis === "loading";
}

function barisSkeleton(jumlahKolom, jumlahBaris = 4) {
  const sel = `<td class="sel"><span class="block h-4 w-full max-w-32 animate-pulse rounded bg-slate-200"></span></td>`;
  return Array.from({ length: jumlahBaris }, () => `<tr class="baris">${sel.repeat(jumlahKolom)}</tr>`).join("");
}

function tampilkanLoading() {
  aturStatus("loading", "Mengambil data dari API…");
  [ringkasanJenis, ringkasanMasuk, ringkasanKeluar, ringkasanPerlu].forEach((el) => (el.textContent = "…"));
  tabelStok.innerHTML = barisSkeleton(5);
  tabelMasuk.innerHTML = barisSkeleton(5);
  tabelKeluar.innerHTML = barisSkeleton(5);
  stokKosong.classList.add("hidden");
  stokError.classList.add("hidden");
  form.querySelector('button[type="submit"]').disabled = true;
}

function tampilkanError(pesan) {
  aturStatus("error", `${pesan}. Periksa koneksi atau server, lalu coba lagi.`);
  [ringkasanJenis, ringkasanMasuk, ringkasanKeluar, ringkasanPerlu].forEach((el) => (el.textContent = "-"));
  tabelStok.innerHTML = "";
  tabelMasuk.innerHTML = "";
  tabelKeluar.innerHTML = "";
  stokError.classList.remove("hidden");
  stokError.innerHTML = `
    <p class="text-lg font-semibold text-red-600">Gagal mengambil data</p>
    <p class="mt-1 text-slate-500">${amankan(pesan)}</p>
    <button type="button" id="tombol-coba-lagi" class="mt-4 rounded-lg bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700">Coba lagi</button>`;
  document.querySelector("#tombol-coba-lagi").addEventListener("click", () => muatData(false));
}

// ---------- 9. Alur utama: request -> loading -> response -> render ----------
async function muatData(pakaiSimulasiError = SIMULASI_ERROR) {
  tampilkanLoading();
  try {
    await tunggu(JEDA_SIMULASI); // simulasi jaringan agar loading terlihat saat demo

    // tiga request dikirim bersamaan
    const [barang, masuk, keluar] = await Promise.all([
      ambilData(pakaiSimulasiError ? "barang-salah" : "barang"),
      ambilData("barang-masuk"),
      ambilData("barang-keluar"),
    ]);

    daftarBarang = barang;
    barangMasuk = masuk;
    barangKeluar = keluar;

    // render data API ke DOM (Task 02)
    isiPilihanBarang();
    renderRingkasan();
    renderStok();
    renderMasuk();
    renderKeluar();
    form.querySelector('button[type="submit"]').disabled = false;

    const jam = new Date().toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit" });
    aturStatus("sukses", `Data berhasil dimuat dari API: ${barang.length} barang, ${masuk.length} barang masuk, ${keluar.length} barang keluar (pukul ${jam}).`);
  } catch (error) {
    console.error(error);
    tampilkanError(error.message);
  }
}

tombolMuatUlang.addEventListener("click", () => muatData(false));

// ---------- 10. Jalankan saat halaman dimuat ----------
tampilkanSapaan();
pilihTab("masuk");
muatData();
