<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompteCandidatController extends AbstractController
{
    #[Route('/compte/candidat', name: 'app_compte_candidat')]
    public function index(): Response
    {
        return $this->render('compte_candidat/index.html.twig', [
            'controller_name' => 'CompteCandidatController',
        ]);
    }
}
