<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkOrder extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function standardBatchVariety(): BelongsTo
    {
        return $this->belongsTo(StandardBatchVariety::class);
    }


}
