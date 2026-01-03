<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Forecast extends Model
{
    protected $fillable = [
        'product_id',
        'year',
        'week',
        'month',
        'trend_component',
        'seasonal_component',
        'irregular_component',
        'forecast_value',
    ];

    protected $casts = [
        'year' => 'integer',
        'week' => 'integer',
        'trend_component' => 'decimal:4',
        'seasonal_component' => 'decimal:4',
        'irregular_component' => 'decimal:4',
        'forecast_value' => 'decimal:2',
    ];

    /**
     * Relationship with Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship with MPS (changed from hasMany to hasOne)
     */
    public function masterProductionSchedule(): HasOne
    {
        return $this->hasOne(MasterProductionSchedule::class);
    }

    /**
     * Alias for masterProductionSchedule
     */
    public function mps(): HasOne
    {
        return $this->masterProductionSchedule();
    }

    /**
     * Boot method to handle model events
     */
    protected static function booted(): void
    {
        // When forecast value is updated, update related MPS
        static::updated(function (Forecast $forecast) {
            if ($forecast->isDirty('forecast_value') && $forecast->mps) {
                $mps = $forecast->mps;

                // Only auto-update if not manually edited
                if (! $mps->is_edited) {
                    $mps->mps_value = $forecast->forecast_value - $mps->projected_on_hand;
                    $mps->save();
                }
            }
        });
    }

    /**
     * Scope to get forecasts for a specific product
     */
    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope to get forecasts for a specific year
     */
    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope to get forecasts for a specific month
     */
    public function scopeForMonth($query, string $month)
    {
        return $query->where('month', $month);
    }

    /**
     * Get formatted period
     */
    public function getPeriodAttribute(): string
    {
        return "{$this->month} {$this->year} - Week {$this->week}";
    }
}
