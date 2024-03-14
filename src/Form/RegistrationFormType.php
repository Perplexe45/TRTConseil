<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\User;
use App\Entity\Recruteur;
use App\Form\RecruteurType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recruteur', RecruteurType::class, [ //Autre formulaire créé, sinon trop compliqué
                'label' => false,
            ])

            ->add('candidat', CandidatType::class, [ //Autre formulaire créé ; RecruteurType, sinon trop compliqué
                'label' => false,
            ])

            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Choisissez votre email ...'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes du contrat.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'Nouveau mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ])
                ],
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Choisissez votre mot de passe ...',
                        'class' => 'form-control'
                    ],
                    'row_attr' => [
                        'class' => 'form-group mb-3'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Confirmer le mot de passe ...',
                        'class' => 'form-control'
                    ],
                    'row_attr' => [
                        'class' => 'form-group mb-3'
                    ]
                ]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
