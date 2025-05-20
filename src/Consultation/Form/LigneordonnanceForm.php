<?php

namespace App\Consultation\Form;

use App\Consultation\Entity\Ligneordonnance;
use App\Consultation\Entity\Ordonnance;
use App\Pharmaciegros\Entity\Medicament;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneordonnanceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('medicament', EntityType::class, [
                'class' => Medicament::class,
                'choice_label' => 'nom',
                'placeholder' => '-- Sélectionner un acte --',
                'label' => 'Médicament',
                'required' => true,
            ])
            ->add('quantite')
            ->add('posologie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ligneordonnance::class,
        ]);
    }
}
