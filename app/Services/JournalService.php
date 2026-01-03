<?php

namespace App\Services;

use App\Models\Journal;

class JournalService
{
    public function createFromIncome($income)
    {
        $lastBalance = $this->getLastBalance();

        return Journal::create([
            'code' => $this->generateJournalCode(),
            'date' => $income->date_received,
            'description' => 'Revenue - '.$income->description,
            'debit' => $income->amount,
            'credit' => null,
            'balance' => $lastBalance + $income->amount,
            'transaction_type' => 'income',
            'reference_table' => 'incomes',
            'reference_id' => $income->id,
            'product_id' => $income->product_id,
        ]);
    }

    public function createFromPurchase($purchase)
    {
        $lastBalance = $this->getLastBalance();

        return Journal::create([
            'code' => $this->generateJournalCode(),
            'date' => $purchase->date,
            'description' => 'Purchase - '.$purchase->description,
            'debit' => null,
            'credit' => $purchase->total_amount,
            'balance' => $lastBalance - $purchase->total_amount,
            'transaction_type' => 'purchase',
            'reference_table' => 'purchases',
            'reference_id' => $purchase->id,
            'product_id' => null,
        ]);
    }

    public function updateFromIncome($income)
    {
        $journal = Journal::where('reference_table', 'incomes')
            ->where('reference_id', $income->id)
            ->first();

        if ($journal) {
            $previousBalance = $this->getBalanceBefore($journal->id);

            $journal->update([
                'date' => $income->date_received,
                'description' => 'Revenue - '.$income->description,
                'debit' => $income->amount,
                'balance' => $previousBalance + $income->amount,
            ]);

            $this->recalculateBalancesAfter($journal->id);
        }
    }

    public function updateFromPurchase($purchase)
    {
        $journal = Journal::where('reference_table', 'purchases')
            ->where('reference_id', $purchase->id)
            ->first();

        if ($journal) {
            $previousBalance = $this->getBalanceBefore($journal->id);

            $journal->update([
                'date' => $purchase->date,
                'description' => 'Purchase - '.$purchase->description,
                'credit' => $purchase->total_amount,
                'balance' => $previousBalance - $purchase->total_amount,
            ]);

            $this->recalculateBalancesAfter($journal->id);
        }
    }

    public function deleteFromIncome($income)
    {
        $this->deleteJournalEntry('incomes', $income->id);
    }

    public function deleteFromPurchase($purchase)
    {
        $this->deleteJournalEntry('purchases', $purchase->id);
    }

    private function deleteJournalEntry($table, $id)
    {
        $journal = Journal::where('reference_table', $table)
            ->where('reference_id', $id)
            ->first();

        if ($journal) {
            $journalId = $journal->id;
            $journal->delete();
            $this->recalculateBalancesAfter($journalId);
        }
    }

    private function getLastBalance()
    {
        return Journal::latest('id')->first()->balance ?? 0;
    }

    private function getBalanceBefore($journalId)
    {
        return Journal::where('id', '<', $journalId)
            ->latest('id')
            ->first()
            ->balance ?? 0;
    }

    private function recalculateBalancesAfter($journalId)
    {
        $journals = Journal::where('id', '>', $journalId)
            ->orderBy('id')
            ->get();

        $runningBalance = $this->getBalanceBefore($journalId);

        foreach ($journals as $journal) {
            $amount = ($journal->debit ?? 0) - ($journal->credit ?? 0);
            $runningBalance += $amount;
            $journal->update(['balance' => $runningBalance]);
        }
    }

    private function generateJournalCode()
    {
        $lastJournal = Journal::latest('id')->first();
        $lastNumber = $lastJournal
          ? intval(substr($lastJournal->code, 1))
          : 0;

        return 'J'.str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}
