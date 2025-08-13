<?php

namespace App\Pharmaciegros\Form;

use App\Pharmaciegros\Entity\Product;
use App\Pharmaciegros\Entity\Receptionline;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class ReceptionlineForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantityReceived')
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'disabled' => true,
            ])
            ->add('purchaseprice', MoneyType::class, [
                'label' => 'Prix d’achat',
                'mapped' => true,
                'property_path' => 'product.purchaseprice',
                'currency' => 'MGA',
                'required' => false,
                'scale' => 2,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Receptionline::class,
        ]);
    }
}
