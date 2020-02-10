<?php

namespace App\Controller;

use App\Entity\ClientOrder;
use App\Form\ClientOrderType;
use App\Repository\ClientOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client/order")
 */
class ClientOrderController extends AbstractController
{
    /**
     * @Route("/", name="client_order_index", methods={"GET"})
     * @param ClientOrderRepository $clientOrderRepository
     * @return Response
     */
    public function index(ClientOrderRepository $clientOrderRepository): Response
    {
        return $this->render('client_order/index.html.twig', [
            'client_orders' => $clientOrderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="client_order_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $clientOrder = new ClientOrder();
        $form = $this->createForm(ClientOrderType::class, $clientOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($clientOrder);
            $entityManager->flush();

            return $this->redirectToRoute('client_order_index');
        }

        return $this->render('client_order/new.html.twig', [
            'client_order' => $clientOrder,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_order_show", methods={"GET"})
     */
    public function show(ClientOrder $clientOrder): Response
    {
        return $this->render('client_order/show.html.twig', [
            'client_order' => $clientOrder,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="client_order_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ClientOrder $clientOrder): Response
    {
        $form = $this->createForm(ClientOrderType::class, $clientOrder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_order_index');
        }

        return $this->render('client_order/edit.html.twig', [
            'client_order' => $clientOrder,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_order_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ClientOrder $clientOrder): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clientOrder->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($clientOrder);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_order_index');
    }
}
