@extends('layouts.app')

@section('title', 'Network Nodes')
@php
    $isAdmin = auth()->user()->isAdmin();
    $indexRouteName = $isAdmin ? 'admin.network-nodes.index' : 'technician.network-nodes.index';
    $nodeMeta = [
        'active' => ['label' => 'Active', 'pill' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
        'maintenance' => ['label' => 'Maintenance', 'pill' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500'],
        'down' => ['label' => 'Down', 'pill' => 'bg-rose-50 text-rose-700 border-rose-200', 'dot' => 'bg-rose-500'],
    ];
@endphp
@section('page_title', 'Network Nodes')
@section('page_subtitle', ($isAdmin ? 'Kelola titik distribusi ODP & perangkat jaringan' : 'Pantau & kelola titik distribusi ODP (akses teknisi)'))

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route($indexRouteName, ['status' => '']) }}" class="rounded-lg px-3.5 py-2 text-sm font-semibold transition {{ $filters['status'] === '' ? 'bg-slate-900 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">Semua ({{ $stats['total'] }})</a>
            @foreach (['active', 'maintenance', 'down'] as $st)
                <a href="{{ route($indexRouteName, ['status' => $st]) }}" class="rounded-lg px-3.5 py-2 text-sm font-semibold capitalize transition {{ $filters['status'] === $st ? ($st === 'down' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($st === 'maintenance' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200')) : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                    {{ ucfirst($st) }} ({{ $stats[$st] }})
                </a>
            @endforeach
        </div>
        <button type="button" onclick="openModal('modal-add-node')" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Node
        </button>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($nodes as $n)
            <div class="rounded-xl border border-slate-200/90 bg-white p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)] transition hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg {{ $n->status === 'active' ? 'bg-emerald-50 text-emerald-600' : ($n->status === 'maintenance' ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600') }}">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3"/></svg>
                        </span>
                        <div>
                            <p class="font-mono text-sm font-bold text-slate-700">{{ $n->name }}</p>
                            <p class="text-xs text-slate-500">{{ $n->location_area }}</p>
                        </div>
                    </div>
                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-medium {{ $nodeMeta[$n->status]['pill'] }}">
                        <span class="{{ $n->status === 'down' ? 'animate-pulse ' : '' }}h-1.5 w-1.5 rounded-full {{ $nodeMeta[$n->status]['dot'] }}"></span>
                        {{ $nodeMeta[$n->status]['label'] }}
                    </span>
                </div>
                <dl class="mt-4 grid grid-cols-2 gap-2 border-t border-slate-100 pt-4 text-center">
                    <div><dt class="text-[11px] font-medium uppercase text-slate-400">IP Manage</dt><dd class="mt-0.5 truncate font-mono text-xs font-semibold text-slate-700">@if($n->ip_address){{ $n->ip_address }}@else<span class="italic text-slate-400">belum diset</span>@endif</dd></div>
                    <div><dt class="text-[11px] font-medium uppercase text-slate-400">Pelanggan Aktif</dt><dd class="mt-0.5 text-xs font-semibold text-slate-700">{{ $n->active_customers }} terhubung</dd></div>
                </dl>
                <div class="mt-4 flex gap-2">
                    <button type="button" data-node-edit data-url="{{ route('network-nodes.update', $n) }}"
                        data-name="{{ $n->name }}" data-area="{{ $n->location_area }}" data-ip="{{ $n->ip_address }}" data-status="{{ $n->status }}"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-100">
                        Edit IP / Lokasi / Status
                    </button>
                    @if ($isAdmin)
                        <form method="POST" action="{{ route('network-nodes.destroy', $n) }}" onsubmit="return confirm('Hapus node {{ $n->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus" class="rounded-lg border border-slate-200 p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-500">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-300 bg-white py-14 text-center">
                <p class="text-sm font-semibold text-slate-500">Tidak ada node dengan filter status ini.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6 overflow-hidden rounded-xl border border-slate-200/90 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <div class="border-b border-slate-100 px-5 py-4">
            <h3 class="text-sm font-semibold text-slate-700">Rekapitulasi Tabel Node</h3>
            <p class="text-xs text-slate-500">Sumber data: tabel network_nodes (MySQL) &middot; total {{ $nodes->count() }} node tampil</p>
        </div>
        <div class="w-full overflow-x-auto">
            <table class="min-w-[800px] w-full text-left text-sm">
                <thead class="bg-slate-50/80 text-xs font-mono uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Nama Node</th>
                        <th class="px-4 py-3">Lokasi Wilayah</th>
                        <th class="px-4 py-3">IP Address</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($nodes as $n)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="whitespace-nowrap px-4 py-3 font-mono text-sm font-bold text-slate-700">{{ $n->name }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-600">{{ $n->location_area }}</td>
                            <td class="whitespace-nowrap px-4 py-3">@if ($n->ip_address)<span class="font-mono text-xs text-slate-600">{{ $n->ip_address }}</span>@else<span class="text-xs italic text-slate-400">belum diset</span>@endif</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-semibold text-slate-700">{{ $n->active_customers }}</td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-medium {{ $nodeMeta[$n->status]['pill'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $nodeMeta[$n->status]['dot'] }}"></span>{{ $nodeMeta[$n->status]['label'] }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right"><button type="button" onclick="document.querySelectorAll('[data-node-edit]').forEach(function(b){ if(b.dataset.name === '{{ $n->name }}') b.click(); })" class="text-xs font-medium text-slate-900 underline underline-offset-4 hover:text-slate-700">Edit</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('modals')
    <div id="modal-add-node" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modal-add-node')"></div>
        <div class="absolute left-1/2 top-1/2 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-xl border border-slate-200/90 bg-white shadow-2xl">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Tambah Network Node</h3>
                    <p class="text-xs text-slate-500">Tersimpan permanen ke tabel network_nodes</p>
                </div>
                <button type="button" onclick="closeModal('modal-add-node')" class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form method="POST" action="{{ route('network-nodes.store') }}" class="space-y-4 px-6 py-5">
                @csrf
                @include('nodes._fields')
                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" onclick="closeModal('modal-add-node')" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Simpan Node</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-edit-node" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modal-edit-node')"></div>
        <div class="absolute left-1/2 top-1/2 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-xl border border-slate-200/90 bg-white shadow-2xl">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Edit Node</h3>
                    <p class="text-xs text-slate-500">Ubah nama, lokasi, IP manajemen, atau status operasional</p>
                </div>
                <button type="button" onclick="closeModal('modal-edit-node')" class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form id="edit-node-form" method="POST" action="#" class="space-y-4 px-6 py-5">
                @csrf
                @method('PUT')
                @include('nodes._fields')
                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" onclick="closeModal('modal-edit-node')" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-node-edit]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var form = document.getElementById('edit-node-form');
                form.action = btn.dataset.url;
                form.querySelector('[name=name]').value = btn.dataset.name;
                form.querySelector('[name=location_area]').value = btn.dataset.area;
                form.querySelector('[name=ip_address]').value = btn.dataset.ip;
                form.querySelector('[name=status]').value = btn.dataset.status;
                openModal('modal-edit-node');
            });
        });
    </script>
@endpush