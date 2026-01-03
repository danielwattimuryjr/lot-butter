<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialRequirementsPlanning extends Model
{
    protected $fillable = [
        'level',
        'product_id',
        'product_variant_id',
        'component_id',
        'year',
        'week',
        'scheduled_receipts',
        'projected_on_hand',
        'planned_order_receipts',
        'planned_order_releases',
        'is_edited',
    ];

    protected $casts = [
        'year' => 'integer',
        'week' => 'integer',
        'scheduled_receipts' => 'integer',
        'projected_on_hand' => 'integer',
        'planned_order_receipts' => 'integer',
        'planned_order_releases' => 'integer',
        'is_edited' => 'boolean',
    ];

    /**
     * Relationship with Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship with ProductVariant
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Relationship with Component
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    /**
     * Scope to get MRP for a specific level
     */
    public function scopeForLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope to get MRP for a specific product variant (Level 0)
     */
    public function scopeForVariant($query, int $variantId)
    {
        return $query->where('product_variant_id', $variantId);
    }

    /**
     * Scope to get MRP for a specific product (Level 1)
     */
    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope to get MRP for a specific component (Level 2)
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
}
