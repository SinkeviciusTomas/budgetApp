<?php

namespace App\Service;

use App\Repository\TransactionRepository;

class TransactionCalculations
{
    public function __construct(
        private readonly TransactionRepository $tr
    )
    {}
    public function getTotals(string $transactionType): float
    {
        $total = 0;

        $transaction = $this->tr->transactionsCurrentMonth($transactionType);

        foreach ($transaction as $item) {
            $total += $item->getAmount();
        }
        return $total;
    }
}
