<?php

namespace App\Consultation\Form;

use App\Consultation\Entity\Ligneanalyse;
use App\Facturation\Entity\Typeanalyse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneanalyseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeanalyse', EntityType::class,[
                'class' => Typeanalyse::class,
                'choice_label' => 'nom',
                'choice_attr' => function (Typeanalyse $tarif) {
                    return ['data-prix' => $tarif->getMontant()];
                },
                'placeholder' => '-- Sélectionner un acte --',
                'label' => 'Acte facturé',
                'required' => true,
            ])
            ->add('montant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ligneanalyse::class,
        ]);
    }
}
