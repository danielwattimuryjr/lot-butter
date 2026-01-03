<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterProductionSchedule extends Model
{
    protected $fillable = [
        'product_variant_id',
        'year',
        'month',
        'week',
        'beginning_inventory',
        'projected_on_hand',
        'available',
        'is_edited',
    ];

    protected $casts = [
        'product_variant_id' => 'integer',
        'year' => 'integer',
        'month' => 'integer',
        'week' => 'integer',
        'beginning_inventory' => 'integer',
        'projected_on_hand' => 'integer',
        'available' => 'integer',
        'is_edited' => 'boolean',
    ];

    /**
     * Relationship with ProductVariant
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Scope to get MPS for a specific product variant
     */
    public function scopeForVariant($query, int $variantId)
    {
        return $query->where('product_variant_id', $variantId);
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
     * Get formatted period
     */
    public function getPeriodAttribute(): string
    {
        return "Month {$this->month} {$this->year}";
    }
}
