<?php


namespace App\Event;

use App\Entity\ClientOrder;
use App\Services\RhService;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Environment;

class OrderEventListener
{
    private $mailer;
    private $template;
    private $rh;

    private $eventDispatcher;

    public function __construct(\Swift_Mailer $mailer, Environment $env, EventDispatcherInterface $eventDispatcher, RhService $rh)
    {
        $this->mailer = $mailer;
        $this->template = $env;
        $this->eventDispatcher = $eventDispatcher;
        $this->rh = $rh;
    }
    public function postPersist(ClientOrder $order, LifecycleEventArgs $event)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody('nouvelle commande en salle')
            ->addPart(
                $this->template->render(
                    'email.html.twig',
                    ['dishes' => $order->getDish()]
                ),
                'text/plain'
            )
        ;
        $this->mailer->send($message);
    }

    public function postUpdate(ClientOrder $order, LifecycleEventArgs $event){
        $body ='';
        $receiver = 'serveur';
        switch ($order->getStatus()){
            case 'payee':
                $body = 'commande facturée';
                $payedEvent = new OrderPayedEvent($order);
                $this->eventDispatcher->dispatch($payedEvent, OrderPayedEvent::NAME);
                break;
            case 'servie':
                $body = "commande servie";
                $receiver = "accueil";
                break;
            case 'preparee ' :
                $body ="commande prête a etre service";
                break;
        }

        $message = (new \Swift_Message('nouveau status'))
            ->setFrom('send@example.com')
            ->setTo($receiver.'@example.com')
            ->setBody($body)
            ->addPart(
                $this->template->render(
                    'email_status_changed.html.twig',
                    ['body' => $body]
                ),
                'text/plain'
            )
        ;
        $this->mailer->send($message);
    }
}