<?php

namespace App\Controller;

use App\Entity\Dish;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlockController extends AbstractController
{
    public function dayDishesAction($max = 3)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dishesRepository = $entityManager->getRepository(Dish::class);

        $dishes = $dishesRepository->findBy(['sticky'=>'1'],null, $max);

        return $this->render('layout/partials/day_dishes.html.twig', array(
            'dishes'=>$dishes
        ));
    }
}
