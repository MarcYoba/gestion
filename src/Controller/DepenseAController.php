<?php

namespace App\Controller;

use App\Entity\DepenseA;
use App\Form\DepenseAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepenseAController extends AbstractController
{
    #[Route('/depense/a/create', name: 'app_depense_a')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $depense = new DepenseA();
        $form = $this->createForm(DepenseAType::class,$depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $depense->setUser($user);

            $em->persist($depense);
            $em->flush();

           return $this->redirectToRoute('depense_a_list');
        }

        return $this->render('depense_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/depense/a/list', name: 'depense_a_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $depenses = $em->getRepository(DepenseA::class)->findAll();

        return $this->render('depense_a/list.html.twig', [
            'depenses' => $depenses,
        ]);
    }
}
