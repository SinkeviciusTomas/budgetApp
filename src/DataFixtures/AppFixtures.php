<?php

namespace App\DataFixtures;

use App\Entity\Expense;
use App\Entity\Income;
use App\Entity\Transaction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // INCOMES
        $income1 = new Income();
        $income1->setAmount(1000);
        $income1->setDescription("Income from job");
        $income1->setCategory('Salary');
        $income1->setDate(new \DateTime('2025-06-28 14:30:00'));
        $income1->setMainType('Income');
        $manager->persist($income1);

        $income2 = new Income();
        $income2->setAmount(200);
        $income2->setDescription("Sold investments");
        $income2->setCategory('Investments');
        $income2->setDate(new \DateTime('2025-06-05 14:30:00'));
        $income2->setMainType('Income');
        $manager->persist($income2);

        // EXPENSES
        $expense1 = new Expense();
        $expense1->setAmount(200);
        $expense1->setDescription("Rent");
        $expense1->setCategory('Mandatory');
        $expense1->setDate(new \DateTime('2025-06-15 17:00:00'));
        $expense1->setMainType('Expense');
        $manager->persist($expense1);

        $expense2 = new Expense();
        $expense2->setAmount(100);
        $expense2->setDescription("Fuel");
        $expense2->setCategory('Mandatory');
        $expense2->setDate(new \DateTime('2025-06-13 17:00:00'));
        $expense2->setMainType('Expense');
        $manager->persist($expense2);

        $manager->flush();

        // TRANSACTIONS from Income
        foreach ([$income1, $income2] as $income) {
            $transaction = new Transaction();
            $transaction->setMainType('income');
            $transaction->setAmount($income->getAmount());
            $transaction->setDescription($income->getDescription());
            $transaction->setCategory($income->getCategory());
            $transaction->setDate($income->getDate());
            $transaction->setSourceId($income->getId());
            $transaction->setSourceTable('income');
            $manager->persist($transaction);
        }

        // TRANSACTIONS from Expense
        foreach ([$expense1, $expense2] as $expense) {
            $transaction = new Transaction();
            $transaction->setMainType('expense');
            $transaction->setAmount($expense->getAmount());
            $transaction->setDescription($expense->getDescription());
            $transaction->setCategory($expense->getCategory());
            $transaction->setDate($expense->getDate());
            $transaction->setSourceId($expense->getId());
            $transaction->setSourceTable('expense');
            $manager->persist($transaction);
        }

        $manager->flush();
    }
}
