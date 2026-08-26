<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') · NetInfo</title>
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
@php
    $authUser = auth()->user();
    $isAdmin = $authUser->isAdmin();
    $isTechnician = $authUser->isTechnician();

    $navTicketBadge = 0;
    $navBillingBadge = 0;
    $notifItems = [];

    if ($isAdmin) {
        $openUnassigned = \App\Models\Ticket::where('status', 'open')->whereNull('technician_id')->count();
        $awaitingVerify = \App\Models\Invoice::where('payment_status', 'unpaid')->whereNotNull('payment_proof')->count();
        $nodeDown = \App\Models\NetworkNode::where('status', 'down')->count();
        $navTicketBadge = $openUnassigned;
        $navBillingBadge = $awaitingVerify;

        if ($openUnassigned > 0) {
            $notifItems[] = ['color' => 'bg-red-500', 'text' => "{$openUnassigned} tiket open belum ditugaskan ke teknisi.", 'time' => 'Butuh tindakan', 'url' => route('admin.tickets.index')];
        }
        if ($awaitingVerify > 0) {
            $notifItems[] = ['color' => 'bg-amber-500', 'text' => "{$awaitingVerify} bukti pembayaran menunggu verifikasi.", 'time' => 'Butuh tindakan', 'url' => route('admin.billing.index')];
        }
        if ($nodeDown > 0) {
            $notifItems[] = ['color' => 'bg-red-500', 'text' => "{$nodeDown} network node berstatus Down.", 'time' => 'Pantau jaringan', 'url' => route('admin.network-nodes.index')];
        }

        $nav = [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => request()->routeIs('admin.dashboard'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>'],
            ['label' => 'Data Pelanggan', 'route' => 'admin.customers.index', 'active' => request()->routeIs('admin.customers.*'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>'],
            ['label' => 'Network Nodes', 'route' => 'admin.network-nodes.index', 'active' => request()->routeIs('admin.network-nodes.index'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z"/>'],
            ['label' => 'Tiket Gangguan', 'route' => 'admin.tickets.index', 'active' => request()->routeIs('admin.tickets.index') || request()->routeIs('tickets.show'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/>', 'badge' => $navTicketBadge],
            ['label' => 'Billing & Invoice', 'route' => 'admin.billing.index', 'active' => request()->routeIs('admin.billing.*'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>', 'badge' => $navBillingBadge],
        ];
    } elseif ($isTechnician) {
        $myOpen = \App\Models\Ticket::where('technician_id', $authUser->id)->whereIn('status', ['open', 'in_progress'])->count();
        $navTicketBadge = $myOpen;

        if ($myOpen > 0) {
            $notifItems[] = ['color' => 'bg-red-500', 'text' => "Anda memiliki {$myOpen} work order aktif menunggu pengerjaan.", 'time' => 'Agenda Anda', 'url' => route('technician.tickets.index')];
        }

        $nav = [
            ['label' => 'Dashboard', 'route' => 'technician.dashboard', 'active' => request()->routeIs('technician.dashboard'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>'],
            ['label' => 'Data Pelanggan', 'route' => 'technician.customers.index', 'active' => request()->routeIs('technician.customers.index'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>'],
            ['label' => 'Network Nodes', 'route' => 'technician.network-nodes.index', 'active' => request()->routeIs('technician.network-nodes.index'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z"/>'],
            ['label' => 'Tiket Saya', 'route' => 'technician.tickets.index', 'active' => request()->routeIs('technician.tickets.index') || request()->routeIs('tickets.show'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/>', 'badge' => $navTicketBadge],
        ];
    } else {
        $customer = $authUser->customer;
        $unpaidCount = $customer ? $customer->invoices()->where('payment_status', 'unpaid')->count() : 0;

        if ($unpaidCount > 0) {
            $notifItems[] = ['color' => 'bg-red-500', 'text' => "Anda punya {$unpaidCount} tagihan belum lunas.", 'time' => 'Segera bayar', 'url' => route('customer.dashboard')];
        }
        if ($customer && $customer->tickets()->whereIn('status', ['in_progress'])->exists()) {
            $notifItems[] = ['color' => 'bg-emerald-500', 'text' => 'Teknisi sedang menangani laporan Anda.', 'time' => 'Progres', 'url' => route('customer.helpcare')];
        }

        $nav = [
            ['label' => 'Dashboard', 'route' => 'customer.dashboard', 'active' => request()->routeIs('customer.dashboard'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>'],
            ['label' => 'Helpcare', 'route' => 'customer.helpcare', 'active' => request()->routeIs('customer.helpcare'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>'],
        ];
    }

    $notifCount = count($notifItems);
@endphp

    <div id="sidebar-backdrop" class="fixed inset-0 z-30 hidden bg-slate-900/60 backdrop-blur-sm lg:hidden" onclick="toggleSidebar(false)"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col bg-slate-900 transition-transform duration-200 ease-in-out lg:translate-x-0">
        <div class="flex h-16 shrink-0 items-center gap-3 border-b border-slate-800 px-5">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-500 shadow-lg shadow-indigo-500/30">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.288 16.038a5.25 5.25 0 017.433 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"/></svg>
            </span>
            <div>
                <p class="text-base font-bold leading-tight text-white">NetInfo</p>
                <p class="text-[11px] font-medium text-slate-400">Network Operation System</p>
            </div>
            <span class="ml-auto rounded-full border border-emerald-400/40 bg-emerald-500/10 px-2 py-0.5 text-[10px] font-semibold tracking-wider text-emerald-300">LIVE</span>
            <button type="button" class="ml-1 rounded-md p-1 text-slate-400 hover:bg-slate-800 hover:text-white lg:hidden" onclick="toggleSidebar(false)">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5">
            <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Menu {{ $isAdmin ? 'Administrator' : ($isTechnician ? 'Teknisi Lapangan' : 'Pelanggan') }}</p>
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $item['active'] ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/40' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="h-5 w-5 shrink-0 {{ $item['active'] ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">{!! $item['icon'] !!}</svg>
                    {{ $item['label'] }}
                    @if (!empty($item['badge']))
                        <span class="ml-auto inline-flex min-w-[1.5rem] items-center justify-center rounded-full px-1.5 py-0.5 text-[11px] font-bold {{ $item['active'] ? 'bg-white/20 text-white' : 'bg-red-500/90 text-white' }}">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="border-t border-slate-800 p-4">
            <div class="flex items-center gap-3 rounded-xl bg-slate-800/70 p-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 text-xs font-bold text-white">{{ $authUser->initials() }}</span>
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-white">{{ $authUser->name }}</p>
                    <p class="truncate text-xs text-slate-400">{{ $authUser->roleLabel() }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar" class="rounded-md p-1.5 text-slate-400 transition hover:bg-slate-700 hover:text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex min-h-screen flex-col lg:pl-72">
        <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 lg:hidden" onclick="toggleSidebar(true)">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>
                <div class="min-w-0">
                    <h1 class="truncate text-base font-bold text-slate-900 sm:text-lg">@yield('page_title', 'Dashboard')</h1>
                    <p class="hidden truncate text-xs text-slate-500 sm:block">@yield('page_subtitle', 'NetInfo — Sistem Manajemen Operasional Jaringan')</p>
                </div>

                <div class="ml-auto flex items-center gap-2 sm:gap-3">
                    <form action="{{ route('search') }}" method="GET" class="relative hidden md:block">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="search" name="q" value="{{ request()->query('q') }}" placeholder="Cari tiket, pelanggan, invoice..." autocomplete="off"
                            class="w-64 rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm placeholder:text-slate-400 focus:border-indigo-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    </form>

                    <div class="relative">
                        <button type="button" onclick="netinfoToggleDropdown('notif-panel')" class="relative rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            @if ($notifCount > 0)
                                <span class="absolute right-1 top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-0.5 text-[10px] font-bold text-white ring-2 ring-white">{{ $notifCount }}</span>
                            @endif
                        </button>
                        <div id="notif-panel" data-dropdown-panel class="absolute right-0 mt-2 hidden w-80 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                                <p class="text-sm font-semibold text-slate-900">Notifikasi</p>
                                @if ($notifCount > 0)<span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-600">{{ $notifCount }} baru</span>@endif
                            </div>
                            <ul class="divide-y divide-slate-100">
                                @forelse ($notifItems as $n)
                                    <li>
                                        <a href="{{ $n['url'] }}" class="flex gap-3 px-4 py-3 transition hover:bg-slate-50">
                                            <span class="mt-1 h-2 w-2 shrink-0 rounded-full {{ $n['color'] }}"></span>
                                            <div><p class="text-sm text-slate-700">{{ $n['text'] }}</p><p class="text-xs text-slate-400">{{ $n['time'] }}</p></div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="px-4 py-6 text-center text-sm text-slate-400">Tidak ada notifikasi.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div class="relative">
                        <button type="button" onclick="netinfoToggleDropdown('user-panel')" class="flex items-center gap-2 rounded-lg p-1.5 transition hover:bg-slate-100">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 text-xs font-bold text-white">{{ $authUser->initials() }}</span>
                            <span class="hidden text-left sm:block">
                                <span class="block text-sm font-semibold leading-tight text-slate-800">{{ $authUser->name }}</span>
                                <span class="block text-[11px] leading-tight text-slate-500">{{ $authUser->roleLabel() }}</span>
                            </span>
                            <svg class="hidden h-4 w-4 text-slate-400 sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div id="user-panel" data-dropdown-panel class="absolute right-0 mt-2 hidden w-52 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                            <div class="border-b border-slate-100 px-4 py-3">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ $authUser->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $authUser->email }}</p>
                            </div>
                            <a href="{{ route('profile') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">Profil Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full border-t border-slate-100 px-4 py-2.5 text-left text-sm font-medium text-red-600 hover:bg-red-50">Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-5 flex items-start gap-2.5 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 flex items-start gap-2.5 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 shadow-sm">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="py-4 text-center text-xs text-slate-400">&copy; 2026 NetInfo · Laravel + MySQL + Tailwind CSS</footer>
    </div>

    @stack('modals')

    <script>
        function toggleSidebar(force) {
            var sb = document.getElementById('sidebar');
            var bd = document.getElementById('sidebar-backdrop');
            var open = force !== undefined ? force : sb.classList.contains('-translate-x-full');
            if (open) {
                sb.classList.remove('-translate-x-full');
                bd.classList.remove('hidden');
            } else {
                if (window.innerWidth < 1024) sb.classList.add('-translate-x-full');
                bd.classList.add('hidden');
            }
        }
        function netinfoToggleDropdown(id) {
            var panel = document.getElementById(id);
            var wasHidden = panel.classList.contains('hidden');
            document.querySelectorAll('[data-dropdown-panel]').forEach(function (p) { p.classList.add('hidden'); });
            if (wasHidden) panel.classList.remove('hidden');
            event.stopPropagation();
        }
        document.addEventListener('click', function () {
            document.querySelectorAll('[data-dropdown-panel]').forEach(function (p) { p.classList.add('hidden'); });
        });
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    </script>
</body>
</html>
