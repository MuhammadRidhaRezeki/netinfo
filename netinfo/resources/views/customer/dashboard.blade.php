@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang, ' . $customer->user->name)

@section('content')
    @if ($errors->any())
        <div class="mb-5 flex items-start gap-2.5 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 shadow-sm">
            <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            {{ $errors->first() }}
        </div>
    @endif

    @php
        $bulan = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];
        $nodeStatus = $customer->node?->status;
    @endphp

    @if ($nearestDue)
        <div class="overflow-hidden rounded-xl border border-amber-300 bg-gradient-to-r from-amber-50 to-orange-50 p-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600 ring-4 ring-amber-200/50">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-amber-900">Anda memiliki {{ $unpaidCount }} tagihan belum lunas — total Rp {{ number_format($unpaidSum, 0, ',', '.') }}</p>
                    <p class="mt-0.5 text-sm text-amber-700">Invoice <span class="font-mono font-semibold">{{ $nearestDue->invoice_code }}</span> ({{ \Carbon\Carbon::createFromFormat('Y-m', $nearestDue->billing_month)->translatedFormat('F Y') }}) jatuh tempo {{ $nearestDue->due_date->translatedFormat('d F Y') }}.</p>
                </div>
                <button type="button" onclick="openPaymentModal('{{ $nearestDue->id }}')" class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-amber-500/30 transition hover:bg-amber-400">
                    Bayar Sekarang
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </button>
            </div>
        </div>
    @endif

    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.288 16.038a5.25 5.25 0 017.433 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"/></svg>
                </span>
                <span class="rounded-md bg-indigo-50 px-2 py-1 text-xs font-bold text-indigo-700 ring-1 ring-inset ring-indigo-600/20">{{ $customer->package?->name ?? '-' }}</span>
            </div>
            <p class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900">Rp {{ number_format((float) ($customer->package?->price ?? 0), 0, ',', '.') }}<span class="text-sm font-medium text-slate-400">/bulan</span></p>
            <dl class="mt-3 space-y-1.5 border-t border-slate-100 pt-3 text-xs">
                <div class="flex justify-between"><dt class="text-slate-500">Kode Pelanggan</dt><dd class="font-mono font-semibold text-slate-700">{{ $customer->customer_code }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Terdaftar Sejak</dt><dd class="font-medium text-slate-700">{{ $bulan[(int) $customer->installation_date->format('n')] . ' ' . $customer->installation_date->format('Y') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Node Terhubung</dt><dd class="font-mono font-semibold text-slate-700">{{ $customer->node?->name ?? '-' }}</dd></div>
            </dl>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg {{ $customer->status === 'active' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                @if ($customer->status === 'active' && $nodeStatus !== 'down')
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20"><span class="animate-pulse h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Terhubung</span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-red-50 px-2.5 py-1 text-xs font-bold text-red-700 ring-1 ring-inset ring-red-600/20"><span class="animate-pulse h-1.5 w-1.5 rounded-full bg-red-500"></span> Terputus</span>
                @endif
            </div>
            @if ($customer->status === 'isolated')
                <p class="mt-4 text-lg font-extrabold tracking-tight text-red-700">Layanan Diisolir</p>
                <p class="mt-1 text-xs leading-relaxed text-slate-500">Layanan ditangguhkan karena menunggak. Segera lakukan pembayaran & hubungi admin untuk pemulihan.</p>
            @else
                <p class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900">{{ $customer->package?->speed_mbps ?? '-' }} Mbps<span class="text-sm font-medium text-slate-400"> paket aktif</span></p>
                <p class="mt-1 text-xs text-slate-500">Kondisi node jaringan Anda: <b class="capitalize">{{ $nodeStatus ?? '-' }}</b>{{ $nodeStatus === 'maintenance' ? ' (sedang maintenance)' : '' }}</p>
            @endif
            <div class="mt-3 border-t border-slate-100 pt-3">
                <a href="{{ route('customer.helpcare') }}" class="flex w-full items-center justify-center gap-2 rounded-lg border border-indigo-300 bg-indigo-50 px-4 py-2.5 text-sm font-bold text-indigo-700 transition hover:bg-indigo-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Lapor Gangguan Baru
                </a>
            </div>
        </div>

        <div id="tagihan" class="rounded-xl border {{ $unpaidCount ? 'border-red-200' : 'border-emerald-200' }} bg-white p-5 shadow-sm scroll-mt-24">
            <div class="flex items-center justify-between">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg {{ $unpaidCount ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $unpaidCount ? 'bg-red-50 text-red-700 ring-red-600/20' : 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' }}">{{ $unpaidCount ? 'Belum Lunas' : 'Semua Lunas' }}</span>
            </div>
            <p class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900">Rp {{ number_format($unpaidSum, 0, ',', '.') }}</p>
            @if ($nearestDue)
                <p class="mt-1 text-xs text-slate-500">Jatuh tempo terdekat: <b class="{{ $nearestDue->isOverdue() ? 'text-red-600' : 'text-slate-600' }}">{{ $nearestDue->due_date->translatedFormat('d M Y') }}</b> · {{ $paidCount }} invoice sudah lunas</p>
            @else
                <p class="mt-1 text-xs text-slate-500">{{ $paidCount }} invoice lunas. Terima kasih!</p>
            @endif

            @forelse ($unpaidInvoices as $inv)
                <div class="mt-3 rounded-lg bg-slate-50 p-3 ring-1 ring-inset ring-slate-200">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-mono font-bold text-indigo-600">{{ $inv->invoice_code }}</span>
                        <span class="font-bold text-slate-700">Rp {{ number_format((float) $inv->amount, 0, ',', '.') }}</span>
                    </div>
                    @if ($inv->payment_proof)
                        <p class="mt-2 flex items-center justify-between rounded bg-sky-50 px-2 py-1.5 text-[11px] font-medium text-sky-700 ring-1 ring-inset ring-sky-200">
                            <span class="inline-flex items-center gap-1"><span class="h-1.5 w-1.5 animate-pulse rounded-full bg-sky-500"></span> Menunggu Verifikasi Admin</span>
                            <a href="{{ route('invoices.proof.download', $inv) }}" target="_blank" class="underline">lihat bukti</a>
                        </p>
                    @else
                        <button type="button" onclick="openPaymentModal('{{ $inv->id }}')" class="mt-2 w-full rounded-md bg-indigo-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-indigo-500">Bayar Sekarang</button>
                    @endif
                </div>
            @empty
                <p class="mt-3 text-xs italic text-emerald-600">Tidak ada tagihan aktif. Terima kasih!</p>
            @endforelse
        </div>
    </div>

    @if ($paidInvoices->count())
        <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h3 class="text-base font-bold text-slate-900">Riwayat Tagihan Lunas</h3>
                <span class="text-xs font-medium text-slate-400">{{ $paidInvoices->count() }} invoice terbayar</span>
            </div>
            <ul class="divide-y divide-slate-100">
                @foreach ($paidInvoices as $inv)
                    <li class="flex flex-wrap items-center gap-x-4 gap-y-2 px-6 py-3.5 transition hover:bg-slate-50/70">
                        <span class="font-mono text-xs font-bold text-indigo-600">{{ $inv->invoice_code }}</span>
                        <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700">
                            Periode {{ \Carbon\CarbonImmutable::parse($inv->billing_month . '-01')->translatedFormat('F Y') }}
                            @if ($inv->payment_date)
                                <span class="text-[11px] text-slate-400">&middot; lunas {{ $inv->payment_date->translatedFormat('d M Y') }}</span>
                            @endif
                            @if ($inv->payment_method)
                                <span class="text-[11px] text-slate-400">&middot; {{ $inv->payment_method }}</span>
                            @endif
                        </span>
                        <span class="shrink-0 text-sm font-bold text-emerald-600">Rp {{ number_format((float) $inv->amount, 0, ',', '.') }}</span>
                        <a href="{{ route('invoices.print', $inv) }}" target="_blank" title="Cetak / unduh struk resmi"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-md bg-indigo-50 px-2.5 py-1.5 text-xs font-semibold text-indigo-600 ring-1 ring-inset ring-indigo-200 transition hover:bg-indigo-100">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5m-3 0h.008v.008H15V10.5"/></svg>
                            Cetak Struk
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
            <h3 class="text-base font-bold text-slate-900">Tiket Gangguan Terbaru</h3>
            <a href="{{ route('customer.helpcare') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Helpcare &rarr;</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($customer->tickets()->with('technician')->latest()->take(3)->get() as $t)
                <li class="flex items-center gap-4 px-6 py-4 transition hover:bg-slate-50/70">
                    <span class="hidden font-mono text-xs font-bold text-indigo-600 sm:inline">{{ $t->ticket_code }}</span>
                    <span class="min-w-0 flex-1 truncate text-sm font-medium text-slate-700">{{ $t->issue_title }}</span>
                    @if ($t->technician)<span class="hidden shrink-0 text-[11px] text-slate-400 md:inline">Teknisi: {{ $t->technician->name }}</span>@endif
                    <span class="inline-flex shrink-0 rounded-md px-2 py-1 text-[11px] font-semibold {{ $t->status === 'open' ? 'bg-sky-50 text-sky-700' : ($t->status === 'in_progress' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">{{ ['open' => 'Open', 'in_progress' => 'Dikerjakan', 'resolved' => 'Selesai', 'closed' => 'Ditutup'][$t->status] }}</span>
                </li>
            @empty
                <li class="px-6 py-8 text-center text-sm italic text-slate-400">Belum ada tiket gangguan.</li>
            @endforelse
        </ul>
    </div>
@endsection

@push('modals')
    @if ($unpaidCount > 0)
        <div id="payment-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 p-4 backdrop-blur-sm" onclick="if (event.target === this) closeModal('payment-modal')">
            <div class="flex min-h-full items-center justify-center py-6">
                <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                        <div>
                            <h3 class="text-base font-extrabold tracking-tight text-slate-900">Pembayaran Tagihan</h3>
                            <p class="text-xs text-slate-400">Pilih metode pembayaran, lalu unggah bukti transfer.</p>
                        </div>
                        <button type="button" onclick="closeModal('payment-modal')" class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="max-h-[75vh] space-y-4 overflow-y-auto p-5">
                        <div>
                            <label for="pm-invoice" class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Invoice Tagihan</label>
                            <select id="pm-invoice" onchange="pmSync()"
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                                @foreach ($unpaidInvoices as $inv)
                                    <option value="{{ $inv->id }}"
                                        data-amount="Rp {{ number_format((float) $inv->amount, 0, ',', '.') }}"
                                        data-due="{{ $inv->due_date->translatedFormat('d M Y') }}"
                                        data-proof-url="{{ route('customer.invoices.proof.upload', $inv) }}"
                                        data-has-proof="{{ $inv->payment_proof ? '1' : '0' }}">
                                        {{ $inv->invoice_code }} · Rp {{ number_format((float) $inv->amount, 0, ',', '.') }} · jatuh tempo {{ $inv->due_date->translatedFormat('d M Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="rounded-xl bg-gradient-to-r from-indigo-50 to-sky-50 px-4 py-3 ring-1 ring-inset ring-indigo-100">
                            <p class="text-xs font-medium text-slate-500">Total Tagihan</p>
                            <p id="pm-amount" class="text-xl font-extrabold tracking-tight text-indigo-700">-</p>
                        </div>

                        <div class="grid grid-cols-2 gap-1 rounded-xl bg-slate-100 p-1" role="tablist">
                            <button type="button" id="pm-tabbtn-bank" onclick="pmTab('bank')"
                                class="rounded-lg px-3 py-2 text-sm font-bold transition">Transfer Bank (SeaBank)</button>
                            <button type="button" id="pm-tabbtn-qris" onclick="pmTab('qris')"
                                class="rounded-lg px-3 py-2 text-sm font-bold transition">Bayar via QRIS</button>
                        </div>
                        <p class="-mt-1 text-center text-[11px] italic text-slate-400">Metode yang dipilih akan tercatat pada struk / faktur resmi.</p>

                        <div id="pm-panel-bank" role="tabpanel">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Bank Tujuan</span>
                                    <span class="inline-flex items-center gap-2 text-sm font-extrabold text-sky-700">
                                        <span class="flex h-6 w-6 items-center justify-center rounded-md bg-sky-600 text-[10px] font-black text-white">SB</span> SeaBank
                                    </span>
                                </div>
                                <div class="mt-3 flex items-center justify-between gap-2 rounded-lg bg-white px-3 py-2.5 ring-1 ring-inset ring-slate-200">
                                    <div class="min-w-0">
                                        <p class="text-[10px] uppercase tracking-wide text-slate-400">Nomor Rekening</p>
                                        <p class="font-mono text-base font-extrabold tracking-wider text-slate-900">9981237810913</p>
                                    </div>
                                    <button type="button" onclick="pmCopy(this)" title="Salin nomor rekening"
                                        class="inline-flex shrink-0 items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-500">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                                        Salin
                                    </button>
                                </div>
                                <p class="mt-2.5 text-xs text-slate-500">Atas Nama: <b class="text-slate-800">Muhammad Ridha Rezeki</b></p>
                            </div>
                        </div>

                        <div id="pm-panel-qris" role="tabpanel" class="hidden">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <p class="mb-3 text-center text-xs font-semibold text-slate-500">Pindai kode QRIS menggunakan aplikasi e-wallet / m-banking Anda.</p>
                                <div class="flex justify-center">
                                    <img src="{{ asset('images/qris.jpg') }}" alt="Kode QRIS NetInfo"
                                        class="h-56 w-56 rounded-lg bg-white object-contain p-2 ring-1 ring-inset ring-slate-200"
                                        onerror="this.classList.add('hidden'); document.getElementById('qris-fallback').classList.remove('hidden'); this.closest('.flex').classList.remove('justify-center');">
                                    <div id="qris-fallback" class="hidden h-56 w-56 flex-col items-center justify-center gap-2 rounded-lg border border-dashed border-slate-300 bg-white p-4 text-center">
                                        <svg class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/></svg>
                                        <p class="text-xs font-bold text-slate-600">Gambar QRIS belum tersedia</p>
                                        <p class="text-[11px] leading-relaxed text-slate-400">Letakkan berkas di <code class="rounded bg-slate-100 px-1">public/images/qris.jpg</code> atau gunakan opsi Transfer Bank di atas.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="pm-form" method="POST" action="" enctype="multipart/form-data" class="border-t border-slate-100 pt-4" onsubmit="pmSubmit(this)">
                            @csrf
                            <input type="hidden" id="pm-method" name="payment_method" value="SeaBank Transfer">
                            <label class="block text-sm font-bold text-slate-700">Konfirmasi — Unggah Bukti Bayar</label>
                            <p class="mt-0.5 text-xs text-slate-400">Format JPG, JPEG, PNG, atau PDF &middot; maksimal 2 MB. Invoice otomatis ditandai <b>Menunggu Verifikasi</b>.</p>
                            <label for="pm-proof-input" class="mt-2 flex cursor-pointer items-center gap-3 rounded-lg border border-dashed border-indigo-300 bg-indigo-50/50 px-3 py-3 transition hover:border-indigo-500 hover:bg-indigo-50">
                                <svg class="h-6 w-6 shrink-0 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                <span class="min-w-0 flex-1">
                                    <span id="pm-file-name" class="block truncate text-sm font-semibold text-indigo-700">Pilih berkas bukti transfer&hellip;</span>
                                    <span class="block text-[11px] text-slate-400">Klik untuk memilih gambar / PDF</span>
                                </span>
                            </label>
                            <input type="file" id="pm-proof-input" name="proof" required accept=".jpg,.jpeg,.png,.pdf" class="sr-only" onchange="pmFileChosen(this)">
                            <button type="submit" id="pm-submit" class="mt-3 flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/30 transition hover:bg-emerald-500">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                Kirim Bukti ke Admin
                            </button>
                            <div id="pm-pending" class="mt-3 hidden rounded-lg bg-sky-50 px-3 py-2.5 text-xs font-medium leading-relaxed text-sky-700 ring-1 ring-inset ring-sky-200">
                                Bukti untuk invoice ini sudah terunggah dan sedang <b>Menunggu Verifikasi Admin</b>.
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openPaymentModal(invoiceId) {
                var sel = document.getElementById('pm-invoice');
                if (invoiceId && sel.querySelector('option[value="' + invoiceId + '"]')) sel.value = invoiceId;
                pmSync();
                openModal('payment-modal');
            }

            function pmSync() {
                var sel = document.getElementById('pm-invoice');
                var opt = sel.options[sel.selectedIndex];
                if (!opt) return;
                document.getElementById('pm-amount').textContent = opt.dataset.amount;
                var form = document.getElementById('pm-form');
                form.action = opt.dataset.proofUrl;
                var pending = opt.dataset.hasProof === '1';
                document.getElementById('pm-pending').classList.toggle('hidden', !pending);
                document.getElementById('pm-submit').classList.toggle('hidden', pending);
                var fileInput = document.getElementById('pm-proof-input');
                fileInput.required = !pending;
                fileInput.value = '';
                pmFileChosen(fileInput);
            }

            function pmTab(tab) {
                var isBank = tab === 'bank';
                var activeCls = ['bg-white', 'shadow', 'text-indigo-700'];
                var idleCls = ['text-slate-500', 'hover:text-slate-700'];
                [['pm-tabbtn-bank', isBank], ['pm-tabbtn-qris', !isBank]].forEach(function (pair) {
                    var btn = document.getElementById(pair[0]);
                    activeCls.forEach(function (c) { btn.classList.toggle(c, pair[1]); });
                    idleCls.forEach(function (c) { btn.classList.toggle(c, !pair[1]); });
                });
                document.getElementById('pm-panel-bank').classList.toggle('hidden', !isBank);
                document.getElementById('pm-panel-qris').classList.toggle('hidden', isBank);
                var methodInput = document.getElementById('pm-method');
                if (methodInput) methodInput.value = isBank ? 'SeaBank Transfer' : 'QRIS';
            }

            function pmCopy(btn) {
                var num = '9981237810913';
                var done = function () {
                    var old = btn.innerHTML;
                    btn.innerHTML = '&#10003; Tersalin!';
                    btn.classList.replace('bg-indigo-600', 'bg-emerald-600');
                    setTimeout(function () {
                        btn.innerHTML = old;
                        btn.classList.replace('bg-emerald-600', 'bg-indigo-600');
                    }, 2000);
                };
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(num).then(done);
                } else {
                    var ta = document.createElement('textarea');
                    ta.value = num;
                    ta.style.position = 'fixed';
                    ta.style.opacity = '0';
                    document.body.appendChild(ta);
                    ta.select();
                    try { document.execCommand('copy'); } catch (e) {}
                    ta.remove();
                    done();
                }
            }

            function pmFileChosen(input) {
                var name = input.files.length ? input.files[0].name : '';
                var el = document.getElementById('pm-file-name');
                el.textContent = name || 'Pilih berkas bukti transfer\u2026';
                el.classList.toggle('italic', !name);
            }

            function pmSubmit(form) {
                var btn = document.getElementById('pm-submit');
                btn.disabled = true;
                btn.classList.add('opacity-60');
                btn.textContent = 'Mengunggah\u2026';
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal('payment-modal');
            });

            pmTab('bank');
            pmSync();
        </script>
    @endif
@endpush
