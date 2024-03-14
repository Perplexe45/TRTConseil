<?php

namespace App\Controller;

use App\Entity\Annonce;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AnnonceController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/annonces', name: 'app-listAnnonces')]
    public function index(): Response
    {
        // Récupérer toutes les annonces publiées
        $annonces = $this->entityManager->getRepository(Annonce::class)->findBy(['publie' => true]);
       /*  dd($annonces); */

        return $this->render('annonces/index.html.twig', [
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

        return $this->render('annonces/ajoutAnnonces.html.twig'/* , [
            'annonces' => $annonces, */
        );
    }


}
