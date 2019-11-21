<?php

namespace App\Controller;

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
}
