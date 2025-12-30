<?php

namespace App\Form\Type;

use App\Entity\Transaction;
use App\Enum\MainType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mainType', EnumType::class, ['class' => MainType::class,
                'multiple' => false,
                'placeholder' => 'Choose type',
                'required' => true,
                'attr' => ['id' => 'mainType'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please choose the transaction type',
                    ]),
                ],
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
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please choose the category',
                    ]),
                ],
            ])
            ->add('amount', NumberType::class, [
                'required' => true,
                'attr' => [
                    'min' => 0.01,
                    'step' => 0.01,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please input amount',
                    ]),
                    new Type([
                        'type' => 'float',
                        'message' => 'The amount must be a number',
                    ]),
                    new Positive([
                        'message' => 'The amount cannot be negative or 0.',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => 'The description must be a string',
                    ]),
                    new Length([
                        'max' => 128,
                        'maxMessage' => 'The description cannot be longer than 128 characters',
                    ]),
                ],
            ])
            ->add('date', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please choose a date',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
