<?php

namespace App\Controller\Api;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final class TransactionController extends AbstractController
{
    #[Route('/api/transactions', name: 'app_api_transaction', methods: ['GET'])]
    public function index(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $transactions = $em->getRepository(Transaction::class)->findAll();

        $json_content = $serializer->serialize($transactions, 'json', [
            ObjectNormalizer::IGNORED_ATTRIBUTES => ['id']
        ]);

        return JsonResponse::fromJsonString($json_content);
    }

    #[Route('/api/transactions/{id}', name: 'app_api_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): JsonResponse
    {
        return $this->json($transaction);
    }

    #[Route('/api/transactions', methods: ['POST'])]
    public function create(Request $request,
                           EntityManagerInterface $em,
                           SerializerInterface $serializer,
                           ValidatorInterface $validator): JsonResponse
    {
        $content = $request->getContent();

        $data = $serializer->deserialize($content, Transaction::class, 'json');

        $errors = $validator->validate($data);

        if (count($errors) > 0) {
            return $this->json(['errors' =>$errors], 422);
        }
        $em->persist($data);
        $em->flush();

        return $this->json($data, 201);
    }
}
