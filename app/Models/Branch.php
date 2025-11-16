<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // Scope for main branch
    public function scopeMain($query)
    {
        return $query->where('type', 'main');
    }

    // Scope for sub branches
    public function scopeSub($query)
    {
        return $query->where('type', 'sub');
    }

    // Scope for active branches
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
