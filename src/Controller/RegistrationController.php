<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Candidat;
use App\Entity\Recruteur;

use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {

        $user = new User();
        $candidat = null;
        $recruteur = null;
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
       
     
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $request->request->get('categorie');
            
            if (!$categorie) {
                $this->addFlash('danger', 'Veuillez sélectionner une catégorie (candidat ou recruteur).');
                return $this->redirectToRoute('app_register');
            }

            $roles = [];
            if ($categorie === 'Candidat') {
                $roles[] = 'ROLE_CANDIDAT';
                $candidat = new Candidat();
                // Récupére le nom du candidat à partir du formulaire imbriqué
                $nom = $form->get('candidat')->get('nom')->getData();
                $candidat->setNom($nom);

                // Associe le métier au candidat
                $metier = $form->get('metier')->getData();
                $candidat->setMetier($metier);

                // Associe l'utilisateur au candidat
                $user->setCandidat($candidat); //Recup l'ID du candidat dans User
                $user->setRecruteur(NULL);
                $user->setConnecCandidat(true);
                $user->setConnecRecruteur(false);
            } elseif ($categorie === 'Recruteur') {
                $roles[] = 'ROLE_RECRUTEUR';
                // Récupére le nom de l'entreprise à partir du formulaire imbriqué
                $recruteur = new Recruteur();
                $nomEntreprise = $form->get('recruteur')->get('nomEntreprise')->getData();
                $recruteur->setNomEntreprise($nomEntreprise);
                // Associe l'utilisateur au recruteur
                $user->setRecruteur($recruteur); //Recup l'ID du recruteur pour le User
                $user->setCandidat(NULL);
                $user->setConnecCandidat(false);
                $user->setConnecRecruteur(true);
                $user->setMetier(NULL);
            }


            $user->setRoles($roles);
            $user->setEnService(0);


            // Encode le mot de passe
            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));

            // Persiste et flush l'objet Recruteur ou candidat
            if ($categorie === 'Candidat') {
                $entityManager->persist($candidat);
            } elseif ($categorie === 'Recruteur') {
                $entityManager->persist($recruteur);
            }


            //Début de l'enregistrement dans la table User
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Merci de votre enregistrement. Le modérateur du site est prévenu du contact');
            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,

        ]);
    }
}
