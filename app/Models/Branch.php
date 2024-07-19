<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cashCollected(): HasMany
    {
        return $this->hasMany(CashCollected::class);
    }

    public function expense(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function commissionRecipients(): HasMany
    {
        return $this->hasMany(CommissionRecipient::class);
    }

    public function dailySales(): HasMany
    {
        return $this->hasMany(DailySales::class);
    }

}
