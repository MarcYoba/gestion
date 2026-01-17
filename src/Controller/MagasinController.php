<?php

namespace App\Controller;

use App\Entity\Magasin;
use App\Entity\TempAgence;
use App\Form\MagasinType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagasinController extends AbstractController
{
    #[Route('/magasin/create', name: 'app_magasin')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $magasin = new Magasin();
        $form = $this->createForm(MagasinType::class, $magasin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            $produit = $form->get('produit')->getData();
            $quantite = $form->get('quantite')->getData();
            $date = $form->get('createtAt')->getData();
            $existingMagasin = $em->getRepository(Magasin::class)->findOneBy([
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
            return $this->redirectToRoute('app_magasin_list');
        }
        return $this->render('magasin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/magasin/list', name: 'app_magasin_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $magasins = $em->getRepository(Magasin::class)->findBy(['agence' => $agence->getAgence()]);
        return $this->render('magasin/list.html.twig', [
            'magasins' => $magasins,
        ]);
    }
    #[Route('/magasin/edit/{id}', name: 'app_magasin_edit')]
    public function Edit(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        return $this->render('magasin/list.html.twig', [
            'controller_name' => 'MagasinController',
        ]);
    }
    #[Route('/magasin/delete/{id}', name: 'app_magasin_delete')]
    public function Delete(EntityManagerInterface $em,Magasin $magasin): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        if ($magasin) {
            $em->remove($magasin);
            $em->flush();
        }
       return $this->redirectToRoute('app_magasin_list');
    }
}
