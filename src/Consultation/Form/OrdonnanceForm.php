<?php

namespace App\Consultation\Form;

use App\Consultation\Entity\Ordonnance;
use App\Shared\Entity\Dossierpatient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OrdonnanceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('ligne', CollectionType::class,[
                'entry_type' => LigneordonnanceForm::class,
                'allow_add' => true
            ])
            ->add('ligneanalyse', CollectionType::class,[
                'entry_type' => LigneanalyseForm::class,
                'allow_add' => true
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
        ]);
    }
}
