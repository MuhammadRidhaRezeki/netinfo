<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NetworkNode extends Model
{
    protected $table = 'network_nodes';

    protected $fillable = ['name', 'location_area', 'ip_address', 'status'];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'node_id');
    }

    public function activeCustomersCount(): int
    {
        return $this->customers()->where('status', 'active')->count();
    }
}
