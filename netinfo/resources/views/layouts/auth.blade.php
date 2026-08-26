<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk') · NetInfo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] } } },
        };
    </script>
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">
    <div class="flex min-h-screen">
        <aside class="relative hidden w-[46%] flex-col justify-between overflow-hidden bg-gradient-to-br from-indigo-700 via-indigo-800 to-slate-900 p-10 text-white lg:flex xl:p-14">
            <div class="absolute -left-24 -top-24 h-72 w-72 rounded-full bg-indigo-500/30 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-96 w-96 translate-x-1/3 translate-y-1/3 rounded-full bg-indigo-400/20 blur-3xl"></div>

            <div class="relative flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/20 backdrop-blur">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.288 16.038a5.25 5.25 0 017.433 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"/></svg>
                </span>
                <div>
                    <p class="text-xl font-extrabold tracking-tight">NetInfo</p>
                    <p class="text-xs font-medium text-indigo-200">Network Information &amp; Operation Management</p>
                </div>
            </div>

            <div class="relative max-w-md">
                <h1 class="text-3xl font-extrabold leading-tight tracking-tight xl:text-4xl">Pusat Kendali Operasional Jaringan &amp; Layanan Pelanggan Terpadu.</h1>
                <p class="mt-4 text-sm leading-relaxed text-indigo-200">Satu portal terintegrasi untuk pemantauan titik distribusi ODP, penanganan tiket kendala teknis, dan administrasi tagihan pelanggan secara real-time.</p>
                <ul class="mt-8 space-y-3 text-sm text-indigo-100">
                    <li class="flex items-center gap-3"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-300"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span> Manajemen Trouble Ticketing &amp; Disposisi Teknisi</li>
                    <li class="flex items-center gap-3"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-300"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span> Administrasi Penagihan &amp; Verifikasi Pembayaran</li>
                    <li class="flex items-center gap-3"><span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-300"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span> Monitoring Status Titik Distribusi Node (ODP)</li>
                </ul>
            </div>

            <p class="relative text-xs text-indigo-300">&copy; 2026 NetInfo · Mockup Antarmuka v1.0</p>
        </aside>

        <main class="flex flex-1 items-center justify-center p-5 sm:p-8">
            <div class="w-full max-w-md">
                @hasSection('auth_card')
                    @yield('auth_card')
                @else
                    @yield('content')
                @endif
            </div>
        </main>
    </div>
    @stack('modals')
</body>
</html>
