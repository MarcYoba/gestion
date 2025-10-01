<?php

namespace App\Controller;

use App\Entity\DepensePassif;
use App\Entity\Passif;
use App\Entity\TempAgence;
use App\Form\DepensePassifType;
use App\Form\DepensesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepensePassifController extends AbstractController
{
    #[Route('/depense/passif', name: 'app_depense_passif')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $depensepassif = new DepensePassif();
        $form = $this->createForm(DepensePassifType::class,$depensepassif);
        $form->handleRequest($request);
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $passif = $em->getRepository(Passif::class)->findByYear(date("Y"));

        if ($form->isSubmitted() && $form->isValid()) { 
            $ref = $request->get('groupe');
            $date = $form->get('createtAt')->getData();
            $montant = $form->get('montant')->getData();
            $date = $date->format("Y-m-d");
            $anne = substr($date,0,4);
            $passif = $em->getRepository(Passif::class)->findByRefDate($ref, $anne);

            $montant = $montant + $passif->getMontant(); 
            $passif->setMontant($montant );

            $depensepassif->setAgence($tempagence->getAgence());
            $depensepassif->setUser($user);
            $depensepassif->setPassif($passif);

            $em->persist($depensepassif);
            $em->flush();

            return $this->redirectToRoute('app_depense_passif_list');
        }
        return $this->render('depense_passif/index.html.twig', [
            'form' => $form->createView(),
            'passifs' => $passif,
        ]);
    }

    #[Route('/depense/passif/list', name: 'app_depense_passif_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $passif = $em->getRepository(DepensePassif::class)->findBy(['agence'=>$id]);

        return $this->render('depense_passif/list.html.twig', [
            'passifs' => $passif,
        ]);
    }
}
