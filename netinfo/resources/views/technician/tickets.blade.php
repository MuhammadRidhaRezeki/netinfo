@extends('layouts.app')

@section('title', 'Tiket Saya')
@section('page_title', 'Work Order Saya')
@section('page_subtitle', 'Tiket yang ditugaskan kepada Anda — kerjakan sesuai SLA prioritas')

@section('content')
    @php
        $prio = ['high' => ['label' => 'High', 'pill' => 'bg-rose-50 text-rose-700 border-rose-200', 'bar' => 'bg-rose-500'], 'medium' => ['label' => 'Medium', 'pill' => 'bg-amber-50 text-amber-700 border-amber-200', 'bar' => 'bg-amber-500'], 'low' => ['label' => 'Low', 'pill' => 'bg-slate-50 text-slate-600 border-slate-200', 'bar' => 'bg-slate-400']];
        $stat = ['open' => ['label' => 'Menunggu', 'pill' => 'bg-sky-50 text-sky-700 border-sky-200'], 'in_progress' => ['label' => 'Sedang Dikerjakan', 'pill' => 'bg-amber-50 text-amber-700 border-amber-200'], 'resolved' => ['label' => 'Selesai', 'pill' => 'bg-emerald-50 text-emerald-700 border-emerald-200'], 'closed' => ['label' => 'Ditutup', 'pill' => 'bg-slate-50 text-slate-600 border-slate-200']];
    @endphp

    <form method="GET" action="{{ route('technician.tickets.index') }}" class="flex flex-wrap items-center gap-2">
        <div class="relative">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari kode / judul / pelanggan..." class="w-60 rounded-lg border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
        </div>
        <select name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm capitalize text-slate-700 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
            <option value="">Semua Status</option>
            @foreach (['open', 'in_progress', 'resolved'] as $st)<option value="{{ $st }}" {{ $filters['status'] === $st ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($st)) }}</option>@endforeach
        </select>
        <select name="priority" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm capitalize text-slate-700 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
            <option value="">Semua Prioritas</option>
            @foreach (['high', 'medium', 'low'] as $p)<option value="{{ $p }}" {{ $filters['priority'] === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>@endforeach
        </select>
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Filter</button>
    </form>

    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($tickets as $o)
            <div class="relative overflow-hidden rounded-xl border border-slate-200/90 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.04)] transition hover:shadow-md">
                <span class="absolute inset-y-0 left-0 w-1 {{ $prio[$o->priority]['bar'] }}"></span>
                <div class="p-5 pl-6">
                    <div class="flex items-start justify-between gap-2">
                        <span class="font-mono text-xs font-medium text-slate-700">{{ $o->ticket_code }}</span>
                        <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-medium {{ $stat[$o->status]['pill'] }}">{{ $stat[$o->status]['label'] }}</span>
                    </div>
                    <p class="mt-1.5 text-base font-semibold leading-snug text-slate-700">{{ $o->issue_title }}</p>
                    <dl class="mt-3 space-y-1.5 text-sm text-slate-600">
                        <div class="flex items-center gap-2">{{ $o->customer?->user?->name ?? '-' }} &middot; <span class="font-mono text-xs">{{ $o->customer?->node?->name ?? '-' }}</span></div>
                        <div class="flex items-center gap-2 truncate text-xs text-slate-400">{{ $o->customer?->address }}</div>
                        <div class="text-xs text-slate-400">Lapor: {{ $o->created_at->translatedFormat('d M Y, H:i') }}</div>
                    </dl>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-medium {{ $prio[$o->priority]['pill'] }}">{{ $prio[$o->priority]['label'] }}</span>
                        <a href="{{ route('tickets.show', $o) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">
                            Detail & Kerjakan
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-300 bg-white py-14 text-center">
                <p class="text-sm font-semibold text-slate-500">Tidak ada tiket yang cocok dengan filter.</p>
                <p class="text-xs text-slate-400">Work order baru muncul otomatis setelah admin melakukan penugasan.</p>
            </div>
        @endforelse
    </div>
@endsection