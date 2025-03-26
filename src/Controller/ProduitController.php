<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProduitController extends AbstractController
{
    #[Route('/produit/create', name: 'app_produit')]
    public function index(Request $request,EntityManagerInterface $entityManager,Security $security): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $produit->setPrixachat(0);
            $produit->setGain(0);
            $produit->setUser($security->getUser());
            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->redirectToRoute("produit_list");
        }
        return $this->render('produit/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/list', name: 'produit_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $produit = $entityManager->getRepository(Produit::class)->findAll();
        return $this->render('produit/list.html.twig', [
            'produits' => $produit,
        ]);
    }
}
