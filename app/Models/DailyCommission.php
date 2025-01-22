<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyCommission extends Model
{
    use HasFactory;

    public  function dailySales() : BelongsTo
    {
        return $this->belongsTo(DailySales::class);
    }

    public function commissionRecipient(): BelongsTo
    {
        return $this->belongsTo(CommissionRecipient::class);
    }
}
