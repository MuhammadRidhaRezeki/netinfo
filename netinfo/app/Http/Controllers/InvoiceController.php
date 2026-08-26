<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Support\Codes;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function adminIndex(Request $request)
    {
        $month = $this->currentMonthFilter($request);
        $status = $request->string('payment_status')->toString();
        $q = $request->string('q')->toString();

        $invoices = Invoice::with(['customer.user', 'customer.package'])
            ->when($month !== 'all', fn ($qq) => $qq->where('billing_month', $month))
            ->filter($q ?: null, in_array($status, Invoice::STATUSES) ? $status : null)
            ->orderByDesc('billing_month')
            ->orderBy('invoice_code')
            ->get();

        $base = Invoice::query()
            ->when($month !== 'all', fn ($qq) => $qq->where('billing_month', $month));

        $months = Invoice::query()->select('billing_month')->distinct()->orderByDesc('billing_month')->pluck('billing_month');

        return view('admin.billing.index', [
            'invoices' => $invoices,
            'months' => $months,
            'filters' => ['q' => $q, 'payment_status' => $status, 'month' => $month],
            'summary' => [
                'grand' => (float) (clone $base)->sum('amount'),
                'paid_sum' => (float) (clone $base)->where('payment_status', 'paid')->sum('amount'),
                'paid_count' => (clone $base)->where('payment_status', 'paid')->count(),
                'total_count' => (clone $base)->count(),
                'unpaid_sum' => (float) (clone $base)->where('payment_status', 'unpaid')->sum('amount'),
                'unpaid_count' => (clone $base)->where('payment_status', 'unpaid')->count(),
                'overdue_count' => (clone $base)->where('payment_status', 'unpaid')->whereDate('due_date', '<', now())->count(),
            ],
            'generateMonth' => now()->format('Y-m'),
            'generateTargets' => Customer::where('status', 'active')->with('package')->get(),
        ]);
    }

    public function generateMonthly(Request $request)
    {
        $data = $request->validate([
            'billing_month' => ['required', 'date_format:Y-m', 'after_or_equal:2020-01'],
        ]);

        $month = $data['billing_month'];
        $due = CarbonImmutable::parse($month . '-25');
        $created = 0;
        $skipped = 0;

        foreach (Customer::where('status', 'active')->with('package')->get() as $customer) {
            if (Invoice::existsFor($month, $customer->id)) {
                $skipped++;
                continue;
            }

            Invoice::create([
                'customer_id' => $customer->id,
                'invoice_code' => Codes::forInvoice($month),
                'billing_month' => $month,
                'amount' => $customer->package->price,
                'due_date' => $due->toDateString(),
                'payment_status' => 'unpaid',
            ]);

            $created++;
        }

        if ($created === 0) {
            return redirect()->route('admin.billing.index')->with('error', "Tidak ada invoice baru untuk periode {$month} ({$skipped} sudah terbit sebelumnya).");
        }

        return redirect()->route('admin.billing.index')->with('success', "Generate invoice {$month}: {$created} invoice dibuat" . ($skipped ? ", {$skipped} dilewati karena sudah ada." : '.'));
    }

    public function printInvoice(Request $request, Invoice $invoice)
    {
        $user = $request->user();

        if ($user->isCustomer()) {
            abort_unless($invoice->customer()->where('user_id', $user->id)->exists(), 403, 'Invoice ini bukan milik akun Anda.');
        }

        $invoice->load(['customer.user', 'customer.package', 'customer.node']);

        return view('invoices.print', [
            'invoice' => $invoice,
            'printedAt' => now(),
            'periodLabel' => CarbonImmutable::parse($invoice->billing_month . '-01')->translatedFormat('F Y'),
        ]);
    }

    public function verify(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'decision' => ['required', 'in:approve,reject'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        if ($invoice->payment_status === 'paid') {
            return back()->with('error', "Invoice {$invoice->invoice_code} sudah berstatus lunas.");
        }

        if ($data['decision'] === 'approve') {
            $invoice->update([
                'payment_status' => 'paid',
                'payment_date' => now(),
            ]);

            return back()->with('success', "Invoice {$invoice->invoice_code} disetujui & ditandai LUNAS (FR-BIL-03).");
        }

        $invoice->update(['payment_proof' => null, 'payment_method' => null]);

        return back()->with('success', "Bukti pembayaran {$invoice->invoice_code} ditolak. Pelanggan dapat mengunggah ulang bukti.");
    }

    public function exportCsv(Request $request)
    {
        $month = $this->currentMonthFilter($request);
        $status = $request->string('payment_status')->toString();

        $rows = Invoice::with(['customer.user', 'customer.package'])
            ->when($month !== 'all', fn ($qq) => $qq->where('billing_month', $month))
            ->when(in_array($status, Invoice::STATUSES), fn ($qq) => $qq->where('payment_status', $status))
            ->orderByDesc('billing_month')
            ->orderBy('invoice_code')
            ->get();

        $filename = 'rekap-invoice-' . str_replace('-', '', $month) . '-' . now()->format('His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Kode Invoice', 'Pelanggan', 'Paket', 'Periode', 'Nominal', 'Jatuh Tempo', 'Status', 'Tanggal Bayar']);

            foreach ($rows as $inv) {
                fputcsv($out, [
                    $inv->invoice_code,
                    $inv->customer?->user?->name,
                    $inv->customer?->package?->name,
                    $inv->billing_month,
                    number_format((float) $inv->amount, 2, ',', ''),
                    $inv->due_date->format('Y-m-d'),
                    strtoupper($inv->payment_status),
                    $inv->payment_date?->format('Y-m-d H:i') ?? '-',
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function currentMonthFilter(Request $request): string
    {
        $month = $request->string('month')->toString();

        if ($month === 'all') {
            return 'all';
        }

        return preg_match('/^\d{4}-\d{2}$/', $month) ? $month : now()->format('Y-m');
    }
}
