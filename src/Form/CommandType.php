<?php

namespace App\Form;

use App\Entity\Command;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numFact', TextType::class, [
                'label' => 'Facture n°',
            ])
            ->add('status', ChoiceType::class,[
                'label' => 'Status de la commande',
                'choices'  => [
                    // Libellé 
                    'En cours' => 'En cours', 
                    'Payé' => 'Payé',
                    'Préparé' => 'Préparé'
                ],
                // Choix multiple => Tableau ;)
                'multiple' => false,
                // On veut des boutons radio !
                'expanded' => true,
            ])
            ->add('totalTTC', TextType::class, [
                'label' => 'Total TTC',
            ])
           
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
