<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Sale;
use App\Models\Product;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'unit_cost',
        'line_total',
        'line_profit',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'line_total' => 'decimal:2',
        'line_profit' => 'decimal:2',
    ];

    /**
     * Automatically calculate line total and profit on save
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->line_total = $item->quantity * $item->unit_price;
            $item->line_profit = ($item->unit_price - $item->unit_cost) * $item->quantity;
        });
    }

    /**
     * Get the sale this item belongs to
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate profit margin percentage for this line item
     */
    public function profitMargin(): float
    {
        if ($this->unit_price <= 0) {
            return 0.0;
        }

        return (($this->unit_price - $this->unit_cost) / $this->unit_price) * 100;
    }

    /**
     * Calculate total cost for this line item
     */
    public function totalCost(): float
    {
        return $this->quantity * $this->unit_cost;
    }
}
