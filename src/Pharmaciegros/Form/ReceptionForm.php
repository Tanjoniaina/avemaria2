<?php

namespace App\Pharmaciegros\Form;

use App\Pharmaciegros\Entity\Purchaseorder;
use App\Pharmaciegros\Entity\Reception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReceptionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('receiveddate')
            ->add('status', ChoiceType::class,[
                'choices'=>[
                    'Partial' => 'Partial',
                    'Total' => 'Total',
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reception::class,
        ]);
    }
}
