@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan operasional jaringan — ' . now()->translatedFormat('l, d F Y'))

@section('content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </span>
                @if ($custIsolated > 0)
                    <span class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-600 ring-1 ring-inset ring-red-200">{{ $custIsolated }} diisolir</span>
                @endif
            </div>
            <p class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">{{ $custActive }}</p>
            <p class="mt-1 text-sm text-slate-500">Pelanggan Aktif</p>
            <p class="mt-2 text-xs text-slate-400">Total {{ $custTotal }} terdaftar · {{ $custInactive }} nonaktif</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 text-red-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                </span>
                @if ($tHighActive > 0)
                    <span class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-200">{{ $tHighActive }} High</span>
                @endif
            </div>
            <p class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">{{ $tActive }}</p>
            <p class="mt-1 text-sm text-slate-500">Tiket Aktif (Open + Proses)</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                </span>
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Bulan ini</span>
            </div>
            <p class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900">{{ $revenueFmt }}</p>
            <p class="mt-1 text-sm text-slate-500">Pendapatan Lunas</p>
            <p class="mt-2 text-xs text-slate-400">{{ $invPaidMonth }}/{{ $invTotalMonth }} invoice lunas ({{ $invPct }}%)</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-100 text-sky-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3"/></svg>
                </span>
                @if ($nodeAttention > 0)
                    <span class="rounded-full bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-200">{{ $nodeAttention }} perlu cek</span>
                @endif
            </div>
            <p class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">{{ $nodeActive }}<span class="text-base font-semibold text-slate-400">/{{ $nodeTotal }}</span></p>
            <p class="mt-1 text-sm text-slate-500">Node Operasional Normal</p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Pendapatan 6 Bulan Terakhir</h3>
                    <p class="text-xs text-slate-500">Rekap tagihan lunas per bulan (juta Rupiah)</p>
                </div>
            </div>
            <div class="mt-6 flex h-56 items-end gap-3 sm:gap-5">
                @foreach ($chart as $bar)
                    @php
                        $pct = (int) round($bar['value'] / $maxRevenue * 100);
                        $isNow = $loop->last;
                    @endphp
                    <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                        <span class="text-[11px] font-semibold text-slate-500">{{ number_format($bar['value'], 2, ',', '.') }}</span>
                        <div class="flex h-40 w-full items-end overflow-hidden rounded-t-md bg-slate-100">
                            <div class="w-full rounded-t-md transition-all {{ $isNow ? 'bg-gradient-to-t from-indigo-600 to-indigo-400' : 'bg-gradient-to-t from-indigo-300 to-indigo-200' }}" style="height: {{ max(3, $pct) }}%"></div>
                        </div>
                        <span class="text-xs font-medium {{ $isNow ? 'font-bold text-indigo-600' : 'text-slate-500' }}">{{ $bar['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-bold text-slate-900">Distribusi Status Tiket</h3>
            <p class="text-xs text-slate-500">Seluruh tiket ({{ $tTotal }})</p>
            @php
                [$dOpen, $dProg, $dRes, $dClo] = $donut;
                $total = max(1, $tTotal);
                $stop1 = round($dOpen / $total * 100);
                $stop2 = round(($dOpen + $dProg) / $total * 100);
                $stop3 = round(($dOpen + $dProg + $dRes) / $total * 100);
            @endphp
            <div class="mt-6 flex items-center justify-center gap-6">
                <div class="relative h-40 w-40 shrink-0">
                    <div class="h-full w-full rounded-full" style="background: conic-gradient(#0ea5e9 0% {{ $stop1 }}%, #f59e0b {{ $stop1 }}% {{ $stop2 }}%, #10b981 {{ $stop2 }}% {{ $stop3 }}%, #94a3b8 {{ $stop3 }}% 100%)"></div>
                    <div class="absolute inset-[22%] flex flex-col items-center justify-center rounded-full bg-white shadow-inner">
                        <span class="text-2xl font-extrabold text-slate-900">{{ $tTotal }}</span>
                        <span class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Tiket</span>
                    </div>
                </div>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-sky-500"></span><span class="text-slate-600">Open</span><span class="ml-auto pl-3 font-bold text-slate-900">{{ $dOpen }}</span></li>
                    <li class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span><span class="text-slate-600">Dikerjakan</span><span class="ml-auto pl-3 font-bold text-slate-900">{{ $dProg }}</span></li>
                    <li class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span><span class="text-slate-600">Selesai</span><span class="ml-auto pl-3 font-bold text-slate-900">{{ $dRes }}</span></li>
                    <li class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-slate-400"></span><span class="text-slate-600">Ditutup</span><span class="ml-auto pl-3 font-bold text-slate-900">{{ $dClo }}</span></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Antrean Tiket Terbaru</h3>
                <p class="text-xs text-slate-500">Data real-time dari tabel tickets</p>
            </div>
            <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                Kelola Tiket
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
        <div class="w-full overflow-x-auto">
            <table class="min-w-[900px] divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Kode Tiket</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Pelanggan</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Kendala</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Prioritas</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Teknisi</th>
                        <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                @php
                    $prio = ['high' => 'bg-red-50 text-red-700 ring-red-600/20', 'medium' => 'bg-amber-50 text-amber-700 ring-amber-600/20', 'low' => 'bg-slate-100 text-slate-600 ring-slate-500/20'];
                    $stat = ['open' => ['pill' => 'bg-sky-50 text-sky-700 ring-sky-600/20', 'label' => 'Open'], 'in_progress' => ['pill' => 'bg-amber-50 text-amber-700 ring-amber-600/20', 'label' => 'Dikerjakan'], 'resolved' => ['pill' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'label' => 'Selesai'], 'closed' => ['pill' => 'bg-slate-100 text-slate-600 ring-slate-500/20', 'label' => 'Ditutup']];
                @endphp
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentTickets as $t)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-5 py-3.5"><span class="font-mono text-xs font-semibold text-indigo-600">{{ $t->ticket_code }}</span><span class="block text-[11px] text-slate-400">{{ $t->created_at->translatedFormat('d M, H:i') }}</span></td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-sm font-medium text-slate-800">{{ $t->customer?->user?->name ?? '-' }}</td>
                            <td class="max-w-[220px] truncate px-5 py-3.5 text-sm text-slate-600">{{ $t->issue_title }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5"><span class="inline-flex rounded-md px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $prio[$t->priority] }}">{{ ucfirst($t->priority) }}</span></td>
                            <td class="whitespace-nowrap px-5 py-3.5"><span class="inline-flex rounded-md px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $stat[$t->status]['pill'] }}">{{ $stat[$t->status]['label'] }}</span></td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-sm {{ $t->technician ? 'text-slate-700' : 'italic text-slate-400' }}">{{ $t->technician?->name ?? 'Belum ditugaskan' }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-right"><a href="{{ route('tickets.show', $t) }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-8 text-center text-sm italic text-slate-400">Belum ada tiket.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
