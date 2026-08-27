@extends('layouts.app')

@section('title', 'Data Pelanggan')
@section('page_title', 'Data Pelanggan')
@section('page_subtitle', 'Kelola data langganan, paket, dan titik koneksi pelanggan')

@section('content')
    @php
        $custMeta = [
            'active' => ['label' => 'Active', 'pill' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
            'isolated' => ['label' => 'Isolated', 'pill' => 'bg-rose-50 text-rose-700 border-rose-200', 'dot' => 'bg-rose-500'],
            'inactive' => ['label' => 'Inactive', 'pill' => 'bg-slate-50 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400'],
        ];
        $avatarColors = ['bg-slate-100 text-slate-700', 'bg-slate-200 text-slate-800', 'bg-slate-300 text-slate-900', 'bg-slate-100 text-slate-700', 'bg-slate-200 text-slate-800', 'bg-slate-300 text-slate-900'];
    @endphp

    <form method="GET" action="{{ route('admin.customers.index') }}" class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari nama / kode / telp..." class="w-60 rounded-lg border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
            </div>
            <select name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm capitalize text-slate-700 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
                <option value="">Semua Status</option>
                @foreach (['active', 'isolated', 'inactive'] as $st)
                    <option value="{{ $st }}" {{ $filters['status'] === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            <noscript><button type="submit" class="rounded-lg bg-slate-200 px-3 py-2 text-sm font-semibold">Filter</button></noscript>
        </div>
        <button type="button" onclick="openModal('modal-add-customer')" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Pelanggan
        </button>
    </form>

    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200/90 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <div class="w-full overflow-x-auto">
            <table class="min-w-[900px] w-full text-left text-sm">
                <thead class="bg-slate-50/80 text-xs font-mono uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Kode / Nama</th>
                        <th class="px-4 py-3">Kontak Login</th>
                        <th class="px-4 py-3">Paket</th>
                        <th class="px-4 py-3">Node ODP</th>
                        <th class="px-4 py-3">Alamat</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($customers as $i => $c)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $avatarColors[$i % count($avatarColors)] }}">{{ $c->user->initials() }}</span>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">{{ $c->user->name }}</p>
                                        <p class="font-mono text-[11px] text-slate-400">{{ $c->customer_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <p class="whitespace-nowrap font-mono text-xs text-slate-600">{{ $c->phone }}</p>
                                <p class="max-w-[180px] truncate text-[11px] text-slate-400">{{ $c->user->email }}</p>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3"><span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-xs font-medium text-slate-700">{{ $c->package?->name ?? '-' }}</span></td>
                            <td class="whitespace-nowrap px-4 py-3 font-mono text-xs text-slate-600">{{ $c->node?->name ?? '-' }}</td>
                            <td class="max-w-[180px] truncate px-4 py-3 text-sm text-slate-600">{{ $c->address }}</td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-medium {{ $custMeta[$c->status]['pill'] }}">
                                    <span class="{{ $c->status === 'isolated' ? 'animate-pulse ' : '' }}h-1.5 w-1.5 rounded-full {{ $custMeta[$c->status]['dot'] }}"></span>
                                    {{ $custMeta[$c->status]['label'] }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                <button type="button" title="Edit Data" data-edit-open data-url="{{ route('admin.customers.update', $c) }}"
                                    data-name="{{ $c->user->name }}" data-email="{{ $c->user->email }}" data-phone="{{ $c->phone }}"
                                    data-address="{{ $c->address }}" data-package-id="{{ $c->package_id }}" data-node-id="{{ $c->node_id }}" data-status="{{ $c->status }}"
                                    class="rounded-md p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                </button>
                                @if ($c->status === 'active')
                                    <form method="POST" action="{{ route('admin.customers.status', $c) }}" class="inline" onsubmit="return confirm('Isolir pelanggan {{ $c->customer_code }}? Layanan akan ditangguhkan.')">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="isolated">
                                        <button type="submit" title="Isolir" class="rounded-md p-1.5 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        </button>
                                    </form>
                                @elseif ($c->status === 'isolated')
                                    <form method="POST" action="{{ route('admin.customers.status', $c) }}" class="inline" onsubmit="return confirm('Pulihkan layanan pelanggan {{ $c->customer_code }} menjadi Active?')">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" title="Pulihkan" class="rounded-md p-1.5 text-emerald-500 transition hover:bg-emerald-50 hover:text-emerald-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.customers.destroy', $c) }}" class="inline" onsubmit="return confirm('Hapus permanen pelanggan {{ $c->customer_code }} beserta akun loginnya?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus" class="rounded-md p-1.5 text-slate-300 transition hover:bg-rose-50 hover:text-rose-500">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-10 text-center text-sm italic text-slate-400">Tidak ada pelanggan yang cocok dengan filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 bg-slate-50/60 px-4 py-3">
            <p class="text-xs text-slate-500">Menampilkan <span class="font-semibold">{{ $customers->count() }}</span> pelanggan @if($filters['q'] || $filters['status'])<span class="text-slate-900">(terfilter)</span>@endif</p>
        </div>
    </div>
@endsection

@push('modals')
    <div id="modal-add-customer" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modal-add-customer')"></div>
        <div class="absolute left-1/2 top-1/2 max-h-[92vh] w-full max-w-lg -translate-x-1/2 -translate-y-1/2 overflow-y-auto rounded-xl border border-slate-200/90 bg-white shadow-2xl">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Tambah Pelanggan Baru</h3>
                    <p class="text-xs text-slate-500">Akun login dibuat otomatis & tersimpan ke tabel users + customers (FR-MST-03)</p>
                </div>
                <button type="button" onclick="closeModal('modal-add-customer')" class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form method="POST" action="{{ route('admin.customers.store') }}" class="space-y-4 px-6 py-5">
                @csrf
                @include('admin.customers._fields', ['packages' => $packages, 'nodes' => $nodes, 'editMode' => false])
                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" onclick="closeModal('modal-add-customer')" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Simpan Pelanggan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-edit-customer" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modal-edit-customer')"></div>
        <div class="absolute left-1/2 top-1/2 max-h-[92vh] w-full max-w-lg -translate-x-1/2 -translate-y-1/2 overflow-y-auto rounded-xl border border-slate-200/90 bg-white shadow-2xl">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Edit Data Pelanggan</h3>
                    <p class="text-xs text-slate-500">Perubahan tersimpan ke database secara langsung</p>
                </div>
                <button type="button" onclick="closeModal('modal-edit-customer')" class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form id="edit-customer-form" method="POST" action="#" class="space-y-4 px-6 py-5">
                @csrf
                @method('PUT')
                @include('admin.customers._fields', ['packages' => $packages, 'nodes' => $nodes, 'editMode' => true])
                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" onclick="closeModal('modal-edit-customer')" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-edit-open]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var form = document.getElementById('edit-customer-form');
                form.action = btn.dataset.url;
                form.querySelector('[name=name]').value = btn.dataset.name;
                form.querySelector('[name=email]').value = btn.dataset.email;
                form.querySelector('[name=phone]').value = btn.dataset.phone;
                form.querySelector('[name=address]').value = btn.dataset.address;
                form.querySelector('[name=package_id]').value = btn.dataset.packageId;
                form.querySelector('[name=node_id]').value = btn.dataset.nodeId;
                form.querySelector('[name=status]').value = btn.dataset.status;
                openModal('modal-edit-customer');
            });
        });
    </script>
@endpush
