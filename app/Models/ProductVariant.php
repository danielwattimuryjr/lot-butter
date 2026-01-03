<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'number',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the product that owns the variant.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * The components that belong to the product variant.
     */
    public function components(): BelongsToMany
    {
        return $this->belongsToMany(Component::class, 'bill_of_materials', 'product_variant_id')
            ->using(BillOfMaterial::class)
            ->withPivot(['quantity', 'level', 'id']);
    }
}
