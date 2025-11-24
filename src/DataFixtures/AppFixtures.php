<?php

namespace App\DataFixtures;


use App\Entity\Transaction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // INCOMES
        $income1 = new Transaction();
        $income1->setAmount(1000);
        $income1->setDescription("Income from job");
        $income1->setCategory('Salary');
        $income1->setDate(new \DateTime('2025-06-28 14:30:00'));
        $income1->setMainType('Income');
        $manager->persist($income1);

        $income2 = new Transaction();
        $income2->setAmount(200);
        $income2->setDescription("Sold investments");
        $income2->setCategory('Investments');
        $income2->setDate(new \DateTime('2025-06-05 14:30:00'));
        $income2->setMainType('Income');
        $manager->persist($income2);

        // EXPENSES
        $expense1 = new Transaction();
        $expense1->setAmount(200);
        $expense1->setDescription("Rent");
        $expense1->setCategory('Mandatory');
        $expense1->setDate(new \DateTime('2025-06-15 17:00:00'));
        $expense1->setMainType('Expense');
        $manager->persist($expense1);

        $expense2 = new Transaction();
        $expense2->setAmount(100);
        $expense2->setDescription("Fuel");
        $expense2->setCategory('Mandatory');
        $expense2->setDate(new \DateTime('2025-06-13 17:00:00'));
        $expense2->setMainType('Expense');
        $manager->persist($expense2);

        $manager->flush();
    }
}
