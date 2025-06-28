<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ExpenseRepository;
use App\Repository\IncomeRepository;

final class BudgetController extends AbstractController
{
    #[Route('/budget', name: 'budget_index')]
    public function index(IncomeRepository $incomeRepository, ExpenseRepository $expenseRepository): Response
    {
        $incomes = $incomeRepository->findAll();


        return $this->render('budget/index.html.twig', [
            'incomes' => $incomeRepository->findAll(),
        ]);
    }
}
