<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'adjustment_type',
        'quantity',
        'unit_id',
        'operation',
        'reason',
        'approved_by_user_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
    ];

    // Relationships
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_by_user_id');
    }

    public function scopePending($query)
    {
        return $query->whereNull('approved_by_user_id');
    }

    public function scopeType($query, string $type)
    {
        return $query->where('adjustment_type', $type);
    }

    // Check if approved
    public function isApproved(): bool
    {
        return !is_null($this->approved_by_user_id);
    }

    // Create inventory transaction when approved
    public function approve(int $userId): void
    {
        if ($this->isApproved()) {
            return;
        }

        $this->approved_by_user_id = $userId;
        $this->save();

        // Create inventory transaction
        InventoryTransaction::create([
            'transaction_type' => 'adjustment',
            'inventory_item_id' => $this->inventory_item_id,
            'quantity' => $this->operation === 'addition' ? $this->quantity : -$this->quantity,
            'unit_id' => $this->unit_id,
            'transaction_date' => now(),
            'reference_type' => 'InventoryAdjustment',
            'reference_id' => $this->id,
            'branch_id' => auth()->user()->branch_id,
            'created_by_user_id' => $userId,
            'notes' => "Adjustment: {$this->adjustment_type} - {$this->reason}",
        ]);
    }
}
