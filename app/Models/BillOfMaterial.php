<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BillOfMaterial extends Pivot
{
    protected $table = 'bill_of_materials';

    public $timestamps = false;

    public $incrementing = true;

    protected $fillable = [
        'product_id',
        'product_variant_id',
        'component_id',
        'quantity',
        'level',
    ];

    /**
     * Get the product that owns the bill of material.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variant that owns the bill of material.
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get the component for the bill of material.
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }
}
