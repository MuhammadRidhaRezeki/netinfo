@extends('layouts.app')

@section('title', 'Antrean Tiket')
@section('page_title', 'Tiket Gangguan')
@section('page_subtitle', 'Kelola antrean komplain & penugasan teknisi lapangan')

@section('content')
    @php
        $prio = ['high' => 'bg-rose-50 text-rose-700 border-rose-200', 'medium' => 'bg-amber-50 text-amber-700 border-amber-200', 'low' => 'bg-slate-50 text-slate-600 border-slate-200'];
        $stat = ['open' => ['label' => 'Open', 'pill' => 'bg-sky-50 text-sky-700 border-sky-200', 'dot' => 'bg-sky-500'], 'in_progress' => ['label' => 'In Progress', 'pill' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500'], 'resolved' => ['label' => 'Resolved', 'pill' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'], 'closed' => ['label' => 'Closed', 'pill' => 'bg-slate-50 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400']];
        $exportQs = http_build_query(array_filter(['q' => $filters['q'], 'status' => $filters['status'], 'priority' => $filters['priority']]));
    @endphp

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="flex items-center gap-3 rounded-xl border border-slate-200/90 bg-white px-4 py-3 shadow-[0_1px_2px_rgba(0,0,0,0.04)]"><span class="h-2.5 w-2.5 rounded-full bg-sky-500"></span><span class="text-sm font-medium text-slate-600">Open</span><span class="ml-auto text-lg font-bold text-slate-900">{{ $stats['open'] }}</span></div>
        <div class="flex items-center gap-3 rounded-xl border border-slate-200/90 bg-white px-4 py-3 shadow-[0_1px_2px_rgba(0,0,0,0.04)]"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span><span class="text-sm font-medium text-slate-600">In Progress</span><span class="ml-auto text-lg font-bold text-slate-900">{{ $stats['in_progress'] }}</span></div>
        <div class="flex items-center gap-3 rounded-xl border border-slate-200/90 bg-white px-4 py-3 shadow-[0_1px_2px_rgba(0,0,0,0.04)]"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span><span class="text-sm font-medium text-slate-600">Resolved</span><span class="ml-auto text-lg font-bold text-slate-900">{{ $stats['resolved'] }}</span></div>
        <div class="flex items-center gap-3 rounded-xl border border-slate-200/90 bg-white px-4 py-3 shadow-[0_1px_2px_rgba(0,0,0,0.04)]"><span class="h-2.5 w-2.5 rounded-full bg-slate-400"></span><span class="text-sm font-medium text-slate-600">Closed</span><span class="ml-auto text-lg font-bold text-slate-900">{{ $stats['closed'] }}</span></div>
    </div>

    <form method="GET" action="{{ route('admin.tickets.index') }}" class="mt-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari kode / judul / pelanggan..." class="w-64 rounded-lg border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
            </div>
            <select name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm capitalize text-slate-700 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
                <option value="">Semua Status</option>
                @foreach (['open', 'in_progress', 'resolved', 'closed'] as $st)<option value="{{ $st }}" {{ $filters['status'] === $st ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($st)) }}</option>@endforeach
            </select>
            <select name="priority" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm capitalize text-slate-700 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
                <option value="">Semua Prioritas</option>
                @foreach (['high', 'medium', 'low'] as $p)<option value="{{ $p }}" {{ $filters['priority'] === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>@endforeach
            </select>
            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Terapkan</button>
            @if ($filters['q'] || $filters['status'] || $filters['priority'])
                <a href="{{ route('admin.tickets.index') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-500 hover:bg-slate-50 transition-all">Reset</a>
            @endif
        </div>
        <a href="{{ route('admin.tickets.export') }}@if($exportQs)?{{ $exportQs }}@endif" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Ekspor Rekap CSV
        </a>
    </form>

    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200/90 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <div class="w-full overflow-x-auto">
            <table class="min-w-[900px] w-full text-left text-sm">
                <thead class="bg-slate-50/80 text-xs font-mono uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Kode Tiket</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Judul Kendala</th>
                        <th class="px-4 py-3">Prioritas</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Teknisi</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($tickets as $t)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="whitespace-nowrap px-4 py-3"><span class="font-mono text-xs font-medium text-slate-700">{{ $t->ticket_code }}</span><span class="block text-[11px] text-slate-400">{{ $t->created_at->translatedFormat('d M, H:i') }}</span></td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <p class="text-sm font-semibold text-slate-700">{{ $t->customer?->user?->name ?? '-' }}</p>
                                <p class="font-mono text-[11px] text-slate-400">{{ $t->customer?->node?->name ?? '-' }}</p>
                            </td>
                            <td class="max-w-[240px] truncate px-4 py-3 text-sm text-slate-600">{{ $t->issue_title }}</td>
                            <td class="whitespace-nowrap px-4 py-3"><span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-medium {{ $prio[$t->priority] }}">{{ $prio[$t->priority]['label'] ?? ucfirst($t->priority) }}</span></td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-medium {{ $stat[$t->status]['pill'] }}">
                                    <span class="{{ $t->status === 'in_progress' ? 'animate-pulse ' : '' }}h-1.5 w-1.5 rounded-full {{ $stat[$t->status]['dot'] }}"></span>{{ $stat[$t->status]['label'] }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                @if ($t->technician)
                                    <span class="text-sm text-slate-700">{{ $t->technician->name }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-md border border-amber-200 bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-700">Menunggu Assign</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                @if ($t->technician)
                                    <a href="{{ route('tickets.show', $t) }}" class="text-xs font-medium text-slate-900 underline underline-offset-4 hover:text-slate-700">Detail</a>
                                @else
                                    <button type="button" data-assign-open data-url="{{ route('admin.tickets.assign', $t) }}" data-code="{{ $t->ticket_code }}" data-cust="{{ $t->customer?->user?->name }}" data-node="{{ $t->customer?->node?->name }}" data-address="{{ $t->customer?->address }}" data-title="{{ $t->issue_title }}" data-priority="{{ $t->priority }}" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Assign Teknisi</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-10 text-center text-sm italic text-slate-400">Tidak ada tiket yang cocok dengan filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 bg-slate-50/60 px-5 py-3">
            <p class="text-xs text-slate-500">Menampilkan <span class="font-semibold">{{ $tickets->count() }}</span> tiket dari database</p>
        </div>
    </div>
@endsection

@push('modals')
    <div id="modal-assign" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modal-assign')"></div>
        <div class="absolute left-1/2 top-1/2 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-xl border border-slate-200/90 bg-white shadow-2xl">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Assign Teknisi</h3>
                    <p class="text-xs text-slate-500">FR-TCK-02 &middot; technician_id + ticket_histories terupdate otomatis</p>
                </div>
                <button type="button" onclick="closeModal('modal-assign')" class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form id="assign-form" method="POST" action="#" class="space-y-4 px-6 py-5">
                @csrf
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-xs font-medium text-slate-700" id="as-code">-</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-0.5 text-[11px] font-semibold text-rose-700" id="as-priority">-</span>
                    </div>
                    <p class="mt-1.5 text-sm font-semibold text-slate-700" id="as-title">-</p>
                    <p class="mt-0.5 text-xs text-slate-500">Pelapor: <span id="as-cust">-</span> &middot; Titik: <span class="font-mono" id="as-node">-</span> &middot; <span id="as-address">-</span></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Pilih Teknisi Lapangan</label>
                    <select name="technician_id" required class="mt-1.5 block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
                        @foreach ($technicians as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Catatan untuk Teknisi <span class="font-normal text-slate-400">(opsional, masuk ke history)</span></label>
                    <textarea name="note" rows="3" placeholder="cth: Cek redaman di ODC sisi barat..." class="mt-1.5 block w-full resize-none rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all"></textarea>
                </div>
                <input type="hidden" name="redirect" value="admin.tickets.index">
                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" onclick="closeModal('modal-assign')" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Tugaskan Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-assign-open]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('assign-form').action = btn.dataset.url;
                document.getElementById('as-code').textContent = btn.dataset.code;
                document.getElementById('as-priority').textContent = btn.dataset.priority.toUpperCase() + ' Priority';
                document.getElementById('as-title').textContent = btn.dataset.title;
                document.getElementById('as-cust').textContent = btn.dataset.cust;
                document.getElementById('as-node').textContent = btn.dataset.node;
                document.getElementById('as-address').textContent = btn.dataset.address;
                openModal('modal-assign');
            });
        });
    </script>
@endpush