<?php

namespace App\Controller;

use App\Entity\Versement;
use App\Form\VersementType;
use App\Entity\Clients;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VersementController extends AbstractController
{
    /**
     * @Route( path ="/versement", name="app_versement")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $versement = new Versement();
        $form = $this->createForm(VersementType::class,$versement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $versement->setUser($user);
            $entityManager->persist($versement);
            $entityManager->flush();
            return $this->redirectToRoute('versement_list');
        }
        return $this->render('versement/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route( path ="/versement/list/{id}", name="versement_list")
     */
    public function list(EntityManagerInterface $entityManager, int $id): Response
    {
        $versement = $entityManager->getRepository(Versement::class)->findAll(["id" => $id]);
        $clients = $entityManager->getRepository(Clients::class)->findAll();
        return $this->render('versement/list.html.twig', [
            'versement' => $versement,
            'client' => $clients,
        ]);
    }

    /**
     * @Route( path ="/versement/edit/{id}", name="versement_edit")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, Versement $versement): Response
    {
        $form = $this->createForm(VersementType::class, $versement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('versement_list');
        }
        return $this->render('versement/index.html.twig', [
            'form' => $form->createView(),
            'versement' => $versement,
        ]);
    }
    /**
     * @Route( path ="/versement/delete/{id}", name="versement_delete")
     */
    public function delete(EntityManagerInterface $entityManager, Versement $versement): Response
    {
        $entityManager->remove($versement);
        $entityManager->flush();
        return $this->redirectToRoute('versement_list');
    }
}
