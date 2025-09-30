<?php

namespace App\Controller;

use App\Entity\ActifA;
use App\Entity\DepenseActifA;
use App\Entity\TempAgence;
use App\Form\DepenseActifAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepenseActifAController extends AbstractController
{
    #[Route('/depense/actif/a', name: 'app_depense_actif_a')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $depenseactif = new DepenseActifA();
        $form = $this->createForm(DepenseActifAType::class,$depenseactif);
        $form->handleRequest($request);
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $actif = $em->getRepository(ActifA::class)->findByYear(date("Y"));

        if ($form->isSubmitted() && $form->isValid()) {
            $ref = $request->get('groupe');
            $date = $form->get('createtAd')->getData();
            $montant = $form->get('montant')->getData();
            $date = $date->format("Y-m-d");
            $anne = substr($date,0,4);
            $actif = $em->getRepository(ActifA::class)->findByRefDate($ref, $anne);
            $montant = $montant + $actif->getBrut(); 
            $actif->setBrut($montant );
            
            $depenseactif->setActif($actif);
            $depenseactif->setUser($user);
            $depenseactif->setAgence($tempagence->getAgence());

            $em->persist($depenseactif);
            $em->flush();

            return $this->redirectToRoute('app_depense_actif_a_liste');
        }

        return $this->render('depense_actif_a/index.html.twig', [
            'form' => $form->createView(),
            'actifs' => $actif,
        ]);
    }
    #[Route('/depense/actif/a/list',name: 'app_depense_actif_a_liste')]
    public function list(EntityManagerInterface $em): Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $depense = $em->getRepository(DepenseActifA::class)->findBy(['agence' => $id]);

        return $this->render('depense_actif_a/list.html.twig', [
            'depenses' => $depense,
        ]);
    }
}
