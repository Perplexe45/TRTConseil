<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\User;
use App\Entity\Recruteur;
use App\Entity\Consultant;
use App\Entity\Administrateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Your email ...',
                'style' => ' border-color: #928787 !important'
            ]
        ])
        
        
            ->add('motDePasse', RepeatedType::class, [ //Les 2 champs pour le MDP et La vérif du MDP
                'type' => PasswordType::class,      //sont gérés
                'label' => false,
                'required' => true,
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre mot de passe'
                    ]
                ],
                'constraints' => [
                    new EqualTo([
                        'propertyPath' => 'motDePasse',
                        'message' => 'Les mots de passe doivent être identiques',
                    ]),
                ],
                'constraints' => [new Length(['min' => 4, 'max' => 20])]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
