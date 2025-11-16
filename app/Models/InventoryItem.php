<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku_code',
        'material_type_id',
        'brand_id',
        'unit_of_measure_id',
        'current_stock_quantity',
        'reorder_level',
        'is_active',
    ];

    protected $casts = [
        'current_stock_quantity' => 'decimal:3',
        'reorder_level' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function materialType(): BelongsTo
    {
        return $this->belongsTo(MaterialType::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function unitOfMeasure(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_of_measure_id');
    }

    public function purchasePrices(): HasMany
    {
        return $this->hasMany(PurchasePrice::class);
    }

    public function currentPrice(): HasMany
    {
        return $this->purchasePrices()
            ->whereNull('expiry_date')
            ->orWhere('expiry_date', '>', now())
            ->latest('effective_date')
            ->limit(1);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(InventoryAdjustment::class);
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock_quantity', '<=', 'reorder_level');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock_quantity', '<=', 0);
    }

    // Auto-generate SKU on creation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (empty($item->sku_code)) {
                $item->sku_code = self::generateSKU($item);
            }
        });
    }

    private static function generateSKU($item): string
    {
        $materialType = MaterialType::find($item->material_type_id);
        $brand = Brand::find($item->brand_id);

        $typeCode = $materialType->type_code ?? 'ITEM';
        $brandInitial = strtoupper(substr($brand->brand_name ?? 'UNK', 0, 4));

        $sequence = str_pad(
            self::where('material_type_id', $item->material_type_id)
                ->where('brand_id', $item->brand_id)
                ->count() + 1,
            3,
            '0',
            STR_PAD_LEFT
        );

        return strtoupper("{$typeCode}-{$brandInitial}-{$sequence}");
    }

    // Accessor for display name
    public function getDisplayNameAttribute(): string
    {
        return "{$this->materialType->type_name} ({$this->brand->brand_name})";
    }
}
