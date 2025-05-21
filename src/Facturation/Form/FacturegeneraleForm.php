<?php

namespace App\Facturation\Form;

use App\Facturation\Entity\Facture;
use App\Shared\Entity\Dossierpatient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturegeneraleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('estpaye')
            ->add(
                'ligne',
                CollectionType::class,
                [
                    'entry_type' => LignefacturegeneraleForm::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]

            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
