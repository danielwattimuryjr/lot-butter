<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Component extends Model
{
    protected $fillable = [
        'code',
        'safety_stock',
        'stock',
        'name',
        'weight',
        'unit',
        'category',
    ];

    protected $casts = [
        'stock' => 'decimal:2',
    ];

    /**
     * The products that belong to the component.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'bill_of_materials', 'component_id', 'product_id')
            ->using(BillOfMaterial::class)
            ->withPivot(['quantity', 'level', 'id', 'is_level2_parent']);
    }

    /**
     * Get all bill of materials for this component
     */
    public function billOfMaterials()
    {
        return $this->hasMany(BillOfMaterial::class, 'component_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function logistics()
    {
        return $this->hasMany(Logistic::class);
    }
}
