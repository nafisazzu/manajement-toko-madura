// Data awal Toko Madura (nanti di Sprint 03 diganti data dari API)

const BATAS_MENIPIS = 5;

const daftarBarang = [
  { kode: "BRG-001", nama: "Beras Premium 5 kg", kategori: "Sembako", satuan: "karung", hargaJual: 78000 },
  { kode: "BRG-002", nama: "Minyak Goreng 1 L", kategori: "Sembako", satuan: "botol", hargaJual: 18000 },
  { kode: "BRG-003", nama: "Gula Pasir 1 kg", kategori: "Sembako", satuan: "bungkus", hargaJual: 17500 },
  { kode: "BRG-004", nama: "Telur Ayam", kategori: "Sembako", satuan: "kg", hargaJual: 29000 },
  { kode: "BRG-005", nama: "Mi Instan Goreng", kategori: "Makanan", satuan: "bungkus", hargaJual: 3500 },
  { kode: "BRG-006", nama: "Kopi Sachet", kategori: "Minuman", satuan: "renceng", hargaJual: 15000 },
  { kode: "BRG-007", nama: "Air Mineral 600 ml", kategori: "Minuman", satuan: "botol", hargaJual: 4000 },
  { kode: "BRG-008", nama: "Sabun Mandi", kategori: "Kebutuhan Rumah", satuan: "batang", hargaJual: 4500 },
  { kode: "BRG-009", nama: "Gas LPG 3 kg", kategori: "Kebutuhan Rumah", satuan: "tabung", hargaJual: 22000 },
];

const barangMasuk = [
  { id: "BM-001", tanggal: "2026-10-26", kode: "BRG-001", jumlah: 10, supplier: "CV Sumber Pangan", hargaBeli: 72000 },
  { id: "BM-002", tanggal: "2026-10-26", kode: "BRG-002", jumlah: 24, supplier: "CV Sumber Pangan", hargaBeli: 16000 },
  { id: "BM-003", tanggal: "2026-10-27", kode: "BRG-003", jumlah: 20, supplier: "Grosir Barokah", hargaBeli: 15500 },
  { id: "BM-004", tanggal: "2026-10-27", kode: "BRG-005", jumlah: 80, supplier: "Agen Mi Sejahtera", hargaBeli: 2800 },
  { id: "BM-005", tanggal: "2026-10-28", kode: "BRG-006", jumlah: 15, supplier: "Grosir Barokah", hargaBeli: 12500 },
  { id: "BM-006", tanggal: "2026-10-28", kode: "BRG-007", jumlah: 48, supplier: "Agen Air Segar", hargaBeli: 2800 },
  { id: "BM-007", tanggal: "2026-10-29", kode: "BRG-009", jumlah: 12, supplier: "Pangkalan LPG Pak Halim", hargaBeli: 18000 },
  { id: "BM-008", tanggal: "2026-10-30", kode: "BRG-004", jumlah: 8, supplier: "Peternak Telur Lestari", hargaBeli: 26000 },
  { id: "BM-009", tanggal: "2026-10-30", kode: "BRG-008", jumlah: 30, supplier: "Grosir Barokah", hargaBeli: 3500 },
];

const barangKeluar = [
  { id: "BK-001", tanggal: "2026-10-27", kode: "BRG-001", jumlah: 4, keterangan: "Penjualan" },
  { id: "BK-002", tanggal: "2026-10-27", kode: "BRG-005", jumlah: 35, keterangan: "Penjualan" },
  { id: "BK-003", tanggal: "2026-10-28", kode: "BRG-002", jumlah: 20, keterangan: "Penjualan" },
  { id: "BK-004", tanggal: "2026-10-28", kode: "BRG-007", jumlah: 30, keterangan: "Penjualan" },
  { id: "BK-005", tanggal: "2026-10-29", kode: "BRG-009", jumlah: 12, keterangan: "Penjualan" },
  { id: "BK-006", tanggal: "2026-10-29", kode: "BRG-003", jumlah: 6, keterangan: "Penjualan" },
  { id: "BK-007", tanggal: "2026-10-30", kode: "BRG-006", jumlah: 11, keterangan: "Penjualan" },
  { id: "BK-008", tanggal: "2026-10-31", kode: "BRG-004", jumlah: 5, keterangan: "Penjualan" },
  { id: "BK-009", tanggal: "2026-10-31", kode: "BRG-008", jumlah: 2, keterangan: "Barang rusak" },
];
