<?php

namespace App\Controller;

use App\Entity\BalanceA;
use App\Entity\DepenseA;
use App\Form\DepenseAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepenseAController extends AbstractController
{
    #[Route('/depense/a/create', name: 'app_depense_a')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $depense = new DepenseA();
        $form = $this->createForm(DepenseAType::class,$depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type = $form->get('type')->getData();
            $montant = $form->get('montant')->getData();
            if ($type == "Voyages") {
                $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 5111]);
                        if ($balance) {
                            $mouvement = $balance->getMouvementDebit();
                            $mouvement = $mouvement + $montant;
                            $balance->setMouvementDebit($mouvement);
                            $em->persist($balance);
                            
                        }
                $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 6111]);
                        if ($balance) {
                            $mouvement = $balance->getMouvementCredit();
                            $mouvement = $mouvement + $montant;
                            $balance->setMouvementCredit($mouvement);
                            $em->persist($balance);
                        }
            }
            $user = $this->getUser();
            $depense->setUser($user);

            $em->persist($depense);
            $em->flush();

           return $this->redirectToRoute('depense_a_list');
        }

        return $this->render('depense_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/depense/a/list', name: 'depense_a_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $depenses = $em->getRepository(DepenseA::class)->findAll();

        return $this->render('depense_a/list.html.twig', [
            'depenses' => $depenses,
        ]);
    }
    /**
     * @Route(path="/depense/a/delete/{id}", name="depense_a_delete")
     */
    public function delete(DepenseA $depense, EntityManagerInterface $em): Response
    {
        $em->remove($depense);
        $em->flush();

        return $this->redirectToRoute('depense_a_list');
    }
    /**
     * @Route(path="/depense/a/edit/{id}", name="depense_a_edit")
     */
    public function edit(DepenseA $depense, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DepenseAType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($depense);
            $em->flush();

            return $this->redirectToRoute('depense_a_list');
        }

        return $this->render('depense_a/index.html.twig', [
            'form' => $form->createView(),
            'depense' => $depense,
        ]);
    }
}
