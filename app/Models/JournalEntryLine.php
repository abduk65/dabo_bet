<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntryLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'debit_amount',
        'credit_amount',
        'description',
    ];

    protected $casts = [
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
    ];

    /**
     * Get the journal entry this line belongs to
     */
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    /**
     * Get the account for this line
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the net amount (debit - credit)
     */
    public function netAmount(): float
    {
        return $this->debit_amount - $this->credit_amount;
    }

    /**
     * Check if this is a debit line
     */
    public function isDebit(): bool
    {
        return $this->debit_amount > 0;
    }

    /**
     * Check if this is a credit line
     */
    public function isCredit(): bool
    {
        return $this->credit_amount > 0;
    }
}
