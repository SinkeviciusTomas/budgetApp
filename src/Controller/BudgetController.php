<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Expense;
use App\Entity\Income;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Form\TransactionInputTypeForm;
use App\Form\Model\TransactionInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class BudgetController extends AbstractController
{
    #[Route('/', name: 'budget_main')]
    public function index(Request $request, EntityManagerInterface $em, TransactionRepository $transactionRepository): Response
    {
        $recentTransactions = $transactionRepository->findBy([], ['date' => 'DESC'], 6);

        $input = new TransactionInput();
        $form = $this->createForm(TransactionInputTypeForm::class, $input);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($input->mainType === 'income') {
                $entity = new Income();
            } else {
                $entity = new Expense();
            }

            $entity
                ->setAmount($input->amount)
                ->setDescription($input->description)
                ->setCategory($input->category)
                ->setDate($input->date)
                ->setMainType($input->mainType);

            $em->persist($entity);
            $em->flush();

            $transaction = new Transaction();
            $transaction
                ->setMainType($input->mainType)
                ->setAmount($input->amount)
                ->setDescription($input->description)
                ->setCategory($input->category)
                ->setDate($input->date)
                ->setSourceTable($input->mainType) // 'income' or 'expense'
                ->setSourceId($entity->getId());

            $em->persist($transaction);
            $em->flush();

            return $this->redirectToRoute('budget_main');
        }

        return $this->render('budget/index.html.twig', [
            'recentTransactions' => $recentTransactions,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/income/{id<\d+>}', name: 'income_show')]
    public function showIncome(Income $income): Response
    {
       return $this->render('budget/showIncome.html.twig', [
           'income' => $income,
       ]);
    }
    #[Route('/expense/{id<\d+>}', name: 'expense_show')]
    public function showExpense(Expense $expense): Response
    {
        return $this->render('budget/showExpense.html.twig', [
            'expense' => $expense,
        ]);
    }
}
// TODO: Add Edit and remove functionality for transactions
// TODO: Add recent transactions page to see all the transactions in descending order

