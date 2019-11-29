<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Entity\Category;
use App\Entity\Dish;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/equipe/inserer", name="admin_team_insert")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function teamInsert(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        if($request->isMethod("POST")){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $user->setCreatedAt(new \DateTime());
                $user->setUpdatedAt(new \DateTime());
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute("front_team");
            }
        }
        return $this->render('admin/team_insert.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/admin/allergen/inserer", name="admin_allergen_insert")
     */
    public function allergenInsert(){
        $entityManager = $this->getDoctrine()->getManager();
        $dishesRepository = $entityManager->getRepository(Dish::class);
        $categoryRepository = $entityManager->getRepository(Category::class);
        $allergenRepository = $entityManager->getRepository(Allergen::class);

        $dishes = $dishesRepository->findAll();

        $categoryArray = json_decode(file_get_contents("dishes.json"), true);
        foreach ($categoryArray as $key => $category){
            foreach($category as $dish){
                $dishEntity = $dishesRepository->findOneBy(['name' => $dish["name"]]);
                if(!$dishEntity)
                    $dishEntity = new Dish();

                $categoryEntity = $categoryRepository->findOneBy(['name' => $key]);
                if(!$categoryEntity)
                    $categoryEntity = new Category();
                $categoryEntity->setName($key);
                $categoryEntity->setImage("image".$key);
                $entityManager->persist($categoryEntity);
                $entityManager->flush();

                foreach ($dish["allergens"] as $allergen){
                    $allergenEntity = $allergenRepository->findOneBy(['name' => $allergen]);
                    if(!$allergenEntity)
                        $allergenEntity = new Allergen();
                    $allergenEntity->setName($allergen);
                    $allergenEntity->addDish($dishEntity);

                    $dishEntity->addAllergen($allergenEntity);

                    $entityManager->persist($allergenEntity);
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

        dump($dishes);
        return $this->render('front/dishes_category.html.twig', [
            'controller_name' => 'FrontController',
            'dishes' => $dishes
        ]);
    }
}
