<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Logistic extends Model
{
    protected $fillable = [
        'code',
        'component_id',
        'transaction_type',
        'quantity',
        'stock_total',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2',
        'stock_total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($logistic) {
            $logistic->code = static::generateLogisticCode();

            $previousStock = static::getLastStockTotal($logistic->component_id);

            if ($logistic->transaction_type === 'in') {
                $logistic->stock_total = $previousStock + $logistic->quantity;
            } else { // 'out'
                $logistic->stock_total = $previousStock - $logistic->quantity;
            }
        });

        static::created(function ($logistic) {
            $logistic->component->update([
                'stock' => $logistic->stock_total
            ]);
        });

        static::updating(function ($logistic) {
            $previousLogistic = static::where('component_id', $logistic->component_id)
                ->where('id', '<', $logistic->id)
                ->latest('id')
                ->first();

            $previousStock = $previousLogistic ? $previousLogistic->stock_total : 0;

            if ($logistic->transaction_type === 'in') {
                $logistic->stock_total = $previousStock + $logistic->quantity;
            } else {
                $logistic->stock_total = $previousStock - $logistic->quantity;
            }
        });

        static::deleted(function ($logistic) {
            static::recalculateStockAfter($logistic->component_id, $logistic->id);
        });
    }

    protected static function generateLogisticCode()
    {
        $lastLogistic = static::latest('id')->first();
        $lastNumber = $lastLogistic
            ? intval(substr($lastLogistic->code, 3))
            : 0;
        return 'LOG' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    protected static function getLastStockTotal($componentId)
    {
        $lastLogistic = static::where('component_id', $componentId)
            ->latest('id')
            ->first();

        return $lastLogistic ? $lastLogistic->stock_total : 0;
    }

    protected static function recalculateStockAfter($componentId, $deletedId)
    {
        $logistics = static::where('component_id', $componentId)
            ->where('id', '>', $deletedId)
            ->orderBy('id')
            ->get();

        $previousLogistic = static::where('component_id', $componentId)
            ->where('id', '<', $deletedId)
            ->latest('id')
            ->first();

        $runningStock = $previousLogistic ? $previousLogistic->stock_total : 0;

        foreach ($logistics as $logistic) {
            if ($logistic->transaction_type === 'in') {
                $runningStock += $logistic->quantity;
            } else {
                $runningStock -= $logistic->quantity;
            }

            $logistic->update(['stock_total' => $runningStock]);
        }

        $component = Component::find($componentId);
        if ($component) {
            $component->update(['stock' => $runningStock]);
        }
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
