<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StandardBatchVariety extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function workOrder(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
