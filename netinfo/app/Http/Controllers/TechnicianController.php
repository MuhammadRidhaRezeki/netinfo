<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\NetworkNode;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function dashboard(Request $request)
    {
        $me = $request->user();

        $myActive = Ticket::where('technician_id', $me->id)
            ->whereIn('status', ['open', 'in_progress']);

        $queue = Ticket::with(['customer.user', 'customer.node'])
            ->where('technician_id', $me->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->orderByRaw("field(priority, 'high', 'medium', 'low')")
            ->orderBy('created_at')
            ->take(6)
            ->get();

        return view('technician.dashboard', [
            'custActive' => Customer::where('status', 'active')->count(),
            'ticketsActive' => $myActive->count(),
            'ticketsHigh' => (clone $myActive)->where('priority', 'high')->count(),
            'ticketsResolvedMonth' => Ticket::where('technician_id', $me->id)
                ->where('status', 'resolved')
                ->whereMonth('resolved_at', now()->month)
                ->whereYear('resolved_at', now()->year)
                ->count(),
            'nodeNormal' => NetworkNode::where('status', 'active')->count(),
            'nodeTotal' => NetworkNode::count(),
            'queue' => $queue,
        ]);
    }

    public function customersIndex(Request $request)
    {
        $customers = Customer::with(['user', 'package', 'node'])
            ->filter($request->string('q')->toString() ?: null, $request->string('status')->toString() ?: null)
            ->orderByDesc('created_at')
            ->get();

        return view('technician.customers', [
            'customers' => $customers,
            'filters' => ['q' => $request->string('q'), 'status' => $request->string('status')],
        ]);
    }
}
