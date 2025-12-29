<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'pack',
        'price'
    ];

    /**
     * The components that belong to the product.
     */
    public function components(): BelongsToMany
    {
        return $this->belongsToMany(Component::class, 'bills_of_materials')
            ->using(BillOfMaterial::class)
            ->withPivot('quantity');
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }
}
