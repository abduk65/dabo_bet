<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function dailyProductionAdjustments()
    {
        return $this->hasMany(DailyProductionAdjustment::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function dailySales(): HasMany
    {
        return $this->hasMany(DailySales::class);
    }

    public function commissionRecipients()
    {
        return $this->belongsToMany(CommissionRecipient::class, 'commissions')
            ->withPivot('discount_amount', 'status')
            ->withTimestamps();
    }
}
