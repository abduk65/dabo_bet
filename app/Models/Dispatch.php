<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Branch;
use App\Models\User;
use App\Models\DispatchItem;

class Dispatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispatch_number',
        'from_branch_id',
        'to_branch_id',
        'dispatch_date',
        'status',
        'dispatched_at',
        'received_at',
        'dispatched_by_user_id',
        'received_by_user_id',
        'notes',
    ];

    protected $casts = [
        'dispatch_date' => 'date',
        'dispatched_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    /**
     * Auto-generate dispatch number on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($dispatch) {
            if (empty($dispatch->dispatch_number)) {
                $dispatch->dispatch_number = self::generateDispatchNumber();
            }
        });
    }

    /**
     * Generate unique dispatch number
     * Format: DSP-YYYYMMDD-001
     */
    private static function generateDispatchNumber(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('dispatch_date', now())->count();
        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "DSP-{$date}-{$sequence}";
    }

    /**
     * Get the source branch
     */
    public function fromBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    /**
     * Get the destination branch
     */
    public function toBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    /**
     * Get the user who dispatched
     */
    public function dispatchedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dispatched_by_user_id');
    }

    /**
     * Get the user who received
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }

    /**
     * Get all dispatch items
     */
    public function items(): HasMany
    {
        return $this->hasMany(DispatchItem::class);
    }

    /**
     * Mark dispatch as dispatched
     */
    public function markAsDispatched(int $userId): void
    {
        $this->status = 'dispatched';
        $this->dispatched_at = now();
        $this->dispatched_by_user_id = $userId;
        $this->save();

        // Create finished goods inventory transactions for source branch
        foreach ($this->items as $item) {
            FinishedGoodsInventory::create([
                'product_id' => $item->product_id,
                'branch_id' => $this->from_branch_id,
                'transaction_type' => 'dispatch',
                'quantity' => -$item->quantity_dispatched, // Negative for outgoing
                'transaction_date' => now(),
                'reference_type' => 'Dispatch',
                'reference_id' => $this->id,
                'unit_cost' => $item->unit_cost,
                'created_by_user_id' => $userId,
            ]);
        }
    }

    /**
     * Mark dispatch as received
     */
    public function markAsReceived(int $userId): void
    {
        $this->status = 'received';
        $this->received_at = now();
        $this->received_by_user_id = $userId;
        $this->save();

        // Create finished goods inventory transactions for destination branch
        foreach ($this->items as $item) {
            $receivedQty = $item->quantity_received ?? $item->quantity_dispatched;

            FinishedGoodsInventory::create([
                'product_id' => $item->product_id,
                'branch_id' => $this->to_branch_id,
                'transaction_type' => 'dispatch',
                'quantity' => $receivedQty, // Positive for incoming
                'transaction_date' => now(),
                'reference_type' => 'Dispatch',
                'reference_id' => $this->id,
                'unit_cost' => $item->unit_cost,
                'created_by_user_id' => $userId,
            ]);

            // Record damage if any
            if ($item->damage_quantity > 0) {
                FinishedGoodsInventory::create([
                    'product_id' => $item->product_id,
                    'branch_id' => $this->to_branch_id,
                    'transaction_type' => 'waste',
                    'quantity' => -$item->damage_quantity,
                    'transaction_date' => now(),
                    'reference_type' => 'DispatchDamage',
                    'reference_id' => $item->id,
                    'unit_cost' => $item->unit_cost,
                    'created_by_user_id' => $userId,
                ]);
            }
        }
    }

    /**
     * Scope for dispatches by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for pending dispatches (dispatched but not received)
     */
    public function scopePending($query)
    {
        return $query->where('status', 'dispatched');
    }
}
