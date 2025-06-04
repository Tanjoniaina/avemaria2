<?php

namespace App\Pharmaciegros\Form;

use App\Pharmaciegros\Entity\Invoice;
use App\Pharmaciegros\Entity\Supplier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('invoicenumber')
            ->add('invoicedate')
            ->add('duedate')
            ->add('amount')
            ->add('status')
            ->add('supplier', EntityType::class, [
                'class' => Supplier::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
