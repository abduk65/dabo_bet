<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ProductionOrder;
use App\Models\RecipeComponent;
use App\Models\InventoryItem;
use App\Models\User;

class RecipeSubstitution extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_order_id',
        'original_recipe_component_id',
        'original_inventory_item_id',
        'substitute_inventory_item_id',
        'substitution_reason',
        'approved_by_user_id',
        'notes',
    ];

    /**
     * Get the production order this substitution belongs to
     */
    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    /**
     * Get the original recipe component
     */
    public function originalRecipeComponent(): BelongsTo
    {
        return $this->belongsTo(RecipeComponent::class, 'original_recipe_component_id');
    }

    /**
     * Get the original inventory item (the one specified in recipe)
     */
    public function originalInventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'original_inventory_item_id');
    }

    /**
     * Get the substitute inventory item (what was actually used)
     */
    public function substituteInventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'substitute_inventory_item_id');
    }

    /**
     * Get the user who approved this substitution
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /**
     * Get human-readable substitution summary
     */
    public function getSummary(): string
    {
        $original = $this->originalInventoryItem;
        $substitute = $this->substituteInventoryItem;

        return sprintf(
            'Substituted %s (%s) with %s (%s). Reason: %s',
            $original->materialType->type_name,
            $original->brand->brand_name,
            $substitute->materialType->type_name,
            $substitute->brand->brand_name,
            $this->substitution_reason
        );
    }
}
