<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NetInfo · Internet Cepat & Transparan untuk Wilayah Anda</title>
    <meta name="description" content="NetInfo — layanan ISP lokal dengan jaringan terpantau NOC 24 jam, lapor gangguan Helpcare, dan billing mudah.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] } } },
        };
    </script>
    <style>
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="min-h-screen bg-white font-sans text-slate-800 antialiased">
    @php
        $navLogo = '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.288 16.038a5.25 5.25 0 017.433 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"/></svg>';
        $pkgMeta = [
            ['badge' => 'bg-slate-100 text-slate-600 ring-slate-500/20',   'ring' => '', 'tag' => null,          'cta' => 'bg-slate-800 hover:bg-slate-700'],
            ['badge' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20', 'ring' => '', 'tag' => null,          'cta' => 'bg-slate-800 hover:bg-slate-700'],
            ['badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'ring' => 'ring-2 ring-indigo-600', 'tag' => 'Paling Laris', 'cta' => 'bg-indigo-600 shadow-lg shadow-indigo-600/30 hover:bg-indigo-500'],
            ['badge' => 'bg-violet-50 text-violet-700 ring-violet-600/20', 'ring' => '', 'tag' => null,          'cta' => 'bg-slate-800 hover:bg-slate-700'],
        ];
    @endphp

    <header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/85 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 shadow-sm shadow-indigo-600/30 text-white">{!! $navLogo !!}</span>
                <span class="text-lg font-extrabold tracking-tight text-slate-900">NetInfo</span>
            </a>
            <nav class="hidden items-center gap-7 text-sm font-medium text-slate-600 md:flex">
                <a href="#paket" class="transition hover:text-indigo-600">Paket Layanan</a>
                <a href="#keunggulan" class="transition hover:text-indigo-600">Keunggulan</a>
                <a href="#kontak" class="transition hover:text-indigo-600">Kontak</a>
            </nav>
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-indigo-600/30 transition hover:bg-indigo-500">
                Masuk ke Portal
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </header>

    <main>
        <section class="relative overflow-hidden bg-gradient-to-br from-indigo-700 via-indigo-800 to-slate-900 text-white">
            <div class="absolute -left-24 -top-24 h-72 w-72 rounded-full bg-indigo-500/30 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-96 w-96 translate-x-1/3 translate-y-1/3 rounded-full bg-indigo-400/20 blur-3xl"></div>

            <div class="relative mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:py-28">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-indigo-100 ring-1 ring-inset ring-white/20 backdrop-blur">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                    </span>
                    Layanan Internet Fiber Optik &amp; NOC Terintegrasi
                </span>

                <h1 class="mt-6 max-w-3xl text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                    Koneksi Internet Cepat, Layanan Responsif, Tagihan Transparan.
                </h1>
                <p class="mt-5 max-w-2xl text-base leading-relaxed text-indigo-200 sm:text-lg">
                    NetInfo menghadirkan koneksi fiber optik berkecepatan tinggi dan stabil untuk kebutuhan residensial maupun bisnis. Didukung sistem Network Operations Center (NOC) terintegrasi untuk penanganan keluhan yang cepat dan pencatatan tagihan yang mudah.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="#paket"
                        class="rounded-lg bg-white px-5 py-2.5 text-sm font-bold text-indigo-700 shadow-lg transition hover:bg-indigo-50">
                        Lihat Paket Layanan
                    </a>
                    <a href="{{ route('login') }}"
                        class="rounded-lg border border-white/30 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/10">
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
                            <dt class="text-xl font-extrabold tracking-tight">{{ $v }}</dt>
                            <dd class="mt-1 text-sm leading-relaxed text-indigo-200">{{ $l }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </section>

        <section id="paket" class="scroll-mt-20 bg-slate-50 py-20 lg:py-24">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-sm font-bold uppercase tracking-widest text-indigo-600">Paket Layanan</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Pilih paket sesuai kebutuhan Anda</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-500">Semua paket menggunakan jaringan fiber optik dengan harga bulanan flat tanpa biaya tersembunyi.</p>
                </div>

                @if ($packages->isEmpty())
                    <p class="mt-12 rounded-xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center text-sm italic text-slate-400">Belum ada paket layanan yang tersedia saat ini.</p>
                @else
                    <div class="mt-12 grid gap-6 sm:grid-cols-2 {{ $packages->count() >= 3 ? 'lg:grid-cols-4' : '' }}">
                        @foreach ($packages as $i => $p)
                            @php $meta = $pkgMeta[$i % count($pkgMeta)]; @endphp
                            <article class="relative flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/60 {{ $meta['ring'] }}">
                                @if ($meta['tag'])
                                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-indigo-600 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-white shadow">{{ $meta['tag'] }}</span>
                                @endif
                                <span class="inline-flex w-fit items-center gap-1.5 rounded-md px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $meta['badge'] }}">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    {{ $p->speed_mbps }} Mbps
                                </span>
                                <h3 class="mt-4 text-lg font-bold text-slate-900">{{ $p->name }}</h3>
                                <p class="mt-2 min-h-[40px] text-sm leading-relaxed text-slate-500">{{ $p->description }}</p>
                                <p class="mt-5 text-2xl font-extrabold tracking-tight text-slate-900">
                                    Rp {{ number_format((float) $p->price, 0, ',', '.') }}
                                    <span class="text-sm font-medium text-slate-400">/bln</span>
                                </p>
                                <ul class="mt-4 space-y-2 text-sm text-slate-600">
                                    <li class="flex items-center gap-2"><svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Unlimited kuota</li>
                                    <li class="flex items-center gap-2"><svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Terhubung node fiber lokal</li>
                                    <li class="flex items-center gap-2"><svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Support via portal Helpcare</li>
                                </ul>
                                <a href="{{ route('register', ['package' => $p->id]) }}"
                                    class="mt-6 inline-flex justify-center rounded-lg px-4 py-2.5 text-sm font-semibold text-white transition {{ $meta['cta'] }}">
                                    Berlangganan
                                </a>
                            </article>
                        @endforeach
                    </div>
                    <p class="mt-8 text-center text-xs text-slate-400">Harga sewaktu-waktu dapat berubah · Pemasangan di area cakupan node NetInfo</p>
                @endif
            </div>
        </section>

        <section id="keunggulan" class="scroll-mt-20 py-20 lg:py-24">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-sm font-bold uppercase tracking-widest text-indigo-600">Keunggulan</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Dikelola sistem, bukan seadanya</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-500">Portal pelanggan NetInfo membuat semua urusan layanan Anda bisa dilacak dari satu tempat.</p>
                </div>

                <div class="mt-12 grid gap-6 md:grid-cols-2">
                    <article class="group rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-8 shadow-sm transition hover:border-indigo-200 hover:shadow-xl hover:shadow-slate-200/60">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-600 transition group-hover:scale-110">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        </span>
                        <h3 class="mt-5 text-xl font-bold text-slate-900">Helpcare — Lapor Gangguan Seketika</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-500">
                            Internet lambat atau putus total? Buat tiket gangguan langsung dari portal. Setiap laporan mendapat nomor tiket, status yang bisa dipantau (diterima → dikerjakan → selesai), dan ditangani teknisi lapangan terdekat. Tidak perlu telepon berulang kali.
                        </p>
                    </article>

                    <article class="group rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-8 shadow-sm transition hover:border-indigo-200 hover:shadow-xl hover:shadow-slate-200/60">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 transition group-hover:scale-110">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        </span>
                        <h3 class="mt-5 text-xl font-bold text-slate-900">Billing Mudah & Tertib</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-500">
                            Tagihan bulanan dibuat otomatis sesuai paket Anda. Unduh invoice, unggah bukti pembayaran, dan tunggu verifikasi admin — semuanya tercatat rapi di portal. Riwayat pembayaran lengkap bisa dilihat kapan saja.
                        </p>
                    </article>
                </div>

                <div class="mt-6 overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-800 p-8 text-white shadow-xl shadow-indigo-600/20 sm:p-10">
                    <div class="flex flex-col items-start justify-between gap-6 lg:flex-row lg:items-center">
                        <div class="max-w-xl">
                            <h3 class="text-xl font-bold">Transparansi jaringan real-time</h3>
                            <p class="mt-2 text-sm leading-relaxed text-indigo-100">
                                Setiap titik distribusi (node ODP) kami tercatat dengan status operasionalnya. Saat ada pemeliharaan atau gangguan jaringan, pelanggan terdampak langsung teridentifikasi dan diinformasikan — bukan menunggu keluhan masuk.
                            </p>
                        </div>
                        <a href="{{ route('login') }}"
                            class="shrink-0 rounded-lg bg-white px-5 py-2.5 text-sm font-bold text-indigo-700 shadow transition hover:bg-indigo-50">
                            Cek Portal Anda
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="kontak" class="scroll-mt-20 border-t border-slate-200 bg-slate-900 text-slate-300">
        <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6">
            <div class="grid gap-10 md:grid-cols-3">
                <div>
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white">{!! $navLogo !!}</span>
                        <span class="text-lg font-extrabold tracking-tight text-white">NetInfo</span>
                    </div>
                    <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate-400">Network Information & Operation Management. ISP lokal yang mengutamakan transparansi jaringan dan kemudahan pelanggan.</p>
                </div>

                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">Kontak Operasional</h3>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            <a href="mailto:noc@netinfo.local" class="transition hover:text-white">noc@netinfo.local</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            <span>NOC Hotline (0641) 123-456</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Layanan gangguan 24 jam · Admin 08.00–17.00 WIB</span>
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

            <div class="mt-12 border-t border-slate-800 pt-6 text-xs text-slate-500">
                <p>&copy; 2026 NetInfo · Semua hak dilindungi · Mockup Antarmuka v1.0</p>
            </div>
        </div>
    </footer>
</body>
</html>
