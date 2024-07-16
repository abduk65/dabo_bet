<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }



    public function inventoryItems(): BelongsToMany
    {
        return $this->belongsToMany(InventoryItem::class, 'recipe_inventory_items')
            ->withPivot('quantity', 'unit_id')
            ->withTimestamps();
    }

    public function unit(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'recipe_inventory_items');
    }

}
