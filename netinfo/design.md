# UI/UX Design System Specification — NetInfo

**Prinsip Desain:** Minimalist Design Engineering, Precision Spacing, High Contrast Typography, Clean Micro-interactions (Gaya chanhdai.com / shadcn / Vercel).  
**Basis Teknologi:** Tailwind CSS, Inter / Geist Sans font, Lucide / Heroicons SVG icons.

---

## 1. Design Tokens & Color Palette

* **Canvas & Surface Tokens:**
  - Base Page Background: `bg-[#fafafa]` (Light) / `bg-slate-50`
  - Container / Card Surface: `bg-white border border-slate-200/90 shadow-[0_1px_2px_rgba(0,0,0,0.04)] rounded-xl`
  - Subtle Surface: `bg-slate-100/70 border border-slate-200`
  - Sidebar Surface: `bg-slate-950 text-slate-400 border-r border-slate-800/80`
* **Typography Hierarchy:**
  - Headings: `font-semibold tracking-tight text-slate-900`
  - Body Text: `text-sm text-slate-600 leading-relaxed`
  - Muted / Secondary: `text-xs text-slate-400 font-normal`
  - Monospace Data (IP, ID Tiket, No Invoice, Port, Speed): `font-mono text-xs tracking-tight font-medium text-slate-700`
* **Brand & Action Buttons:**
  - Primary Button: `rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all`
  - Secondary Button: `rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-800 hover:bg-slate-50 active:scale-[0.99] transition-all`
  - Danger Button (Isolir / Reject): `rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-100 transition-all`
* **Micro-Badge Status Indicators (Soft Tinted & Monospaced Dot):**
  - **Active / Resolved / Paid:** `inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200`
  - **Maintenance / Pending Verification:** `inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200`
  - **Down / Isolated / Unpaid / High Priority:** `inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200`
  - **Open / In Progress / Info:** `inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-50 text-sky-700 border border-sky-200`

---

## 2. Component Guidelines & Styling Standards

### A. App Shell & Layout (`layouts/app.blade.php`)
* **Sidebar:**
  - Fixed-width minimalis (`w-64`), latar gelap pekat (`bg-slate-950`).
  - Active Menu Item: `bg-slate-900 text-white font-medium` dengan aksen border kiri tipis `border-l-2 border-indigo-500`.
  - Inactive Menu Item: `text-slate-400 hover:text-slate-200 hover:bg-slate-900/50 transition-colors`.
* **Top Header:**
  - Header melayang transparan (`sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200/80 px-6 py-3`).
  - User Dropdown: Avatar inisial minimalis dengan dropdown menu yang clean (`shadow-lg ring-1 ring-black/5 rounded-xl`).

### B. Metric Summary Cards
* Card minimalis ber-border tipis: Container ikon kecil `p-2 rounded-lg bg-slate-100 text-slate-700`, label metrik abu-abu `text-xs font-medium text-slate-500 uppercase tracking-wider`, dan angka statistik `text-2xl font-bold tracking-tight text-slate-900`.

### C. Data Tables & Scrollers
* Wrapper: Wajib menggunakan `<div class="w-full overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">`.
* Table Tag: Memiliki class `min-w-[800px] w-full text-left text-sm`.
* Table Head: `bg-slate-50/80 text-xs font-mono uppercase tracking-wider text-slate-500 border-b border-slate-200 px-4 py-3`.
* Table Row: `border-b border-slate-100 last:border-0 hover:bg-slate-50/60 transition-colors px-4 py-3`.

### D. Input Form & Inputs
* Input Style: `rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all`.
* Password Input: Memiliki tombol ikon mata (eye toggle) yang disematkan di dalam kontainer input.

---

## 3. Page-Specific Guidelines

### 1. Public Landing Page (`welcome.blade.php`)
* Hero banner yang bersih dengan headline tegas mengenai keandalan fiber & NOC terintegrasi.
* Grid 3 paket internet responsif: paket paling populer ditandai dengan border `border-slate-900 shadow-md`.
* Tombol CTA navigasi cepat langsung mengarah ke `route('login')`.

### 2. Login Page (`auth/login.blade.php`)
* Card login terpusat (centered) yang rapi tanpa elemen berlebih.
* Sediakan tombol kembali ke landing page publik dan toggle show/hide password.

### 3. Client Dashboard & Helpcare
* Banner status koneksi (*Active / Isolated*) yang ringkas.
* Kartu informasi paket langganan aktif beserta tanggal jatuh tempo.
* Modal pembayaran interaktif (SeaBank & QRIS) dengan form unggah bukti transfer yang elegan.
* Helpcare: Form aduan terstruktur dengan radio button prioritas yang responsif dan indikator status tiket yang bersih.

### 4. Technician Workspace & Work Orders
* Daftar antrean tiket perbaikan yang ditugaskan (*Work Orders*) dengan log timeline tindakan teknis yang runtut.
* Modul pemetaan titik ODP dengan indikator kapasitas port yang akurat.

### 5. Invoice Print View (`invoices/print.blade.php`)
* Layout struk/faktur individual berstandar formal A4.
* Kop surat resmi NetInfo, rincian pembayaran, dan stempel digital lunas (*PAID*).
* Dilengkapi aturan `@media print` untuk menyembunyikan tombol cetak fisik saat mencetak.

---

## 4. Frontend Guardrails (STRICT)
1. DILARANG mengubah Controller, Model, Migration, Service, dan Routes backend.
2. Pertahankan seluruh atribut form (`action`, `method`, `@csrf`, `name`).
3. Pertahankan semua variabel Blade (`$tickets`, `$customers`, `$invoices`, `$nodes`, `$packages`, dll).
4. Gunakan `{!! ... !!}` hanya untuk konten HTML yang terpercaya, jangan biarkan tag HTML muncul sebagai teks mentah pada badge.