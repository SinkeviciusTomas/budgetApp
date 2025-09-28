<?php

namespace App\Form;

use App\Form\Model\TransactionInput;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionInputTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mainType', ChoiceType::class, [
                'choices' => [
                    'Income' => 'income',
                    'Expense' => 'expense',
                ],
                //'expanded' => false,
                'multiple' => false,

            ])
            ->add('category')
            ->add('amount')
            ->add('description')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'html5' => true,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TransactionInput::class,
        ]);
    }
}
