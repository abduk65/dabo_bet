<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Unit;
use App\Models\InventoryItem;

class MaterialType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_code',
        'type_name',
        'type_name_am',
        'category',
        'base_unit_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function baseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getLocalizedNameAttribute(): string
    {
        return app()->getLocale() === 'am' && $this->type_name_am
            ? $this->type_name_am
            : $this->type_name;
    }
}
