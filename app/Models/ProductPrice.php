<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;
use App\Models\User;

class ProductPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'price_per_unit',
        'effective_date',
        'expiry_date',
        'recorded_by_user_id',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the product this price belongs to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who recorded this price
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    /**
     * Scope for active prices only
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
     * Scope for price at a specific date
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
     * Check if this price is currently active
     */
    public function isActive(): bool
    {
        $now = now();
        return $this->effective_date <= $now &&
               ($this->expiry_date === null || $this->expiry_date >= $now);
    }
}
