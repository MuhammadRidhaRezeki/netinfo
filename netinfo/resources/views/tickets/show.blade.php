@extends('layouts.app')

@php
    $prioMap = ['high' => ['label' => 'High', 'pill' => 'bg-rose-50 text-rose-700 border-rose-200'], 'medium' => ['label' => 'Medium', 'pill' => 'bg-amber-50 text-amber-700 border-amber-200'], 'low' => ['label' => 'Low', 'pill' => 'bg-slate-50 text-slate-600 border-slate-200']];
    $statMap = ['open' => ['label' => 'Open', 'pill' => 'bg-sky-50 text-sky-700 border-sky-200'], 'in_progress' => ['label' => 'In Progress', 'pill' => 'bg-amber-50 text-amber-700 border-amber-200'], 'resolved' => ['label' => 'Resolved', 'pill' => 'bg-emerald-50 text-emerald-700 border-emerald-200'], 'closed' => ['label' => 'Closed', 'pill' => 'bg-slate-50 text-slate-600 border-slate-200']];
    $histTypeColor = ['created' => 'bg-sky-500', 'assigned' => 'bg-slate-700', 'status_changed' => 'bg-amber-500', 'note_added' => 'bg-violet-500'];
    $backRoute = auth()->user()->isAdmin() ? 'admin.tickets.index' : 'technician.tickets.index';
@endphp

@section('title', $ticket->ticket_code)
@section('page_title', 'WO · ' . $ticket->ticket_code)
@section('page_subtitle', 'Detail penanganan gangguan pelanggan')

