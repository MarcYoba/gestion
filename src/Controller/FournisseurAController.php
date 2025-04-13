<?php

namespace App\Controller;

use App\Entity\FournisseurA;
use App\Form\FournisseurAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FournisseurAController extends AbstractController
{
    #[Route('/fournisseur/a/create', name: 'app_fournisseur_a')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $fournisseurA = new FournisseurA();
        $form = $this->createForm(FournisseurAType::class,$fournisseurA);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fournisseurA->setCreatedAt(new \DateTimeImmutable());
            $em->persist($fournisseurA);
            $em->flush();

            return $this->redirectToRoute('fournisseur_a_list');
        }
        return $this->render('fournisseur_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/fournisseur/a/list', name: 'fournisseur_a_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $fournisseurs = $em->getRepository(FournisseurA::class)->findAll();

        return $this->render('fournisseur_a/list.html.twig', [
            'fournisseurs' => $fournisseurs,
        ]);
    }
}
