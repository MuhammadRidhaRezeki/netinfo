<?php

namespace App\Models;

use App\Support\Codes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'node_id',
        'customer_code',
        'address',
        'phone',
        'installation_date',
        'status',
        'isolated_by_node_id',
    ];

    protected function casts(): array
    {
        return [
            'installation_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(NetworkNode::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function scopeFilter($query, ?string $q, ?string $status)
    {
        return $query
            ->when($q, fn ($qq) => $qq->where(function ($w) use ($q) {
                $w->where('customer_code', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u
                        ->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%"));
            }))
            ->when(in_array($status, ['active', 'isolated', 'inactive']), fn ($qq) => $qq->where('status', $status));
    }

    public static function generateCode(): string
    {
        return Codes::forCustomer();
    }
}
