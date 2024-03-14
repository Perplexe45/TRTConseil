<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CompteCandidatController extends AbstractController
{
    #[Route('/compte/candidat', name: 'app_compte_candidat')]
    public function index(): Response
    {
        return $this->render('compte_candidat/index.html.twig', [
            'controller_name' => 'CompteCandidatController',
        ]);
    }

    ///////////////////Modif du compte///////////////////////////
    public function ModifCompte(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('compte_candidat/index.html.twig', [
            'controller_name' => 'CompteCandidatController',
        ]);
    }


}
