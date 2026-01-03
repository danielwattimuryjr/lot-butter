<?php

namespace App\Models;

use App\Services\JournalService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        'code',
        'product_id',
        'product_variant_id',
        'description',
        'quantity',
        'unit_price',
        'amount',
        'date_received',
        'week',
    ];

    protected $casts = [
        'date_received' => 'date',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2',
        'week' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($income) {
            $income->code = static::generateIncomeCode();

            $income->week = Carbon::parse($income->date_received)->week;

            // Get price from variant if variant_id exists, otherwise from product
            if ($income->product_variant_id) {
                $variant = \App\Models\ProductVariant::find($income->product_variant_id);
                $income->unit_price = $variant->price;
                $income->amount = $income->quantity * $variant->price;
            } else {
                $product = \App\Models\Product::find($income->product_id);
                // Since product no longer has price, we need to handle this differently
                // You might want to throw an error or require variant selection
                $income->unit_price = 0;
                $income->amount = 0;
            }
        });

        static::created(function ($income) {
            $journalService = app(JournalService::class);
            $journalService->createFromIncome($income);
        });

        static::updating(function ($income) {
            if ($income->isDirty('date_received')) {
                $income->week = Carbon::parse($income->date_received)->week;
            }

            if ($income->isDirty('quantity') || $income->isDirty('product_variant_id')) {
                if ($income->product_variant_id) {
                    $variant = \App\Models\ProductVariant::find($income->product_variant_id);
                    $income->unit_price = $variant->price;
                    $income->amount = $income->quantity * $variant->price;
                }
            }
        });

        static::updated(function ($income) {
            if ($income->wasChanged(['amount', 'date_received', 'description'])) {
                $journalService = app(JournalService::class);
                $journalService->updateFromIncome($income);
            }
        });

        static::deleted(function ($income) {
            $journalService = app(JournalService::class);
            $journalService->deleteFromIncome($income);
        });
    }

    protected static function generateIncomeCode()
    {
        $lastIncome = static::latest('id')->first();
        $lastNumber = $lastIncome
            ? intval(substr($lastIncome->code, 3))
            : 0;

        return 'INC'.str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function journal()
    {
        return $this->hasOne(Journal::class, 'reference_id')
            ->where('reference_table', 'incomes');
    }
}
