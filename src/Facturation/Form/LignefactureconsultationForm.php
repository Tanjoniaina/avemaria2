<?php

namespace App\Facturation\Form;

use App\Facturation\Entity\Lignefacture;
use App\Facturation\Entity\Tarifacte;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LignefactureconsultationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tarifacte', EntityType::class,[
                'class' => Tarifacte::class,
                'choice_label' => 'nom',
                'choice_attr' => function (TarifActe $tarif) {
                    return ['data-prix' => $tarif->getMontant()];
                },
                'placeholder' => '-- Sélectionner un acte --',
                'label' => 'Acte facturé',
                'required' => true,
            ])
            ->add('montant')
            ->add('type')
            ->add('referenceid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lignefacture::class,
        ]);
    }
}
