<?php

namespace App\Controller;

use App\Entity\Actif;
use App\Entity\DepenseActif;
use App\Entity\TempAgence;
use App\Form\DepenseActifType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepenseActifController extends AbstractController
{
    #[Route('/depense/actif', name: 'app_depense_actif')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $depenseactif = new DepenseActif();
        $form = $this->createForm(DepenseActifType::class,$depenseactif);
        $form->handleRequest($request);
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $actif = $em->getRepository(Actif::class)->findByYear(date("Y"));

        if ($form->isSubmitted() && $form->isValid()) {
            $ref = $request->get('groupe');
            $date = $form->get('createtAd')->getData();
            $montant = $form->get('montant')->getData();
            $date = $date->format("Y-m-d");
            $anne = substr($date,0,4);
            $actif = $em->getRepository(Actif::class)->findByRefDate($ref, $anne);
            $montant = $montant + $actif->getBrut(); 
            $actif->setBrut($montant );
            
            $depenseactif->setActif($actif);
            $depenseactif->setUser($user);
            $depenseactif->setAgence($tempagence->getAgence());

            $em->persist($depenseactif);
            $em->flush();

            return $this->redirectToRoute('app_depense_actif_liste');
        }
        return $this->render('depense_actif/index.html.twig', [
            'form' => $form->createView(),
            'actifs' => $actif,
        ]);
    }

    #[Route('/depense/actif/list',name: 'app_depense_actif_liste')]
    public function list(EntityManagerInterface $em): Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $depense = $em->getRepository(DepenseActif::class)->findBy(['agence' => $id]);

        return $this->render('depense_actif/list.html.twig', [
            'depenses' => $depense,
        ]);
    }
}
