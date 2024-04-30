<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class AnnonceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Annonce::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setLabel('Delete');
            });
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('recruteur')
                ->setLabel('Recruteur')
                ->setFormTypeOption('choice_label', 'recruteurInfo')
                ->onlyOnIndex(),
            /*  IdField::new('id')->setLabel('ID'), */
            TextField::new('poste')->setLabel('Poste'),
            TextField::new('lieu')->setLabel('Lieu'),
            TextField::new('horaire')->setLabel('Horaire'),
            TextField::new('salaire')->setLabel('Salaire'),
            TextField::new('description')->setLabel('Description'),
            BooleanField::new('publie')

            // Autres champs à afficher
        ];
    }
}
