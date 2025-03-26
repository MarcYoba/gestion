<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class FournisseurController extends AbstractController
{
    #[Route('/fournisseur/create', name: 'app_fournisseur')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $fournisseur->setUser($this->getUser());
            $entityManager->persist($fournisseur);
            $entityManager->flush();
            return $this->redirectToRoute('fournisseur_list');
        }

        return $this->render('fournisseur/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/fournisseur/list', name: 'fournisseur_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->findAll();
        return $this->render('fournisseur/list.html.twig', [
            'fournisseurs' => $fournisseur,
        ]);
    }
}
