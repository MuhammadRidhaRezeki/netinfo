<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function adminIndex(Request $request)
    {
        $tickets = Ticket::with(['customer.user', 'customer.node', 'technician'])
            ->filter(
                $request->string('q')->toString() ?: null,
                $request->string('status')->toString() ?: null,
                $request->string('priority')->toString() ?: null,
            )
            ->orderByRaw("field(status, 'open', 'in_progress', 'resolved', 'closed')")
            ->orderByDesc('created_at')
            ->get();

        $base = Ticket::query();
        $technicians = \App\Models\User::where('role', 'technician')->orderBy('name')->get();

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'technicians' => $technicians,
            'stats' => [
                'open' => (clone $base)->where('status', 'open')->count(),
                'in_progress' => (clone $base)->where('status', 'in_progress')->count(),
                'resolved' => (clone $base)->where('status', 'resolved')->count(),
                'closed' => (clone $base)->where('status', 'closed')->count(),
            ],
            'filters' => [
                'q' => $request->string('q'),
                'status' => $request->string('status'),
                'priority' => $request->string('priority'),
            ],
        ]);
    }

    public function technicianIndex(Request $request)
    {
        $me = $request->user();

        $tickets = Ticket::with(['customer.user', 'customer.node', 'technician'])
            ->where('technician_id', $me->id)
            ->filter(
                $request->string('q')->toString() ?: null,
                $request->string('status')->toString() ?: null,
                $request->string('priority')->toString() ?: null,
            )
            ->orderByRaw("field(status, 'in_progress', 'open', 'resolved')")
            ->orderByRaw("field(priority, 'high', 'medium', 'low')")
            ->orderByDesc('created_at')
            ->get();

        return view('technician.tickets', [
            'tickets' => $tickets,
            'filters' => [
                'q' => $request->string('q'),
                'status' => $request->string('status'),
                'priority' => $request->string('priority'),
            ],
        ]);
    }

    public function show(Request $request, Ticket $ticket)
    {
        $user = $request->user();
        $this->authorizeView($user, $ticket);

        $ticket->load(['customer.user', 'customer.node', 'technician', 'histories.user']);

        return view('tickets.show', [
            'ticket' => $ticket,
            'canWork' => $user->isTechnician() && $ticket->technician_id === $user->id && in_array($ticket->status, ['open', 'in_progress']),
        ]);
    }

    public function assign(Request $request, Ticket $ticket)
    {
        abort_unless($request->user()->isAdmin(), 403);

        $data = $request->validate([
            'technician_id' => ['required', 'exists:users,id,role,technician'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($ticket, $data, $request) {
            $tech = \App\Models\User::find($data['technician_id']);
            $wasOpen = $ticket->status === 'open';

            $ticket->update([
                'technician_id' => $tech->id,
                'status' => $wasOpen ? 'in_progress' : $ticket->status,
            ]);

            $ticket->addHistory($request->user()->id, 'assigned', 'Ditugaskan kepada ' . $tech->name . ($data['note'] ? '. Catatan: ' . $data['note'] : '.'));

            if ($wasOpen) {
                $ticket->addHistory($request->user()->id, 'status_changed', 'Status diubah dari open menjadi in_progress.');
            }
        });

        return redirect()
            ->route($request->input('redirect', 'admin.tickets.index'))
            ->with('success', "Tiket {$ticket->ticket_code} ditugaskan ke teknisi & riwayat tercatat (FR-TCK-02).");
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $user = $request->user();
        abort_unless($user->isTechnician() && $ticket->technician_id === $user->id, 403);

        $data = $request->validate([
            'status' => ['required', 'in:in_progress,resolved,closed'],
            'resolution_note' => ['required_if:status,resolved', 'nullable', 'string', 'min:5'],
        ], [
            'resolution_note.required_if' => 'Catatan solusi wajib diisi saat menyelesaikan tiket.',
            'resolution_note.min' => 'Catatan solusi minimal 5 karakter.',
        ]);

        $from = $ticket->status;
        $to = $data['status'];

        if (! in_array($to, $this->allowedTransitions($from), true)) {
            return back()->with('error', "Transisi status '{$from}' → '{$to}' tidak diizinkan.");
        }

        $note = $data['resolution_note'] ?? null;

        DB::transaction(function () use ($ticket, $to, $note, $user, $from) {
            $ticket->update([
                'status' => $to,
                'resolved_at' => $to === 'resolved' ? now() : $ticket->resolved_at,
            ]);

            $ticket->addHistory(
                $user->id,
                'status_changed',
                "Status diubah dari {$from} menjadi {$to}." . ($note ? ' Solusi: ' . $note : '')
            );
        });

        return redirect()
            ->route('technician.tickets.index')
            ->with('success', "Tiket {$ticket->ticket_code} diubah menjadi '{$to}'.");
    }

    public function addNote(Request $request, Ticket $ticket)
    {
        $user = $request->user();
        abort_unless(($user->isTechnician() && $ticket->technician_id === $user->id) || $user->isAdmin(), 403);

        $data = $request->validate([
            'note' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        $ticket->addHistory($user->id, 'note_added', $data['note']);

        return back()->with('success', 'Catatan teknis berhasil ditambahkan ke riwayat.');
    }

    public function export(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);

        $rows = Ticket::with(['customer.user', 'technician'])
            ->filter(
                $request->string('q')->toString() ?: null,
                $request->string('status')->toString() ?: null,
                $request->string('priority')->toString() ?: null,
            )
            ->orderByDesc('created_at')
            ->get();

        $filename = 'rekap-tiket-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Kode Tiket', 'Pelanggan', 'Kode Pelanggan', 'Judul Kendala', 'Prioritas', 'Status', 'Teknisi', 'Dibuat', 'Selesai']);

            foreach ($rows as $t) {
                fputcsv($out, [
                    $t->ticket_code,
                    $t->customer?->user?->name,
                    $t->customer?->customer_code,
                    $t->issue_title,
                    strtoupper($t->priority),
                    $t->status,
                    $t->technician?->name ?? '-',
                    $t->created_at->format('Y-m-d H:i'),
                    $t->resolved_at?->format('Y-m-d H:i') ?? '-',
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function allowedTransitions(string $from): array
    {
        return match ($from) {
            'open' => ['in_progress'],
            'in_progress' => ['resolved'],
            'resolved' => ['closed'],
            default => [],
        };
    }

    private function authorizeView($user, Ticket $ticket): void
    {
        if ($user->isAdmin()) {
            return;
        }

        if ($user->isTechnician()) {
            abort_unless($ticket->technician_id === $user->id, 403, 'Tiket ini bukan penugasan Anda.');
            return;
        }

        abort(403, 'Akses ditolak.');
    }
}
