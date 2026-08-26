<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    public const STATUSES = ['open', 'in_progress', 'resolved', 'closed'];
    public const PRIORITIES = ['low', 'medium', 'high'];

    protected $fillable = [
        'customer_id',
        'technician_id',
        'ticket_code',
        'issue_title',
        'description',
        'priority',
        'status',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(TicketHistory::class)->orderBy('created_at')->orderBy('id');
    }

    public function scopeFilter($query, ?string $q, ?string $status, ?string $priority): Builder
    {
        return $query
            ->when($q, fn ($qq) => $qq->where(function ($w) use ($q) {
                $w->where('ticket_code', 'like', "%{$q}%")
                    ->orWhere('issue_title', 'like', "%{$q}%")
                    ->orWhereHas('customer.user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
            }))
            ->when(in_array($status, self::STATUSES), fn ($qq) => $qq->where('status', $status))
            ->when(in_array($priority, self::PRIORITIES), fn ($qq) => $qq->where('priority', $priority));
    }

    public function addHistory(int $userId, string $actionType, ?string $note = null): TicketHistory
    {
        return $this->histories()->create([
            'user_id' => $userId,
            'action_type' => $actionType,
            'note' => $note,
        ]);
    }
}
