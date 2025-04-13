<?php

namespace App\Controller;

use App\Entity\ProduitA;
use App\Form\ProduitAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitAController extends AbstractController
{
    #[Route('/produit/a/create', name: 'app_produit_a')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $produitA = new ProduitA();
        $form = $this->createForm(ProduitAType::class, $produitA);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitA->setUser($this->getUser());
            $produitA->setGain(0);
            $produitA->setStockdebut($produitA->getQuantite());
            $em->persist($produitA);
            $em->flush();

            return $this->redirectToRoute('produit_a_list');
        }
        return $this->render('produit_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/a/list', name: 'produit_a_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $produits = $em->getRepository(ProduitA::class)->findAll();
        return $this->render('produit_a/list.html.twig', [
            'produits' => $produits,
        ]);
    }
}