@section('content')
    <a href="{{ route($backRoute) }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-slate-900">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Kembali ke daftar tiket
    </a>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <div class="overflow-hidden rounded-xl border border-slate-200/90 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                <div class="border-b border-slate-100 bg-slate-50/40 px-6 py-5">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <span class="font-mono text-xs font-medium text-slate-700">{{ $ticket->ticket_code }}</span>
                            <h2 class="mt-1 text-xl font-semibold tracking-tight text-slate-900">{{ $ticket->issue_title }}</h2>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium {{ $prioMap[$ticket->priority]['pill'] }}">{{ $prioMap[$ticket->priority]['label'] }} Priority</span>
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium {{ $statMap[$ticket->status]['pill'] }}">{{ $statMap[$ticket->status]['label'] }}</span>
                        </div>
                    </div>
                </div>
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 px-6 py-5 sm:grid-cols-2">
                    <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Pelanggan</dt><dd class="mt-0.5 text-sm font-semibold text-slate-700">{{ $ticket->customer?->user?->name ?? '-' }}</dd></div>
                    <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Kontak WhatsApp</dt><dd class="mt-0.5 font-mono text-sm font-semibold text-slate-700">{{ $ticket->customer?->phone ?? '-' }}</dd></div>
                    <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Node ODP</dt><dd class="mt-0.5 font-mono text-sm font-semibold text-slate-700">{{ $ticket->customer?->node?->name ?? '-' }}</dd></div>
                    <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Teknisi</dt><dd class="mt-0.5 text-sm font-semibold {{ $ticket->technician ? 'text-slate-700' : 'italic text-slate-400' }}">{{ $ticket->technician?->name ?? 'Belum ditugaskan' }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Alamat Pemasangan</dt><dd class="mt-0.5 text-sm text-slate-600">{{ $ticket->customer?->address ?? '-' }}</dd></div>
                    <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Waktu Lapor</dt><dd class="mt-0.5 text-sm text-slate-600">{{ $ticket->created_at->translatedFormat('d M Y, H:i') }} WIB</dd></div>
                    <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Selesai</dt><dd class="mt-0.5 text-sm {{ $ticket->resolved_at ? 'font-semibold text-emerald-600' : 'text-slate-400' }}">{{ $ticket->resolved_at?->translatedFormat('d M Y, H:i') ?? '-' }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Deskripsi Pelapor</dt><dd class="mt-1 rounded-lg border border-slate-200 bg-slate-50 p-3.5 text-sm leading-relaxed text-slate-600">{{ $ticket->description }}</dd></div>
                </dl>
            </div>

            <div class="rounded-xl border border-slate-200/90 bg-white p-6 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                <h3 class="text-sm font-semibold text-slate-900">Riwayat Penanganan</h3>
                <p class="text-xs text-slate-500">Rekaman otomatis tabel ticket_histories (FR-TCK-04) — total {{ $ticket->histories->count() }} entri</p>
                <ol class="relative mt-5 space-y-6 border-l-2 border-slate-200 pl-6">
                    @forelse ($ticket->histories as $h)
                        @php
                            $resolvedStep = in_array($h->status_to, ['resolved', 'closed']);
                            $progressStep = $h->status_to === 'in_progress';
                        @endphp
                        <li>
                            <span class="absolute -left-[9px] flex h-4 w-4 items-center justify-center rounded-full ring-4 {{ $resolvedStep ? 'bg-emerald-500 ring-emerald-50' : (($histTypeColor[$h->action_type] ?? 'bg-slate-400') . ' ring-slate-50') }}"></span>
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-[10px] font-semibold text-slate-500">{{ $h->action_type }}</p>
                                @if ($resolvedStep)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">Resolved / Selesai</span>
                                @elseif ($progressStep)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-[10px] font-bold text-amber-700">In Progress / Dikerjakan</span>
                                @endif
                                <p class="text-xs text-slate-400">{{ $h->created_at->translatedFormat('d M Y, H:i') }} &middot; oleh {{ $h->user?->name }} ({{ $h->user?->roleLabel() ?? '-' }})</p>
                            </div>
                            @if ($h->note)<p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $h->note }}</p>@endif
                        </li>
                    @empty
                        <li class="text-sm italic text-slate-400">Belum ada riwayat penanganan.</li>
                    @endforelse
                </ol>

                @if (auth()->user()->isAdmin() || $canWork)
                    <form method="POST" action="{{ route('tickets.note', $ticket) }}" class="mt-6 space-y-3 border-t border-slate-100 pt-5">
                        @csrf
                        <label class="block text-sm font-medium text-slate-700">Tambah Catatan Teknis ke History</label>
                        <textarea name="note" rows="2" required minlength="5" placeholder="cth: Redaman normal kembali -21,3 dBm setelah pembersihan konektor."
                            class="block w-full resize-none rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all"></textarea>
                        <button type="submit" class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">Simpan Catatan</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            @if ($canWork)
                <div class="rounded-xl border border-slate-200/90 bg-white p-6 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                    <h3 class="text-sm font-semibold text-slate-900">Aksi Pengerjaan</h3>
                    <div class="mt-4 space-y-3">
                        @if ($ticket->status === 'open')
                            <form method="POST" action="{{ route('technician.tickets.status', $ticket) }}" onsubmit="return confirm('Mulai kerjakan tiket ini?')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">
                                <input type="hidden" name="resolution_note" value="Survey lokasi dimulai.">
                                <button type="submit" class="w-full rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-amber-500 active:scale-[0.99]">Mulai Kerjakan (In Progress)</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('technician.tickets.status', $ticket) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="resolved">
                            <label class="block text-sm font-medium text-slate-700">Catatan Solusi <span class="text-rose-500">*</span></label>
                            <textarea name="resolution_note" rows="4" required minlength="5" placeholder="Jelaskan akar masalah & tindakan perbaikan..."
                                class="mt-1.5 block w-full resize-none rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all"></textarea>
                            <button type="submit" class="mt-3 flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-500 active:scale-[0.99]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                Selesaikan (Resolved)
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="rounded-xl border border-slate-200/90 bg-white p-6 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3"/></svg>
                    Info Titik ODP
                </h3>
                <div class="mt-4 space-y-2.5 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">Nama Node</dt><dd class="font-mono font-semibold text-slate-700">{{ $ticket->customer?->node?->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Lokasi Wilayah</dt><dd class="max-w-[60%] text-right font-medium text-slate-700">{{ $ticket->customer?->node?->location_area ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">IP Manajemen</dt><dd class="font-mono font-medium text-slate-700">{{ $ticket->customer?->node?->ip_address ?? '—' }}</dd></div>
                    @php $nd = $ticket->customer?->node; @endphp
                    @if ($nd)
                        @php
                            $nodePill = ['active' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'down' => 'bg-rose-50 text-rose-700 border-rose-200', 'maintenance' => 'bg-amber-50 text-amber-700 border-amber-200'][$nd->status] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                        @endphp
                        <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                            <span class="text-xs font-medium text-slate-500">Status Node</span>
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold capitalize {{ $nodePill }}">{{ ucfirst($nd->status) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection