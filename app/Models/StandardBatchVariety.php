<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;

class StandardBatchVariety extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variety_name',
        'scaling_factor',
        'expected_output_quantity',
        'is_default',
    ];

    protected $casts = [
        'scaling_factor' => 'decimal:2',
        'expected_output_quantity' => 'decimal:2',
        'is_default' => 'boolean',
    ];

    /**
     * Get the product this batch variety belongs to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate expected material requirements based on scaling factor
     */
    public function calculateMaterialRequirements(): array
    {
        $recipe = $this->product->activeRecipe();

        if (!$recipe) {
            return [];
        }

        $requirements = [];
        foreach ($recipe->components as $component) {
            $requirements[] = [
                'inventory_item_id' => $component->inventory_item_id,
                'inventory_item_name' => $component->inventoryItem->materialType->type_name,
                'brand_name' => $component->inventoryItem->brand->brand_name,
                'required_quantity' => $component->quantity_required * $this->scaling_factor,
                'unit' => $component->unit->unit_abbreviation,
            ];
        }

        return $requirements;
    }

    /**
     * Scope for default batch varieties
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
