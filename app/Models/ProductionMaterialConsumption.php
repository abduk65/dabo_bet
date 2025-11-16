<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionMaterialConsumption extends Model
{
    use HasFactory;

    protected $table = 'production_material_consumption';

    protected $fillable = [
        'production_order_id',
        'recipe_component_id',
        'inventory_item_id',
        'planned_quantity',
        'actual_quantity_used',
        'variance_quantity',
        'variance_reason',
        'unit_cost',
        'total_cost',
        'is_substitution',
        'recorded_by_user_id',
    ];

    protected $casts = [
        'planned_quantity' => 'decimal:3',
        'actual_quantity_used' => 'decimal:3',
        'variance_quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'is_substitution' => 'boolean',
    ];

    /**
     * Automatically create inventory transaction when actual quantity is recorded
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($consumption) {
            // If actual_quantity_used was just set, create inventory transaction
            if ($consumption->actual_quantity_used !== null &&
                $consumption->isDirty('actual_quantity_used')) {
                $consumption->createInventoryTransaction();
            }
        });
    }

    /**
     * Get the production order this consumption belongs to
     */
    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    /**
     * Get the recipe component
     */
    public function recipeComponent(): BelongsTo
    {
        return $this->belongsTo(RecipeComponent::class);
    }

    /**
     * Get the inventory item (actual material used, may differ from recipe if substituted)
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the user who recorded this consumption
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    /**
     * Record actual usage and calculate variance
     */
    public function recordActualUsage(float $actualQuantity, ?string $varianceReason = null): void
    {
        $this->actual_quantity_used = $actualQuantity;
        $this->variance_quantity = $actualQuantity - $this->planned_quantity;

        if ($varianceReason) {
            $this->variance_reason = $varianceReason;
        }

        // Recalculate total cost based on actual usage
        $this->total_cost = $actualQuantity * $this->unit_cost;
        $this->save();
    }

    /**
     * Create inventory transaction for material consumption
     */
    private function createInventoryTransaction(): void
    {
        InventoryTransaction::create([
            'inventory_item_id' => $this->inventory_item_id,
            'branch_id' => 1, // Main production branch
            'transaction_type' => 'production_consumption',
            'quantity' => $this->actual_quantity_used,
            'transaction_date' => now(),
            'reference_type' => 'ProductionOrder',
            'reference_id' => $this->production_order_id,
            'unit_cost' => $this->unit_cost,
            'created_by_user_id' => $this->recorded_by_user_id,
        ]);
    }

    /**
     * Check if there's a significant variance
     */
    public function hasSignificantVariance(float $thresholdPercent = 5.0): bool
    {
        if ($this->planned_quantity == 0) {
            return false;
        }

        $variancePercent = abs($this->variance_quantity / $this->planned_quantity) * 100;
        return $variancePercent > $thresholdPercent;
    }
}
