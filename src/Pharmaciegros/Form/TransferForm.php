<?php

namespace App\Pharmaciegros\Form;

use App\Pharmaciegros\Entity\Location;
use App\Pharmaciegros\Entity\Supplier;
use App\Pharmaciegros\Entity\Transfer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransferForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class,[
                'choices'=>[
                    'Envoyé' => 'Envoyé',
                    'Reçu' => 'Reçu',
                    'Annulé' => 'Annulé'
                ]
            ] )
            ->add('transfertDate')
            ->add('comment')
            ->add('sourceLocation', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
            ])
            ->add('destinationLocation', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
            ])
            ->add('ligne', CollectionType::class,[
                'entry_type' => TransferLineForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transfer::class,
        ]);
    }
}
