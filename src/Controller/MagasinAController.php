<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\MagasinA;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use App\Form\MagasinAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagasinAController extends AbstractController
{
    #[Route('/magasin/a/create', name: 'app_magasin_a')]
    public function index(EntityManagerInterface $em,Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $magasin = new MagasinA();
        $form = $this->createForm(MagasinAType::class, $magasin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            $produit = $form->get('produit')->getData();
            $quantite = $form->get('quantite')->getData();
            $date = $form->get('createtAt')->getData();
            $existingMagasin = $em->getRepository(MagasinA::class)->findOneBy([
                'produit' => $produit,
                'agence' => $agence->getAgence(),
            ]);
            if ($existingMagasin) {
                $this->addFlash('success', 'Le produit existe déjà dans le magasin de cette agence.');
                $existingMagasin->setQuantite($existingMagasin->getQuantite() + $quantite);
                $operation = $existingMagasin->getOperation();
                $operation[] = $date->format('Y-m-d');
                $existingMagasin->setOperation($operation);
                $em->persist($existingMagasin);
                $em->flush();
            }else{
            $magasin->setAgence($agence->getAgence());
            $magasin->setUser($user);
            $magasin->setOperation([$date->format('Y-m-d')]);
            $em->persist($magasin);
            $em->flush();
            }
            return $this->redirectToRoute('app_magasin_a_list');
        }
        return $this->render('magasin_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/magasin/a/list', name: 'app_magasin_a_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $magasins = $em->getRepository(MagasinA::class)->findBy(['agence' => $agence->getAgence()]);
        return $this->render('magasin_a/list.html.twig', [
            'magasins' => $magasins,
        ]);
    }
    #[Route('/magasin/edit/{id}', name: 'app_magasin_a_edit')]
    public function Edit(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        return $this->redirectToRoute('app_magasin_a_list');
    }
    #[Route('/magasin/a/delete/{id}', name: 'app_magasin_a_delete')]
    public function Delete(EntityManagerInterface $em,MagasinA $magasin): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        if ($magasin) {
            $em->remove($magasin);
            $em->flush();
        }
       return $this->redirectToRoute('app_magasin_a_list');
    }

    #[Route('/magasin/bon/tranfert', name: 'app_magasin_a_bon')]
    public function transfert(EntityManagerInterface $em,Request $request) : Response {
        $produit = $em->getRepository(ProduitA::class)->findAll();
        $employer = $em->getRepository(Employer::class)->findAll();

        
        return $this->render('magasin_a/transfert.html.twig', [
            'produits' => $produit,
            'employers' => $employer,
        ]);
    }
}
