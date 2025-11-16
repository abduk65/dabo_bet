<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ProductionOrder;
use App\Models\Product;
use App\Models\User;

class ProductionOutput extends Model
{
    use HasFactory;

    protected $table = 'production_output';

    protected $fillable = [
        'production_order_id',
        'product_id',
        'quantity_produced',
        'quantity_good',
        'quantity_rejected',
        'rejection_reason',
        'unit_production_cost',
        'recorded_by_user_id',
    ];

    protected $casts = [
        'quantity_produced' => 'decimal:2',
        'quantity_good' => 'decimal:2',
        'quantity_rejected' => 'decimal:2',
        'unit_production_cost' => 'decimal:2',
    ];

    /**
     * Automatically create finished goods inventory transaction when output is recorded
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($output) {
            $output->createFinishedGoodsInventoryTransaction();
        });
    }

    /**
     * Get the production order this output belongs to
     */
    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    /**
     * Get the product produced
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who recorded this output
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    /**
     * Calculate unit production cost (COGS per unit)
     */
    public function calculateUnitProductionCost(): void
    {
        $totalProductionCost = $this->productionOrder->production_cost_total;

        if ($this->quantity_good > 0) {
            $this->unit_production_cost = $totalProductionCost / $this->quantity_good;
            $this->save();
        }
    }

    /**
     * Create finished goods inventory transaction
     */
    private function createFinishedGoodsInventoryTransaction(): void
    {
        if ($this->quantity_good > 0) {
            FinishedGoodsInventory::create([
                'product_id' => $this->product_id,
                'branch_id' => 1, // Main production branch
                'transaction_type' => 'production',
                'quantity' => $this->quantity_good,
                'transaction_date' => now(),
                'reference_type' => 'ProductionOutput',
                'reference_id' => $this->id,
                'unit_cost' => $this->unit_production_cost,
                'created_by_user_id' => $this->recorded_by_user_id,
            ]);
        }
    }

    /**
     * Calculate quality control pass rate
     */
    public function qualityPassRate(): float
    {
        if ($this->quantity_produced == 0) {
            return 0.0;
        }

        return ($this->quantity_good / $this->quantity_produced) * 100;
    }

    /**
     * Calculate rejection rate
     */
    public function rejectionRate(): float
    {
        if ($this->quantity_produced == 0) {
            return 0.0;
        }

        return ($this->quantity_rejected / $this->quantity_produced) * 100;
    }
}
