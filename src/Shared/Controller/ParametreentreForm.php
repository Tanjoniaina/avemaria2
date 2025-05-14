<?php

namespace App\Shared\Controller;

use App\Prisedesparametres\Entity\Parametreentre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParametreentreForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tensionarteriel')
            ->add('temperature')
            ->add('fc')
            ->add('fr')
            ->add('glycemie')
            ->add('poids')
            ->add('spo2')
            ->add('remarque')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Parametreentre::class,
        ]);
    }
}
