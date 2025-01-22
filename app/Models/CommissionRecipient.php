<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommissionRecipient extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'commissions')
            ->withPivot('discount_amount', 'status')
            ->withTimestamps();
    }

    public function dailyCommission(): HasMany
    {
        return $this->hasMany(DailyCommission::class);
    }
}
