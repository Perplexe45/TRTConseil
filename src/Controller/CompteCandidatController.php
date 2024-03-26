<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Metier;
use App\Entity\CandidatAnnonce;
use App\Repository\CandidatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;

class CompteCandidatController extends AbstractController
{

    private $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /////////////////////////Page d'accueil/////////////////////////////
    #[Route('/compte/candidat', name: 'app_compte_candidat')]
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

    //////////////////Modif des coordonnées du candidat//////////////////
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

            //Recupération du CV en fichier pdf
            $cvFile = $request->files->get('cv-file');
            if ($cvFile instanceof UploadedFile && $cvFile->guessExtension() === 'pdf') {
                $fileName = $cvFile->getClientOriginalName();

                // Déplacer le fichier téléversé vers le répertoire de destination
                $cvFile->move($this->getParameter('cv_directory'), $fileName);
                $candidat->setCv($fileName);
            } else {  //ce n'est pas un fichier pdf
                $this->addFlash('error', 'Le fichier doit être un fichier PDF.');
            }

            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');

            //Modif du mot de passe si un des 2 champs ont été remplis
            if (!empty($motdepasseModif) && !empty($motdepasseMofif1)) {

                // Vérifie si les deux champs de mot de passe ont le même mot de passe
                if ($motdepasseModif === $motdepasseMofif1) {
                    $userPassword = new User;
                    // Encode le mot de passe
                    $userPassword->setPassword(
                        $userPasswordHasher->hashPassword($userPassword, $motdepasseModif)
                    );
                } else {
                    // Les mots de passe ne correspondent pas.
                    throw $this->createAccessDeniedException()('Les mots de passe ne sont pas identiques.');
                }
            }

            // MAJ de l'entreprise
            $candidat->setNom($nom);
            $candidat->setPrenom($prenom);
            $candidat->setEmail($email);

            // Enregistre l'annonce en base de données
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
