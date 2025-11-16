<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_number',
        'recipe_id',
        'scaling_factor',
        'planned_start_time',
        'actual_start_time',
        'actual_end_time',
        'status',
        'supervisor_user_id',
        'production_cost_total',
        'notes',
    ];

    protected $casts = [
        'scaling_factor' => 'decimal:2',
        'planned_start_time' => 'datetime',
        'actual_start_time' => 'datetime',
        'actual_end_time' => 'datetime',
        'production_cost_total' => 'decimal:2',
    ];

    /**
     * Auto-generate work order number on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->work_order_number)) {
                $order->work_order_number = self::generateWorkOrderNumber();
            }
        });
    }

    /**
     * Generate unique work order number
     * Format: WO-YYYYMMDD-001
     */
    private static function generateWorkOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count();
        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "WO-{$date}-{$sequence}";
    }

    /**
     * Get the recipe for this production order
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the supervisor assigned to this order
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_user_id');
    }

    /**
     * Get material consumption records
     */
    public function materialConsumption(): HasMany
    {
        return $this->hasMany(ProductionMaterialConsumption::class);
    }

    /**
     * Get production output records
     */
    public function output(): HasMany
    {
        return $this->hasMany(ProductionOutput::class);
    }

    /**
     * Get recipe substitutions made during this order
     */
    public function substitutions(): HasMany
    {
        return $this->hasMany(RecipeSubstitution::class);
    }

    /**
     * Start production (change status to in_progress)
     */
    public function start(): void
    {
        $this->status = 'in_progress';
        $this->actual_start_time = now();
        $this->save();
    }

    /**
     * Complete production and calculate total cost
     */
    public function complete(): void
    {
        $this->status = 'completed';
        $this->actual_end_time = now();
        $this->calculateProductionCost();
        $this->save();
    }

    /**
     * Calculate total production cost from material consumption
     */
    public function calculateProductionCost(): void
    {
        $this->production_cost_total = $this->materialConsumption()
            ->sum('total_cost');
        $this->save();
    }

    /**
     * Create planned material consumption records from recipe
     */
    public function createPlannedMaterialConsumption(int $userId): void
    {
        foreach ($this->recipe->components as $component) {
            $currentPrice = $component->inventoryItem->currentPurchasePrice();
            $unitCost = $currentPrice ? $currentPrice->price_per_unit : 0;

            $plannedQuantity = $component->quantity_required * $this->scaling_factor;

            ProductionMaterialConsumption::create([
                'production_order_id' => $this->id,
                'recipe_component_id' => $component->id,
                'inventory_item_id' => $component->inventory_item_id,
                'planned_quantity' => $plannedQuantity,
                'unit_cost' => $unitCost,
                'total_cost' => $plannedQuantity * $unitCost,
                'is_substitution' => false,
                'recorded_by_user_id' => $userId,
            ]);
        }
    }

    /**
     * Scope for orders by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for active production orders
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['planned', 'in_progress']);
    }
}
