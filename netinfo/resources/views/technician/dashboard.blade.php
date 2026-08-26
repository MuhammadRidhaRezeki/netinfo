@extends('layouts.app')

@section('title', 'Dashboard Teknisi')
@section('page_title', 'Dashboard')
@php $techName = auth()->user()->name; @endphp
@section('page_subtitle', 'Selamat bertugas, ' . $techName . ' — ' . now()->translatedFormat('l, d F Y'))

@section('content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">{{ $custActive }}</p>
            <p class="mt-1 text-sm text-slate-500">Client Aktif</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 text-red-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                </span>
                @if ($ticketsHigh > 0)
                    <span class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-200">{{ $ticketsHigh }} High</span>
                @endif
            </div>
            <p class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">{{ $ticketsActive }}</p>
            <p class="mt-1 text-sm text-slate-500">Tiket Aktif Milik Anda</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">{{ $ticketsResolvedMonth }}</p>
            <p class="mt-1 text-sm text-slate-500">Selesai Bulan Ini</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-100 text-sky-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">{{ $nodeNormal }}<span class="text-base font-semibold text-slate-400">/{{ $nodeTotal }}</span></p>
            <p class="mt-1 text-sm text-slate-500">Node Normal</p>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Antrean Tiket Terbaru (Ditugaskan ke Anda)</h3>
                <p class="text-xs text-slate-500">Diurutkan berdasarkan prioritas tertinggi</p>
            </div>
            <a href="{{ route('technician.tickets.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">Semua Tiket Saya &rarr;</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($queue as $t)
                <li class="flex flex-col gap-3 px-5 py-4 transition hover:bg-slate-50/70 sm:flex-row sm:items-center">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono text-xs font-bold text-indigo-600">{{ $t->ticket_code }}</span>
                            <span class="inline-flex rounded-md px-2 py-0.5 text-[10px] font-bold uppercase ring-1 ring-inset {{ $t->priority === 'high' ? 'bg-red-50 text-red-700 ring-red-600/20' : ($t->priority === 'medium' ? 'bg-amber-50 text-amber-700 ring-amber-600/20' : 'bg-slate-100 text-slate-600 ring-slate-500/20') }}">{{ $t->priority }}</span>
                            <span class="inline-flex rounded-md px-2 py-0.5 text-[10px] font-semibold {{ $t->status === 'open' ? 'bg-sky-50 text-sky-700' : 'bg-amber-50 text-amber-700' }}">{{ $t->status === 'open' ? 'Menunggu' : 'Sedang Dikerjakan' }}</span>
                        </div>
                        <p class="mt-1 truncate text-sm font-semibold text-slate-800">{{ $t->issue_title }}</p>
                        <p class="truncate text-xs text-slate-500">{{ $t->customer?->user?->name }} · {{ $t->customer?->address }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <span class="rounded-md bg-slate-100 px-2 py-1 font-mono text-[10px] font-semibold text-slate-500">{{ $t->customer?->node?->name ?? '-' }}</span>
                        <a href="{{ route('tickets.show', $t) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-500">Kerjakan</a>
                    </div>
                </li>
            @empty
                <li class="px-5 py-10 text-center text-sm italic text-slate-400">Tidak ada tugas aktif saat ini.</li>
            @endforelse
        </ul>
    </div>
@endsection
