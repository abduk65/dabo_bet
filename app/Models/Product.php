<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_am',
        'type',
        'description',
        'unit_id',
        'shelf_life_days',
        'is_active',
    ];

    protected $casts = [
        'shelf_life_days' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the unit for this product
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get all recipes for this product
     */
    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    /**
     * Get the active recipe for this product
     */
    public function activeRecipe()
    {
        return $this->recipes()
            ->where('is_active', true)
            ->where('effective_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now());
            })
            ->first();
    }

    /**
     * Get all price history for this product
     */
    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    /**
     * Get the current active price
     */
    public function currentPrice()
    {
        return $this->prices()
            ->where('effective_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now());
            })
            ->orderBy('effective_date', 'desc')
            ->first();
    }

    /**
     * Get production output records
     */
    public function productionOutput(): HasMany
    {
        return $this->hasMany(ProductionOutput::class);
    }

    /**
     * Get finished goods inventory movements
     */
    public function finishedGoodsInventory(): HasMany
    {
        return $this->hasMany(FinishedGoodsInventory::class);
    }

    /**
     * Scope for active products only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for products by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
