<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class MasterProductionSchedule extends Model
{
    protected $fillable = [
        'product_id',
        'forecast_id',
        'forecast_value',
        'mps_value',
        'available',
        'projected_on_hand',
        'is_edited',
        'year',
        'week',
        'month'
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'forecast_value' => 'integer',
        'mps_value' => 'integer',
        'available' => 'integer',
        'projected_on_hand' => 'integer',
        'year' => 'integer',
        'week' => 'integer',
        'month' => 'integer',
    ];

    /**
     * Relationship with Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship with Forecast (optional, for legacy reference)
     */
    public function forecast(): BelongsTo
    {
        return $this->belongsTo(Forecast::class);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Before creating a new MPS record
        static::creating(function ($mps) {
            // Set default values if not provided
            if (is_null($mps->is_edited)) {
                $mps->is_edited = false;
            }

            if (is_null($mps->projected_on_hand)) {
                $mps->projected_on_hand = 0;
            }
        });

        // Before updating an MPS record
        static::updating(function ($mps) {
            // Mark as edited if projected_on_hand or available is changed manually
            if ($mps->isDirty('projected_on_hand') || $mps->isDirty('available')) {
                $mps->is_edited = true;
            }
        });

        // After updating an MPS record
        static::updated(function ($mps) {
            // If projected_on_hand changed, recalculate MPS and availability for the entire month
            if ($mps->wasChanged('projected_on_hand')) {
                static::recalculateMonth($mps);
            }
            // If only availability changed, recalculate chain from this point
            elseif ($mps->wasChanged('available')) {
                static::recalculateAvailabilityChain($mps);
            }
        });

        // After deleting an MPS record
        static::deleted(function ($mps) {
            // Recalculate availability for remaining records
            static::recalculateAvailabilityAfterDelete($mps);
        });
    }

    /**
     * Recalculate MPS and availability for the entire month when projected_on_hand changes
     */
    protected static function recalculateMonth($updatedMps)
    {
        $month = $updatedMps->month;
        $year = $updatedMps->year;
        $product_id = $updatedMps->product_id;

        // Get all MPS records for this month and product (including First Stock and regular weeks)
        $monthMps = static::where('product_id', $product_id)
            ->where('year', $year)
            ->where('month', $month)
            ->orderBy('week')
            ->get();

        if ($monthMps->isEmpty()) return;

        // Get previous month's last availability to start the chain
        $prevMonthLastMps = static::where('product_id', $product_id)
            ->where('year', $year)
            ->where('month', '<', $month)
            ->orderBy('month', 'desc')
            ->orderBy('week', 'desc')
            ->first();

        $prev_available = $prevMonthLastMps ? $prevMonthLastMps->available : 0;
        $prev_projected_on_hand = 0;

        foreach ($monthMps as $mps) {
            // Handle First Stock row (forecast_id is NULL)
            if (is_null($mps->forecast_id)) {
                // First Stock row stores initial projected_on_hand
                $prev_available = $mps->projected_on_hand;
                $prev_projected_on_hand = $mps->projected_on_hand;

                // Update without triggering events
                DB::table('master_production_schedules')
                    ->where('id', $mps->id)
                    ->update([
                        'available' => null,
                        'mps_value' => null
                    ]);
            } else {
                // Regular forecast week - use forecast_value from MPS record itself
                $forecast_value = $mps->forecast_value ?? 0;

                // MPS = current_week_forecast - last_week_projected_on_hand
                $mps_value = $forecast_value - $prev_projected_on_hand;

                // Available = last_week_available + current_week_mps - current_week_forecast
                $available = $prev_available + $mps_value - $forecast_value;

                // Update without triggering events
                DB::table('master_production_schedules')
                    ->where('id', $mps->id)
                    ->update([
                        'mps_value' => $mps_value,
                        'available' => $available
                    ]);

                // Update trackers for next iteration
                $prev_available = $available;
                $prev_projected_on_hand = $mps->projected_on_hand;
            }
        }

        // Now recalculate all subsequent months
        static::recalculateSubsequentMonths($year, $month, $product_id);
    }

    /**
     * Recalculate all months after the changed month
     */
    protected static function recalculateSubsequentMonths($year, $changedMonth, $product_id)
    {
        // Get all subsequent months for this product
        $subsequentMps = static::where('product_id', $product_id)
            ->where(function ($query) use ($year, $changedMonth) {
                $query->where('year', $year)
                    ->where('month', '>', $changedMonth);
            })
            ->orWhere(function ($query) use ($year, $product_id) {
                $query->where('product_id', $product_id)
                    ->where('year', '>', $year);
            })
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('week')
            ->get();

        if ($subsequentMps->isEmpty()) return;

        // Get last availability from changed month
        $lastMpsOfChangedMonth = static::where('product_id', $product_id)
            ->where('year', $year)
            ->where('month', $changedMonth)
            ->whereNotNull('forecast_id')
            ->orderBy('week', 'desc')
            ->first();

        $prev_available = $lastMpsOfChangedMonth ? $lastMpsOfChangedMonth->available : 0;
        $prev_projected_on_hand = $lastMpsOfChangedMonth ? $lastMpsOfChangedMonth->projected_on_hand : 0;
        $current_month = null;

        foreach ($subsequentMps as $mps) {
            // Check if we're entering a new month
            if ($current_month !== $mps->month) {
                // Reset prev_projected_on_hand at start of new month
                // Use First Stock row if exists
                $firstStock = static::where('product_id', $mps->product_id)
                    ->where('year', $mps->year)
                    ->where('month', $mps->month)
                    ->whereNull('forecast_id')
                    ->first();

                if ($firstStock) {
                    $prev_available = $firstStock->projected_on_hand;
                    $prev_projected_on_hand = $firstStock->projected_on_hand;
                }

                $current_month = $mps->month;
            }

            // Skip First Stock rows in recalculation
            if (is_null($mps->forecast_id)) continue;

            // Use forecast_value from MPS record itself
            $forecast_value = $mps->forecast_value ?? 0;

            // MPS = current_week_forecast - last_week_projected_on_hand
            $mps_value = $forecast_value - $prev_projected_on_hand;

            // Available = last_week_available + current_week_mps - current_week_forecast
            $available = $prev_available + $mps_value - $forecast_value;

            // Update without triggering events
            DB::table('master_production_schedules')
                ->where('id', $mps->id)
                ->update([
                    'mps_value' => $mps_value,
                    'available' => $available
                ]);

            $prev_available = $available;
            $prev_projected_on_hand = $mps->projected_on_hand;
        }
    }

    /**
     * Recalculate availability for this and all subsequent weeks (when only availability changed)
     */
    protected static function recalculateAvailabilityChain($updatedMps)
    {
        // Only recalculate subsequent weeks for this product, not the whole month
        $allMps = static::where('product_id', $updatedMps->product_id)
            ->where('year', '>=', $updatedMps->year)
            ->where(function ($query) use ($updatedMps) {
                $query->where('year', '>', $updatedMps->year)
                    ->orWhere(function ($q) use ($updatedMps) {
                        $q->where('year', $updatedMps->year)
                            ->where('month', '>=', $updatedMps->month);
                    });
            })
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('week')
            ->get();

        $prev_available = $updatedMps->available;
        $prev_projected_on_hand = $updatedMps->projected_on_hand;
        $startRecalculating = false;
        $current_month = null;

        foreach ($allMps as $mps) {
            if ($mps->id === $updatedMps->id) {
                $startRecalculating = true;
                $current_month = $mps->month;
                continue;
            }

            if ($startRecalculating) {
                // Check if we're entering a new month
                if ($current_month !== $mps->month) {
                    // Reset for new month using First Stock if exists
                    $firstStock = static::where('product_id', $mps->product_id)
                        ->where('year', $mps->year)
                        ->where('month', $mps->month)
                        ->whereNull('forecast_id')
                        ->first();

                    if ($firstStock) {
                        $prev_available = $firstStock->projected_on_hand;
                        $prev_projected_on_hand = $firstStock->projected_on_hand;
                    }

                    $current_month = $mps->month;
                }

                // Skip First Stock rows
                if (is_null($mps->forecast_id)) continue;

                // Use forecast_value from MPS record itself
                $forecast_value = $mps->forecast_value ?? 0;

                // MPS = current_week_forecast - last_week_projected_on_hand
                $mps_value = $forecast_value - $prev_projected_on_hand;

                // Available = last_week_available + current_week_mps - current_week_forecast
                $available = $prev_available + $mps_value - $forecast_value;

                DB::table('master_production_schedules')
                    ->where('id', $mps->id)
                    ->update([
                        'mps_value' => $mps_value,
                        'available' => $available
                    ]);

                $prev_available = $available;
                $prev_projected_on_hand = $mps->projected_on_hand;
            }
        }
    }

    /**
     * Recalculate availability after a record is deleted
     */
    protected static function recalculateAvailabilityAfterDelete($deletedMps)
    {
        // Simply recalculate the entire month and subsequent months
        $dummyMps = new static();
        $dummyMps->year = $deletedMps->year;
        $dummyMps->month = $deletedMps->month;

        static::recalculateMonth($dummyMps);
    }

    /**
     * Scope to get MPS for a specific product
     */
    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope to get only edited MPS records
     */
    public function scopeEdited($query)
    {
        return $query->where('is_edited', true);
    }

    /**
     * Scope to get MPS for a specific year
     */
    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope to get MPS for a specific month
     */
    public function scopeForMonth($query, int $month)
    {
        return $query->where('month', $month);
    }

    /**
     * Scope to get weeks with negative availability (stock issues)
     */
    public function scopeWithStockIssues($query)
    {
        return $query->where('available', '<', 0);
    }

    /**
     * Reset the edited flag
     */
    public function resetEditedFlag(): void
    {
        $this->is_edited = false;
        $this->save();
    }

    /**
     * Get formatted week label
     */
    public function getWeekLabelAttribute(): string
    {
        return "Week {$this->week}";
    }

    /**
     * Get formatted period
     */
    public function getPeriodAttribute(): string
    {
        return "Month {$this->month} {$this->year} - Week {$this->week}";
    }

    /**
     * Check if there's a stock shortage
     */
    public function hasStockShortage(): bool
    {
        return $this->available < 0;
    }

    /**
     * Get the shortage amount
     */
    public function getShortageAmount(): int
    {
        return $this->available < 0 ? abs($this->available) : 0;
    }
}
