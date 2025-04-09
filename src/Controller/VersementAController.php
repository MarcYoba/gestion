<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\VersementA;
use App\Form\VersementAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VersementAController extends AbstractController
{
    #[Route('/versement/a/create', name: 'app_versement_a')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $versement = new VersementA();
        $form = $this->createForm(VersementAType::class,$versement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $versement->setUser($user);
            $em->persist($versement);
            $em->flush();

            return $this->redirectToRoute("versement_a_list");
        }
        return $this->render('versement_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/versement/a/list', name: 'versement_a_list')]
    public function list(EntityManagerInterface $em) : Response {
        $versement = $em->getRepository(VersementA::class)->findAll();
        $client = $em->getRepository(Clients::class)->findAll();

        return $this->render('versement_a/list.html.twig',[
            'versement' => $versement,
            'client' => $client,
        ]);
    }
}
