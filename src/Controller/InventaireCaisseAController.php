<?php

namespace App\Controller;

use App\Entity\InventaireCaisseA;
use App\Entity\TempAgence;
use App\Entity\VenteA;
use App\Form\InventaireCaisseAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InventaireCaisseAController extends AbstractController
{
    #[Route('/inventaire/caisse/a', name: 'app_inventaire_caisse_a')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $inventaire = new InventaireCaisseA();
        $form = $this->createForm(InventaireCaisseAType::class,$inventaire);
        $form->handleRequest($request);
        $user  = $this->getUser();
        $tempreagence = $em->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        $agence = $tempreagence->getAgence();
        $date_debut = new \DateTimeImmutable();
        $date_fin = new \DateTimeImmutable();
        $vente = $em->getRepository(VenteA::class)->findRapportVenteToWeekCash($date_debut, $date_fin,$agence);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $inventaire->setAgence($agence);
            $inventaire->setUser($user);
            $em->persist($inventaire);
            $em->flush();

            return $this->redirectToRoute('app_inventaire_caisse_a_list');
        }
        return $this->render('inventaire_caisse_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/inventaire/caisse/a/list', name: 'app_inventaire_caisse_a_list')]
    public function list(): Response
    {
        return $this->render('inventaire_caisse_a/liste.html.twig', [
            '' => 'InventaireCaisseAController',
        ]);
    }
}
