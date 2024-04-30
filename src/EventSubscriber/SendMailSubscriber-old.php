<?php

namespace App\EventSubscriber;

use App\Entity\CandidatAnnonce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendMailSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $requestStack;
    private $entityManager;

    public function __construct(MailerInterface $mailer, RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return;
        }

        // Récupérez l'ID de l'entité CandidatAnnonce à partir de la requête
        $id = $request->query->get('id');
      
        $candidatAnnonce = $this->entityManager->getRepository(CandidatAnnonce::class)->find($id);
        dd($id);
        // Récupérez l'entité CandidatAnnonce à partir de l'ID
        $candidatAnnonce = $this->entityManager->getRepository(CandidatAnnonce::class)->find($candidatAnnonce);

        if (!$candidatAnnonce) {
            return;
        }

        // Récupérez l'entité Annonce associée à la CandidatAnnonce
        $annonce = $candidatAnnonce->getAnnonce();

        // Récupérez l'entité Recruteur associée à l'Annonce
        $recruteur = $annonce->getRecruteur();

        // Vérifiez que le commutateur Envoi Mail Recruteur est activé
        if ($candidatAnnonce->isEnvoiMailRecruteur()) {
            // Créez un nouveau message électronique
            $email = (new Email())
                ->from('trtconseil@gmail.com')
                ->to($recruteur->getEmail())
                ->subject('Nouveau CV pour l\'annonce '.$annonce->getTitre())
                ->text('Veuillez trouver ci-joint le CV du candidat '.$candidatAnnonce->getCandidat()->getNom().' '.$candidatAnnonce->getCandidat()->getPrenom());

            // Ajoutez le CV en pièce jointe si présent
            if ($candidatAnnonce->getCv()) {
                $email->attachFromPath('/uploads/'.$candidatAnnonce->getCv());
            }

            // Envoyez le message électronique
            $this->mailer->send($email);

            // Mettez à jour la propriété Envoi Mail Recruteur de l'entité CandidatAnnonce
            $candidatAnnonce->setEnvoiMailRecruteur(true);
            $this->entityManager->flush();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
