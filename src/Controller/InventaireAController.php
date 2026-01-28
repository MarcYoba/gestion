<?php

namespace App\Controller;

use App\Entity\InventaireA;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use App\Form\InventaireAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InventaireAController extends AbstractController
{
    #[Route('/inventaire/a/create', name: 'app_inventaire_a')]
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $inventaire = new InventaireA();
        $form = $this->createForm(InventaireAType::class, $inventaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $inventaire->setUser($this->getUser());
            $entityManager->persist($inventaire);
            $entityManager->flush();
            return $this->redirectToRoute('app_inventaire_a_list');
        }
        return $this->render('inventaire_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/inventaire/a/list', name: 'app_inventaire_a_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $agence = $tempagence->getAgence();

        $inventaires = $entityManager->getRepository(InventaireA::class)->findAll();

        return $this->render('inventaire_a/list.html.twig', [
            'inventaires' => $inventaires,
            'id' =>$agence->getId(),
        ]);
    }
    #[Route('/inventaire/a/delete/{id}', name: 'app_inventaire_a_delete')]
    public function delete(EntityManagerInterface $entityManager,InventaireA $inventaire): Response
    {
        if ($inventaire) {
            $entityManager->remove($inventaire);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_inventaire_a_list');
    }
    #[Route('/inventaire/a/edit/{id}', name: 'app_inventaire_a_edit')]
    public function edit(EntityManagerInterface $entityManager,InventaireA $inventaire): Response
    {
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $agence = $tempagence->getAgence();
        $produit = $entityManager->getRepository(ProduitA::class)->findAll();

        return $this->render('inventaire_a/edit.html.twig', [
            'inventaires' => $inventaire,
            'id' => $agence->getId(),
            'produits' => $produit,
        ]);
    }
    #[Route('/inventaire/a/update', name: 'app_inventaire_a_update')]
    public function Update(EntityManagerInterface $entityManager, Request $request): Response
    {
        $data = $request->request->all('inventaires');
        foreach ($data as $id => $inventaireData) {
            $inventaire = $entityManager->getRepository(InventaireA::class)->find($id);
            if ($inventaire) {
                $inventaire->setCreatetAt(new \DateTime($inventaireData['date']));
                $produit = $entityManager->getRepository(ProduitA::class)->find($inventaireData['produit']);
                $inventaire->setProduit($produit);
                $inventaire->setQuantite($inventaireData['quantite']);
                $inventaire->setInventaire($inventaireData['inventaire']);
                $inventaire->setEcart($inventaireData['ecart']);
                $entityManager->persist($inventaire);
            }
        }
        $entityManager->flush();
        return $this->redirectToRoute('app_inventaire_a_list');
    }
    
}
