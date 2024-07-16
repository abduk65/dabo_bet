<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InventoryItem extends Model
{
    use HasFactory;

    public function dailyInventoryOut()
    {
        return $this->hasMany(DailyInventoryOut::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipe(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'recipe_inventory_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function recipeUnit(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'recipe_inventory_items');
    }
}
