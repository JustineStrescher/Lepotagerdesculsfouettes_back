<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    // Libellé => Valeur
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                // Choix multiple => Tableau ;)
                'multiple' => true,
                // On veut des checkboxes !
                'expanded' => true,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                // On récupère le form depuis l'event (pour travailler avec)
                $form = $event->getForm();
                // On récupère le user mappé sur le form
                $user = $event->getData();

                // On conditionne le champ "password"
                // Si user existant, il a id non null
                if ($user->getId() !== null) {
                    // Edit
                    $form->add('password', null, [
                        'label' => 'Mot de passe',
                        // Pour le form d'édition, on n'associe pas le password à l'entité
                        // @link https://symfony.com/doc/current/reference/forms/types/form.html#mapped
                        'mapped' => false,
                        'attr' => [
                            'placeholder' => 'Laissez vide si inchangé',
                            
                        ]
                    ]);
                } else {
                    // New
                    $form->add('password', null, [
                        'label' => 'Mot de passe',
                        // En cas d'erreur du type
                        // Expected argument of type "string", "null" given at property path "password".
                        // (notamment à l'edit en cas de passage d'une valeur existante à vide)
                        'empty_data' => '',
                        // On déplace les contraintes de l'entité vers le form d'ajout
                        'constraints' => [
                            new NotBlank(array(), "Le champ 'mot de passe' ne peut être vide"),
                            new Regex(
                                "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                                "Le mot de passe doit contenir au minimum 8 caractères, une majuscule, un chiffre et un caractère spécial"
                            ),
                        ],
                    ]);
                }
            })
            ->add('firstname', TextType::class, [
                //'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                //'label' => 'Nom',
            ])
            ->add('address', TextType::class, [
                //'label' => 'Adresse',
            ])
            ->add('zip', TextType::class, [
                //'label' => 'code postal',
            ])
            ->add('city', TextType::class, [
                //'label' => 'Ville',
            ])
            ->add('country', TextType::class, [
                //'label' => 'Pays',
            ])
            ->add('phone', TextType::class, [
                //'label' => 'Téléphone',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
