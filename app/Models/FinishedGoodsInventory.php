<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinishedGoodsInventory extends Model
{
    use HasFactory;

    protected $table = 'finished_goods_inventory';

    protected $fillable = [
        'product_id',
        'branch_id',
        'transaction_type',
        'quantity',
        'transaction_date',
        'reference_type',
        'reference_id',
        'unit_cost',
        'created_by_user_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'transaction_date' => 'datetime',
        'unit_cost' => 'decimal:2',
    ];

    /**
     * Automatically update product stock levels when transaction is created
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($transaction) {
            $transaction->updateProductStock();
        });
    }

    /**
     * Get the product this transaction applies to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the branch this transaction occurred at
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who created this transaction
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Update product stock levels based on transaction type
     */
    private function updateProductStock(): void
    {
        // In a real implementation, you might have a current_stock field on products table
        // or a separate finished_goods_stock table per branch
        // This is a placeholder for that logic

        // Types that increase stock: production, return
        // Types that decrease stock: dispatch, sales, waste, adjustment (if negative)

        $multiplier = $this->getStockMultiplier();

        // Update product stock logic would go here
        // For now, this is just a placeholder
    }

    /**
     * Get stock multiplier based on transaction type
     */
    private function getStockMultiplier(): int
    {
        return match($this->transaction_type) {
            'production', 'return' => 1,
            'dispatch', 'sales', 'waste' => -1,
            'adjustment' => $this->quantity >= 0 ? 1 : -1,
            default => 0,
        };
    }

    /**
     * Calculate total value of this transaction
     */
    public function totalValue(): float
    {
        if ($this->unit_cost === null) {
            return 0.0;
        }

        return abs($this->quantity) * $this->unit_cost;
    }

    /**
     * Scope for transactions of a specific type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('transaction_type', $type);
    }

    /**
     * Scope for transactions within a date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    /**
     * Scope for transactions at a specific branch
     */
    public function scopeAtBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }
}
