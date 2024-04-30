<?php
namespace App\Controller\Admin;


use App\Entity\CandidatAnnonce;
use App\Repository\AnnonceRepository;
use App\Repository\CandidatRepository;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CandidatAnnonceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CandidatAnnonce::class;
    }
    

    /* public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setLabel('Delete');
            });
    } */

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            
            AssociationField::new('annonce')
                ->setFormTypeOption('query_builder', function (AnnonceRepository $repository) {
                    return $repository->createQueryBuilder('a')
                        ->orderBy('a.poste', 'ASC');
                })
                ->setLabel('Annonce'),

            TextField::new('annonce.recruteur.nomEntreprise')
                ->setLabel('Recruteur')
                ->setFormTypeOption('disabled', true), 

            AssociationField::new('candidat')
                ->setFormTypeOption('query_builder', function (CandidatRepository $repository) {
                    return $repository->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC')
                        ->addOrderBy('c.prenom', 'ASC');
                })
                ->setLabel('Candidat'),

            TextField::new('cv', 'CV')
            ->setFormTypeOption('required', false)
            ->formatValue(function ($value, $entity) {
                if ($value) {
                    $uploadsPath = '/uploads';
                    return '<a href="' . $uploadsPath . '/' . $value . '" target="_blank">' . $value . '</a>';
                }
                return null;
            }),

            BooleanField::new('approbationConsultant')
                ->setLabel('Accord consultant'),
            BooleanField::new('envoiMailRecruteur')
                ->setLabel('Envoi Mail Recruteur')
                ->setFormTypeOption('disabled', $pageName === 'index'), 
            ];
    }
}
