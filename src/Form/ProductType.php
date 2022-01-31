<?php

namespace App\Form;

use App\Entity\Product;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom',
        ])
            ->add('summary', TextType::class, [
                'label' => 'Résumé de la déscription',
            ])
            ->add('available', TextType::class, [
                'label' => 'Disponibilité (0 non dispo 1 en stock)',
            ])
            ->add('stock', TextType::class, [
                'label' => 'Quantité disponible',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '10240k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/pjpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez remplir ce champ avec une image (.png .gif .jpg ou .jpeg)',
                    ])
                ],
            ])
            ->add('weight', TextType::class, [
                'label' => 'Poids',
            ])
            ->add('hihlighted')
            ->add('online', TextType::class, [
                'label' => 'Afficher sur le site',
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix',
            ])
            ->add('unitType', TextType::class, [
                'label' => 'Pièce ou kg',
            ])
            ->add('category', null,
            ['label' => 'Catégorie'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
