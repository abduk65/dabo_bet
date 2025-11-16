<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\JournalEntryLine;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_code',
        'account_name',
        'account_type',
        'sub_type',
        'parent_account_id',
        'normal_balance',
        'opening_balance',
        'is_active',
        'is_system_account',
        'description',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'is_system_account' => 'boolean',
    ];

    /**
     * Get the parent account (for hierarchical chart of accounts)
     */
    public function parentAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_account_id');
    }

    /**
     * Get child accounts
     */
    public function childAccounts(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_account_id');
    }

    /**
     * Get journal entry lines for this account
     */
    public function journalEntryLines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    /**
     * Calculate current balance for this account
     */
    public function currentBalance(): float
    {
        $debits = $this->journalEntryLines()
            ->whereHas('journalEntry', function ($q) {
                $q->where('status', 'posted');
            })
            ->sum('debit_amount');

        $credits = $this->journalEntryLines()
            ->whereHas('journalEntry', function ($q) {
                $q->where('status', 'posted');
            })
            ->sum('credit_amount');

        // For accounts with normal debit balance (assets, expenses, COGS)
        if ($this->normal_balance === 'debit') {
            return $this->opening_balance + $debits - $credits;
        }

        // For accounts with normal credit balance (liabilities, equity, revenue)
        return $this->opening_balance + $credits - $debits;
    }

    /**
     * Calculate balance as of a specific date
     */
    public function balanceAsOf($date): float
    {
        $debits = $this->journalEntryLines()
            ->whereHas('journalEntry', function ($q) use ($date) {
                $q->where('status', 'posted')
                  ->where('entry_date', '<=', $date);
            })
            ->sum('debit_amount');

        $credits = $this->journalEntryLines()
            ->whereHas('journalEntry', function ($q) use ($date) {
                $q->where('status', 'posted')
                  ->where('entry_date', '<=', $date);
            })
            ->sum('credit_amount');

        if ($this->normal_balance === 'debit') {
            return $this->opening_balance + $debits - $credits;
        }

        return $this->opening_balance + $credits - $debits;
    }

    /**
     * Scope for active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for accounts by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('account_type', $type);
    }

    /**
     * Scope for top-level accounts (no parent)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_account_id');
    }

    /**
     * Check if this is an asset account
     */
    public function isAsset(): bool
    {
        return $this->account_type === 'asset';
    }

    /**
     * Check if this is a liability account
     */
    public function isLiability(): bool
    {
        return $this->account_type === 'liability';
    }

    /**
     * Check if this is an equity account
     */
    public function isEquity(): bool
    {
        return $this->account_type === 'equity';
    }

    /**
     * Check if this is a revenue account
     */
    public function isRevenue(): bool
    {
        return $this->account_type === 'revenue';
    }

    /**
     * Check if this is an expense account
     */
    public function isExpense(): bool
    {
        return $this->account_type === 'expense';
    }

    /**
     * Check if this is a COGS account
     */
    public function isCOGS(): bool
    {
        return $this->account_type === 'cogs';
    }
}
