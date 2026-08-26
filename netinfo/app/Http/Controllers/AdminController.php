<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\NetworkNode;
use App\Models\Package;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use App\Support\Codes;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $month = now()->format('Y-m');

        $ticketCounts = Ticket::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $tOpen = (int) ($ticketCounts['open'] ?? 0);
        $tProg = (int) ($ticketCounts['in_progress'] ?? 0);
        $tRes = (int) ($ticketCounts['resolved'] ?? 0);
        $tClo = (int) ($ticketCounts['closed'] ?? 0);

        $custStatus = Customer::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        $paidMonth = Invoice::query()->where('billing_month', $month)->where('payment_status', 'paid');
        $revenueMonth = (float) $paidMonth->sum('amount');
        $invTotalMonth = Invoice::query()->where('billing_month', $month)->count();
        $invPaidMonth = $paidMonth->count();

        $chartRows = Invoice::query()
            ->selectRaw("billing_month, sum(case when payment_status = 'paid' then amount else 0 end) as revenue")
            ->whereIn('billing_month', collect(range(5, 0))->map(fn ($i) => now()->subMonths($i)->format('Y-m')))
            ->groupBy('billing_month')
            ->pluck('revenue', 'billing_month');

        $chart = collect(range(5, 0))->map(function ($i) use ($chartRows) {
            $dt = now()->subMonths($i);
            $key = $dt->format('Y-m');

            return [
                'label' => $dt->translatedFormat('M'),
                'value' => round((float) ($chartRows[$key] ?? 0) / 1_000_000, 2),
            ];
        });
        $maxRevenue = max(1.0, $chart->max('value'));

        return view('admin.dashboard', [
            'custActive' => (int) ($custStatus['active'] ?? 0),
            'custIsolated' => (int) ($custStatus['isolated'] ?? 0),
            'custInactive' => (int) ($custStatus['inactive'] ?? 0),
            'custTotal' => Customer::count(),
            'tActive' => $tOpen + $tProg,
            'tHighActive' => Ticket::whereIn('status', ['open', 'in_progress'])->where('priority', 'high')->count(),
            'donut' => [$tOpen, $tProg, $tRes, $tClo],
            'tTotal' => $tOpen + $tProg + $tRes + $tClo,
            'revenueFmt' => 'Rp ' . number_format($revenueMonth, 0, ',', '.'),
            'invPaidMonth' => $invPaidMonth,
            'invTotalMonth' => $invTotalMonth,
            'invPct' => $invTotalMonth ? (int) round($invPaidMonth / $invTotalMonth * 100) : 0,
            'nodeActive' => NetworkNode::where('status', 'active')->count(),
            'nodeTotal' => NetworkNode::count(),
            'nodeAttention' => NetworkNode::whereIn('status', ['maintenance', 'down'])->count(),
            'chart' => $chart,
            'maxRevenue' => $maxRevenue,
            'recentTickets' => Ticket::with(['customer.user', 'technician'])->latest()->take(6)->get(),
        ]);
    }

    public function customersIndex(Request $request)
    {
        $customers = Customer::with(['user', 'package', 'node'])
            ->filter($request->string('q')->toString() ?: null, $request->string('status')->toString() ?: null)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.customers.index', [
            'customers' => $customers,
            'packages' => Package::orderBy('price')->get(),
            'nodes' => NetworkNode::orderBy('name')->get(),
            'filters' => ['q' => $request->string('q'), 'status' => $request->string('status')],
        ]);
    }

    public function customersStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
            'package_id' => ['required', 'exists:packages,id'],
            'node_id' => ['required', 'exists:network_nodes,id'],
            'phone' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:10', 'max:15'],
            'address' => ['required', 'string'],
            'installation_date' => ['required', 'date'],
            'status' => ['required', 'in:active,isolated,inactive'],
        ], [
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'phone.min' => 'Nomor WhatsApp minimal 10 digit.',
            'phone.max' => 'Nomor WhatsApp maksimal 15 digit.',
        ]);

        $customer = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'] ?? 'password',
                'role' => 'customer',
            ]);

            return Customer::create([
                'user_id' => $user->id,
                'package_id' => $data['package_id'],
                'node_id' => $data['node_id'],
                'customer_code' => Codes::forCustomer(),
                'address' => $data['address'],
                'phone' => $data['phone'],
                'installation_date' => $data['installation_date'],
                'status' => $data['status'],
            ]);
        });

        return redirect()
            ->route('admin.customers.index')
            ->with('success', "Pelanggan {$customer->user->name} ({$customer->customer_code}) berhasil ditambahkan beserta akun loginnya.");
    }

    public function customersUpdate(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $customer->user_id],
            'package_id' => ['required', 'exists:packages,id'],
            'node_id' => ['required', 'exists:network_nodes,id'],
            'phone' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:10', 'max:15'],
            'address' => ['required', 'string'],
            'status' => ['required', 'in:active,isolated,inactive'],
        ], [
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'phone.min' => 'Nomor WhatsApp minimal 10 digit.',
            'phone.max' => 'Nomor WhatsApp maksimal 15 digit.',
        ]);

        DB::transaction(function () use ($customer, $data) {
            $customer->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            $customer->update([
                'package_id' => $data['package_id'],
                'node_id' => $data['node_id'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'status' => $data['status'],
                'isolated_by_node_id' => null,
            ]);
        });

        return redirect()->route('admin.customers.index')->with('success', "Data pelanggan {$customer->customer_code} berhasil diperbarui.");
    }

    public function customersDestroy(Customer $customer)
    {
        if ($customer->invoices()->exists()) {
            return redirect()->route('admin.customers.index')
                ->with('error', "Pelanggan {$customer->customer_code} memiliki riwayat tagihan sehingga tidak dapat dihapus.");
        }

        $code = $customer->customer_code;
        $customer->tickets()->delete();
        $customer->user->delete();

        return redirect()->route('admin.customers.index')->with('success', "Pelanggan {$code} berhasil dihapus.");
    }

    public function customerChangeStatus(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'status' => ['required', 'in:active,isolated,inactive'],
        ]);

        $from = $customer->status;
        $to = $data['status'];

        if ($from === $to) {
            return back()->with('error', 'Status pelanggan sudah ' . $to . '.');
        }

        $customer->update(['status' => $to, 'isolated_by_node_id' => null]);

        $label = ['active' => 'Aktif', 'isolated' => 'Diisolir', 'inactive' => 'Nonaktif'];

        return back()->with('success', "Pelanggan {$customer->customer_code} diubah dari '{$label[$from]}' menjadi '{$label[$to]}'.");
    }
}
