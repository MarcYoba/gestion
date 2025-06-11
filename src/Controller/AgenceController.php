<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\Vente;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgenceController extends AbstractController
{
    #[Route('/agence/home', name: 'app_agence')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agence = new Agence();
        $nbagence = $entityManager->getRepository(Agence::class)->findAll();
        $form = $this->createForm(AgenceType::class,$agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           // $user = $this->getUser();
            $agence->setCreatedBY($agence->getUser()->getId());

            $entityManager->persist($agence);
            $entityManager->flush();

            $this->redirectToRoute("app_home");
        }

        if (count($nbagence) <= 0) {
            return $this->render('agence/index.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        $agence = $entityManager->getRepository(Agence::class)->findAll();
        return $this->render('home/index.html.twig', [
            'agence' => $agence,
        ]);
    }

    #[Route('/agence/client/', name: 'app_client')]
    public function client(EntityManagerInterface $em): Response
    {
        $vente = $em->getRepository(Vente::class)->findAll(["client"=>$this->getUser()]);
        return $this->render('agence/client.html.twig', [
            'vente' => $vente,
            'user' => $this->getUser(),
        ]);
    }
    #[Route('/agence/create/new', name: 'app_agence_new')]
    public function Create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agence->setCreatedBY($agence->getUser()->getId());

            $entityManager->persist($agence);
            $entityManager->flush();

            $this->redirectToRoute("app_home");
        }

        return $this->render('agence/index.html.twig', [
                'form' => $form->createView(),
            ]);
    }
}
