<?php

namespace App\Form\Type;

use App\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mainType', ChoiceType::class, [
                'choices' => [
                    'Income' => 'income',
                    'Expense' => 'expense',
                ],
                'multiple' => false,
                'placeholder' => 'Choose type',
                'required' => true,
                'attr' => ['id' => 'mainType'],
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Salary' => 'Salary',
                    'Gift' => 'Gift',
                    'Invoice' => 'Invoice',
                    'Rent' => 'Rent',
                    'Groceries' => 'Groceries',
                    'Transport' => 'Transport',
                ],
                'placeholder' => 'Choose category',
                'required' => true,
                'attr' => ['id' => 'category'],
            ])
            ->add('amount', NumberType::class, [
                'required' => true,
                'attr' => [
                    'min' => 0.01,
                    'step' => 0.01,
                ],
            ])
            ->add('description', TextType::class, [
                'required' => false,
            ])
            ->add('date', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
