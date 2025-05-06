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
            $fournisseurA->setUser($this->getUser());
            $fournisseurA->setAgence($this->getUser()->getEmployer()->getAgence());
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

    #[Route('/fournisseur/a/edit/{id}', name: 'fournisseur_a_edit')]
    public function edit(Request $request, EntityManagerInterface $em, FournisseurA $fournisseurA): Response
    {
        $form = $this->createForm(FournisseurAType::class, $fournisseurA);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('fournisseur_a_list');
        }

        return $this->render('fournisseur_a/index.html.twig', [
            'form' => $form->createView(),
            'fournisseur' => $fournisseurA,
        ]);
    }

    #[Route('/fournisseur/a/delete/{id}', name: 'fournisseur_a_delete')]
    public function delete(EntityManagerInterface $em, FournisseurA $fournisseurA): Response
    {
        $em->remove($fournisseurA);
        $em->flush();

        return $this->redirectToRoute('fournisseur_a_list');
    }
}
