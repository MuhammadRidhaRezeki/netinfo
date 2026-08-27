@extends('layouts.app')

@section('title', 'Hasil Pencarian')
@section('page_title', 'Hasil Pencarian')
@php
    $isTech = auth()->user()->isTechnician();
    $statPill = ['open' => 'bg-sky-50 text-sky-700 border-sky-200', 'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200', 'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'closed' => 'bg-slate-50 text-slate-600 border-slate-200'];
    $custPill = ['active' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'isolated' => 'bg-rose-50 text-rose-700 border-rose-200', 'inactive' => 'bg-slate-50 text-slate-600 border-slate-200'];
@endphp
@section('page_subtitle', $q ? "Menampilkan hasil untuk \"{$q}\"" : 'Masukkan kata kunci pada kolom pencarian')

@section('content')
    <form action="{{ route('search') }}" method="GET" class="mb-6 flex max-w-xl items-center gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari kode/judul tiket, nama pelanggan, no. invoice..." autofocus
            class="flex-1 rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Cari</button>
    </form>

    @if ($q === '')
        <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <svg class="mx-auto h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <p class="mt-3 text-sm font-semibold text-slate-500">Mulai ketik kata kunci di kolom pencarian navbar atau di atas.</p>
        </div>
    @elseif ($total === 0)
        <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <p class="text-sm font-semibold text-slate-500">Tidak ada hasil untuk "{{ $q }}".</p>
            <p class="mt-1 text-xs text-slate-400">Coba kode tiket (TICK-...), nama/kode pelanggan, atau kode invoice (INV-...).</p>
        </div>
    @endif

    @if ($results['tickets']->isNotEmpty())
        <div class="mb-6 overflow-hidden rounded-xl border border-slate-200/90 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <div class="border-b border-slate-100 px-5 py-3.5">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tiket Gangguan &middot; {{ $results['tickets']->count() }} hasil</h3>
            </div>
            <ul class="divide-y divide-slate-100">
                @foreach ($results['tickets'] as $t)
                    <li class="flex flex-wrap items-center gap-3 px-5 py-3.5 transition hover:bg-slate-50/60">
                        <span class="font-mono text-xs font-medium text-slate-700">{{ $t->ticket_code }}</span>
                        <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700">{{ $t->issue_title }}</span>
                        <span class="hidden text-xs text-slate-400 sm:inline">{{ $t->customer?->user?->name }}</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-medium {{ $statPill[$t->status] }}">{{ strtoupper($t->status) }}</span>
                        @if (!auth()->user()->isCustomer())
                            <a href="{{ route('tickets.show', $t) }}" class="text-xs font-medium text-slate-900 underline underline-offset-4 hover:text-slate-700">Detail</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($results['customers']->isNotEmpty())
        <div class="mb-6 overflow-hidden rounded-xl border border-slate-200/90 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <div class="border-b border-slate-100 px-5 py-3.5">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pelanggan &middot; {{ $results['customers']->count() }} hasil</h3>
            </div>
            <ul class="divide-y divide-slate-100">
                @foreach ($results['customers'] as $c)
                    <li class="flex flex-wrap items-center gap-3 px-5 py-3.5 transition hover:bg-slate-50/60">
                        <span class="font-mono text-xs font-medium text-slate-500">{{ $c->customer_code }}</span>
                        <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700">{{ $c->user?->name }}</span>
                        <span class="hidden text-xs text-slate-400 sm:inline">{{ $c->phone }} &middot; {{ $c->package?->name }}</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-medium capitalize {{ $custPill[$c->status] }}">{{ $c->status }}</span>
                        @if (!auth()->user()->isCustomer())
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.customers.index', ['q' => $c->customer_code]) : route('technician.customers.index', ['q' => $c->customer_code]) }}" class="text-xs font-medium text-slate-900 underline underline-offset-4 hover:text-slate-700">Lihat</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($results['invoices']->isNotEmpty() && !$isTech)
        <div class="overflow-hidden rounded-xl border border-slate-200/90 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <div class="border-b border-slate-100 px-5 py-3.5">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Invoice &middot; {{ $results['invoices']->count() }} hasil</h3>
            </div>
            <ul class="divide-y divide-slate-100">
                @foreach ($results['invoices'] as $inv)
                    <li class="flex flex-wrap items-center gap-3 px-5 py-3.5 transition hover:bg-slate-50/60">
                        <span class="font-mono text-xs font-medium text-slate-700">{{ $inv->invoice_code }}</span>
                        <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700">{{ $inv->customer?->user?->name }}</span>
                        <span class="hidden text-xs text-slate-400 sm:inline">{{ $inv->billing_month }} &middot; Rp {{ number_format((float) $inv->amount, 0, ',', '.') }}</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-medium uppercase {{ $inv->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">{{ $inv->payment_status }}</span>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.billing.index', ['q' => $inv->invoice_code]) }}" class="text-xs font-medium text-slate-900 underline underline-offset-4 hover:text-slate-700">Lihat</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
