<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Candidat;
use App\Entity\CandidatAnnonce;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\AnnonceRepository;


class AnnonceController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/annonces', name: 'app-listAnnonces')]
    public function index(Request $request): Response
    {
        // Récupérer toutes les annonces publiées
        $annonces = $this->entityManager->getRepository(Annonce::class)->findBy(['publie' => true]);

        return $this->render('annoncesValidees/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    /////////////////////////////////////////////////
    ///////////Ajouter une annonce//////////////////
    #[Route('/annonces/ajout', name: 'app_ajoutAnnonces')]
    public function ajouterAnnonce(): Response
    {
        /*   // Récupérer toutes les annonces publiées
        $annonces = $this->entityManager->getRepository(Annonce::class)->findBy(['publie' => true]); */
        /*  dd($annonces); */

        return $this->render(
            'annonces/ajoutAnnonces.html.twig'/* , [
            'annonces' => $annonces, */
        );
    }

    ////////////////////////////////////////////////////////////////////////
    ////////////////postuler a une annonce pour un candidat/////////////////
    #[Route('/annonces/candidat/{annonce}/{candidat}', name: 'app_postuler')]
    public function postuler(Annonce $annonce, Candidat $candidat, EntityManagerInterface $entityManager, RequestStack $requestStack, Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $candidatUser = $this->getUser();

        if ($candidatUser instanceof User) {
            //Recup du CV du candidat
            $cv = $candidatUser->getCandidat()->getCv();
        }

        // Vérifier si l'utilisateur est connecté
        if (!$candidatUser) {
            // Rediriger l'utilisateur vers la page de connexion
            return $this->redirectToRoute('app_login');
        }
        // Vérifie que l'utilisateur a le rôle "ROLE_CANDIDAT"
        if (!in_array('ROLE_CANDIDAT', $candidatUser->getRoles())) {
            // Redirige l'utilisateur vers la page de connexion car mauvaise identif
            return $this->redirectToRoute('app_login');
        }

        // Vérifiez si l'utilisateur a cliqué sur le bouton "Postuler"
        if (!in_array('ROLE_CANDIDAT', $candidatUser->getRoles())) {
            if ($request->query->get('postuler') && !$candidatUser instanceof User) {
                // Récupérez l'ID de l'annonce de la requête
                $annonceId = $request->query->get('annonce');

                // Récupérez l'objet annonce de la base de données
                $annonce = $this->entityManager->getRepository(Annonce::class)->find($annonceId);

                if (!$annonce) {
                    // L'annonce n'existe pas, redirigez vers la page d'erreur
                    return $this->redirectToRoute('app_error');
                }

                // Stockez l'ID de l'annonce dans la session
                $request->getSession()->set('annonceId', $annonce->getId());

                return $this->redirectToRoute('app_login');
            }
        }

        // Créer une nouvelle instance de CandidatAnnonce
        $candidatAnnonce = new CandidatAnnonce();
        $candidatAnnonce->setAnnonce($annonce);
        $candidatAnnonce->setCandidat($candidat);
        $candidatAnnonce->setCv($cv);
        $candidatAnnonce;

        // Enregistrer la candidature dans la base de données
        $entityManager->persist($candidatAnnonce);
        $entityManager->flush();

        //Message indiquant que l'enregistrement a bien eu lieu
        $this->addFlash('success', 'Merci d\avoir postulé pour cette offre. Le recruteur vous contactera');

        // Rediriger l'utilisateur vers une page de confirmation ou une autre page
        return $this->redirectToRoute('app-listAnnonces');
    }

    ///////////////////////////////////////////////////////////////////////////
    /////////////////Réponses des candidats aux annonces///////////////////////
    #[Route('/annonces/reponse/{id}', name: 'app_reponseCandidat')]
    public function ReponseCandidat(AnnonceRepository $annonceRepository, int $id): Response
    {
        // Convertir l'identifiant de l'annonce en entier
        $id = intval($id);
       
        // Récupérer l'annonce à partir de l'identifiant du param de la route
        $annonce = $annonceRepository->find($id);
        /* dd($annonce); */

        // Vérifier si l'annonce existe
        if (!$annonce) {
            throw $this->createNotFoundException('L\'annonce demandée n\'existe pas.');
        }


        // Rediriger vers la page de réponses de l'annonce
        return $this->render('reponsesAnnonces/index.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    ////////////////////////////////////////////////////////////////////////
    //////////////////////Supprimer une annonce////////////////////////////
    #[Route('/annonces/supprimer/id', name: 'app_supprimerAnnonce')]
    public function SupprimerAnnonce(Annonce $annonce, Candidat $candidat, EntityManagerInterface $entityManager, RequestStack $requestStack, Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $candidatUser = $this->getUser();

        // Rediriger l'utilisateur vers une page de confirmation ou une autre page
        return $this->redirectToRoute('app-listAnnonces');
    }
}
