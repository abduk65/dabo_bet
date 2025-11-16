<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\User;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'customer_id',
        'sale_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference_number',
        'received_by_user_id',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Auto-generate payment number on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = self::generatePaymentNumber();
            }
        });

        static::created(function ($payment) {
            // Update sale balance if payment is linked to a sale
            if ($payment->sale_id) {
                $payment->sale->recordPayment($payment->amount);
            }
        });
    }

    /**
     * Generate unique payment number
     * Format: PAY-YYYYMMDD-001
     */
    private static function generatePaymentNumber(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('payment_date', now())->count();
        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "PAY-{$date}-{$sequence}";
    }

    /**
     * Get the customer who made this payment
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the sale this payment is for (nullable for advance payments)
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the user who received this payment
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }

    /**
     * Check if this is an advance payment
     */
    public function isAdvancePayment(): bool
    {
        return $this->sale_id === null;
    }

    /**
     * Scope for payments by method
     */
    public function scopeByMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope for advance payments
     */
    public function scopeAdvance($query)
    {
        return $query->whereNull('sale_id');
    }
}
