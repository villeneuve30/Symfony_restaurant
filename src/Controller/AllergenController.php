<?php

namespace App\Controller;

use App\Entity\Allergen;
use App\Form\AllergenType;
use App\Repository\AllergenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllergenController extends AbstractController
{
    /**
     * @Route("/admin/allergen", name="allergen_index", methods={"GET"})
     */
    public function index(AllergenRepository $allergenRepository): Response
    {
        return $this->render('allergen/index.html.twig', [
            'allergens' => $allergenRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/allergen/new", name="allergen_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $allergen = new Allergen();
        $form = $this->createForm(AllergenType::class, $allergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Allergen '.$allergen->getName().' '.$allergen->getName().' créée !');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($allergen);
            $entityManager->flush();

            return $this->redirectToRoute('allergen_index');
        }

        return $this->render('allergen/new.html.twig', [
            'allergen' => $allergen,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/allergen/{id}", name="allergen_show", methods={"GET"})
     */
    public function show(Allergen $allergen): Response
    {
        return $this->render('allergen/show.html.twig', [
            'allergen' => $allergen,
        ]);
    }

    /**
     * @Route("/admin/allergen/{id}/edit", name="allergen_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Allergen $allergen): Response
    {
        $form = $this->createForm(AllergenType::class, $allergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Allergen '.$allergen->getName().' '.$allergen->getName().' mis à jour !');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('allergen_index');
        }

        return $this->render('allergen/edit.html.twig', [
            'allergen' => $allergen,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/allergen/{id}", name="allergen_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Allergen $allergen): Response
    {
        if ($this->isCsrfTokenValid('delete'.$allergen->getId(), $request->request->get('_token'))) {
            $this->addFlash('success', 'Allergen '.$allergen->getName().' '.$allergen->getName().' supprimé !');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($allergen);
            $entityManager->flush();
        }

        return $this->redirectToRoute('allergen_index');
    }
}
