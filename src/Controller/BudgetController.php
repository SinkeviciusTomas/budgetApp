<?php

declare(strict_types=1);

namespace App\Controller;


use App\Entity\Transaction;
use App\Form\Type\TransactionType;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BudgetController extends AbstractController
{
    #[Route('/', name: 'transaction_main')]
    public function index(Request $request, EntityManagerInterface $em, TransactionRepository $transactionRepository): Response
    {
        $recentTransactions = $transactionRepository->recentTransactions(10);

        $transaction = new Transaction();
        $form = $this->createForm(transactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($transaction);
            $em->flush();

            return $this->redirectToRoute('transaction_main');
        }

        return $this->render('budget/index.html.twig', [
            'recentTransactions' => $recentTransactions,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/transaction/{id<\d+>}', name: 'transaction_show')]
    public function transactionShow(Transaction $transaction, TransactionRepository $transactionRepository): Response
    {
        $transaction = $transactionRepository->findOneBy([
            'id' => $transaction->getId()
        ]);
       return $this->render('budget/transactionShow.html.twig', [
           'transaction' => $transaction,
       ]);
    }
    #[Route('/transaction/{id<\d+>}/edit', name: 'transaction_edit')]
    public function editTransaction(Transaction $transaction, Request $request, EntityManagerInterface $em, TransactionRepository $transactionRepository): Response
    {
        $form = $this->createForm(transactionType::class, $transaction);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('transaction_show', ['id' => $transaction->getId()]);
        }

        return $this->render('budget/transactionEdit.html.twig', [
            'form' => $form->createView(),
            'transaction' => $transaction,
        ]);
    }
    #[Route('/transaction/{id}/', name: 'transaction_delete', methods: ['POST'])]
    public function deleteTransaction(int $id, Request $request, EntityManagerInterface $em, TransactionRepository $transactionRepository): Response
    {
        $transaction = $transactionRepository->find($id);

        if ($request->isMethod('POST')) {

            $em->remove($transaction);
            $em->flush();

            return $this->redirectToRoute('transaction_main');
        };


        return $this->render('budget/index.html.twig', []);
    }


}

