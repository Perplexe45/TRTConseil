<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Candidat;
use App\Entity\Consultant;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardAdminController extends AbstractDashboardController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('security/dashboard_admin.html.twig');

      
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('administrateur');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Ajouter consultant', 'fas fa-user', User::class);
       
        /* yield MenuItem::linkToCrud('annonce', 'fas fa-user', Annonce::class); */
    }
}
