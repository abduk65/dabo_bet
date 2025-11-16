<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'name',
        'type',
        'branch_id',
        'phone',
        'email',
        'address',
        'tin_number',
        'credit_limit',
        'credit_period_days',
        'is_active',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'credit_period_days' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Auto-generate customer code on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->customer_code)) {
                $customer->customer_code = self::generateCustomerCode($customer);
            }
        });
    }

    /**
     * Generate unique customer code
     * Format: CUST-TYPE-001 (e.g., CUST-WI-001, CUST-CR-001, CUST-BR-001)
     */
    private static function generateCustomerCode($customer): string
    {
        $typeCode = match($customer->type) {
            'walk_in' => 'WI',
            'commission_recipient' => 'CR',
            'branch' => 'BR',
            default => 'XX',
        };

        $count = self::where('type', $customer->type)->count();
        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "CUST-{$typeCode}-{$sequence}";
    }

    /**
     * Get the branch this customer is associated with (if type=branch)
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get all custom pricing for this customer
     */
    public function customPricing(): HasMany
    {
        return $this->hasMany(CustomerPricing::class);
    }

    /**
     * Get all sales for this customer
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get all payments from this customer
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get active custom price for a specific product
     */
    public function getPriceForProduct(int $productId)
    {
        return $this->customPricing()
            ->where('product_id', $productId)
            ->where('effective_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now());
            })
            ->orderBy('effective_date', 'desc')
            ->first();
    }

    /**
     * Calculate total outstanding balance
     */
    public function outstandingBalance(): float
    {
        return $this->sales()
            ->where('status', 'completed')
            ->sum('balance_due');
    }

    /**
     * Check if customer has exceeded credit limit
     */
    public function isOverCreditLimit(): bool
    {
        if ($this->credit_limit <= 0) {
            return false;
        }

        return $this->outstandingBalance() > $this->credit_limit;
    }

    /**
     * Scope for active customers only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for customers by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for commission recipients only
     */
    public function scopeCommissionRecipients($query)
    {
        return $query->where('type', 'commission_recipient');
    }

    /**
     * Check if customer is a walk-in customer
     */
    public function isWalkIn(): bool
    {
        return $this->type === 'walk_in';
    }

    /**
     * Check if customer is a commission recipient
     */
    public function isCommissionRecipient(): bool
    {
        return $this->type === 'commission_recipient';
    }

    /**
     * Check if customer is a branch
     */
    public function isBranch(): bool
    {
        return $this->type === 'branch';
    }
}
