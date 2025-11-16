<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipe_code',
        'product_id',
        'recipe_name',
        'batch_size',
        'unit_id',
        'effective_date',
        'expiry_date',
        'is_active',
        'cost_per_batch',
        'instructions',
        'created_by_user_id',
    ];

    protected $casts = [
        'batch_size' => 'decimal:2',
        'cost_per_batch' => 'decimal:2',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Auto-generate recipe code on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recipe) {
            if (empty($recipe->recipe_code)) {
                $recipe->recipe_code = self::generateRecipeCode($recipe);
            }
        });
    }

    /**
     * Generate unique recipe code
     * Format: RCP-PRODUCTNAME-001
     */
    private static function generateRecipeCode($recipe): string
    {
        $product = Product::find($recipe->product_id);
        $productCode = strtoupper(substr(str_replace(' ', '', $product->name ?? 'PRODUCT'), 0, 8));

        $count = self::where('product_id', $recipe->product_id)->count();
        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "RCP-{$productCode}-{$sequence}";
    }

    /**
     * Get the product this recipe belongs to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the unit for batch size
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the user who created this recipe
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get all components for this recipe
     */
    public function components(): HasMany
    {
        return $this->hasMany(RecipeComponent::class)->orderBy('sequence_order');
    }

    /**
     * Get production orders using this recipe
     */
    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class);
    }

    /**
     * Calculate total cost per batch based on current component prices
     */
    public function calculateCostPerBatch(): void
    {
        $totalCost = 0;

        foreach ($this->components as $component) {
            $currentPrice = $component->inventoryItem->currentPurchasePrice();
            if ($currentPrice) {
                $totalCost += ($component->quantity_required * $currentPrice->price_per_unit);
            }
        }

        $this->cost_per_batch = $totalCost;
        $this->save();
    }

    /**
     * Scope for active recipes only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('effective_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now());
            });
    }

    /**
     * Check if this recipe is currently active
     */
    public function isActive(): bool
    {
        $now = now();
        return $this->is_active &&
               $this->effective_date <= $now &&
               ($this->expiry_date === null || $this->expiry_date >= $now);
    }
}
