<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyInventoryOut extends Model
{
    use HasFactory;


    public function InventoryItem()
    {
        return $this->hasMany(InventoryItem::class);
    }
}
