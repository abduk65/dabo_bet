<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_number',
        'branch_id',
        'customer_id',
        'sale_date',
        'payment_type',
        'subtotal_amount',
        'tax_amount',
        'total_amount',
        'amount_paid',
        'balance_due',
        'due_date',
        'status',
        'created_by_user_id',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'subtotal_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'due_date' => 'date',
    ];

    /**
     * Auto-generate sale number on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (empty($sale->sale_number)) {
                $sale->sale_number = self::generateSaleNumber($sale);
            }
        });
    }

    /**
     * Generate unique sale number
     * Format: SALE-BRANCHID-YYYYMMDD-001
     */
    private static function generateSaleNumber($sale): string
    {
        $branchCode = str_pad($sale->branch_id, 2, '0', STR_PAD_LEFT);
        $date = now()->format('Ymd');
        $count = self::where('branch_id', $sale->branch_id)
                     ->whereDate('sale_date', now())
                     ->count();
        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "SALE-{$branchCode}-{$date}-{$sequence}";
    }

    /**
     * Get the branch where this sale occurred
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user who created this sale
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get all sale items
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get all payments for this sale
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Calculate totals from sale items
     */
    public function calculateTotals(): void
    {
        $this->subtotal_amount = $this->items()->sum('line_total');
        $this->total_amount = $this->subtotal_amount + $this->tax_amount;
        $this->balance_due = $this->total_amount - $this->amount_paid;
        $this->save();
    }

    /**
     * Complete the sale and create inventory transactions
     */
    public function complete(int $userId): void
    {
        $this->status = 'completed';
        $this->save();

        // Create finished goods inventory transactions
        foreach ($this->items as $item) {
            FinishedGoodsInventory::create([
                'product_id' => $item->product_id,
                'branch_id' => $this->branch_id,
                'transaction_type' => 'sales',
                'quantity' => -$item->quantity, // Negative for outgoing
                'transaction_date' => $this->sale_date,
                'reference_type' => 'Sale',
                'reference_id' => $this->id,
                'unit_cost' => $item->unit_cost,
                'created_by_user_id' => $userId,
            ]);
        }
    }

    /**
     * Record payment for this sale
     */
    public function recordPayment(float $amount): void
    {
        $this->amount_paid += $amount;
        $this->balance_due = $this->total_amount - $this->amount_paid;
        $this->save();
    }

    /**
     * Calculate total profit for this sale
     */
    public function totalProfit(): float
    {
        return $this->items()->sum('line_profit');
    }

    /**
     * Calculate profit margin percentage
     */
    public function profitMargin(): float
    {
        if ($this->total_amount <= 0) {
            return 0.0;
        }

        return ($this->totalProfit() / $this->total_amount) * 100;
    }

    /**
     * Check if sale is fully paid
     */
    public function isFullyPaid(): bool
    {
        return $this->balance_due <= 0;
    }

    /**
     * Check if sale is overdue
     */
    public function isOverdue(): bool
    {
        if ($this->due_date === null || $this->isFullyPaid()) {
            return false;
        }

        return $this->due_date < now();
    }

    /**
     * Scope for sales by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for credit sales with outstanding balance
     */
    public function scopeOutstanding($query)
    {
        return $query->where('payment_type', 'credit')
                     ->where('balance_due', '>', 0);
    }

    /**
     * Scope for overdue sales
     */
    public function scopeOverdue($query)
    {
        return $query->where('payment_type', 'credit')
                     ->where('balance_due', '>', 0)
                     ->where('due_date', '<', now());
    }
}
