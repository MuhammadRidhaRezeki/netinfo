<div align="center">

# 🌐 NetInfo

### *Network Information & Operation Management System*

Sistem Informasi Manajemen Operasional Terpadu untuk Penyedia Layanan Internet Lokal

![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP_8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS_4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)

</div>

---

## 📖 Tentang NetInfo

**NetInfo** adalah aplikasi web berbasis **Laravel** yang dirancang untuk penyedia layanan internet skala lokal — ISP kecil, RT/RW Net, hingga unit NOC (*Network Operations Center*). Sistem ini menyatukan tiga pilar operasional yang selama ini terfragmentasi menjadi satu platform terpusat:

> Pelaporan gangguan lewat chat pribadi sering terabaikan, validasi pembayaran bulanan memakan waktu, dan sebaran pelanggan di titik distribusi sulit dipantau. NetInfo menyelesaikan ketiganya.

---

## ✨ Fitur Utama

### 🎫 Helpcare — Trouble Ticketing Lifecycle
- **Pelaporan mandiri** oleh pelanggan dengan tingkat prioritas (*Low / Medium / High*) & kode tiket otomatis
- **Disposisi teknisi** oleh Admin NOC ke teknisi lapangan (*work orders*)
- **Log penanganan bertahap** oleh teknisi hingga status *Resolved*
- **Audit trail otomatis** — setiap mutasi status terekam di log sistem

### 💳 Billing & Faktur Individual
- **Pembangkitan tagihan otomatis** untuk seluruh pelanggan aktif
- **Portal pembayaran interaktif**: Transfer Bank (SeaBank) & QRIS dinamis + unggah bukti bayar (`.jpg/.png/.pdf`)
- **Verifikasi pembayaran** oleh Admin (*Approve / Reject*)
- **Faktur resmi siap cetak** format A4 per transaksi dengan stempel digital LUNAS
- **Kontrol isolir** pelanggan menunggak + pemulihan status layanan

### 📡 Manajemen Titik Distribusi (Network Nodes / ODP)
- Pemetaan titik ODP: kode node, lokasi, kapasitas port, port terpakai, IP manajemen
- Indikator status operasional: 🟢 *Active* · 🟡 *Maintenance* · 🔴 *Down*

---

## 🔐 Hak Akses Peran (RBAC)

| Peran | Hak Akses |
|---|---|
| 👨‍💼 **Administrator** (NOC & Billing) | Kontrol penuh master data paket, ODP, pelanggan, penugasan tiket teknisi, verifikasi invoice, isolir/pemulihan pelanggan, generate tagihan & ekspor laporan |
| 🔧 **Teknisi Lapangan** | Pengelolaan penuh modul ODP, antrean *work order* tiket yang ditugaskan, input riwayat solusi teknis · *read-only* data pelanggan · tanpa akses modul Billing |
| 👤 **Pelanggan** | Dashboard mandiri (paket aktif & jatuh tempo), portal aduan Helpcare, pembayaran tagihan (SeaBank/QRIS), cetak faktur lunas |

---

## 🛠️ Teknologi

| Layer | Teknologi |
|---|---|
| Backend | Laravel 12 · PHP 8.2+ |
| Frontend | Blade Templating · Tailwind CSS 4 · Vite |
| Database | MySQL / MariaDB |
| Keamanan | Session Auth · Role-Based Access Control · Bcrypt |

---

## 📁 Struktur Repositori

```
📦 netinfo (repo root)
├── 📁 Data/            → Dokumentasi pendukung (daftar akun dummy, catatan bug)
└── 📁 netinfo/         → Aplikasi utama (Laravel Project)
    ├── app/            → Controllers, Models, Middleware
    ├── resources/views → Blade templates (admin, technician, customer, tickets, invoices...)
    ├── routes/         → Definisi rute berbasis role
    ├── database/       → Migrations & Seeders (data dummy)
    └── PRD.md, architecture.md, schema.md → Dokumen rekayasa perangkat lunak
```

---

## 🚀 Cara Menjalankan

**Prasyarat:** PHP ≥ 8.2 · Composer · Node.js & npm · MySQL/MariaDB

```bash
# 1. Clone repositori
git clone https://github.com/MuhammadRidhaRezeki/netinfo.git
cd netinfo/netinfo

# 2. Install dependency backend & frontend
composer install
npm install

# 3. Siapkan database
#    - Buat database baru bernama `netinfo`
#    - Sesuaikan kredensial DB pada file .env bila diperlukan
php artisan key:generate
php artisan migrate --seed     # membangun skema + mengisi data dummy

# 4. Build aset frontend
npm run build

# 5. Jalankan server
php artisan serve
```

Aplikasi terbuka di **http://localhost:8000** 🎉

---

## 📚 Dokumentasi Lanjutan

| Dokumen | Isi |
|---|---|
| [`netinfo/PRD.md`](netinfo/PRD.md) | Product Requirement Document — kebutuhan fungsional per modul |
| [`netinfo/architecture.md`](netinfo/architecture.md) | Arsitektur perangkat lunak (MVC Monolitik) |
| [`netinfo/schema.md`](netinfo/schema.md) | Skema basis data |
| [`Data/BUG.md`](Data/BUG.md) | Catatan bug & permintaan perubahan selama pengembangan |

---

## 👥 Tim Pengembang

| | |
|---|---|
| **Muhammad Ridha Rezeki** | [@MuhammadRidhaRezeki](https://github.com/MuhammadRidhaRezeki) |
| **Rausyanul Fikri** | |

---

<div align="center">

*Proyek ini dikembangkan sebagai pemenuhan tugas mata kuliah Rekayasa Perangkat Lunak — Semester 5.*

⭐ Jangan lupa beri star jika proyek ini bermanfaat!

</div>
