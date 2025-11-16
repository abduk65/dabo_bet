<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\MaterialType;
use App\Models\InventoryItem;
use App\Models\PurchasePrice;
use App\Models\InventoryTransaction;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_name',
        'unit_abbreviation',
        'unit_type',
    ];

    // Relationships
    public function materialTypes(): HasMany
    {
        return $this->hasMany(MaterialType::class, 'base_unit_id');
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class, 'unit_of_measure_id');
    }

    public function purchasePrices(): HasMany
    {
        return $this->hasMany(PurchasePrice::class);
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
