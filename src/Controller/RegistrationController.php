<?php

namespace App\Controller;

use App\Entity\User;
/* use App\Entity\Candidat;
use App\Entity\Recruteur; */
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { //Verif si dans twig, la soumission du form est ok
            $categorie = $request->request->get('categorie');

            //Verification si le choix a bien été sélectionné avec le sélecteur (recruteur ou candidat)
            if (!$categorie) {
                $this->addFlash('danger', 'Veuillez sélectionner une catégorie (candidat ou recruteur).');
                return $this->redirectToRoute('app_register'); // Rediriger vers la page d'inscription
            }


            // Déterminer les rôles en fonction de la catégorie
            $roles = [];
            if ($categorie === 'Candidats') {
                $roles[] = 'ROLE_CANDIDAT';
            } elseif ($categorie === 'Recruteurs') {
                $roles[] = 'ROLE_RECRUTEUR';
            }

            //On met un booléen pour identifier le recruteur ou le candidat
            if ($categorie === 'Candidats') {
                $user->setConnecCandidat(true);
                $user->setConnecRecruteur(false);
            } elseif ($categorie === 'Recruteurs') {
                $user->setConnecCandidat(false);
                $user->setConnecRecruteur(true);
            }
            // Attribuer les rôles à l'utilisateur
            $user->setRoles($roles);

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
