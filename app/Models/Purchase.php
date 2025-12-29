<?php

namespace App\Models;

use App\Services\JournalService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'code',
        'component_id',
        'description',
        'quantity',
        'unit_price',
        'total_amount',
        'date',
        'supplier'
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchase) {
            $purchase->code = static::generatePurchaseCode();

            $purchase->week = Carbon::parse($purchase->date)->week;

            $purchase->total_amount = $purchase->quantity * $purchase->unit_price;
        });

        static::created(function ($purchase) {
            $journalService = app(JournalService::class);
            $journalService->createFromPurchase($purchase);
        });

        static::updating(function ($purchase) {
            if ($purchase->isDirty('quantity') || $purchase->isDirty('unit_price')) {
                $purchase->total_amount = $purchase->quantity * $purchase->unit_price;
            }

            if ($purchase->isDirty('date')) {
                $purchase->week = Carbon::parse($purchase->date)->week;
            }
        });

        static::updated(function ($purchase) {
            if ($purchase->wasChanged(['total_amount', 'date', 'description'])) {
                $journalService = app(JournalService::class);
                $journalService->updateFromPurchase($purchase);
            }
        });

        static::deleted(function ($purchase) {
            $journalService = app(JournalService::class);
            $journalService->deleteFromPurchase($purchase);
        });
    }

    protected static function generatePurchaseCode()
    {
        $lastPurchase = static::latest('id')->first();
        $lastNumber = $lastPurchase
            ? intval(substr($lastPurchase->code, 3))
            : 0;
        return 'PUR' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function journal()
    {
        return $this->hasOne(Journal::class, 'reference_id')
            ->where('reference_table', 'purchases');
    }
}
