<?php

namespace App\Controller;

use App\Entity\BalanceA;
use App\Entity\EmpruntA;
use App\Entity\RemboursementA;
use App\Entity\TempAgence;
use App\Form\RemboursementAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RemboursementAController extends AbstractController
{
    #[Route('/remboursement/a/creat/{id}', name: 'app_remboursement_a')]
    public function index(EntityManagerInterface $em, Request $request, EmpruntA $emprunt): Response
    {
        $remboursement  = new RemboursementA();
        $form = $this->createForm(RemboursementAType::class,$remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $remboursement->setAgence($emprunt->getAgence());
            $remboursement->setUser($this->getUser());
            $remboursement->setEmprunt($emprunt);

            $em->persist($remboursement);
            $em->flush();

            $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 1600]);
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

            return $this->redirectToRoute('app_remboursement_list_a');
        }
        return $this->render('remboursement_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/remboursement/a/list', name: 'app_remboursement_list_a')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $remboursement = $em->getRepository(RemboursementA::class)->findBy(['agence' => $id]);

        return $this->render('remboursement_a/list.html.twig', [
            'remboursements' => $remboursement,
        ]);
    }

    #[Route('/remboursement/a/edit/{id}', name: 'app_remboursement_edit_a')]
    public function Edit(RemboursementA $remboursement): Response
    {
        if ($remboursement) {
            return $this->render('remboursement_a/edit.html.twig', [
                'remboursement' => $remboursement,
            ]);
        }
        
        return $this->redirectToRoute('app_remboursement_list_a');
    }

    #[Route('/remboursement/a/update', name: 'app_remboursement_update_a')]
    public function update(EntityManagerInterface $em, Request $request): Response
    {
        $variable = $request->request->all('remboursements');
        foreach ($variable as $key => $value) {
            $remboursement = $em->getRepository(RemboursementA::class)->find($key);

            if ($remboursement) {
                $remboursement->setType($value['type'] ?? 0);
                $remboursement->setCreatetAt(new \DateTime($value['createtAt']) ?? date('Y-m-d'));
                $remboursement->setMontant($value['montant'] ?? 0);
                $remboursement->setContrat($value['contrat'] ?? 0);
                $remboursement->setEtablissement($value['etablissement'] ?? 0);
                $remboursement->setDatesignature(new \DateTime($value['datesignature']) ?? date('Y-m-d'));
                $remboursement->setDateprelevement(new \DateTime($value['dateprelevement']) ?? date('Y-m-d'));
                $remboursement->setComptedebiter($value['comptedebiter'] ?? 0);

                $em->persist($remboursement);
                $em->flush();
            }
        }
        return $this->redirectToRoute('app_remboursement_list_a');
    }

    #[Route('/remboursement/a/delete/{id}', name: 'app_remboursement_delete_a')]
    public function delete(EntityManagerInterface $em,RemboursementA $remboursement): Response
    {
        if ($remboursement) {
            $em->remove($remboursement);
            $em->flush();
        }
        
        return $this->redirectToRoute('app_remboursement_list_a');
    }
}
