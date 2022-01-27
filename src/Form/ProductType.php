<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('summary')
            ->add('available')
            ->add('stock')
            ->add('description')
            ->add('picture')
            ->add('weight')
            ->add('updatedAt')
            ->add('creationAt')
            ->add('slug')
            ->add('hihlighted')
            ->add('online')
            ->add('price')
            ->add('unitType')
            ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
