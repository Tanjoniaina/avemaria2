<?php

namespace App\Pharmaciegros\Form;

use App\Pharmaciegros\Entity\Invoice;
use App\Pharmaciegros\Entity\Payment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('payementdate')
            ->add('amount')
            ->add('payementmethod', ChoiceType::class,[
                'choices'=>[
                    'Chèque' => 'Chèque',
                    'Mobile money' => 'Mobile Money',
                    'Virement'=> 'Virement',
                    'Espèce'=> 'Espèce'
                ]
            ])
            ->add('reference')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
