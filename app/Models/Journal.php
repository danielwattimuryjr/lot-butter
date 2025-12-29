<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'code',
        'date',
        'description',
        'debit',
        'credit',
        'balance',
        'transaction_type',
        'reference_table',
        'reference_id',
        'product_id',
    ];

    protected $casts = [
        'date' => 'date',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function reference()
    {
        if (!$this->reference_table || !$this->reference_id) {
            return null;
        }

        $modelMap = [
            'incomes' => Income::class,
            'purchases' => Purchase::class,
        ];

        $modelClass = $modelMap[$this->reference_table] ?? null;

        return $modelClass ? $modelClass::find($this->reference_id) : null;
    }
}
