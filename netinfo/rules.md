# Aturan Sistem, Bisnis, dan Standar Rekayasa (System Rules & Engineering Guidelines)

**Nama Produk:** NetInfo (Network Information & Operation Management System)  
**Tujuan Dokumen:** Menetapkan seluruh aturan bisnis domain (*Business Rules*), batasan otorisasi (*Authorization Rules*), siklus hidup entitas (*Lifecycle State Machines*), dan standar rekayasa perangkat lunak (*Code Conventions*) untuk pengembangan sistem NetInfo.  
**Versi Dokumen:** 1.0 (Final System Rules Blueprint)  

---

## 1. Aturan Bisnis & Logika Domain (System & Business Domain Rules)

### 1.1. Aturan Autentikasi & Otorisasi Pengguna (Authentication & RBAC Rules)

* **`BR-AUTH-01 (Single Account Per Role):`** Sistem memiliki 3 peran baku yang eksklusif: `admin`, `technician`, dan `customer`.
* **`BR-AUTH-02 (Dynamic Redirection):`** Setelah pengguna berhasil login, sistem wajib melakukan pengalihan (*redirection*) otomatis sesuai perannya:
  * Role `admin` $\rightarrow$ `route('admin.dashboard')`
  * Role `technician` $\rightarrow$ `route('technician.dashboard')`
  * Role `customer` $\rightarrow$ `route('customer.dashboard')`
* **`BR-AUTH-03 (Technician Billing Restriction):`** Teknisi **DIBLOKIR TOTAL** dari seluruh route dan fitur Billing / Keuangan. Setiap upaya akses langsung ke URL billing wajib menghasilkan respon `403 Forbidden`.
* **`BR-AUTH-04 (Customer Self-Service Isolation):`** Pelanggan hanya dapat melihat data profilnya sendiri, riwayat tiket gangguannya sendiri, dan tagihan/invoice atas nama dirinya sendiri (*IDOR Prevention*).
* **`BR-AUTH-05 (Self-Registration Initial State):`** Pendaftaran mandiri via `/register` otomatis membuat akun `users` bertipe `customer` dan data `customers` dengan status awal `inactive` hingga dilakukan verifikasi dan jadwal instalasi oleh Admin.
* **`BR-AUTH-06 (Admin-Created Customer):`** Ketika Administrator menambahkan pelanggan baru melalui panel admin, sistem wajib secara otomatis membuat akun login `users` baru (dengan kata sandi default atau yang ditentukan) dalam satu transaksi atomik database.

---

### 1.2. Aturan Manajemen Pelanggan (Customer Management Rules)

* **`BR-CUST-01 (Unique Customer Code):`** Setiap pelanggan wajib memiliki kode identitas unik sekuensial dengan format `CUST-YYYYMM-XXXX`.
* **`BR-CUST-02 (1:1 User-Customer Binding):`** Satu akun `users` bertipe `customer` hanya boleh terhubung dengan tepat 1 entitas `customers` (`user_id` bersifat unik).
* **`BR-CUST-03 (Status Layanan Pelanggan):`**
  * `active`: Layanan internet normal dan berhak mendapatkan penerbitan invoice bulanan.
  * `isolated`: Layanan dibatasi/dinonaktifkan sementara (baik karena isolasi manual nunggak atau akibat kendala ODP *down/maintenance*).
  * `inactive`: Pelanggan baru mendaftar / berhenti berlangganan.
* **`BR-CUST-04 (Aturan Penghapusan Pelanggan / Deletion Guard):`** Data pelanggan **TIDAK DAPAT DIHAPUS** jika sudah memiliki riwayat tagihan/invoice di sistem. Hal ini untuk menjaga konsistensi audit keuangan.
* **`BR-CUST-05 (Otoritas Mutasi Status):`** Hanya Administrator yang berhak mengubah status pelanggan secara manual antara `active`, `isolated`, dan `inactive`.

---

### 1.3. Aturan Infrastruktur Jaringan & Isolasi Bertingkat (Network Node & Cascade Isolation Rules)

* **`BR-NODE-01 (Status Titik ODP):`** Status operasional ODP terdiri dari `active`, `maintenance`, dan `down`.
* **`BR-NODE-02 (Cascade Isolation):`** Ketika status ODP diubah dari `active` menjadi `maintenance` atau `down`:
  1. Sistem secara otomatis mencari seluruh pelanggan terhubung ke ODP tersebut yang berstatus `active`.
  2. Sistem mengubah status seluruh pelanggan tersebut menjadi `isolated`.
  3. Sistem mengisi kolom `isolated_by_node_id` dengan `id` ODP terkait sebagai penanda isolasi sistemik.
