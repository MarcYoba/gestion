<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\TempAgence;
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
            $tempagence =$entityManager->getRepository(TempAgence::class)->findOneBy(["user"=>$this->getUser()]);
            $fournisseur->setAgence($tempagence->getAgence());
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
        $user = $this->getUser();
        $fournisseur = new Fournisseur();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        $id = $tempagence->getAgence()->getId();
        if ($tempagence->isGenerale()== 1) {
            $fournisseur = $entityManager->getRepository(Fournisseur::class)->findAll();
        }else{
            $fournisseur = $entityManager->getRepository(Fournisseur::class)->findBy(["agence" => $id]);
        }
        
        return $this->render('fournisseur/list.html.twig', [
            'fournisseurs' => $fournisseur,
        ]);
    }

    #[Route('/fournisseur/edit/{id}', name: 'fournisseur_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Fournisseur $fournisseur): Response
    {
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('fournisseur_list');
        }

        return $this->render('fournisseur/index.html.twig', [
            'form' => $form->createView(),
            'fournisseur' => $fournisseur,
        ]);
    }
    #[Route('/fournisseur/delete/{id}', name: 'fournisseur_delete')]
    public function delete(EntityManagerInterface $entityManager, Fournisseur $fournisseur): Response
    {
        $entityManager->remove($fournisseur);
        $entityManager->flush();
        return $this->redirectToRoute('fournisseur_list');
    }
}
