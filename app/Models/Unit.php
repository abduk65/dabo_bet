<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function dailyInventoryOut()
    {
        return $this->hasMany(DailyInventoryOut::class);
    }

    public function inventoryItem()
    {
        return $this->hasMany(DailyInventoryOut::class);
    }

    public function dailyProductionAdjustment(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function inventoryAdjustment(): HasMany
    {
        return $this->hasMany(InventoryAdjustment::class);
    }
}
