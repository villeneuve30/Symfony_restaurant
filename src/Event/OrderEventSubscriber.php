<?php


namespace App\Event;
use App\Entity\ClientOrder;
use App\Entity\Dish;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class OrderEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->logActivity('persist', $args);
    }
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->logActivity('update', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof ClientOrder) {
            if($action === 'persist') {
                $entity->setDate(new \DateTime());
                $entity->setStatus("prise");
            }
            $entity->setPrice($this->calcOrderPrice($entity->getDish()));
        }
    }
    private function calcOrderPrice($dishes):float
    {
        $price = 0;
        foreach ($dishes as $dish){
            if($dish instanceof Dish){
                $price += $dish->getPrice();
            }
        }
        return $price;
    }
}