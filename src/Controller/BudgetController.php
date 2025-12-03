<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Transaction;
use App\Enum\MainType;
use App\Form\Type\TransactionType;
use App\Repository\TransactionRepository;
use App\Service\TransactionCalculations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BudgetController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TransactionRepository $transactionRepository,
    ) {
    }

    #[Route('/', name: 'transaction_main')]
    public function index(Request $request, TransactionCalculations $calculations): Response
    {
        $recentTransactions = $this->transactionRepository->findRecentTransactions(10);
        $incomes = $this->transactionRepository->findCurrentMonthTransactionsByType(MainType::Income);
        $expenses = $this->transactionRepository->findCurrentMonthTransactionsByType(MainType::Expense);
        $totalIncome = $calculations->getTransactionTotalByMainType($incomes);
        $totalExpense = $calculations->getTransactionTotalByMainType($expenses);

        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($transaction);
            $this->em->flush();

            return $this->redirectToRoute('transaction_main');
        }

        return $this->render('budget/index.html.twig', [
            'recentTransactions' => $recentTransactions,
            'form' => $form->createView(),
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
        ]);
    }

    #[Route('/transaction/{id<\d+>}', name: 'transaction_show')]
    public function transactionShow(Transaction $transaction): Response
    {
        return $this->render('budget/transactionShow.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    #[Route('/transaction/{id<\d+>}/edit', name: 'transaction_edit')]
    public function editTransaction(Transaction $transaction, Request $request): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('transaction_show', ['id' => $transaction->getId()]);
        }

        return $this->render('budget/transactionEdit.html.twig', [
            'form' => $form->createView(),
            'transaction' => $transaction,
        ]);
    }

    #[Route('/transaction/{id}/', name: 'transaction_delete', methods: ['POST'])]
    public function deleteTransaction(Transaction $transaction, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $this->em->remove($transaction);
            $this->em->flush();

            return $this->redirectToRoute('transaction_main');
        }

        return $this->render('budget/index.html.twig', []);
    }
}
