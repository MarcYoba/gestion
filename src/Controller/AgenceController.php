<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgenceController extends AbstractController
{
    #[Route('/agence', name: 'app_agence')]
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
    public function client(): Response
    {
        return $this->render('agence/index.html.twig', [
            'controller_name' => 'AgenceController',
        ]);
    }
}
