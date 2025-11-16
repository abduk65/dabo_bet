<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyProductionAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'unit_id',
        'type',
        'remark',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    /**
     * Get the product this adjustment applies to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the unit for quantity measurement
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Create finished goods inventory transaction for this adjustment
     */
    public function createInventoryAdjustment(int $userId): void
    {
        FinishedGoodsInventory::create([
            'product_id' => $this->product_id,
            'branch_id' => 1, // Main production branch
            'transaction_type' => 'waste',
            'quantity' => -$this->quantity, // Negative for waste/loss
            'transaction_date' => now(),
            'reference_type' => 'DailyProductionAdjustment',
            'reference_id' => $this->id,
            'unit_cost' => null, // Cost already absorbed in production
            'created_by_user_id' => $userId,
        ]);
    }

    /**
     * Scope for adjustments by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for today's adjustments
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now());
    }
}
