<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Income;
use App\Entity\Expense;



class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $Income = new Income();
        $Income ->setAmount(1000);
        $Income ->setDescription("Income from job");
        $Income ->setType('Salary');
        $Income ->setDate(new \DateTime('2025-06-28 14:30:00'));

        $manager->persist($Income);

        $Income = new Income();
        $Income ->setAmount(200);
        $Income ->setDescription("Sold investments");
        $Income ->setType('Investments');
        $Income ->setDate(new \DateTime('2025-06-05 14:30:00'));

        $manager->persist($Income);

        $Expense = new Expense();
        $Expense ->setAmount(200);
        $Expense ->setDescription("Rent");
        $Expense ->setType('Mandatory');
        $Expense ->setDate(new \DateTime('2025-06-15 17:00:00'));

        $manager->persist($Expense);

        $Expense = new Expense();
        $Expense ->setAmount(100);
        $Expense ->setDescription("Fuel");
        $Expense ->setType('Mandatory');
        $Expense ->setDate(new \DateTime('2025-06-13 17:00:00'));

        $manager->persist($Expense);

        $manager->flush();
    }
}
