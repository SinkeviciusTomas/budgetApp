<?php

namespace App\Command;

use App\Entity\Income;
use App\Entity\Expense;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
name: 'migrate:transactions',
description: 'Migrate existing Income and Expense entries to the new Transaction entity',
)]
class MigrateTransactionsCommand extends Command
{
private EntityManagerInterface $em;

public function __construct(EntityManagerInterface $em)
{
parent::__construct();
$this->em = $em;
}

protected function execute(InputInterface $input, OutputInterface $output): int
{
$incomeRepo = $this->em->getRepository(Income::class);
$expenseRepo = $this->em->getRepository(Expense::class);

$incomes = $incomeRepo->findAll();
$expenses = $expenseRepo->findAll();

$count = 0;

foreach ($incomes as $income) {
$transaction = new Transaction();
$transaction->setMainType('income');
$transaction->setAmount($income->getAmount());
$transaction->setCategory($income->getCategory());
$transaction->setDescription($income->getDescription());
$transaction->setDate($income->getDate());
$this->em->persist($transaction);
$count++;
}

foreach ($expenses as $expense) {
$transaction = new Transaction();
$transaction->setMainType('expense');
$transaction->setAmount($expense->getAmount());
$transaction->setCategory($expense->getCategory());
$transaction->setDescription($expense->getDescription());
$transaction->setDate($expense->getDate());
$this->em->persist($transaction);
$count++;
}

$this->em->flush();

return Command::SUCCESS;
}
}
