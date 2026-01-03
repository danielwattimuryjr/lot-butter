<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'product_code',
        'name',
    ];

    /**
     * The components that belong to the product.
     */
    public function components(): BelongsToMany
    {
        return $this->belongsToMany(Component::class, 'bill_of_materials')
            ->using(BillOfMaterial::class)
            ->wherePivotNull('product_variant_id')
            ->withPivot(['quantity', 'level', 'id']);
    }

    /**
     * Get the variants for the product.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function forecasts()
    {
        return $this->hasMany(Forecast::class);
    }
}
