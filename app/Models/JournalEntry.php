<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_number',
        'entry_date',
        'entry_type',
        'reference_type',
        'reference_id',
        'description',
        'total_debit',
        'total_credit',
        'is_balanced',
        'status',
        'created_by_user_id',
        'posted_by_user_id',
        'posted_at',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
        'is_balanced' => 'boolean',
        'posted_at' => 'datetime',
    ];

    /**
     * Auto-generate entry number on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($entry) {
            if (empty($entry->entry_number)) {
                $entry->entry_number = self::generateEntryNumber();
            }
        });
    }

    /**
     * Generate unique entry number
     * Format: JE-YYYYMMDD-001
     */
    private static function generateEntryNumber(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count();
        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "JE-{$date}-{$sequence}";
    }

    /**
     * Get the user who created this entry
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get the user who posted this entry
     */
    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by_user_id');
    }

    /**
     * Get all journal entry lines
     */
    public function lines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    /**
     * Add a debit line to this entry
     */
    public function addDebit(int $accountId, float $amount, ?string $description = null): void
    {
        JournalEntryLine::create([
            'journal_entry_id' => $this->id,
            'account_id' => $accountId,
            'debit_amount' => $amount,
            'credit_amount' => 0,
            'description' => $description,
        ]);

        $this->recalculateTotals();
    }

    /**
     * Add a credit line to this entry
     */
    public function addCredit(int $accountId, float $amount, ?string $description = null): void
    {
        JournalEntryLine::create([
            'journal_entry_id' => $this->id,
            'account_id' => $accountId,
            'debit_amount' => 0,
            'credit_amount' => $amount,
            'description' => $description,
        ]);

        $this->recalculateTotals();
    }

    /**
     * Recalculate total debits and credits
     */
    public function recalculateTotals(): void
    {
        $this->total_debit = $this->lines()->sum('debit_amount');
        $this->total_credit = $this->lines()->sum('credit_amount');
        $this->is_balanced = abs($this->total_debit - $this->total_credit) < 0.01;
        $this->save();
    }

    /**
     * Post this journal entry (make it permanent)
     */
    public function post(int $userId): void
    {
        if (!$this->is_balanced) {
            throw new \Exception('Cannot post unbalanced journal entry. Debits must equal credits.');
        }

        $this->status = 'posted';
        $this->posted_by_user_id = $userId;
        $this->posted_at = now();
        $this->save();
    }

    /**
     * Reverse this journal entry
     */
    public function reverse(int $userId, string $reason): self
    {
        if ($this->status !== 'posted') {
            throw new \Exception('Only posted entries can be reversed.');
        }

        // Create reversing entry
        $reversingEntry = self::create([
            'entry_date' => now(),
            'entry_type' => 'adjustment',
            'reference_type' => 'JournalEntry',
            'reference_id' => $this->id,
            'description' => "Reversal: {$reason}",
            'created_by_user_id' => $userId,
        ]);

        // Create opposite lines
        foreach ($this->lines as $line) {
            JournalEntryLine::create([
                'journal_entry_id' => $reversingEntry->id,
                'account_id' => $line->account_id,
                'debit_amount' => $line->credit_amount, // Swap debit/credit
                'credit_amount' => $line->debit_amount,
                'description' => "Reversal of entry {$this->entry_number}",
            ]);
        }

        $reversingEntry->recalculateTotals();
        $reversingEntry->post($userId);

        // Mark original as reversed
        $this->status = 'reversed';
        $this->save();

        return $reversingEntry;
    }

    /**
     * Scope for posted entries only
     */
    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }

    /**
     * Scope for draft entries
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for entries by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('entry_type', $type);
    }

    /**
     * Scope for entries within a date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('entry_date', [$startDate, $endDate]);
    }

    /**
     * Check if entry is balanced
     */
    public function isBalanced(): bool
    {
        return abs($this->total_debit - $this->total_credit) < 0.01;
    }
}
