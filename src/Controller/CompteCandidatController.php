<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Metier;
use App\Entity\CandidatAnnonce;
use App\Repository\CandidatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CompteCandidatController extends AbstractController
{

    private $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /////////////////////////Page d'accueil/////////////////////////////
    #[Route('/compte/candidat', name: 'app_compte_candidat')]
    #[IsGranted('ROLE_CANDIDAT')]
    public function index(RequestStack $requestStack, EntityManagerInterface $entityManager): Response
    {
     // Vérifiez si l'utilisateur est connecté
     $user = $this->getUser();

     if ($user instanceof User) {
         // Vérifiez si l'utilisateur est un candidat
         if ($user->getCandidat()->isApprobationConsultant  ()) {
             // Vérification de l'approbation du consultant
             return $this->render('compte_candidat/index.html.twig', [
                 'controller_name' => 'CompteCandidatController',
             ]);
            } else {
             // Gérer le cas où l'approbation du consultant est refusée
             $this->addFlash('error', 'Votre demande d\'approbation est en cours de traitement.');
             //return $this->deconnexionEtHomepage();
             return $this->redirectToRoute('app_home');
            }
         }

        return $this->render('compte_candidat/index.html.twig', [
            'controller_name' => 'CompteCandidatController',
        ]);
    }

    /////////////////Modif des coordonnées du candidat//////////////////
    #[Route('/compte/candidat/mofifCoordonnees', name: 'coordonnees_candidat')]
    public function modifCoordonnees(Request $request, CandidatRepository $candidatRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // Récup l'utilisateur connecté
        $user = $this->getUser();
        $metiers = $entityManager->getRepository(Metier::class)->findAll();

        // Vérifie que l'utilisateur a le rôle "ROLE_CANDIDAT"
        if (!in_array('ROLE_CANDIDAT', $user->getRoles())) {
            // Redirige l'utilisateur vers la page de connexion car mauvaise identif
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) { // Récupère les données du formulaire en Post
            $candidatId = $request->request->get('candidat_id'); //identif récupéré avec Twig

            // Vérifier si l'identifiant du recruteur est valide
            if ($candidatId === null || $candidatId === '') {
                throw $this->createNotFoundException('Identifiant du candidat manquant');
            }
            // Récupérer l'entité Recruteur à partir de l'identifiant
            $candidat = $candidatRepository->find($candidatId);
            // Vérifier si le recruteur existe
            if (!$candidat) {
                throw $this->createNotFoundException('Candidat non trouvé');
            }

            //Recupération du CV en fichier pdf si existant
            $cvFile = $request->files->get('cv-file');
            if (!empty($cvFile)) {
                if ($cvFile instanceof UploadedFile && $cvFile->guessExtension() === 'pdf') {
                    $fileName = $cvFile->getClientOriginalName();
    
                    // Déplace le fichier téléversé vers le répertoire de destination
                    $cvFile->move($this->getParameter('cv_directory'), $fileName);
                    $candidat->setCv($fileName);
                    //dd($candidat);
                } else {  //ce n'est pas un fichier pdf
                    $this->addFlash('error', 'Le fichier doit être un fichier PDF.');
                }
            }
            
            ///////Récupération des champs de Twig///////////////////
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');
            $motdepasseModif = $request->get('modifpassword');
            $confirmpassword = $request->get('confirmpassword');

            
            /////////////////////////Modif du mot de passe///////////////////////////
            if (!empty($motdepasseModif) && !empty($confirmpassword)) {
                // Modif du mot de passe si les 2 champs ont été remplis
                if ($motdepasseModif === $confirmpassword) {
                    // Mettre à jour le mot de passe de l'utilisateur existant
                    if ($user instanceof User) {
                        // Encode le mot de passe avec Bcrypt et un coût de 13
                        $encodedPassword = password_hash($motdepasseModif, PASSWORD_BCRYPT, ['cost' => 13]);
                        $user->setPassword($encodedPassword);
                    }
                } else {
                    // Les mots de passe ne correspondent pas.
                    throw $this->createAccessDeniedException('Les mots de passe ne sont pas identiques.');
                }
            }
            
            // MAJ des coordonnées
            $candidat->setNom($nom);
            $candidat->setPrenom($prenom);
            $candidat->setEmail($email);

            // Enregistre lles coordonnées en base de données
            $entityManager->flush();

            // Affiche un message de succès à l'utilisateur
            $this->addFlash('success', 'Les coordonnées ont été mis à jour.');
        }

        // Redirige l'utilisateur vers la page d'ajout d'annonces
        return $this->render('compte_candidat/index.html.twig', [
            'metiers' => $metiers,
        ]);
    }

    ///////////////////////Supprimer une annonce///////////////////////////
    #[Route('/compte/candidat/supprimer/{id}', name: 'supprimer_candidature', methods: ['POST', 'DELETE'])]
    public function supprimerCandidature(Request $request, CandidatAnnonce $candidatAnnonce): Response
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($candidatAnnonce);
        $entityManager->flush();

        $this->addFlash('success', 'La candidature a été supprimée avec succès.');

        return $this->redirectToRoute('app_compte_candidat');
    }
}
