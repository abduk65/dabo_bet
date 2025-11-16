<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Recipe;
use App\Models\InventoryItem;
use App\Models\Unit;

class RecipeComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipe_id',
        'inventory_item_id',
        'quantity_required',
        'unit_id',
        'sequence_order',
        'is_substitutable',
        'notes',
    ];

    protected $casts = [
        'quantity_required' => 'decimal:3',
        'sequence_order' => 'integer',
        'is_substitutable' => 'boolean',
    ];

    /**
     * Get the recipe this component belongs to
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the inventory item (brand-specific material)
     * CRITICAL: This is the brand-specific inventory item, not generic material type
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the unit for quantity measurement
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Calculate cost for this component based on current price
     */
    public function currentCost(): float
    {
        $currentPrice = $this->inventoryItem->currentPurchasePrice();

        if (!$currentPrice) {
            return 0.0;
        }

        return $this->quantity_required * $currentPrice->price_per_unit;
    }

    /**
     * Calculate cost for this component at a specific date
     */
    public function costAsOf($date): float
    {
        $price = $this->inventoryItem->purchasePrices()
            ->where('effective_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', $date);
            })
            ->orderBy('effective_date', 'desc')
            ->first();

        if (!$price) {
            return 0.0;
        }

        return $this->quantity_required * $price->price_per_unit;
    }
}
