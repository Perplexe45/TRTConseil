<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use Symfony\Component\Validator\Constraints\NotBlank;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;



class UserCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureCrud(Crud $crud): Crud
    {


        return $crud
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInPlural('Les consultants')
            ->setEntityLabelInSingular('un consultant')
            ->setPageTitle('index', 'Liste des consultants du site')
            ->setPaginatorPageSize(15)
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        yield EmailField::new('email')->setLabel('Email');
        yield TextField::new('Password')->setLabel('Mot de passe');
        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            yield TextField::new('Password')
                ->setLabel('Mot de passe')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe doivent correspondre.',
                    'required' => true,
                    'first_options' => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmer le mot de passe'],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 6]),
                    ],
                ])
                ->setRequired(true)
                ->hideWhenUpdating();

            yield ChoiceField::new('roles')
                ->setFormTypeOption('multiple', true)
                ->autocomplete()
                ->setChoices([
                    'ROLE_CONSULTANT'   => 'ROLE_CONSULTANT',
                    // ajoutez plus de rôles si nécessaire…
                ]);
        }

        yield BooleanField::new('connecConsultant')
            ->setLabel('Accord Consultant')
            ->setFormTypeOption('data', true)
            ->hideOnIndex();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters

            ->add(BooleanFilter::new('connecConsultant'));
    }




    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit')->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')->setLabel(false);
            });
    }
}