* **`BR-NODE-03 (Cascade Restoration):`** Ketika status ODP dikembalikan menjadi `active`:
  1. Sistem hanya mencari pelanggan terhubung yang memiliki nilai `isolated_by_node_id == node.id`.
  2. Sistem memulihkan status mereka menjadi `active` dan mengosongkan kembali kolom `isolated_by_node_id = NULL`.
  3. Pelanggan yang diisolir manual oleh Admin (karena tunggakan) **TIDAK AKAN** ikut terpulihkan secara tidak sengaja.
* **`BR-NODE-04 (Aturan Penghapusan ODP / Deletion Guard):`** Titik ODP tidak dapat dihapus jika masih terdapat pelanggan yang terdaftar pada titik tersebut.
* **`BR-NODE-05 (Hak Akses ODP):`** Administrator dan Teknisi Lapangan sama-sama memiliki izin penuh (*Full CRUD*) untuk mengelola titik ODP guna kebutuhan penyesuaian operasional lapangan.

---

### 1.4. Aturan Layanan Gangguan & Tiket (Trouble Ticketing Lifecycle Rules)

* **`BR-TCK-01 (Mesin Status Tiket Gangguan / State Machine):`**
  Transisi status tiket wajib mengikuti diagram alur berikut:
  $$\text{open} \xrightarrow{\text{Assign / Mulai}} \text{in\_progress} \xrightarrow{\text{Selesai Perbaikan}} \text{resolved} \xrightarrow{\text{Penutupan Akhir}} \text{closed}$$
  * Transisi di luar urutan di atas akan ditolak oleh sistem.
* **`BR-TCK-02 (Penugasan Teknisi Otomatis Mengubah Status):`** Ketika Admin menugaskan teknisi pada tiket yang masih berstatus `open`, sistem wajib otomatis mengubah status tiket menjadi `in_progress`.
* **`BR-TCK-03 (Penyelesaian Tiket Wajib Catatan Solusi):`** Teknisi yang mengubah status tiket menjadi `resolved` **WAJIB** menyertakan catatan solusi teknis minimal 5 karakter. Sistem akan otomatis mengisi timestamp `resolved_at` pada saat yang sama.
* **`BR-TCK-04 (Audit Trail Wajib / Immutable Histories):`** Setiap peristiwa siklus hidup tiket (`created`, `assigned`, `status_changed`, `note_added`) wajib langsung dicatat ke tabel `ticket_histories` bersama `user_id` pelaku dan rincian catatan.
* **`BR-TCK-05 (Scope Penugasan Teknisi):`** Teknisi hanya dapat melihat, mengelola, dan menambahkan log pada tiket-tiket yang ditugaskan secara spesifik kepada dirinya.

---

### 1.5. Aturan Penagihan, Pembayaran & Faktur (Billing & Invoicing Rules)

* **`BR-BIL-01 (Pembangkitan Invoice Bulanan Batch):`** Administrator dapat menerbitkan invoice bulanan secara massal untuk periode `YYYY-MM`. Invoice hanya diterbitkan bagi pelanggan yang berstatus `active`.
* **`BR-BIL-02 (Pencegahan Duplikasi Tagihan):`** Sistem menjamin 1 pelanggan hanya memiliki 1 invoice per bulan tagihan (`UNIQUE(customer_id, billing_month)`). Pelanggan yang sudah memiliki invoice pada periode tersebut otomatis dilewati (*skipped*).
* **`BR-BIL-03 (Jatuh Tempo Standar):`** Tanggal jatuh tempo (*due date*) invoice bulanan ditetapkan secara otomatis pada tanggal 25 di bulan berjalan (`YYYY-MM-25`).
* **`BR-BIL-04 (Metode Pembayaran Resmi):`** Pelanggan dapat memilih salah satu dari 2 metode resmi:
  1. **Transfer Bank:** Rekening SeaBank `9981237810913` a.n. **Muhammad Ridha Rezeki**.
  2. **QRIS Dinamis:** Memindai QRIS resmi NetInfo dari aset sistem (`public/images/qris.jpg`).
