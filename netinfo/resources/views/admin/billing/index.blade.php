@extends('layouts.app')

@section('title', 'Billing & Invoice')
@section('page_title', 'Billing & Invoice')
@section('page_subtitle', 'Generate invoice bulanan & verifikasi bukti pembayaran pelanggan')

@section('content')
    @php
        $payMeta = ['paid' => ['label' => 'Lunas', 'pill' => 'bg-emerald-50 text-emerald-700 border-emerald-200'], 'unpaid' => ['label' => 'Belum Bayar', 'pill' => 'bg-rose-50 text-rose-700 border-rose-200'], 'cancelled' => ['label' => 'Batal', 'pill' => 'bg-slate-50 text-slate-600 border-slate-200']];
        $s = $summary;
        $paidPct = $s['total_count'] ? (int) round($s['paid_count'] / $s['total_count'] * 100) : 0;
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200/90 bg-white p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Tagihan Terbit</p>
            <p class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Rp {{ number_format($s['grand'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-400">{{ $s['total_count'] }} invoice @if($filters['month'] !== 'all')· periode {{ $filters['month'] }}@endif</p>
        </div>
        <div class="rounded-xl border border-emerald-200/90 bg-emerald-50/40 p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <p class="text-xs font-medium text-emerald-700 uppercase tracking-wider">Sudah Lunas</p>
            <p class="mt-2 text-2xl font-bold tracking-tight text-emerald-800">Rp {{ number_format($s['paid_sum'], 0, ',', '.') }}</p>
            <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-emerald-200/60"><div class="h-full rounded-full bg-emerald-500" style="width: {{ $paidPct }}%"></div></div>
            <p class="mt-1.5 text-[11px] font-medium text-emerald-700">{{ $s['paid_count'] }}/{{ $s['total_count'] }} invoice ({{ $paidPct }}%)</p>
        </div>
        <div class="rounded-xl border border-rose-200/90 bg-rose-50/40 p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <p class="text-xs font-medium text-rose-700 uppercase tracking-wider">Belum Bayar</p>
            <p class="mt-2 text-2xl font-bold tracking-tight text-rose-800">Rp {{ number_format($s['unpaid_sum'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-rose-600">{{ $s['unpaid_count'] }} invoice &middot; {{ $s['overdue_count'] }} lewat jatuh tempo</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.billing.index') }}" class="mt-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <select name="month" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
                <option value="all" {{ $filters['month'] === 'all' ? 'selected' : '' }}>Semua Periode</option>
                @foreach ($months as $m)
                    <option value="{{ $m }}" {{ $filters['month'] === $m ? 'selected' : '' }}>Periode: {{ \Carbon\Carbon::createFromFormat('Y-m', $m)->translatedFormat('F Y') }}</option>
                @endforeach
            </select>
            <select name="payment_status" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm capitalize text-slate-700 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
                <option value="">Semua Status</option>
                @foreach (['unpaid' => 'Belum Bayar', 'paid' => 'Lunas'] as $k => $v)
                    <option value="{{ $k }}" {{ $filters['payment_status'] === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
            <input type="hidden" name="q" value="{{ $filters['q'] }}">
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari kode invoice / nama..." class="w-56 rounded-lg border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
            </div>
            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Terapkan</button>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.invoices.export') }}?{{ http_build_query(array_filter(['month' => $filters['month'], 'payment_status' => $filters['payment_status'], 'q' => $filters['q']])) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Ekspor CSV
            </a>
            <button type="button" onclick="openModal('modal-generate')" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Generate Invoice Bulanan
            </button>
        </div>
    </form>

    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200/90 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <div class="w-full overflow-x-auto">
            <table class="min-w-[1000px] w-full text-left text-sm">
                <thead class="bg-slate-50/80 text-xs font-mono uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Kode Invoice</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Periode</th>
                        <th class="px-4 py-3">Nominal</th>
                        <th class="px-4 py-3">Jatuh Tempo</th>
                        <th class="px-4 py-3">Bukti</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($invoices as $inv)
                        @php $overdue = $inv->payment_status === 'unpaid' && $inv->due_date->endOfDay()->isPast(); @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="whitespace-nowrap px-4 py-3"><span class="font-mono text-xs font-medium text-slate-700">{{ $inv->invoice_code }}</span></td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-semibold text-slate-700">{{ $inv->customer?->user?->name ?? '-' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-600">{{ \Carbon\Carbon::createFromFormat('Y-m', $inv->billing_month)->translatedFormat('F Y') }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-bold text-slate-700">Rp {{ number_format((float) $inv->amount, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="text-sm {{ $overdue ? 'font-semibold text-rose-600' : 'text-slate-600' }}">{{ $inv->due_date->format('d M Y') }}</span>
                                @if ($overdue)<span class="block text-[10px] font-bold uppercase text-rose-500">Terlambat {{ (int) abs(now()->diffInDays($inv->due_date)) }} hari</span>@endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                @if ($inv->payment_proof)
                                    <a href="{{ route('invoices.proof.download', $inv) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-md border border-sky-200 bg-sky-50 px-2.5 py-1.5 text-xs font-medium text-sky-700 transition hover:bg-sky-100">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>Lihat
                                    </a>
                                @else
                                    <span class="text-xs italic text-slate-400">belum ada</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-medium {{ $payMeta[$inv->payment_status]['pill'] }}">{{ $payMeta[$inv->payment_status]['label'] }}</span>
                                @if ($inv->payment_date)<span class="block pt-0.5 text-[10px] text-slate-400">{{ $inv->payment_date->format('d M Y H:i') }}</span>@endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right">
                                @if ($inv->payment_status === 'unpaid')
                                    @if ($inv->payment_proof)
                                        <button type="button" data-verify-open data-url="{{ route('admin.invoices.verify', $inv) }}" data-code="{{ $inv->invoice_code }}" data-cust="{{ $inv->customer?->user?->name }}" data-amount="Rp {{ number_format((float) $inv->amount, 0, ',', '.') }}" data-proof-url="{{ route('invoices.proof.download', $inv) }}" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Verifikasi</button>
                                    @else
                                        <span class="text-[11px] italic text-slate-400">menunggu bukti</span>
                                    @endif
                                    @if ($overdue)
                                        <a href="{{ route('admin.customers.status', $inv->customer) . '?status=isolated' }}" onclick="event.preventDefault(); document.getElementById('isol-form-{{ $inv->id }}').submit();" title="Isolir pelanggan menunggak" class="ml-1 inline-flex items-center gap-1 rounded-md border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-medium text-rose-700 transition hover:bg-rose-100">Isolir</a>
                                        <form id="isol-form-{{ $inv->id }}" method="POST" action="{{ route('admin.customers.status', $inv->customer) }}" class="hidden">@csrf @method('PATCH')<input type="hidden" name="status" value="isolated"></form>
                                    @endif
                                @elseif ($inv->payment_status === 'paid')
                                    <a href="{{ route('invoices.print', $inv) }}" target="_blank" title="Cetak faktur resmi {{ $inv->invoice_code }}" class="text-xs font-medium text-slate-900 underline underline-offset-4 hover:text-slate-700">Cetak</a>
                                @elseif ($inv->payment_status === 'cancelled')
                                    <span class="text-[11px] italic text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-10 text-center text-sm italic text-slate-400">Tidak ada invoice sesuai filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('modals')
    <div id="modal-generate" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modal-generate')"></div>
        <div class="absolute left-1/2 top-1/2 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-xl border border-slate-200/90 bg-white shadow-2xl">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-semibold text-slate-900">Generate Invoice Bulanan</h3>
                <p class="text-xs text-slate-500">FR-BIL-01 &middot; Massal untuk seluruh pelanggan status active</p>
            </div>
            <form method="POST" action="{{ route('admin.invoices.generate') }}" class="space-y-4 px-6 py-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700">Periode Tagihan</label>
                    <input type="month" name="billing_month" required value="{{ $generateMonth }}" min="2024-01" class="mt-1.5 block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
                </div>
                <div class="space-y-2.5 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm">
                    <div class="flex justify-between"><span class="text-slate-500">Pelanggan aktif ditagih</span><span class="font-semibold text-slate-700">{{ $generateTargets->count() }} orang</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Estimasi total</span><span class="font-semibold text-slate-700">Rp {{ number_format($generateTargets->sum(fn ($c) => $c->package?->price ?? 0), 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Jatuh tempo otomatis</span><span class="font-semibold text-slate-700">tanggal 25</span></div>
                </div>
                <p class="text-xs leading-relaxed text-slate-400">Invoice yang sudah terbit untuk periode sama akan dilewati agar tidak duplikat.</p>
                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" onclick="closeModal('modal-generate')" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Generate Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-verify" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('modal-verify')"></div>
        <div class="absolute left-1/2 top-1/2 max-h-[92vh] w-full max-w-md -translate-x-1/2 -translate-y-1/2 overflow-y-auto rounded-xl border border-slate-200/90 bg-white shadow-2xl">
            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Verifikasi Bukti Pembayaran</h3>
                    <p class="font-mono text-xs text-slate-400"><span id="vf-code">-</span> &middot; <span id="vf-cust">-</span></p>
                </div>
                <button type="button" onclick="closeModal('modal-verify')" class="rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="space-y-4 px-6 py-5">
                <div class="grid grid-cols-2 gap-2 text-center">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-2.5"><p class="text-[10px] font-semibold uppercase text-slate-400">Nominal</p><p class="text-sm font-bold text-slate-700" id="vf-amount">-</p></div>
                    <div class="rounded-lg border border-sky-200 bg-sky-50 p-2.5"><p class="text-[10px] font-semibold uppercase text-sky-500">Bukti Transfer</p><a href="#" target="_blank" id="vf-proof-link" class="text-sm font-bold text-sky-600 underline underline-offset-2">Buka File</a></div>
                </div>
                <form id="vf-form" method="POST" action="#" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <textarea name="note" rows="2" placeholder="Catatan verifikasi (opsional)..." class="block w-full resize-none rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all"></textarea>
                    <div class="flex gap-2 border-t border-slate-100 pt-4">
                        <button type="submit" name="decision" value="reject" formnovalidate class="w-full rounded-lg border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-700 transition hover:bg-rose-100">Tolak Bukti</button>
                        <button type="submit" name="decision" value="approve" class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">Setujui &amp; Lunas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-verify-open]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var form = document.getElementById('vf-form');
                form.action = btn.dataset.url;
                document.getElementById('vf-code').textContent = btn.dataset.code;
                document.getElementById('vf-cust').textContent = btn.dataset.cust;
                document.getElementById('vf-amount').textContent = btn.dataset.amount;
                document.getElementById('vf-proof-link').href = btn.dataset.proofUrl;
                openModal('modal-verify');
            });
        });
    </script>
@endpush
