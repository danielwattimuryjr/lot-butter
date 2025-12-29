<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BillOfMaterial extends Pivot
{
  protected $fillable = [
    'product_id',
    'component_id',
    'quantity'
  ];
}
