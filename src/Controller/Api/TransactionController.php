<?php

namespace App\Controller\Api;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class TransactionController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('/api/transactions', name: 'app_api_transaction', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $transactions = $this->em->getRepository(Transaction::class)->findAll();

        $json_content = $this->serializer->serialize($transactions, 'json', [
            ObjectNormalizer::IGNORED_ATTRIBUTES => ['id'],
        ]);

        return JsonResponse::fromJsonString($json_content);
    }

    #[Route('/api/transactions/{id}', name: 'app_api_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): JsonResponse
    {
        return $this->json($transaction);
    }

    #[Route('/api/transactions', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $content = $request->getContent();

        $data = $this->serializer->deserialize($content, Transaction::class, 'json');

        $errors = $this->validator->validate($data);

        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], 422);
        }
        $this->em->persist($data);
        $this->em->flush();

        return $this->json($data, 201);
    }
}
