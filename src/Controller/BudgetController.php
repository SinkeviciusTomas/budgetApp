<?php

namespace App\Controller;

use App\Entity\Income;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ExpenseRepository;
use App\Repository\IncomeRepository;

final class BudgetController extends AbstractController
{
    #[Route('/', name: 'budget_index')]
    public function index(IncomeRepository $incomeRepository, ExpenseRepository $expenseRepository, TransactionRepository $transactionRepository): Response
    {
        $incomes = $incomeRepository->findAll();
        $expenses = $expenseRepository->findAll();
        $recentTransactions = $transactionRepository->findBy([], ['date' => 'DESC'], 6);
        return $this->render('budget/index.html.twig', [
            'recentTransactions' => $recentTransactions,
        ]);
    }
    #[Route('/{id<\d+>}', name: 'income_show')]
    public function showIncome($id, IncomeRepository $incomeRepository): Response
    {
        $income = $incomeRepository->find($id);

       return $this->render('budget/showIncome.html.twig', [
           'income' => $income,
       ]);
    }
}
