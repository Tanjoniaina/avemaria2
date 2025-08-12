<?php

namespace App\Pharmaciegros\Form;

use App\Pharmaciegros\Entity\Product;
use App\Pharmaciegros\Entity\Stockmovement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockmovementForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('movementdate')
            ->add('quantity')
            ->add('type', ChoiceType::class,[
                'choices'=>[
                    'ENTREE' => 'ENTREE',
                    'SORTIE' => 'SORTIE'
                ]
            ])
            ->add('comment')
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
            'data_class' => Stockmovement::class,
        ]);
    }
}
