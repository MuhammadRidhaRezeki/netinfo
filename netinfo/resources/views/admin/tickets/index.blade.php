@extends('layouts.app')

@section('title', 'Antrean Tiket')
@section('page_title', 'Tiket Gangguan')
@section('page_subtitle', 'Kelola antrean komplain & penugasan teknisi lapangan')

@section('content')
    @php
        $prio = ['high' => ['label' => 'High', 'pill' => 'bg-red-50 text-red-700 ring-red-600/20'], 'medium' => ['label' => 'Medium', 'pill' => 'bg-amber-50 text-amber-700 ring-amber-600/20'], 'low' => ['label' => 'Low', 'pill' => 'bg-slate-100 text-slate-600 ring-slate-500/20']];
        $stat = ['open' => ['label' => 'Open', 'pill' => 'bg-sky-50 text-sky-700 ring-sky-600/20', 'dot' => 'bg-sky-500'], 'in_progress' => ['label' => 'In Progress', 'pill' => 'bg-amber-50 text-amber-700 ring-amber-600/20', 'dot' => 'bg-amber-500'], 'resolved' => ['label' => 'Resolved', 'pill' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'dot' => 'bg-emerald-500'], 'closed' => ['label' => 'Closed', 'pill' => 'bg-slate-100 text-slate-600 ring-slate-500/20', 'dot' => 'bg-slate-400']];
        $exportQs = http_build_query(array_filter(['q' => $filters['q'], 'status' => $filters['status'], 'priority' => $filters['priority']]));
    @endphp

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm"><span class="h-2.5 w-2.5 rounded-full bg-sky-500"></span><span class="text-sm font-medium text-slate-600">Open</span><span class="ml-auto text-lg font-extrabold text-slate-900">{{ $stats['open'] }}</span></div>
        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span><span class="text-sm font-medium text-slate-600">In Progress</span><span class="ml-auto text-lg font-extrabold text-slate-900">{{ $stats['in_progress'] }}</span></div>
        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span><span class="text-sm font-medium text-slate-600">Resolved</span><span class="ml-auto text-lg font-extrabold text-slate-900">{{ $stats['resolved'] }}</span></div>
        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm"><span class="h-2.5 w-2.5 rounded-full bg-slate-400"></span><span class="text-sm font-medium text-slate-600">Closed</span><span class="ml-auto text-lg font-extrabold text-slate-900">{{ $stats['closed'] }}</span></div>
    </div>

    <form method="GET" action="{{ route('admin.tickets.index') }}" class="mt-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari kode / judul / pelanggan..." class="w-64 rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-3 text-sm placeholder:text-slate-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100">
            </div>
            <select name="status" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm capitalize text-slate-600 focus:border-indigo-400 focus:outline-none">
                <option value="">Semua Status</option>
                @foreach (['open', 'in_progress', 'resolved', 'closed'] as $st)<option value="{{ $st }}" {{ $filters['status'] === $st ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($st)) }}</option>@endforeach
            </select>
            <select name="priority" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm capitalize text-slate-600 focus:border-indigo-400 focus:outline-none">
                <option value="">Semua Prioritas</option>
                @foreach (['high', 'medium', 'low'] as $p)<option value="{{ $p }}" {{ $filters['priority'] === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>@endforeach
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Terapkan</button>
            @if ($filters['q'] || $filters['status'] || $filters['priority'])
                <a href="{{ route('admin.tickets.index') }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-500 hover:bg-slate-50">Reset</a>
            @endif
        </div>
        <a href="{{ route('admin.tickets.export') }}@if($exportQs)?{{ $exportQs }}@endif"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Ekspor Rekap CSV
        </a>
    </form>

    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="w-full overflow-x-auto">
            <table class="min-w-[900px] divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Kode Tiket</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Pelanggan</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Judul Kendala</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Prioritas</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Teknisi</th>
                        <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($tickets as $t)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-5 py-3.5"><span class="font-mono text-xs font-semibold text-indigo-600">{{ $t->ticket_code }}</span><span class="block text-[11px] text-slate-400">{{ $t->created_at->translatedFormat('d M, H:i') }}</span></td>
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <p class="text-sm font-semibold text-slate-800">{{ $t->customer?->user?->name ?? '-' }}</p>
                                <p class="font-mono text-[11px] text-slate-400">{{ $t->customer?->node?->name ?? '-' }}</p>
                            </td>
                            <td class="max-w-[240px] truncate px-5 py-3.5 text-sm text-slate-600">{{ $t->issue_title }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5"><span class="inline-flex rounded-md px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $prio[$t->priority]['pill'] }}">{{ $prio[$t->priority]['label'] }}</span></td>
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $stat[$t->status]['pill'] }}">
                                    <span class="{{ $t->status === 'in_progress' ? 'animate-pulse ' : '' }}h-1.5 w-1.5 rounded-full {{ $stat[$t->status]['dot'] }}"></span>{{ $stat[$t->status]['label'] }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5">
                                @if ($t->technician)
                                    <span class="text-sm text-slate-700">{{ $t->technician->name }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-md bg-orange-50 px-2 py-1 text-xs font-semibold text-orange-600 ring-1 ring-inset ring-orange-600/20">Menunggu Assign</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-right">
                                @if ($t->technician)
                                    <a href="{{ route('tickets.show', $t) }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Detail</a>
                                @else
                                    <button type="button" data-assign-open data-url="{{ route('admin.tickets.assign', $t) }}"
                                        data-code="{{ $t->ticket_code }}" data-cust="{{ $t->customer?->user?->name }}" data-node="{{ $t->customer?->node?->name }}"
                                        data-address="{{ $t->customer?->address }}" data-title="{{ $t->issue_title }}" data-priority="{{ $t->priority }}"
                                        class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500">Assign Teknisi</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-sm italic text-slate-400">Tidak ada tiket yang cocok dengan filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 bg-slate-50 px-5 py-3">
            <p class="text-xs text-slate-500">Menampilkan <span class="font-semibold">{{ $tickets->count() }}</span> tiket dari database</p>
        </div>
    </div>
@endsection

@push('modals')
    <div id="modal-assign" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modal-assign')"></div>
        <div class="absolute left-1/2 top-1/2 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-2xl bg-white shadow-2xl">
            <div class="flex items-start justify-between border-b border-slate-200 px-6 py-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Assign Teknisi</h3>
                    <p class="text-xs text-slate-500">FR-TCK-02 · technician_id + ticket_histories terupdate otomatis</p>
                </div>
                <button type="button" onclick="closeModal('modal-assign')" class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form id="assign-form" method="POST" action="#" class="space-y-4 px-6 py-5">
                @csrf
                <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-inset ring-slate-200">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-xs font-bold text-indigo-600" id="as-code">-</span>
                        <span class="inline-flex rounded-md bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20" id="as-priority">-</span>
                    </div>
                    <p class="mt-1.5 text-sm font-semibold text-slate-800" id="as-title">-</p>
                    <p class="mt-0.5 text-xs text-slate-500">Pelapor: <span id="as-cust">-</span> · Titik: <span class="font-mono" id="as-node">-</span> · <span id="as-address">-</span></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Pilih Teknisi Lapangan *</label>
                    <select name="technician_id" required class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        @foreach ($technicians as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Catatan untuk Teknisi <span class="font-normal text-slate-400">(opsional, masuk ke history)</span></label>
                    <textarea name="note" rows="3" placeholder="cth: Cek redaman di ODC sisi barat..."
                        class="mt-1.5 block w-full resize-none rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"></textarea>
                </div>
                <input type="hidden" name="redirect" value="admin.tickets.index">
                <div class="flex justify-end gap-2 border-t border-slate-200 pt-4">
                    <button type="button" onclick="closeModal('modal-assign')" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Tugaskan Sekarang</button>
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
