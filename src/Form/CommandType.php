<?php

namespace App\Form;

use App\Entity\Command;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numFact')
            ->add('status', TextType::class, [
                'label' => 'Facture n°',
            ])
            ->add('totalTTC', TextType::class, [
                'label' => 'Total TTC',
            ])
            ->add('totalHT', TextType::class, [
                'label' => 'Total HT',
            ])
            ->add('totalTVA', TextType::class, [
                'label' => 'Montant TVA',
            ])
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
        ]);
    }
}
