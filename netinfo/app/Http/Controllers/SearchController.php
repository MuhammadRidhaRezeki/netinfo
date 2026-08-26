<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Ticket;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $q = trim((string) $request->query('q', ''));

        $results = [
            'tickets' => collect(),
            'customers' => collect(),
            'invoices' => collect(),
        ];

        if ($q !== '') {
            $like = "%{$q}%";

            $ticketQuery = Ticket::with(['customer.user'])->where(function ($w) use ($like) {
                $w->where('ticket_code', 'like', $like)->orWhere('issue_title', 'like', $like);
            });

            $invoiceQuery = Invoice::with(['customer.user'])->where('invoice_code', 'like', $like);

            if ($user->isTechnician()) {
                $ticketQuery->where('technician_id', $user->id);
            } elseif ($user->isCustomer()) {
                $customerId = $user->customer?->id;
                $ticketQuery->where('customer_id', $customerId);
                $invoiceQuery->where('customer_id', $customerId);
            }

            $results['tickets'] = $ticketQuery->latest()->take(8)->get();

            if ($user->isAdmin() || $user->isTechnician()) {
                $results['customers'] = Customer::with(['package', 'node'])
                    ->whereHas('user', fn ($u) => $u->where('name', 'like', $like)->orWhere('email', 'like', $like))
                    ->orWhere('customer_code', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->take(8)
                    ->get();
            }

            if (! $user->isTechnician()) {
                $results['invoices'] = $invoiceQuery->orderByDesc('billing_month')->take(8)->get();
            }
        }

        return view('search.results', [
            'q' => $q,
            'results' => $results,
            'total' => $results['tickets']->count() + $results['customers']->count() + $results['invoices']->count(),
        ]);
    }
}
