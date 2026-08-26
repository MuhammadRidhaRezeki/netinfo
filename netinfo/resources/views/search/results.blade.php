@extends('layouts.app')

@section('title', 'Hasil Pencarian')
@section('page_title', 'Hasil Pencarian')
@php
    $isTech = auth()->user()->isTechnician();
    $statPill = ['open' => 'bg-sky-50 text-sky-700 ring-sky-600/20', 'in_progress' => 'bg-amber-50 text-amber-700 ring-amber-600/20', 'resolved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'closed' => 'bg-slate-100 text-slate-600 ring-slate-500/20'];
    $custPill = ['active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'isolated' => 'bg-red-50 text-red-700 ring-red-600/20', 'inactive' => 'bg-slate-100 text-slate-600 ring-slate-500/20'];
@endphp
@section('page_subtitle', $q ? "Menampilkan hasil untuk \"{$q}\"" : 'Masukkan kata kunci pada kolom pencarian')

@section('content')
    <form action="{{ route('search') }}" method="GET" class="mb-6 flex max-w-xl items-center gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari kode/judul tiket, nama pelanggan, no. invoice..." autofocus
            class="flex-1 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Cari</button>
    </form>

    @if ($q === '')
        <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm">
            <svg class="mx-auto h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <p class="mt-3 text-sm font-semibold text-slate-500">Mulai ketik kata kunci di kolom pencarian navbar atau di atas.</p>
        </div>
    @elseif ($total === 0)
        <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Tidak ada hasil untuk "{{ $q }}".</p>
            <p class="mt-1 text-xs text-slate-400">Coba kode tiket (TICK-...), nama/kode pelanggan, atau kode invoice (INV-...).</p>
        </div>
    @endif

    @if ($results['tickets']->isNotEmpty())
        <div class="mb-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-3.5"><h3 class="text-sm font-bold uppercase tracking-wide text-slate-500">Tiket Gangguan · {{ $results['tickets']->count() }} hasil</h3></div>
            <ul class="divide-y divide-slate-100">
                @foreach ($results['tickets'] as $t)
                    <li class="flex flex-wrap items-center gap-3 px-5 py-3.5 transition hover:bg-slate-50">
                        <span class="font-mono text-xs font-bold text-indigo-600">{{ $t->ticket_code }}</span>
                        <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700">{{ $t->issue_title }}</span>
                        <span class="hidden text-xs text-slate-400 sm:inline">{{ $t->customer?->user?->name }}</span>
                        <span class="inline-flex rounded-md px-2 py-1 text-[11px] font-semibold {{ $statPill[$t->status] }}">{{ strtoupper($t->status) }}</span>
                        @if (!auth()->user()->isCustomer())
                            <a href="{{ route('tickets.show', $t) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500">Detail</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($results['customers']->isNotEmpty())
        <div class="mb-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-3.5"><h3 class="text-sm font-bold uppercase tracking-wide text-slate-500">Pelanggan · {{ $results['customers']->count() }} hasil</h3></div>
            <ul class="divide-y divide-slate-100">
                @foreach ($results['customers'] as $c)
                    <li class="flex flex-wrap items-center gap-3 px-5 py-3.5 transition hover:bg-slate-50">
                        <span class="font-mono text-xs font-bold text-slate-500">{{ $c->customer_code }}</span>
                        <span class="min-w-0 flex-1 truncate text-sm font-semibold text-slate-800">{{ $c->user?->name }}</span>
                        <span class="hidden text-xs text-slate-400 sm:inline">{{ $c->phone }} · {{ $c->package?->name }}</span>
                        <span class="inline-flex rounded-md px-2 py-1 text-[11px] font-semibold capitalize ring-1 ring-inset {{ $custPill[$c->status] }}">{{ $c->status }}</span>
                        @if (!auth()->user()->isCustomer())
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.customers.index', ['q' => $c->customer_code]) : route('technician.customers.index', ['q' => $c->customer_code]) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500">Lihat</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($results['invoices']->isNotEmpty() && !$isTech)
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-3.5"><h3 class="text-sm font-bold uppercase tracking-wide text-slate-500">Invoice · {{ $results['invoices']->count() }} hasil</h3></div>
            <ul class="divide-y divide-slate-100">
                @foreach ($results['invoices'] as $inv)
                    <li class="flex flex-wrap items-center gap-3 px-5 py-3.5 transition hover:bg-slate-50">
                        <span class="font-mono text-xs font-bold text-indigo-600">{{ $inv->invoice_code }}</span>
                        <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700">{{ $inv->customer?->user?->name }}</span>
                        <span class="hidden text-xs text-slate-400 sm:inline">{{ $inv->billing_month }} · Rp {{ number_format((float) $inv->amount, 0, ',', '.') }}</span>
                        <span class="inline-flex rounded-md px-2 py-1 text-[11px] font-semibold uppercase {{ $inv->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">{{ $inv->payment_status }}</span>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.billing.index', ['q' => $inv->invoice_code]) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500">Lihat</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
