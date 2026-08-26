<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketHistory extends Model
{
    public const TYPES = ['created', 'assigned', 'status_changed', 'note_added', 'proof_uploaded', 'verified'];

    protected $fillable = [
        'ticket_id',
        'user_id',
        'action_type',
        'note',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusFromAttribute(): ?string
    {
        return $this->parseStatusPair()[0];
    }

    public function getStatusToAttribute(): ?string
    {
        return $this->parseStatusPair()[1];
    }

    private function parseStatusPair(): array
    {
        if ($this->action_type !== 'status_changed' || $this->note === null) {
            return [null, null];
        }

        $status = '(open|in[_ ]progress|resolved|closed)';

        if (preg_match('/Status diubah dari\s+' . $status . '\s+menjadi\s+' . $status . '\./i', $this->note, $m)) {
            return [
                $this->normalizeStatus($m[1]),
                $this->normalizeStatus($m[2]),
            ];
        }

        if (preg_match('/diubah(?: otomatis)? menjadi\s+' . $status . '/i', $this->note, $m)) {
            return [null, $this->normalizeStatus($m[1])];
        }

        return [null, null];
    }

    private function normalizeStatus(string $raw): string
    {
        return str_replace(' ', '_', strtolower(trim($raw)));
    }
}
