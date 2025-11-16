<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_type',
        'inventory_item_id',
        'quantity',
        'unit_id',
        'unit_cost',
        'transaction_date',
        'reference_type',
        'reference_id',
        'branch_id',
        'created_by_user_id',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'transaction_date' => 'datetime',
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

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    // Polymorphic reference
    public function reference()
    {
        if ($this->reference_type && $this->reference_id) {
            $class = 'App\\Models\\' . $this->reference_type;
            if (class_exists($class)) {
                return $class::find($this->reference_id);
            }
        }
        return null;
    }

    // Scopes
    public function scopeForItem($query, int $inventoryItemId)
    {
        return $query->where('inventory_item_id', $inventoryItemId);
    }

    public function scopeForBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('transaction_type', $type);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    // Update inventory stock after transaction
    protected static function boot()
    {
        parent::boot();

        static::created(function ($transaction) {
            $transaction->updateInventoryStock();
        });
    }

    private function updateInventoryStock(): void
    {
        $item = $this->inventoryItem;

        // Determine if this is addition or subtraction
        $multiplier = in_array($this->transaction_type, ['purchase', 'adjustment']) ? 1 : -1;

        $item->current_stock_quantity += ($this->quantity * $multiplier);
        $item->save();
    }
}
