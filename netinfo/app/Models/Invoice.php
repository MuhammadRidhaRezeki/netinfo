<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    public const STATUSES = ['unpaid', 'paid', 'cancelled'];

    protected $fillable = [
        'customer_id',
        'invoice_code',
        'billing_month',
        'amount',
        'due_date',
        'payment_status',
        'payment_method',
        'payment_date',
        'payment_proof',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'payment_date' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeFilter($query, ?string $q, ?string $status)
    {
        return $query
            ->when($q, fn ($qq) => $qq->where(function ($w) use ($q) {
                $w->where('invoice_code', 'like', "%{$q}%")
                    ->orWhereHas('customer.user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
            }))
            ->when(in_array($status, self::STATUSES), fn ($qq) => $qq->where('payment_status', $status));
    }

    public static function existsFor(string $billingMonth, int $customerId): bool
    {
        return self::query()
            ->where('billing_month', $billingMonth)
            ->where('customer_id', $customerId)
            ->exists();
    }

    public function isOverdue(): bool
    {
        return $this->payment_status === 'unpaid' && $this->due_date->endOfDay()->isPast();
    }
}
