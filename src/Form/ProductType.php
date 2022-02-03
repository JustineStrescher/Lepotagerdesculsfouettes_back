<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\FormEvent;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
            ->add('available', ChoiceType::class,[
                'label' => 'Disponibilité',
                'choices'  => [
                    // Libellé => Valeur
                    'En stock' => 1, 
                    'Non disponible' => 0
                ],
                // Choix multiple => Tableau ;)
                'multiple' => false,
                // On veut des boutons radio !
                'expanded' => true,
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
            ->add('hihlighted', ChoiceType::class,[
                'label' => 'Mettre le produit en avant',
                'choices'  => [
                    // Libellé => Valeur
                    'OUI' => 1, 
                    'NON' => 0
                ],
                // Choix multiple => Tableau ;)
                'multiple' => false,
                // On veut des boutons radio !
                'expanded' => true,
            ])
            ->add('online', ChoiceType::class,[
                'label' => 'Afficher sur le catalogue en ligne',
                'choices'  => [
                    // Libellé => Valeur
                    'OUI' => 1, 
                    'NON' => 0
                ],
                // Choix multiple => Tableau ;)
                'multiple' => false,
                // On veut des boutons radio !
                'expanded' => true,
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix',
            ])
            ->add('unitType', ChoiceType::class,[
                'label' => 'Vendu à la pièce ou au kg',
                'choices'  => [
                    // Libellé => Valeur
                    'Pièce' => 1, 
                    'Au kg' => 0
                ],
                // Choix multiple => Tableau ;)
                'multiple' => false,
                // On veut des boutons radio !
                'expanded' => true,
            ])->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $form = $event->getForm();
                $formOptions = [
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'multiple' => false,
                    'expanded' => false,
                    'query_builder' => function (CategoryRepository $CategoryRepository) {
                        // call a method on your repository that returns the query builder
                        return $CategoryRepository->findAllSubCategoriesQb();
                    },
                ];
                // create the field, this is similar the $builder->add()
                // field name, field type, field options
                $form->add('category', EntityType::class, $formOptions);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
