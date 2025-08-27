<?php

namespace App\Controller;

use App\Entity\Poussin;
use App\Form\PoussinType;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PoussinController extends AbstractController
{
    #[Route('/poussin', name: 'app_poussin')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $poussin = new Poussin();
        $form = $this->createForm(PoussinType::class, $poussin);
        $form->handleRequest($request);
        $user = $this->getUser();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            $poussin->setAgence($agence->getAgence());
            $poussin->setStatus("EN COUR");
            $em->persist($poussin);
            $em->flush();

            return $this->redirectToRoute('app_poussin_list');
        }
        return $this->render('poussin/index.html.twig', [
            'form' => $form->createView() ,
        ]);
    }

    #[Route('/poussin/list', name: 'app_poussin_list')]
    public function List(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $poussin = $em->getRepository(Poussin::class)->findAll(["agence" => $id]);
        return $this->render('poussin/list.html.twig', [
            'poussins' => $poussin,
        ]);
    }
    #[Route('/poussin/edit/{id}', name:'app_pousssin_edit')]
    public function Edite(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $poussin = new Poussin();
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $poussin = $em->getRepository(Poussin::class)->findAll(["agence" => $id]);
        
        $form = $this->createForm(PoussinType::class,$poussin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tempagence);
            $em->flush();
        }
        return $this->render("poussin/Edit.html.twig", [
            "Poussin"=> $poussin,
            "form"=> $form->createView(),
        ]);

    }
}
