<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AnnonceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Annonce::class;
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
