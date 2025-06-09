<?php

namespace App\Pharmaciegros\Form;

use App\Pharmaciegros\Entity\Purchaseorder;
use App\Pharmaciegros\Entity\Supplier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseorderForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('orderdate')
            ->add('status', ChoiceType::class,[
                'choices'=>[
                    'Brouillon' => 'brouillon',
                    'envoyé' => 'envoye',
                    'reçu' => 'recu',
                    'annulé'=> 'annule'
                ]
            ])
            ->add('referencenumber')
            ->add('totalamount')
            ->add('supplier', EntityType::class, [
                'class' => Supplier::class,
                'choice_label' => 'name',
                'autocomplete' => true,
            ])
            ->add('ligne', CollectionType::class,[
                'entry_type' => PurchaseorderlineForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchaseorder::class,
        ]);
    }
}
