<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, RequestStack $requestStack): Response
    {

        // Vérifiez si l'utilisateur est connecté
        $user = $this->getUser();
        if ($user instanceof User) {

            // Vérifiez si l'utilisateur est un administrateur
            if ($user->isConnecAdministrateur()) {
                $session = $requestStack->getSession();
                $session->set('adminconnect', 1);
                return $this->redirectToRoute('app_admin');
            }

            // Vérifiez si l'utilisateur est un consultant
            if ($user->isConnecConsultant()) {
                $session = $requestStack->getSession();
                $session->set('consultantconnect', 2);
                return $this->redirectToRoute('app_consultant');
            }
            // Vérifiez si l'utilisateur est un recruteur
            if ($user->isConnecRecruteur()) {
                $session = $requestStack->getSession();
                $session->set('recruteurconnect', 3);
                return $this->redirectToRoute('app_compte_recruteur');
            }

            // Vérifiez si l'utilisateur est un candidat
            if ($user->isConnecCandidat()) {
                $session = $requestStack->getSession();
                $session->set('candidatconnect', 4);
                return $this->redirectToRoute('app_compte_candidat');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
