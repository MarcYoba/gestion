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
            $entityManager->persist($versement);
            $entityManager->flush();
            return $this->redirectToRoute('versement_list');
        }
        return $this->render('versement/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route( path ="/versement/list", name="versement_list")
     */
    public function list(EntityManagerInterface $entityManager): Response
    {
        $versement = $entityManager->getRepository(Versement::class)->findAll();
        $clients = $entityManager->getRepository(Clients::class)->findAll();
        return $this->render('versement/list.html.twig', [
            'versement' => $versement,
            'client' => $clients,
        ]);
    }
}
