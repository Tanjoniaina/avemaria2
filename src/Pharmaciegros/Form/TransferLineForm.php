<?php

namespace App\Pharmaciegros\Form;

use App\Pharmaciegros\Entity\Product;
use App\Pharmaciegros\Entity\Transfer;
use App\Pharmaciegros\Entity\TransferLine;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransferLineForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity')
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'autocomplete' => true,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TransferLine::class,
        ]);
    }
}