* **`BR-BIL-05 (Unggah Bukti Bayar):`** Pelanggan mengunggah berkas bukti transfer dengan validasi format (`jpg, jpeg, png, pdf`) dan ukuran maksimal 2 MB.
* **`BR-BIL-06 (Verifikasi Admin):`**
  * **Approve:** Mengubah status invoice menjadi `paid` dan mencatat waktu `payment_date = now()`.
  * **Reject:** Menghapus referensi berkas `payment_proof` dan `payment_method` dari database sehingga pelanggan dapat melakukan unggah ulang bukti yang valid.
* **`BR-BIL-07 (Cetak Faktur Resmi A4):`** Faktur cetak individual (`GET /invoices/{id}/print`) harus berstandar format cetak A4 (`@media print`) yang memuat rincian identitas NetInfo, kode invoice, nama & alamat pelanggan, titik ODP terhubung, detail paket, rincian biaya, serta cap stempel resmi (**LUNAS** atau **BELUM LUNAS**).

---

## 2. Standar Rekayasa Perangkat Lunak & Konvensi Kode (Engineering & Code Conventions)

### 2.1. Standar Pemrograman PHP & Laravel
1. **Standar PSR-12:** Seluruh kode PHP harus mengikuti standar penulisan *PSR-12 Extended Coding Style*.
2. **Strict Typing & Type Hinting:** Gunakan deklarasi tipe data eksplisit pada parameter method dan return type controller/model.
3. **Pemisahan Tanggung Jawab (Thin Controller, Rich Model):**
   * Controller hanya bertugas memvalidasi request, memanggil model/service, dan mengembalikan view atau redirect response.
   * Logika kueri pencarian, filter, dan relasi harus didefinisikan di dalam Model atau Scope (`scopeFilter`).
   * Logika pembuatan kode sekuensial didelegasikan ke helper class `App\Support\Codes`.

### 2.2. Integritas Transaksi Basis Data (Database Transactions)
Semua operasi mutasi yang melibatkan lebih dari 1 tabel **WAJIB** dibungkus dalam `DB::transaction()`:
```php
// Contoh standar mutasi pelanggan + user
$customer = DB::transaction(function () use ($data) {
    $user = User::create([...]);
    return Customer::create([...]);
});
```

### 2.3. Optimasi Kueri & Pencegahan Masalah N+1 (Eager Loading)
Dilarang melakukan loop kueri relasi di dalam file Blade view tanpa melakukan *Eager Loading* di controller:
```php
// Standar yang diwajibkan:
$tickets = Ticket::with(['customer.user', 'customer.node', 'technician'])->get();
$customers = Customer::with(['user', 'package', 'node'])->get();
```

### 2.4. Standar Validasi Input & Pesan Bahasa Indonesia
Setiap request yang memodifikasi state wajib divalidasi dengan pesan error yang ramah dan berbahasa Indonesia:
```php
$data = $request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'unique:users,email'],
], [
    'name.required' => 'Nama lengkap wajib diisi.',
    'email.required' => 'Alamat email wajib diisi.',
    'email.unique' => 'Email sudah terdaftar dalam sistem.',
]);
```

### 2.5. Standar Keamanan Berkas (File Upload Security)
1. Berkas unggahan bukti bayar wajib divalidasi MIME type dan ekstensi (`mimes:jpg,jpeg,png,pdf`).
2. Batas ukuran berkas tidak boleh melebihi 2048 KB (2 MB).
3. Berkas disimpan di disk terproteksi (`storage/app/public/proofs/`).
4. Pengunduhan dan penampilan berkas bukti bayar dilindungi pengecekan otorisasi: hanya Admin dan Pelanggan pemilik tagihan yang dapat mengaksesnya.

### 2.6. Standar Desain Antarmuka & Token Styling (UI Guidelines)
1. **Desain Mobile-First & Responsif:** Seluruh tabel data harus dibungkus dalam kontainer `overflow-x-auto`.
2. **Indikator Visual & Status Badge:**
   * **Active / Paid / Low Priority:** Badge Hijau (`bg-emerald-100 text-emerald-800 border-emerald-300`).
   * **Maintenance / Pending / Medium Priority:** Badge Kuning/Amber (`bg-amber-100 text-amber-800 border-amber-300`).
   * **Down / Isolated / Unpaid / High Priority:** Badge Merah (`bg-rose-100 text-rose-800 border-rose-300`).
   * **Resolved / Info:** Badge Biru/Indigo (`bg-indigo-100 text-indigo-800 border-indigo-300`).
3. **Umpan Balik Instan (Flash Alerts):** Seluruh aksi controller yang menghasilkan redirect wajib menyertakan flash message `->with('success', ...)` atau `->with('error', ...)`.
