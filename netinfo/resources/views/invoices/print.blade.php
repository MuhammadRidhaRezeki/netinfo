<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Faktur {{ $invoice->invoice_code }} · NetInfo</title>
<style>
    @@page { size: A4; margin: 12mm; }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: "Segoe UI", Tahoma, Arial, sans-serif;
        background: #e2e8f0;
        color: #0f172a;
        font-size: 13px;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .sheet {
        width: 210mm;
        max-width: 100%;
        margin: 10mm auto;
        padding: 14mm 15mm;
        background: #ffffff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, .18);
        position: relative;
    }

    /* ---------- Kop ---------- */
    .kop { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
    .brand { display: flex; gap: 12px; align-items: center; }
    .brand-mark {
        width: 46px; height: 46px; border-radius: 12px; flex-shrink: 0;
        background: linear-gradient(135deg, #4f46e5, #0ea5e9);
        color: #fff; font-size: 19px; font-weight: 800;
        display: flex; align-items: center; justify-content: center; letter-spacing: -.5px;
    }
    .brand-name { font-size: 17px; font-weight: 800; letter-spacing: -.3px; }
    .brand-sub { font-size: 10px; color: #475569; margin-top: 1px; }
    .brand-meta { font-size: 10.5px; color: #64748b; line-height: 1.55; margin-top: 7px; }

    .doc-id { text-align: right; }
    .doc-label {
        display: inline-block; font-size: 10px; font-weight: 800; letter-spacing: 2.5px;
        color: #4f46e5; text-transform: uppercase;
        border-bottom: 2px solid #4f46e5; padding-bottom: 3px;
    }
    .doc-number { font-family: Consolas, monospace; font-size: 15px; font-weight: 700; margin-top: 8px; letter-spacing: .5px; }
    .doc-date { font-size: 11px; color: #64748b; margin-top: 4px; }

    .kop-rule { height: 4px; margin-top: 14px; border-radius: 2px;
        background: linear-gradient(90deg, #4f46e5 0%, #0ea5e9 55%, #e2e8f0 100%); }

    /* ---------- Cap status ---------- */
    .stamp {
        position: absolute; top: 118px; right: 15mm;
        transform: rotate(-6deg);
        border: 3px double currentColor; border-radius: 6px;
        padding: 7px 18px; text-align: center;
        font-weight: 900; font-size: 20px; letter-spacing: 2px; text-transform: uppercase;
        opacity: .92;
    }
    .stamp small { display: block; font-size: 9px; font-weight: 700; letter-spacing: 3px; }
    .stamp-paid   { color: #15803d; background: rgba(34, 197, 94, .07); }
    .stamp-unpaid { color: #b91c1c; background: rgba(239, 68, 68, .06); }
    .stamp-cancel { color: #475569; background: rgba(100, 116, 139, .08); }

    /* ---------- Info pelanggan & faktur ---------- */
    .meta-grid { display: grid; grid-template-columns: 1.25fr 1fr; gap: 22px; margin-top: 26px; }
    .section-title {
        font-size: 10px; font-weight: 800; letter-spacing: 1.8px; text-transform: uppercase;
        color: #64748b; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 9px;
    }
    .cust-name { font-size: 15px; font-weight: 800; }
    .cust-code {
        display: inline-block; font-family: Consolas, monospace; font-size: 11px; font-weight: 700;
        color: #4338ca; background: #eef2ff; border-radius: 5px; padding: 2px 8px; margin-left: 6px;
    }
    .kv { display: grid; grid-template-columns: 108px 1fr; row-gap: 3px; font-size: 12px; }
    .kv dt { color: #64748b; }
    .kv dd { font-weight: 600; }
    .overdue-flag { color: #b91c1c !important; font-weight: 800 !important; }

    /* ---------- Strip ODP ---------- */
    .odp-strip {
        margin-top: 16px; padding: 8px 12px; border-radius: 8px;
        background: #f0f9ff; border: 1px solid #bae6fd;
        font-size: 11.5px; color: #0c4a6e;
    }
    .odp-strip b { color: #075985; }

    /* ---------- Tabel layanan ---------- */
    table.items { width: 100%; border-collapse: collapse; margin-top: 18px; font-size: 12px; }
    table.items th {
        background: #f1f5f9; color: #475569; text-align: left;
        font-size: 10px; letter-spacing: 1.2px; text-transform: uppercase;
        padding: 8px 10px; border-bottom: 2px solid #cbd5e1;
    }
    table.items td { padding: 11px 10px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
    .item-name { font-weight: 700; font-size: 12.5px; }
    .item-desc { font-size: 11px; color: #64748b; margin-top: 2px; }
    .num { text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap; }

    .totals { display: flex; justify-content: flex-end; margin-top: 12px; }
    .totals-box { width: 300px; }
    .totals-row { display: flex; justify-content: space-between; padding: 5px 10px; font-size: 12px; }
    .grand {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 4px; padding: 10px 12px; border-radius: 8px;
        background: #eef2ff; border: 1px solid #c7d2fe;
        font-size: 14px; font-weight: 900; color: #3730a3;
    }

    /* ---------- Footer ---------- */
    .pay-note {
        margin-top: 26px; border-radius: 8px; padding: 11px 14px;
        background: #f8fafc; border: 1px solid #e2e8f0;
        font-size: 11.5px; line-height: 1.65; color: #334155;
    }
    .thanks { text-align: center; font-style: italic; color: #64748b; font-size: 11.5px; margin-top: 22px; }

    .signatures { display: flex; justify-content: space-between; gap: 40px; margin-top: 30px; }
    .sig { flex: 1; text-align: center; font-size: 11.5px; }
    .sig-line { margin-top: 52px; border-top: 1px dashed #94a3b8; padding-top: 5px; color: #475569; }

    .footer-brand {
        margin-top: 26px; padding-top: 9px; border-top: 1px solid #e2e8f0;
        display: flex; justify-content: space-between; font-size: 9.5px; color: #94a3b8;
    }

    /* ---------- Tombol melayang ---------- */
    .fab {
        position: fixed; right: 22px; bottom: 22px; z-index: 50;
        display: flex; gap: 10px;
    }
    .fab button {
        cursor: pointer; border: 0; border-radius: 999px; padding: 12px 22px;
        font-size: 13px; font-weight: 800; color: #fff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, .28);
        transition: transform .15s ease, filter .15s ease;
    }
    .fab button:hover { transform: translateY(-2px); filter: brightness(1.08); }
    .fab .btn-print { background: #059669; }
    .fab .btn-back { background: #475569; }

    @@media print {
        body { background: #ffffff; }
        .sheet { width: auto; margin: 0; padding: 0; box-shadow: none; }
        .stamp { top: 96px; }
        .no-print { display: none !important; }
    }
</style>
</head>
<body>

<div class="sheet">
    @php
        $statusMeta = match ($invoice->payment_status) {
            'paid'      => ['class' => 'stamp-paid',   'label' => 'Lunas / Paid',       'sub' => 'Pembayaran Diverifikasi'],
            'cancelled' => ['class' => 'stamp-cancel', 'label' => 'Dibatalkan',         'sub' => 'Cancelled'],
            default     => ['class' => 'stamp-unpaid', 'label' => 'Belum Bayar / Unpaid', 'sub' => 'Menunggu Pembayaran'],
        };
        $custPhone = $invoice->customer->phone ?: ($invoice->customer->user->phone ?? null);
        $rp = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.');
    @endphp

    {{-- ======== KOP ======== --}}
    <div class="kop">
        <div class="brand">
            <div class="brand-mark">NI</div>
            <div>
                <p class="brand-name">NetInfo</p>
                <p class="brand-sub">Network &amp; Internet Management Solution</p>
            </div>
        </div>
        <div class="doc-id">
            <span class="doc-label">Faktur Tagihan</span>
            <p class="doc-number">{{ $invoice->invoice_code }}</p>
            <p class="doc-date">Dicetak: {{ $printedAt->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>
    </div>
    <div class="brand-meta">
        Jl. Medan&ndash;Banda Aceh KM 285, Lhokseumawe, Aceh 24351<br>
        Telepon / WhatsApp: 0812-0000-0000 &middot; Email: support@netinfo.id
    </div>
    <div class="kop-rule"></div>

    {{-- ======== CAP STATUS ======== --}}
    <div class="stamp {{ $statusMeta['class'] }}">
        {{ $statusMeta['label'] }}
        <small>{{ $statusMeta['sub'] }}</small>
    </div>

    {{-- ======== DATA PELANGGAN & METADATA ======== --}}
    <div class="meta-grid">
        <div>
            <p class="section-title">Ditagihkan Kepada</p>
            <p class="cust-name">{{ $invoice->customer->user->name }}<span class="cust-code">{{ $invoice->customer->customer_code }}</span></p>
            <dl class="kv" style="margin-top: 8px;">
                <dt>Alamat</dt><dd>{{ $invoice->customer->address }}</dd>
                <dt>No. HP</dt><dd>{{ $custPhone ?: '-' }}</dd>
                <dt>Status Layanan</dt><dd style="text-transform: capitalize;">{{ $invoice->customer->status }}</dd>
            </dl>
        </div>
        <div>
            <p class="section-title">Detail Faktur</p>
            <dl class="kv">
                <dt>Tanggal Terbit</dt><dd>{{ $invoice->created_at->translatedFormat('d F Y') }}</dd>
                <dt>Periode Pemakaian</dt><dd>{{ $periodLabel }}</dd>
                <dt>Jatuh Tempo</dt>
                <dd class="{{ $invoice->isOverdue() ? 'overdue-flag' : '' }}">{{ $invoice->due_date->translatedFormat('d F Y') }}{{ $invoice->isOverdue() ? ' &middot; LEWAT TEMPO' : '' }}</dd>
                @if ($invoice->payment_date)
                    <dt>Dibayar Pada</dt><dd>{{ $invoice->payment_date->translatedFormat('d F Y, H:i') }} WIB</dd>
                @endif
                <dt>Metode Pembayaran</dt><dd>{{ $invoice->payment_method ?: '-' }}</dd>
            </dl>
        </div>
    </div>

    {{-- ======== NODE ODP TERHUBUNG ======== --}}
    @if ($invoice->customer->node)
        <div class="odp-strip">
            Titik distribusi jaringan: <b>{{ $invoice->customer->node->name }}</b>
            &middot; Wilayah {{ $invoice->customer->node->location_area }}
            @if ($invoice->customer->node->ip_address)
                &middot; IP Manajemen <b style="font-family: Consolas, monospace;">{{ $invoice->customer->node->ip_address }}</b>
            @endif
        </div>
    @endif

    {{-- ======== RINCIAN LAYANAN ======== --}}
    <table class="items">
        <thead>
            <tr>
                <th style="width: 44%;">Deskripsi Layanan</th>
                <th>Periode</th>
                <th class="num">Qty</th>
                <th class="num">Tarif / Bulan</th>
                <th class="num">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <p class="item-name">{{ $invoice->customer->package?->name ?: 'Langganan Internet' }}</p>
                    @if ($invoice->customer->package?->speed_mbps)
                        <p class="item-desc">Kecepatan {{ $invoice->customer->package->speed_mbps }} Mbps
                            @if ($invoice->customer->package?->description)
                                &mdash; {{ Str::limit($invoice->customer->package->description, 90) }}
                            @endif
                        </p>
                    @endif
                </td>
                <td>{{ $periodLabel }}</td>
                <td class="num">1</td>
                <td class="num">{{ $rp($invoice->amount) }}</td>
                <td class="num">{{ $rp($invoice->amount) }}</td>
            </tr>
        </tbody>
    </table>

    @php
        // Biaya tambahan (PPN / admin) tidak dipungut saat ini;
        // jika suatu ada, cukup isi variabel berikut agar barisnya tampil otomatis.
        $ppnFee = null;
    @endphp

    <div class="totals">
        <div class="totals-box">
            <div class="totals-row"><span style="color:#64748b;">Subtotal Langganan</span><b>{{ $rp($invoice->amount) }}</b></div>
            @if ($ppnFee !== null && $ppnFee > 0)
                <div class="totals-row"><span style="color:#64748b;">PPN / Biaya Admin</span><b>{{ $rp($ppnFee) }}</b></div>
            @endif
            <div class="grand"><span>Total Bayar</span><span>{{ $rp($invoice->amount) }}</span></div>
        </div>
    </div>

    {{-- ======== FOOTER BUKTI BAYAR ======== --}}
    <div class="pay-note">
        @if ($invoice->payment_status === 'paid')
            <b>Keterangan Pembayaran:</b> Diterima penuh via <b>{{ $invoice->payment_method ?: 'Transfer Bank SeaBank / QRIS' }}</b>.
            Dana telah masuk ke rekening resmi NetInfo (SeaBank 9981237810913 a.n. Muhammad Ridha Rezeki).
        @elseif ($invoice->payment_status === 'cancelled')
            <b>Status:</b> Invoice ini dibatalkan dan tidak dapat dibayarkan. Hubungi admin apabila Anda merasa menerima faktur ini secara keliru.
        @else
            <b>Instruksi Pembayaran:</b> Transfer Bank SeaBank <b>9981237810913</b> a.n. Muhammad Ridha Rezeki,
            atau pindai <b>QRIS Dinamis</b> melalui portal pelanggan NetInfo. Setelah membayar, unggah bukti transfer
            agar diverifikasi admin.
        @endif
    </div>
    <p class="thanks">&ldquo;Terima kasih atas pembayaran Anda. Struk ini merupakan bukti transaksi yang sah.&rdquo;</p>

    <div class="signatures">
        <div class="sig">
            Hormat Kami,<br><b>NetInfo &mdash; NOC / Billing</b>
            <div class="sig-line">( )</div>
        </div>
        <div class="sig">
            Diterima oleh,<br><b>Pelanggan / Penerima</b>
            <div class="sig-line">( {{ $invoice->customer->user->name }} )</div>
        </div>
    </div>

    <div class="footer-brand">
        <span>Dokumen ini dibuat otomatis oleh sistem NetInfo &middot; {{ $invoice->invoice_code }}</span>
        <span>Status: {{ strtoupper($statusMeta['label']) }}</span>
    </div>
</div>

{{-- ======== NAVIGASI MELAYANG ======== --}}
<div class="fab no-print">
    <button type="button" class="btn-back" onclick="window.history.length > 1 ? window.history.back() : window.location.assign('/')">&larr; Kembali</button>
    <button type="button" class="btn-print" onclick="window.print()">&#128424; Cetak / Print PDF</button>
</div>

<script>
    if (new URLSearchParams(window.location.search).has('print')) {
        window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 350); });
    }
</script>

</body>
</html>
