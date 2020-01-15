<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Dish;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $dishesRepository = $entityManager->getRepository(Dish::class);
        $categoryRepository = $entityManager->getRepository(Category::class);

        $dishes = $dishesRepository->findBy(['category' => $id]);

        $categoryArray = json_decode(file_get_contents("dishes.json"), true);
        foreach ($categoryArray as $key => $category){
            foreach($category as $dish){
                $dishEntity = $dishesRepository->findOneBy(['name' => $dish["name"]]);
                if(!$dishEntity)
                    $dishEntity = new Dish();

                $categoryEntity = $categoryRepository->findOneBy(['name' => $key]);
                if(!$categoryEntity){
                    $categoryEntity = new Category();
                    $categoryEntity->setName($key);
                    $categoryEntity->setImage("image".$key);
                    $entityManager->persist($categoryEntity);
                    $entityManager->flush();
                }
                $dishEntity->setCategory($categoryEntity);
                $dishEntity->setName($dish["name"]);
                $dishEntity->setPrice((float)$dish["price"]);
                $dishEntity->setDescription($dish["text"]);
                $dishEntity->setCalories($dish["calories"]);
                $dishEntity->setImage($dish["image"]);
                $dishEntity->setSticky($dish["sticky"]);
                $dishEntity->setDescription($dish["text"]);

                $entityManager->persist($dishEntity);
            }
            $entityManager->flush();
        }

        return $this->render('front/dishes_category.html.twig', [
            'controller_name' => 'FrontController',
            'dishes' => $dishes
        ]);
    }
    /**
     *
     * @Route("/mentions-legales", name="front_legals", methods={"GET"})
     */
    public function legals(){
        return $this->render('front/legals.html.twig', [
            'controller_name' => 'FrontController'
        ]);
    }
}