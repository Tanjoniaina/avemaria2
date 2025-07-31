<?php

namespace App\Pharmaciegros\Form;

use App\Pharmaciegros\Entity\Product;
use App\Pharmaciegros\Entity\Purchaseorder;
use App\Pharmaciegros\Entity\Purchaseorderline;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PurchaseorderlineForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantityordered', TextType::class,[
                'attr' => [
                'placeholder' => 'Quantité' // Le placeholder
                ]
            ])
            ->add('unitprice', TextType::class,[
                'attr' => [
                'placeholder' => 'Prix unitaire' // Le placeholder
                ]
            ])
            ->add('subtotal', TextType::class,[
                'attr' => [
                'placeholder' => 'Total' // Le placeholder
                ]
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'autocomplete' => true,
                'attr' => [
                    'data-autocomplete-url' => '/autocomplete/product'
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchaseorderline::class,
        ]);
    }
}
