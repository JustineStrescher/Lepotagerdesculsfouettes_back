<?php

namespace App\Form;

use App\Entity\ProductCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductCommandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', TextType::class, [
                'label' => 'Quantité',
            ])
            ->add('unitPrice', TextType::class, [
                'label' => 'Prix à la pièce où au kg',
            ])
            ->add('totalTTC', TextType::class, [
                'label' => 'Total TTC',
            ])
            ->add('command', null, [
                'label' => 'Commande'
            ])
            ->add('product', null, [
                'label' => 'Produit'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductCommand::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
