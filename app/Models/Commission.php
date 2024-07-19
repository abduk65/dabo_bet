<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commission extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function commissionRecipient(): BelongsTo
    {
        return $this->belongsTo(CommissionRecipient::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function dailySales(): HasMany
    {
        return $this->hasMany(DailySales::class);
    }
}
