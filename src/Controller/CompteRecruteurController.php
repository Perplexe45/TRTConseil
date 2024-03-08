<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompteRecruteurController extends AbstractController
{
    #[Route('/compte/recruteur', name: 'app_compte_recruteur')]
    public function index(): Response
    {
        return $this->render('compte_recruteur/index.html.twig', [
            'controller_name' => 'CompteRecruteurController',
        ]);
    }
}
