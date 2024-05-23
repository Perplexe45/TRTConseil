<?php

namespace App\EventSubscriber;

use App\Entity\CandidatAnnonce;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;

class CandidatAnnonceSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $router;


    public function __construct(MailerInterface $mailer, RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityUpdatedEvent::class => 'sendEmail',
        ];
    }

    public function sendEmail(AfterEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();
        $response = new RedirectResponse($this->router->generate('app_home')); // Redirection vers la page d'accueil par défaut

        if ($entity instanceof CandidatAnnonce && $entity->isEnvoiMailRecruteur()) {
            $recruteurEmail = $entity->getAnnonce()->getRecruteur()->getEmail();
            $cvPath = '/uploads/' . $entity->getCv();
            $sujetAnnonce = $entity->getAnnonce()->getPoste();
            $lieuTravail = $entity->getAnnonce()->getLieu();

            $email = (new Email())
                ->from('alain.asselin@laposte.net')
                ->to($recruteurEmail)
                ->subject('Nouveau CV de candidat pour le poste de ' . $sujetAnnonce . ' à ' . $lieuTravail)
                ->text('Veuillez trouver ci-joint le CV du candidat.')
                ->attachFromPath($cvPath);

                try {
                    $this->mailer->send($email);
                    //dd($email); 
                    // Ajoute un paramètre de succès à la redirection
                    $response->headers->set('Location', $this->router->generate('app_consultant', ['success' => 'Le message a été envoyé au recruteur avec succès']));
                } catch (\Exception $e) {
                    // Ajoute un paramètre d'erreur à la redirection
                    $response->headers->set('Location', $this->router->generate('app_consultant', ['error' => 'Le message n\'a pas été envoyé au recruteur. Erreur : ' . $e->getMessage()]));
                }
        }
        return $response;
    }
}
