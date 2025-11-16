<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'order_date',
        'expected_delivery_date',
        'status',
        'total_amount',
        'created_by_user_id',
        'approved_by_user_id',
        'received_by_user_id',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    // Auto-generate PO number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($po) {
            if (empty($po->po_number)) {
                $po->po_number = self::generatePONumber();
            }
        });
    }

    private static function generatePONumber(): string
    {
        $date = now()->format('Ymd');
        $sequence = str_pad(
            self::whereDate('order_date', now())->count() + 1,
            3,
            '0',
            STR_PAD_LEFT
        );

        return "PO-{$date}-{$sequence}";
    }

    // Calculate total from items
    public function calculateTotal(): void
    {
        $this->total_amount = $this->items()->sum('total_price');
        $this->save();
    }
}
