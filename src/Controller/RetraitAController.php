<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Entity\BalanceA;
use App\Entity\RetraitA;
use App\Entity\TempAgence;
use App\Form\RetraitAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RetraitAController extends AbstractController
{
    #[Route('/retrait/a/creat', name: 'app_retrait_a')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $retrait = new RetraitA();
        $form = $this->createForm(RetraitAType::class,$retrait);
        $form->handleRequest($request);

        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            
            $retrait->setAgence($tempagence->getAgence());
            $retrait->setUser($user);

            $em->persist($retrait);
            $em->flush();

            $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 5111]);
            if ($balance) {
                $mouvement = $balance->getMouvementDebit();
                $mouvement = $mouvement + $form->get('montant')->getData();
                $balance->setMouvementDebit($mouvement);
                $em->persist($balance);
                $em->flush();
            }

            $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 5121]);
            if ($balance) {
                $mouvement = $balance->getMouvementCredit();
                $mouvement = $mouvement + $form->get('montant')->getData();
                $balance->setMouvementCredit($mouvement);
                $em->persist($balance);
                $em->flush();
            }

            return $this->redirectToRoute('app_retrait_list_a');
        }

        return $this->render('retrait_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/retrait/a/list', name: 'app_retrait_list_a')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $retrait = $em->getRepository(RetraitA::class)->findBy(['Agence' =>$id]);

        return $this->render('retrait_a/list.html.twig', [
            'retraits' => $retrait,
        ]);
    }

    #[Route('/retrait/a/delete/{id}', name: 'app_retrait_delete_a')]
    public function delete(EntityManagerInterface $em,RetraitA $retrait): Response
    {
        if ($retrait) {
            $em->remove($retrait);
            $em->flush();
        }
        return $this->redirectToRoute('app_retrait_list_a');
    }

    #[Route('/retrait/a/edit/{id}', name: 'app_retrait_edit_a')]
    public function edit(EntityManagerInterface $em,RetraitA $retrait): Response
    {
        if ($retrait) {
            return $this->render('retrait_a/edit.html.twig', [
                'retraits' => $retrait,
            ]); 
        }
        return $this->redirectToRoute('app_retrait_list_a');
    }

    #[Route('/retrait/a/update', name: 'app_retrait_update_a')]
    public function update(EntityManagerInterface $em,Request $request): Response
    {
       $variable = $request->request->all('retraits');
       
       foreach ($variable as $key => $value) {
            $retrait = $em->getRepository(RetraitA::class)->find($key);
            if ($retrait) {
                $retrait->setMontant($value['montant'] ?? 0);
                $retrait->setCompte($value['compte'] ?? 0);
                $retrait->setCompte($value['libelle'] ?? 0);
                $retrait->setCompte($value['banque'] ?? 0);
                $retrait->setCreatetAt(new \DateTime($value['createtAt'] ) ?? 0);

                $em->persist($retrait);
                $em->flush();
            }
       }
        return $this->redirectToRoute('app_retrait_list_a');
    }
}
