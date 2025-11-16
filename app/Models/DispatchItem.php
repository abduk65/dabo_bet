<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DispatchItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispatch_id',
        'product_id',
        'quantity_dispatched',
        'quantity_received',
        'unit_cost',
        'damage_quantity',
        'damage_notes',
    ];

    protected $casts = [
        'quantity_dispatched' => 'decimal:2',
        'quantity_received' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'damage_quantity' => 'decimal:2',
    ];

    /**
     * Get the dispatch this item belongs to
     */
    public function dispatch(): BelongsTo
    {
        return $this->belongsTo(Dispatch::class);
    }

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Record quantity received and any damages
     */
    public function recordReceipt(float $quantityReceived, ?float $damageQuantity = 0, ?string $damageNotes = null): void
    {
        $this->quantity_received = $quantityReceived;
        $this->damage_quantity = $damageQuantity ?? 0;
        $this->damage_notes = $damageNotes;
        $this->save();
    }

    /**
     * Calculate total cost for this dispatch item
     */
    public function totalCost(): float
    {
        return $this->quantity_dispatched * $this->unit_cost;
    }

    /**
     * Calculate variance between dispatched and received
     */
    public function variance(): float
    {
        if ($this->quantity_received === null) {
            return 0.0;
        }

        return $this->quantity_dispatched - ($this->quantity_received + ($this->damage_quantity ?? 0));
    }

    /**
     * Check if there's any variance (theft/loss during transit)
     */
    public function hasVariance(): bool
    {
        return abs($this->variance()) > 0.01; // Allow for small rounding differences
    }
}
