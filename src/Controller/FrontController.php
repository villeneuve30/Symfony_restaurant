<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Dish;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front_home")
     */
    public function index()
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    /**
     * @Route("/equipe", name="front_team",methods={"GET"})
     */
    public function team()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $allUsers = $entityManager->getRepository(User::class)->findAll();

        return $this->render('front/team.html.twig', [
            'controller_name' => 'FrontController',
            'all_users' => $allUsers
        ]);
    }

    /**
     * @Route("/carte", name="front_dishes",methods={"GET"})
     */
    public function dishes()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('front/dishes.html.twig', [
            'controller_name' => 'FrontController',
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/carte/{id}", name="front_dishes_category", methods={"GET"})
     * @param $id
     * @return Response
     */
    public function dishesCategory($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dishes = $entityManager->getRepository(Dish::class)->findBy(['category' => $id]);

        return $this->render('front/dishes_category.html.twig', [
            'controller_name' => 'FrontController',
            'dishes' => $dishes
        ]);
    }
}