<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Entity\Candidat;
use App\Entity\Recruteur;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardConsultantController extends AbstractDashboardController
{
    #[IsGranted('ROLE_CONSULTANT')]
    #[Route('/consultant', name: 'app_consultant')]
    public function index(): Response
    {
        return $this->render('security/dashboard_consultant.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('consultant');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Les recruteurs', 'fas fa-list', Recruteur::class);
        yield MenuItem::linkToCrud('Les candidat', 'fas fa-user', Candidat::class);
        yield MenuItem::linkToCrud('Les annonces', 'fas fa-user', Annonce::class);
    }
}
