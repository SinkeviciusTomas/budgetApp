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
        $input->date = new \DateTime();
        $form = $this->createForm(TransactionInputTypeForm::class, $input);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $input->date->setTime((int) date('H'), (int) date('i'), (int) date('s'));

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
                ->setSourceTable($input->mainType)
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
    public function showIncome(Income $income, TransactionRepository $transactionRepository): Response
    {
        $transaction = $transactionRepository->findOneBy([
            'sourceTable' => 'income',
            'sourceId' => $income->getId(),
        ]);
       return $this->render('budget/showIncome.html.twig', [
           'income' => $income,
           'transaction' => $transaction,
       ]);
    }
    #[Route('/expense/{id<\d+>}', name: 'expense_show')]
    public function showExpense(Expense $expense, TransactionRepository $transactionRepository): Response
    {
        $transaction = $transactionRepository->findOneBy([
            'sourceTable' => 'expense',
            'sourceId' => $expense->getId(),
        ]);

        return $this->render('budget/showExpense.html.twig', [
            'expense' => $expense,
            'transaction' => $transaction,
        ]);
    }
    #[Route('/{type}/{id<\d+>}/edit', name: 'transaction_edit')]
    public function editTransaction(string $type, int $id, Request $request, EntityManagerInterface $em, TransactionRepository $transactionRepository): Response
    {
        if ($type === 'income') {
            $entity = $em->getRepository(Income::class)->find($id);
        } elseif ($type === 'expense') {
            $entity = $em->getRepository(Expense::class)->find($id);
        } else {
            throw $this->createNotFoundException('Invalid transaction type.');
        }

        if (!$entity) {
            throw $this->createNotFoundException('Entity not found.');
        }

        $transaction = $transactionRepository->findOneBy([
            'sourceTable' => $type,
            'sourceId' => $id,
        ]);

        $dto = new TransactionInput();
        $dto->amount = $entity->getAmount();
        $dto->category = $entity->getCategory();
        $dto->description = $entity->getDescription();
        $dto->date = $entity->getDate();
        $dto->mainType = $entity->getMainType();

        $form = $this->createForm(TransactionInputTypeForm::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTime();
            $dto->date->setTime(
                (int) $now->format('H'),
                (int) $now->format('i'),
                (int) $now->format('s')
            );

            // Check if mainType was changed (and thus, a new entity must be created)
            if (($dto->mainType) !== $type) {
                $em->remove($entity);
                if ($transaction) {
                    $em->remove($transaction);
                }

                $newEntity = $dto->mainType === 'income' ? new Income() : new Expense();
                $newEntity
                    ->setAmount($dto->amount)
                    ->setCategory($dto->category)
                    ->setDescription($dto->description)
                    ->setDate($dto->date)
                    ->setMainType($dto->mainType);
                $em->persist($newEntity);
                $em->flush();

                $newTransaction = new Transaction();
                $newTransaction
                    ->setMainType($dto->mainType)
                    ->setAmount($dto->amount)
                    ->setCategory($dto->category)
                    ->setDescription($dto->description)
                    ->setDate($dto->date)
                    ->setSourceTable($dto->mainType)
                    ->setSourceId($newEntity->getId());
                $em->persist($newTransaction);
            } else {
                // Update same entity
                $entity->setAmount($dto->amount);
                $entity->setCategory($dto->category);
                $entity->setDescription($dto->description);
                $entity->setDate($dto->date);
                $entity->setMainType($dto->mainType);

                if ($transaction) {
                    $transaction->setAmount($dto->amount);
                    $transaction->setCategory($dto->category);
                    $transaction->setDescription($dto->description);
                    $transaction->setDate($dto->date);
                    $transaction->setMainType($dto->mainType);
                }
            }

            $em->flush();
            return $this->redirectToRoute('budget_main');
        }

        return $this->render('budget/edit.html.twig', [
            'form' => $form->createView(),
            'cancelPath' => $this->generateUrl("{$type}_show", ['id' => $id]),
            'type'=> $type,
        ]);
    }
    #[Route('/transaction/{id}/delete', name: 'transaction_delete', methods: ['POST'])]
    public function deleteTransaction(int $id, Request $request, EntityManagerInterface $em, TransactionRepository $transactionRepository): Response {
        $transaction = $transactionRepository->find($id);

        // Remove linked income/expense
        $type = $transaction->getSourceTable();
        $sourceId = $transaction->getSourceId();

        if ($type === 'income') {
            $entity = $em->getRepository(Income::class)->find($sourceId);
        } elseif ($type === 'expense') {
            $entity = $em->getRepository(Expense::class)->find($sourceId);
        }

        if (isset($entity)) {
            $em->remove($entity);
        }

        $em->remove($transaction);
        $em->flush();

        return $this->redirectToRoute('budget_main');
    }


}
// TODO: Add Edit and remove functionality for transactions
// TODO: Add recent transactions page to see all the transactions in descending order

