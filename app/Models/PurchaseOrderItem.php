<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'inventory_item_id',
        'quantity',
        'unit_id',
        'unit_price',
        'total_price',
        'received_quantity',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'received_quantity' => 'decimal:3',
    ];

    // Relationships
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    // Auto-calculate total price
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total_price = $item->quantity * $item->unit_price;
        });

        static::saved(function ($item) {
            $item->purchaseOrder->calculateTotal();
        });
    }

    // Accessor for variance
    public function getVarianceQuantityAttribute(): float
    {
        return $this->received_quantity - $this->quantity;
    }

    // Check if fully received
    public function isFullyReceived(): bool
    {
        return $this->received_quantity >= $this->quantity;
    }
}
