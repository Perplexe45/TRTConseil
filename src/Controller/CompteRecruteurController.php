<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Annonce;
use App\Entity\Recruteur;
use App\Repository\RecruteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompteRecruteurController extends AbstractController
{
    #[Route('/compte/recruteur', name: 'app_compte_recruteur')]
    public function index(): Response
    {
        return $this->render('compte_recruteur/index.html.twig', [
            'controller_name' => 'CompteRecruteurController',
        ]);
    }

    #[Route('/compte/recruteur', name: 'add_annonce')]
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
            $annonce->setPoste($poste);
            $annonce->setLieu($lieu);
            $annonce->setHoraire($horaire);
            $annonce->setSalaire($salaire);
            $annonce->setDescription($description);
            $now = new DateTimeImmutable();
            $annonce->setDateCreation($now);
            /* $annonce->setRecruteur(); */

            $user = $this->getUser();
            if ($user instanceof User && $user->getRecruteur()) {
                $annonce->setRecruteur($user->getRecruteur());
            }
            $annonce->setPublie(false);

            // Enregistre l'annonce en base de données
            $entityManager->persist($annonce);
            $entityManager->flush();
            // Affiche un message de succès à l'utilisateur
            $this->addFlash('success', 'L\'annonce a été ajoutée avec succès.');
        }

        // Redirige l'utilisateur vers la page d'ajout d'annonces
        return $this->redirectToRoute('add_annonce');
    }

    #[Route('/compte/recruteur/modif', name: 'modif_coordonnees')]
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

            // Vérifier si l'identifiant du recruteur est valide
            if ($recruteurId === null || $recruteurId === '') {
                throw $this->createNotFoundException('Identifiant du recruteur manquant');
            }
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

            // MAJ de l'entreprise
            $recruteur->setAdresse($adresse);
            $recruteur->setCodePostal($codepostal);
            $recruteur->setVille($ville);
            $recruteur->setSiteInternet($siteinternet);

            // Enregistre l'annonce en base de données
            $entityManager->flush();

            // Affiche un message de succès à l'utilisateur
            $this->addFlash('success', 'Les coordonnées ont été mis à jour.');
        }

        // Redirige l'utilisateur vers la page d'ajout d'annonces
        return $this->render('compte_recruteur/index.html.twig');
    }
}
