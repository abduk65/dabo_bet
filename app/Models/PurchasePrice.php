<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\InventoryItem;
use App\Models\Unit;
use App\Models\User;

class PurchasePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'unit_price',
        'unit_id',
        'effective_date',
        'expiry_date',
        'currency_code',
        'created_by_user_id',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    // Relationships
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    // Scopes
    public function scopeCurrent($query)
    {
        return $query->whereNull('expiry_date')
            ->orWhere('expiry_date', '>', now());
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('effective_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', $date);
            });
    }

    // Static method to get current price for an item
    public static function getCurrentPrice(int $inventoryItemId): ?self
    {
        return self::where('inventory_item_id', $inventoryItemId)
            ->current()
            ->latest('effective_date')
            ->first();
    }
}
