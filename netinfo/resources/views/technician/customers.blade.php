@extends('layouts.app')

@section('title', 'Data Pelanggan')
@section('page_title', 'Data Pelanggan')
@section('page_subtitle', 'Akses read-only untuk teknisi — data master pelanggan terdaftar')

@section('content')
    @php
        $custMeta = [
            'active' => ['label' => 'Active', 'pill' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'],
            'isolated' => ['label' => 'Isolated', 'pill' => 'bg-red-50 text-red-700 ring-red-600/20'],
            'inactive' => ['label' => 'Inactive', 'pill' => 'bg-slate-100 text-slate-600 ring-slate-500/20'],
        ];
        $avatarColors = ['bg-indigo-100 text-indigo-700', 'bg-sky-100 text-sky-700', 'bg-violet-100 text-violet-700', 'bg-rose-100 text-rose-700'];
    @endphp

    <div class="mb-3 flex items-start gap-2.5 rounded-xl border border-sky-300 bg-sky-50 px-4 py-3 text-sm text-sky-800">
        <svg class="mt-0.5 h-4 w-4 shrink-0 text-sky-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
        Peran teknisi hanya dapat melihat (read-only). Tambah/ubah/hapus serta isolir hanya bisa dilakukan Administrator.
    </div>

    <form method="GET" action="{{ route('technician.customers.index') }}" class="flex flex-wrap items-center gap-2">
        <div class="relative">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari nama / kode / telp..." class="w-60 rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-3 text-sm placeholder:text-slate-400 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100">
        </div>
        <select name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm capitalize text-slate-600 focus:border-indigo-400 focus:outline-none">
            <option value="">Semua Status</option>
            @foreach (['active', 'isolated', 'inactive'] as $st)
                <option value="{{ $st }}" {{ $filters['status'] === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Filter</button>
    </form>

    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="w-full overflow-x-auto">
            <table class="min-w-[800px] divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Kode / Nama</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Kontak</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Paket</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Node ODP</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Alamat</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($customers as $i => $c)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $avatarColors[$i % count($avatarColors)] }}">{{ $c->user->initials() }}</span>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $c->user->name }}</p>
                                        <p class="font-mono text-[11px] text-slate-400">{{ $c->customer_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="whitespace-nowrap font-mono text-xs text-slate-600">{{ $c->phone }}</p>
                                <p class="max-w-[180px] truncate text-[11px] text-slate-400">{{ $c->user->email }}</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-3.5"><span class="rounded-md bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-600/20">{{ $c->package?->name ?? '-' }}</span></td>
                            <td class="whitespace-nowrap px-5 py-3.5 font-mono text-xs text-slate-600">{{ $c->node?->name ?? '-' }}</td>
                            <td class="max-w-[180px] truncate px-5 py-3.5 text-sm text-slate-600">{{ $c->address }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <span class="inline-flex rounded-md px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $custMeta[$c->status]['pill'] }}">{{ $custMeta[$c->status]['label'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-sm italic text-slate-400">Tidak ada pelanggan sesuai filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
