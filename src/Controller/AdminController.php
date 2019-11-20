<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
     */
    public function teamInsert(Request $request)
    {
        $user = new User();
        //$user->setCreatedAt(new \DateTime());
        $form = $this->createForm(UserType::class, $user);

        return $this->render('admin/team_insert.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    public function new(Request $request)
    {

    }
}
