<?php
namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class OrderPayedEvent extends Event
{
    public const NAME = 'order.payed';
    protected $order;

    public function __construct(\App\Entity\ClientOrder $order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }
}