<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
