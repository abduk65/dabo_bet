<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPricing extends Model
{
    use HasFactory;

    protected $table = 'customer_pricing';

    protected $fillable = [
        'customer_id',
        'product_id',
        'price_per_unit',
        'effective_date',
        'expiry_date',
        'created_by_user_id',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the customer this pricing belongs to
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product this pricing applies to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who created this pricing
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Scope for active pricing only
     */
    public function scopeActive($query)
    {
        return $query->where('effective_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now());
            });
    }

    /**
     * Scope for pricing at a specific date
     */
    public function scopeAsOf($query, $date)
    {
        return $query->where('effective_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', $date);
            });
    }

    /**
     * Check if this pricing is currently active
     */
    public function isActive(): bool
    {
        $now = now();
        return $this->effective_date <= $now &&
               ($this->expiry_date === null || $this->expiry_date >= $now);
    }
}
