<?php

namespace App\Service;

class TransactionCalculations
{
    public function getTransactionTotalByMainType(array $transactions): float
    {
        $total = 0;
        foreach ($transactions as $item) {
            $total += $item->getAmount();
        }

        return $total;
    }
}
