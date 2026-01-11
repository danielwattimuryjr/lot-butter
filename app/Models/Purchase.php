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
        'supplier',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected $attributes = [
        'status' => 'pending',
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
            if ($purchase->status === 'approved') {
                $journalService = app(JournalService::class);
                $journalService->createFromPurchase($purchase);
            }
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
            $journalService = app(JournalService::class);
            
            // Handle status changes
            if ($purchase->wasChanged('status')) {
                $oldStatus = $purchase->getOriginal('status');
                $newStatus = $purchase->status;

                // If changing to approved, create journal entry
                if ($newStatus === 'approved') {
                    $journalService->createFromPurchase($purchase);
                }
                // If changing from approved to pending/rejected, delete journal entry
                elseif ($oldStatus === 'approved' && in_array($newStatus, ['pending', 'rejected'])) {
                    $journalService->deleteFromPurchase($purchase);
                }
                // If changing to rejected (from pending), delete journal if exists
                elseif ($newStatus === 'rejected') {
                    $journalService->deleteFromPurchase($purchase);
                }
            }
            // If status is approved and financial data changed, update journal
            elseif ($purchase->status === 'approved' && $purchase->wasChanged(['total_amount', 'date', 'description', 'quantity', 'unit_price'])) {
                $journalService->updateFromPurchase($purchase);
            }
        });

        static::deleted(function ($purchase) {
            // Only delete journal entry if the purchase was approved
            if ($purchase->status === 'approved') {
                $journalService = app(JournalService::class);
                $journalService->deleteFromPurchase($purchase);
            }
        });
    }

    protected static function generatePurchaseCode()
    {
        $lastPurchase = static::latest('id')->first();
        $lastNumber = $lastPurchase
            ? intval(substr($lastPurchase->code, 3))
            : 0;

        return 'PUR'.str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
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
