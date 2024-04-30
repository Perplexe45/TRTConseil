<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Annonce;
use App\Entity\CandidatAnnonce;
use App\Repository\RecruteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Throwable;

class CompteRecruteurController extends AbstractController
{
    /////////////////////////////Page d'accueil du recruteur////////////////////////////////
    #[Route('/compte/recruteur', name: 'app_compte_recruteur')]
    #[IsGranted('ROLE_RECRUTEUR')]
    public function index(): Response
    {
        $user = $this->getUser();

        if ($user instanceof User) {
            // Vérifiez si l'utilisateur est un recruteur
            if ($user->getRecruteur()->isApprobationConsultant  ()) {
                // Vérification de l'approbation du consultant
                return $this->render('compte_recruteur/index.html.twig');
               } else {
                // Gérer le cas où l'approbation du consultant est refusée
                $this->addFlash('error', 'Votre demande d\'approbation est en cours de traitement.');
                //return $this->deconnexionEtHomepage();
                return $this->redirectToRoute('app_home');
               }
            }

        return $this->render('compte_recruteur/index.html.twig');
    }

    ////////////////////////Ajouter une annonce du recruteur//////////////////////////////
    #[Route('/compte/recruteur/addAnnonce', name: 'add_annonce')]
    public function addAnnonce(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récup l'utilisateur connecté
        $user = $this->getUser();

        // Vérifie que l'utilisateur a le rôle "ROLE_RECRUTEUR"
        if (!in_array('ROLE_RECRUTEUR', $user->getRoles())) {
            // Redirige l'utilisateur vers la page de connexion car mauvaise identif
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {

            // Récupère les données du formulaire en Post
            $lieu = $request->request->get('lieu');
            $poste = $request->request->get('poste');
            $horaire = $request->request->get('horaire');
            $salaire = $request->request->get('salaire');
            $description = $request->request->get('description');

            // Crée une nouvelle annonce
            $annonce = new Annonce();
            $annonce->setLieu($lieu);
            $annonce->setPoste($poste);
            $annonce->setHoraire($horaire);
            $annonce->setSalaire($salaire);
            $annonce->setDescription($description);
            $now = new DateTimeImmutable();
            $annonce->setDateCreation($now);
            

            $user = $this->getUser();
            if ($user instanceof User && $user->getRecruteur()) {
                $annonce->setRecruteur($user->getRecruteur());
            }
            $annonce->setPublie(false);

            // Enregistre l'annonce en base de données
            $entityManager->persist($annonce);
            $entityManager->flush();
            // Affiche un message de succès à l'utilisateur
            $this->addFlash('success', 'L\'annonce a été ajoutée avec succès. En attente de l\'accord du consultant');
        }

        // Redirige l'utilisateur vers la page d'ajout d'annonces
        return $this->render('compte_recruteur/index.html.twig');
    }

    //////////////////////Modif des coordonnées du recruteur//////////////////////////
    #[Route('/compte/recruteur/mofifCoordonnees', name: 'modif_coordonnees')]
    public function modifCoordonnees(Request $request, RecruteurRepository $recruteurRepository, EntityManagerInterface $entityManager): Response
    {
        // Récup l'utilisateur connecté
        $user = $this->getUser();

        // Vérifie que l'utilisateur a le rôle "ROLE_RECRUTEUR"
            if (!in_array('ROLE_RECRUTEUR', $user->getRoles())) {
                // Redirige l'utilisateur vers la page de connexion car mauvaise identif
                return $this->redirectToRoute('app_login');
            }

        if ($request->isMethod('POST')) { // Récupère les données du formulaire en Post
            $recruteurId = $request->request->get('recruteur_id'); //identif récupéré avec Twig

            // Récupérer l'entité Recruteur à partir de l'identifiant
            $recruteur = $recruteurRepository->find($recruteurId);
            
            // Vérifier si le recruteur existe
            if (!$recruteur) {
                throw $this->createNotFoundException('Recruteur non trouvé');
            }

            $adresse = $request->request->get('adresse');
            $codepostal = $request->request->get('codepostal');
            $ville = $request->request->get('ville');
            $siteinternet = $request->request->get('siteinternet');
            $email = $request->request->get('email');

            // MAJ de l'entreprise
            $recruteur->setAdresse($adresse);
            $recruteur->setCodePostal($codepostal);
            $recruteur->setVille($ville);
            $recruteur->setSiteInternet($siteinternet);
            $recruteur->setEmail($email);

            // Enregistre l'annonce en base de données
            $entityManager->flush();

            // Affiche un message de succès à l'utilisateur
            $this->addFlash('success', 'Les coordonnées ont été mis à jour.');
        }

        // Redirige l'utilisateur vers la page d'ajout d'annonces
        return $this->render('compte_recruteur/index.html.twig');
    }

    /////////////////Modif du compte////////////////////////
    #[Route('/compte/recruteur/modifCompte', name: 'modif_compte')]
    public function modifCompte(Request $request, RecruteurRepository $recruteurRepository, EntityManagerInterface $entityManager): Response
    {
        // Récup l'utilisateur connecté
        $user = $this->getUser();

        // Vérifie que l'utilisateur a le rôle "ROLE_RECRUTEUR"
            if (!in_array('ROLE_RECRUTEUR', $user->getRoles())) {
                // Redirige l'utilisateur vers la page de connexion car mauvaise identif
                return $this->redirectToRoute('app_login');
            }

        if ($request->isMethod('POST')) { // Récupère les données du formulaire en Post
            $recruteurId = $request->request->get('recruteur_id'); //identif récupéré avec Twig

            // Récupérer l'entité Recruteur à partir de l'identifiant
            $recruteur = $recruteurRepository->find($recruteurId);
            // Vérifier si le recruteur existe
            if (!$recruteur) {
                throw $this->createNotFoundException('Recruteur non trouvé');
            }
            
            $passwordOld = $request->get('npassword');
            $passwordNew = $request->get('cpassword');

            if ($passwordNew  !== $passwordOld) {
                $this->addFlash('error', 'les mots de passe ne sont pas identiques!');
                return $this->redirect('modifCompte#address');
            }

            //Vérification si l'utilisateur est connecté
            $user = $this->getUser();
            if ($user instanceof User) {
                $user->setPassword($passwordNew);
                
                // Enregistre l'annonce en base de données
                $entityManager->flush();
            }
            
            // Affiche un message de succès à l'utilisateur
            $this->addFlash('success', 'Les coordonnées ont été mis à jour.');
        }
        // Redirige l'utilisateur vers la page d'ajout d'annonces
        return $this->render('compte_recruteur/index.html.twig');
    }

    //////////////Suppression le compte du rercruteur////////////
    /////////////////////////////////////////////////////////////
    #[Route('compte/recruteur/supprimer', name: 'supprimer_recruteur', methods: ['POST', 'DELETE'])]
    public function supprimerCompteRecruteur(EntityManagerInterface $entityManager): Response
    {
       // Récup l'utilisateur connecté
       $user = $this->getUser();
       if (!$user instanceof User) {
           throw new Exception('Utilisateur introuvable');
       }
   
       // Suppression des enregistrements de la table"candidat_annonce"
        $entityManager->createQueryBuilder()
        ->delete(CandidatAnnonce::class, 'ca')
        ->where('ca.annonce IN (:annonces)')
        ->setParameter('annonces', $user->getRecruteur()->getAnnonces())
        ->getQuery()
        ->execute();

        // Suppression des annonces du recruteur
        $entityManager->createQueryBuilder()
        ->delete(Annonce::class, 'a')
        ->where('a.recruteur = :recruteur')
        ->setParameter('recruteur', $user->getRecruteur())
        ->getQuery()
        ->execute();


        // Suppression de l'id du recruteur
        $entityManager->remove($user->getRecruteur());

        // Suppression de l'id de la table user
        $entityManager->remove($user);

        // Enregistrement en base de données
        $entityManager->flush();
        $this->addFlash('success', 'Votre compte a été supprimé avec succès.'); 
        return $this->redirectToRoute('app_logout');
        //return $this->redirectToRoute('app_home');



    
    //return $this->redirectToRoute('app_home');
    }

}
