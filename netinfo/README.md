# NetInfo — Network Information & Operation Management System

**NetInfo** adalah sistem informasi manajemen operasional terpadu berbasis web yang dirancang untuk penyedia layanan internet (ISP) lokal, RT/RW Net, dan unit NOC (*Network Operations Center*). Sistem ini mengintegrasikan pemantauan titik distribusi jaringan (*Optical Distribution Point* / ODP), manajemen siklus aduan gangguan (*Trouble Ticketing*), serta administrasi penagihan pelanggan (*Billing Management*).

Proyek ini dikembangkan sebagai implementasi sistem operasional jaringan dan pemenuhan tugas akhir / mini skripsi.

---

## 👥 Tim Pengembang (Developers)

* **Muhammad Ridha Rezeki**
* **Rausyanul Fikri**

---

## 🚀 Fitur Utama Sistem

### 1. Trouble Ticketing Lifecycle (Helpcare)
* **Pelaporan Mandiri Pelanggan:** Pengajuan tiket kendala dengan tingkat prioritas (*Low, Medium, High*).
* **Disposisi Teknisi:** Admin NOC mendisposisikan penanganan ke teknisi lapangan.
* **Log Teknis:** Teknisi mencatat tindakan perbaikan secara bertahap hingga status *Resolved*.
* **Audit Trail:** Riwayat mutasi status tiket tercatat otomatis pada log sistem.

### 2. Billing & Faktur Individual
* **Otomatisasi Tagihan:** Pembangkitan invoice bulanan berkala untuk seluruh pelanggan aktif.
* **Portal Pembayaran Interaktif:** Pilihan transfer bank (SeaBank) dan QRIS dinamis disertai form unggah bukti bayar.
* **Verifikasi Pembayaran:** Verifikasi bukti transfer oleh Admin (status *Paid / Unpaid*).
* **Faktur Individual Siap Cetak:** Format cetak struk/faktur resmi A4 per transaksi dengan stempel digital lunas.
* **Kontrol Isolir:** Isolasi layanan bagi pelanggan menunggak dan fitur pemulihan status layanan.

### 3. Manajemen Titik Distribusi (Network Nodes / ODP)
* Pemetaan titik ODP, kapasitas port, port terpakai, dan IP manajemen perangkat.
* Indikator status operasional visual (*Active*, *Maintenance*, *Down*).

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

* **Backend Framework:** Laravel 11 (PHP 8.2+)
* **Frontend UI:** Blade Templating, Tailwind CSS
* **Database Engine:** MySQL / MariaDB
* **Otentikasi & Keamanan:** Laravel Session Auth, Role-Based Access Control (RBAC), Bcrypt Hash

---

## 🔐 Hak Akses Peran (RBAC)

1. **Administrator (NOC & Billing):** Kontrol penuh atas seluruh master data, ODP, pelanggan, penugasan tiket teknisi, verifikasi invoice, dan ekspor laporan.
2. **Teknisi Lapangan (Technician):** Akses penuh modul ODP, penanganan antrean tiket tugas (*Work Orders*), input riwayat solusi teknis, dan hak *read-only* data pelanggan.
3. **Pelanggan (Customer):** Akses dashboard mandiri (ringkasan paket aktif & jatuh tempo), portal aduan Helpcare, serta transaksi pembayaran tagihan.

---
