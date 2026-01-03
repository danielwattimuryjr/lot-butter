<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class MaterialRequirementsPlanning extends Model
{
    protected $fillable = [
        'component_id',
        'year',
        'week',
        'month',
        'gross_requirements',
        'schedule_receipts',
        'projected_on_hand',
        'net_requirements',
        'planned_order_receipts',
        'planned_order_releases',
    ];

    protected $casts = [
        'year' => 'integer',
        'week' => 'integer',
        'month' => 'integer',
        'gross_requirements' => 'integer',
        'schedule_receipts' => 'integer',
        'projected_on_hand' => 'integer',
        'net_requirements' => 'integer',
        'planned_order_receipts' => 'integer',
        'planned_order_releases' => 'integer',
    ];

    /**
     * Relationship with Component
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Before updating an MRP record
        static::updating(function ($mrp) {
            // If user updates planned_order_receipts, schedule_receipts, or projected_on_hand, recalculate the chain
            if ($mrp->isDirty('planned_order_receipts') || $mrp->isDirty('schedule_receipts') || $mrp->isDirty('projected_on_hand')) {
                // Will trigger recalculation after update
            }
        });

        // After updating an MRP record
        static::updated(function ($mrp) {
            // Recalculate subsequent weeks when planned orders, scheduled receipts, or projected on hand change
            if ($mrp->wasChanged('planned_order_receipts') || $mrp->wasChanged('schedule_receipts') || $mrp->wasChanged('projected_on_hand')) {
                static::recalculateMRPChain($mrp);
            }
        });
    }

    /**
     * Recalculate MRP for this and all subsequent weeks
     */
    protected static function recalculateMRPChain($updatedMrp)
    {
        $component_id = $updatedMrp->component_id;

        // Get all MRP records for this component from this week onwards
        $allMrp = static::where('component_id', $component_id)
            ->where(function ($query) use ($updatedMrp) {
                $query->where('year', '>', $updatedMrp->year)
                    ->orWhere(function ($q) use ($updatedMrp) {
                        $q->where('year', $updatedMrp->year)
                            ->where('month', '>', $updatedMrp->month);
                    })
                    ->orWhere(function ($q) use ($updatedMrp) {
                        $q->where('year', $updatedMrp->year)
                            ->where('month', $updatedMrp->month)
                            ->where('week', '>=', $updatedMrp->week);
                    });
            })
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('week')
            ->get();

        $prev_projected_on_hand = 0;
        $startRecalculating = false;

        foreach ($allMrp as $mrp) {
            if ($mrp->id === $updatedMrp->id) {
                $startRecalculating = true;
                // Use updated values
                $prev_projected_on_hand = $updatedMrp->projected_on_hand;

                continue;
            }

            if ($startRecalculating) {
                // Recalculate net requirements
                $net_requirements = $mrp->gross_requirements - ($prev_projected_on_hand + $mrp->schedule_receipts);
                $net_requirements = max(0, $net_requirements);

                // Recalculate projected on hand
                $projected_on_hand = $prev_projected_on_hand + $mrp->schedule_receipts + $mrp->planned_order_receipts - $mrp->gross_requirements;

                // Update without triggering events
                DB::table('material_requirements_plannings')
                    ->where('id', $mrp->id)
                    ->update([
                        'net_requirements' => $net_requirements,
                        'projected_on_hand' => $projected_on_hand,
                        'updated_at' => now(),
                    ]);

                $prev_projected_on_hand = $projected_on_hand;
            }
        }
    }

    /**
     * Scope to get MRP for a specific component
     */
    public function scopeForComponent($query, int $componentId)
    {
        return $query->where('component_id', $componentId);
    }

    /**
     * Scope to get MRP for a specific year
     */
    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope to get MRP for a specific month
     */
    public function scopeForMonth($query, int $month)
    {
        return $query->where('month', $month);
    }

    /**
     * Check if there's a shortage
     */
    public function hasShortage(): bool
    {
        return $this->projected_on_hand < 0;
    }

    /**
     * Get the shortage amount
     */
    public function getShortageAmount(): int
    {
        return $this->projected_on_hand < 0 ? abs($this->projected_on_hand) : 0;
    }
}
