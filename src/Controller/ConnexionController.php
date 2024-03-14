<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'app_connexion')]
    public function index(Request $request): Response
    {
    
        $user = $this->getUser();
       // Vérifier si l'utilisateur est un administrateur
       if ($user instanceof User && $user->getAdministrateur()) {
           // Rediriger l'administrateur vers /admin
           return $this->redirectToRoute('app_admin');
       }
       
        return $this->render('connexion/index.html.twig');
    }

}
