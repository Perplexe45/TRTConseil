<?php

    namespace App\Controller\Admin;

    use App\Entity\Consultant;
    use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
    use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
    use Symfony\Component\Validator\Constraints\Length;
    use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
    use Symfony\Component\Validator\Constraints\NotBlank;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
    use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

    class ConsultantCrudController extends AbstractCrudController
    {


        public static function getEntityFqcn(): string
        {
            return Consultant::class;
        }

        public function configureCrud(Crud $crud): Crud
        {
            return $crud
                ->setDefaultSort(['id' => 'DESC'])
                ->setEntityLabelInPlural('Les consultants')
                ->setEntityLabelInSingular('un consultant')
                ->setPageTitle('index', 'Liste des consultants du site')
                ->setPaginatorPageSize(10)
                ->showEntityActionsInlined();
        }

        public function configureFields(string $pageName): iterable
        {
            yield IdField::new('id')->hideOnForm();
            yield TextField::new('prenom')->setLabel('Prénom');
            yield TextField::new('nom')->setLabel('Nom');
            yield TextField::new('telephone')->setLabel('Téléphone');
            yield EmailField::new('email');

            if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
                yield TextField::new('password')
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
            }
        }

        
    }
