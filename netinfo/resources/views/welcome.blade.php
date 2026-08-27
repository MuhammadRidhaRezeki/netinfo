<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NetInfo · Internet Cepat & Transparan untuk Wilayah Anda</title>
    <meta name="description" content="NetInfo — layanan ISP lokal dengan jaringan terpantau NOC 24 jam, lapor gangguan Helpcare, dan billing mudah.">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] } } },
        };
    </script>
    <style> html { scroll-behavior: smooth; } </style>
</head>
<body class="min-h-screen bg-[#fafafa] font-sans text-slate-800 antialiased">
    @php
        $navLogo = '<svg class="h-5 w-5" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="welcomeNetGrad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#00f0ff"/><stop offset="35%" stop-color="#38bdf8"/><stop offset="70%" stop-color="#a855f7"/><stop offset="100%" stop-color="#ec4899"/></linearGradient><linearGradient id="welcomePulseGrad" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#00f0ff"/><stop offset="50%" stop-color="#ffffff"/><stop offset="100%" stop-color="#ec4899"/></linearGradient></defs><path d="M12 36V14C12 9.5 16 8.5 19 11.5L25.5 19" stroke="url(#welcomeNetGrad)" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M22.5 29L29 36.5C32 39.5 36 38.5 36 34V12" stroke="url(#welcomeNetGrad)" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 24H19.5L21.5 18L24.5 30L26.5 24H33" stroke="url(#welcomePulseGrad)" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="36" r="1.8" fill="#00f0ff"/><circle cx="36" cy="12" r="1.8" fill="#ec4899"/><circle cx="23" cy="24" r="1.4" fill="#ffffff"/></svg>';
    @endphp

    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-900 text-white">{!! $navLogo !!}</span>
                <span class="text-lg font-bold tracking-tight text-slate-900">NetInfo</span>
            </a>
            <nav class="hidden items-center gap-7 text-sm font-medium text-slate-600 md:flex">
                <a href="#paket" class="transition hover:text-slate-900">Paket Layanan</a>
                <a href="#keunggulan" class="transition hover:text-slate-900">Keunggulan</a>
                <a href="#kontak" class="transition hover:text-slate-900">Kontak</a>
            </nav>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">
                Masuk ke Portal
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </header>

    <main>
        <section class="relative overflow-hidden border-b border-slate-200/80 bg-slate-950 text-white">
            <div class="relative mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:py-28">
                <span class="inline-flex items-center gap-2 rounded-full border border-slate-700 bg-slate-900 px-3.5 py-1.5 text-xs font-medium text-slate-300">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                    </span>
                    Layanan Internet Fiber Optik &amp; NOC Terintegrasi
                </span>

                <h1 class="mt-6 max-w-3xl text-4xl font-semibold leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                    Koneksi internet cepat, layanan responsif, tagihan transparan.
                </h1>
                <p class="mt-5 max-w-2xl text-base leading-relaxed text-slate-400 sm:text-lg">
                    NetInfo menghadirkan koneksi fiber optik berkecepatan tinggi dan stabil untuk kebutuhan residensial maupun bisnis. Didukung sistem Network Operations Center (NOC) terintegrasi untuk penanganan keluhan yang cepat dan pencatatan tagihan yang mudah.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="#paket" class="rounded-lg bg-white px-5 py-2.5 text-sm font-bold text-slate-900 shadow-sm transition hover:bg-slate-100">
                        Lihat Paket Layanan
                    </a>
                    <a href="{{ route('login') }}" class="rounded-lg border border-slate-700 px-5 py-2.5 text-sm font-medium text-slate-300 transition hover:bg-slate-900 hover:text-white">
                        Masuk ke Portal
                    </a>
                </div>

                <dl class="mt-14 grid max-w-2xl grid-cols-1 gap-x-10 gap-y-6 sm:grid-cols-3">
                    @foreach ([
                        ['v' => 'Fiber Optik', 'l' => 'Koneksi stabil hingga 100 Mbps'],
                        ['v' => 'Real-time', 'l' => 'Status node & ODP selalu terpantau'],
                        ['v' => 'Digital', 'l' => 'Tagihan & bukti bayar dalam satu portal'],
                    ] as ['v' => $v, 'l' => $l])
                        <div>
                            <dt class="text-xl font-bold tracking-tight">{{ $v }}</dt>
                            <dd class="mt-1 text-sm leading-relaxed text-slate-400">{{ $l }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </section>

        <section id="paket" class="scroll-mt-20 py-20 lg:py-24">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="font-mono text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Paket Layanan</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Pilih paket sesuai kebutuhan Anda</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-500">Semua paket menggunakan jaringan fiber optik dengan harga bulanan flat tanpa biaya tersembunyi.</p>
                </div>

                @if ($packages->isEmpty())
                    <p class="mt-12 rounded-xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center text-sm italic text-slate-400 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">Belum ada paket layanan yang tersedia saat ini.</p>
                @else
                    <div class="mt-12 grid gap-6 sm:grid-cols-2 {{ $packages->count() >= 3 ? 'lg:grid-cols-4' : '' }}">
                        @foreach ($packages as $i => $p)
                            @php
                                $isPopular = $p->speed_mbps >= 50;
                            @endphp
                            <article class="relative flex flex-col rounded-xl border {{ $isPopular ? 'border-slate-900 shadow-md' : 'border-slate-200/90 shadow-[0_1px_2px_rgba(0,0,0,0.04)]' }} bg-white p-6 transition hover:-translate-y-0.5 hover:shadow-lg">
                                @if ($isPopular)
                                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-slate-900 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-white shadow-sm">Paling Laris</span>
                                @endif
                                <span class="inline-flex w-fit items-center gap-1.5 rounded-full border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    {{ $p->speed_mbps }} Mbps
                                </span>
                                <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ $p->name }}</h3>
                                <p class="mt-2 min-h-[40px] text-sm leading-relaxed text-slate-500">{{ $p->description }}</p>
                                <p class="mt-5 text-2xl font-bold tracking-tight text-slate-900">
                                    Rp {{ number_format((float) $p->price, 0, ',', '.') }}
                                    <span class="text-sm font-medium text-slate-400">/bln</span>
                                </p>
                                <ul class="mt-4 space-y-2 text-sm text-slate-600">
                                    <li class="flex items-center gap-2"><svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Unlimited kuota</li>
                                    <li class="flex items-center gap-2"><svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Terhubung node fiber lokal</li>
                                    <li class="flex items-center gap-2"><svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Support via portal Helpcare</li>
                                </ul>
                                <a href="{{ route('register', ['package' => $p->id]) }}" class="mt-6 inline-flex justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white shadow-sm transition {{ $isPopular ? 'bg-slate-900 hover:bg-slate-800' : 'bg-slate-800 hover:bg-slate-700' }}">
                                    Berlangganan
                                </a>
                            </article>
                        @endforeach
                    </div>
                    <p class="mt-8 text-center text-xs text-slate-400">Harga sewaktu-waktu dapat berubah &middot; Pemasangan di area cakupan node NetInfo</p>
                @endif
            </div>
        </section>

        <section id="keunggulan" class="scroll-mt-20 border-t border-slate-200/80 bg-white py-20 lg:py-24">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="font-mono text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Keunggulan</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Dikelola sistem, bukan seadanya</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-500">Portal pelanggan NetInfo membuat semua urusan layanan Anda bisa dilacak dari satu tempat.</p>
                </div>

                <div class="mt-12 grid gap-6 md:grid-cols-2">
                    <article class="rounded-xl border border-slate-200/90 bg-white p-8 shadow-[0_1px_2px_rgba(0,0,0,0.04)] transition hover:border-slate-300 hover:shadow-md">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-50 text-rose-600 ring-1 ring-rose-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-slate-900">Helpcare — Lapor Gangguan Seketika</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-500">
                            Internet lambat atau putus total? Buat tiket gangguan langsung dari portal. Setiap laporan mendapat nomor tiket, status yang bisa dipantau (diterima → dikerjakan → selesai), dan ditangani teknisi lapangan terdekat. Tidak perlu telepon berulang kali.
                        </p>
                    </article>

                    <article class="rounded-xl border border-slate-200/90 bg-white p-8 shadow-[0_1px_2px_rgba(0,0,0,0.04)] transition hover:border-slate-300 hover:shadow-md">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-slate-900">Billing Mudah & Tertib</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-500">
                            Tagihan bulanan dibuat otomatis sesuai paket Anda. Unduh invoice, unggah bukti pembayaran, dan tunggu verifikasi admin — semuanya tercatat rapi di portal. Riwayat pembayaran lengkap bisa dilihat kapan saja.
                        </p>
                    </article>
                </div>

                <div class="mt-6 overflow-hidden rounded-xl border border-slate-200/90 bg-slate-950 p-8 text-white shadow-[0_1px_2px_rgba(0,0,0,0.04)] sm:p-10">
                    <div class="flex flex-col items-start justify-between gap-6 lg:flex-row lg:items-center">
                        <div class="max-w-xl">
                            <h3 class="text-xl font-semibold">Transparansi jaringan real-time</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-400">
                                Setiap titik distribusi (node ODP) kami tercatat dengan status operasionalnya. Saat ada pemeliharaan atau gangguan jaringan, pelanggan terdampak langsung teridentifikasi dan diinformasikan — bukan menunggu keluhan masuk.
                            </p>
                        </div>
                        <a href="{{ route('login') }}" class="shrink-0 rounded-lg bg-white px-5 py-2.5 text-sm font-bold text-slate-900 shadow-sm transition hover:bg-slate-100">
                            Cek Portal Anda
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="kontak" class="scroll-mt-20 border-t border-slate-200/80 bg-slate-950 text-slate-300">
        <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6">
            <div class="grid gap-10 md:grid-cols-3">
                <div>
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-900 text-white ring-1 ring-slate-700">{!! $navLogo !!}</span>
                        <span class="text-lg font-bold tracking-tight text-white">NetInfo</span>
                    </div>
                    <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate-500">Network Information & Operation Management. ISP lokal yang mengutamakan transparansi jaringan dan kemudahan pelanggan.</p>
                </div>

                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">Kontak Operasional</h3>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            <a href="mailto:noc@netinfo.local" class="transition hover:text-white">noc@netinfo.local</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            <span>NOC Hotline (0641) 123-456</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Layanan gangguan 24 jam &middot; Admin 08.00–17.00 WIB</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">Navigasi</h3>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li><a href="#paket" class="transition hover:text-white">Paket Layanan</a></li>
                        <li><a href="#keunggulan" class="transition hover:text-white">Keunggulan</a></li>
                        <li><a href="{{ route('login') }}" class="transition hover:text-white">Masuk ke Portal</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 border-t border-slate-800 pt-6 text-xs text-slate-600">
                <p>&copy; 2026 NetInfo &middot; Semua hak dilindungi</p>
            </div>
        </div>
    </footer>
</body>
</html>
