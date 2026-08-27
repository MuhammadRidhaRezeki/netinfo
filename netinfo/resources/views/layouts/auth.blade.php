<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk') · NetInfo</title>
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
</head>
<body class="min-h-screen bg-[#fafafa] font-sans text-slate-800 antialiased">
    <div class="grid min-h-screen lg:grid-cols-[minmax(0,0.9fr)_minmax(420px,0.7fr)]">
        <aside class="hidden border-r border-slate-800/80 bg-slate-950 p-10 text-white lg:flex lg:flex-col lg:justify-between xl:p-14">
            <a href="{{ route('home') }}" class="inline-flex">
                <x-brand-logo size="lg" :withText="true" textColor="white" subtext="Network Operation System" />
            </a>

            <div class="max-w-md">
                <p class="font-mono text-xs font-medium uppercase tracking-[0.18em] text-indigo-400">Network operations</p>
                <h1 class="mt-4 text-4xl font-semibold leading-tight tracking-tight">Operasional jaringan, satu sumber kebenaran.</h1>
                <p class="mt-5 text-sm leading-relaxed text-slate-400">Pantau layanan pelanggan, kelola tiket gangguan, dan tangani proses penagihan dari satu portal yang terstruktur.</p>
                <ul class="mt-9 space-y-4 text-sm text-slate-300">
                    <li class="flex items-center gap-3"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Tiket dan riwayat penanganan terpusat</li>
                    <li class="flex items-center gap-3"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Monitoring titik distribusi ODP</li>
                    <li class="flex items-center gap-3"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Billing dan verifikasi pembayaran</li>
                </ul>
            </div>

            <p class="text-xs text-slate-600">&copy; 2026 NetInfo</p>
        </aside>

        <main class="flex items-center justify-center px-5 py-10 sm:px-8">
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
