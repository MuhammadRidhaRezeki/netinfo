<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard(Request $request)
    {
        $customer = $this->currentCustomer($request);

        $invoices = $customer->invoices()->orderByDesc('billing_month')->get();
        $unpaid = $invoices->where('payment_status', 'unpaid');

        return view('customer.dashboard', [
            'customer' => $customer,
            'unpaidInvoices' => $unpaid->sortBy('due_date')->values(),
            'unpaidSum' => (float) $unpaid->sum('amount'),
            'unpaidCount' => $unpaid->count(),
            'nearestDue' => $unpaid->sortBy('due_date')->first(),
            'paidInvoices' => $invoices->where('payment_status', 'paid')->sortByDesc('billing_month')->values(),
            'paidCount' => $invoices->where('payment_status', 'paid')->count(),
            'activeTickets' => $customer->tickets()->whereIn('status', ['open', 'in_progress'])->count(),
            'totalTickets' => $customer->tickets()->count(),
            'latestResolved' => $customer->tickets()->where('status', 'resolved')->latest('resolved_at')->first(),
        ]);
    }

    public function helpcare(Request $request)
    {
        $customer = $this->currentCustomer($request);

        $tickets = $customer->tickets()->with(['technician', 'histories.user'])->orderByDesc('created_at')->get();

        $featured = $tickets->firstWhere('status', 'in_progress')
            ?? $tickets->firstWhere('status', 'open')
            ?? $tickets->first();

        return view('customer.helpcare', [
            'customer' => $customer,
            'tickets' => $tickets,
            'featured' => $featured,
        ]);
    }

    public function storeTicket(Request $request)
    {
        $customer = $this->currentCustomer($request);

        $data = $request->validate([
            'issue_title' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:low,medium,high'],
            'description' => ['required', 'string', 'min:10'],
        ], [
            'description.min' => 'Deskripsi minimal 10 karakter agar teknisi memahami kendala Anda.',
        ]);

        $ticket = Ticket::create([
            'customer_id' => $customer->id,
            'ticket_code' => \App\Support\Codes::forTicket(),
            'issue_title' => $data['issue_title'],
            'description' => $data['description'],
            'priority' => $data['priority'],
            'status' => 'open',
        ]);

        $ticket->addHistory(Auth::id(), 'created', 'Tiket dibuat oleh pelanggan melalui halaman Helpcare.');

        return redirect()
            ->route('customer.helpcare')
            ->with('success', "Laporan {$ticket->ticket_code} berhasil dikirim dan langsung terlihat di panel Admin & Teknisi.");
    }

    public function uploadProof(Request $request, Invoice $invoice)
    {
        $customer = $this->currentCustomer($request);
        abort_unless($invoice->customer_id === $customer->id, 403);

        if ($invoice->payment_status === 'paid') {
            return back()->with('error', "Invoice {$invoice->invoice_code} sudah lunas.");
        }

        $data = $request->validate([
            'proof' => ['required', 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
            'payment_method' => ['required', 'in:SeaBank Transfer,QRIS'],
        ]);

        $path = $data['proof']->store('proofs', 'public');
        $invoice->update(['payment_proof' => $path, 'payment_method' => $data['payment_method']]);

        return back()->with('success', "Bukti pembayaran untuk {$invoice->invoice_code} terunggah. Menunggu verifikasi admin.");
    }

    public function proofFile(Invoice $invoice)
    {
        $user = Auth::user();
        $isOwner = $user->isCustomer() && $invoice->customer->user_id === $user->id;
        abort_unless($user->isAdmin() || $isOwner, 403);

        abort_if(empty($invoice->payment_proof), 404);

        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        abort_unless($disk->exists($invoice->payment_proof), 404, 'Berkas bukti tidak ditemukan.');

        return response()->file($disk->path($invoice->payment_proof));
    }

    private function currentCustomer(Request $request)
    {
        return $request->user()->customer()->with(['user', 'package', 'node'])->firstOrFail();
    }
}
