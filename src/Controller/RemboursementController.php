<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Entity\Emprunt;
use App\Entity\Remboursement;
use App\Entity\TempAgence;
use App\Form\RemboursementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RemboursementController extends AbstractController
{
    #[Route('/remboursement/creat/{id}', name: 'app_remboursement')]
    public function index(EntityManagerInterface $em,Request $request, Emprunt $emprunt): Response
    {
        $remboursement  = new Remboursement();
        $form = $this->createForm(RemboursementType::class,$remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $remboursement->setAgence($emprunt->getAgence());
            $remboursement->setUser($this->getUser());
            $remboursement->setEmprunt($emprunt);

            $em->persist($remboursement);
            $em->flush();

            $balance = $em->getRepository(Balance::class)->findOneBy(['Compte' => 1600]);
            if ($balance) {
                $mouvement = $balance->getMouvementDebit();
                $mouvement = $mouvement + $form->get('montant')->getData();
                $balance->setMouvementDebit($mouvement);
                $em->persist($balance);
                $em->flush();
            }

            $balance = $em->getRepository(Balance::class)->findOneBy(['Compte' => 5121]);
            if ($balance) {
                $mouvement = $balance->getMouvementCredit();
                $mouvement = $mouvement + $form->get('montant')->getData();
                $balance->setMouvementCredit($mouvement);
                $em->persist($balance);
                $em->flush();
            }

            return $this->redirectToRoute('app_remboursement_list');
        }
        return $this->render('remboursement/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/remboursement/list', name: 'app_remboursement_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $remboursement = $em->getRepository(Remboursement::class)->findBy(['agence' => $id]);

        return $this->render('remboursement/list.html.twig', [
            'remboursements' => $remboursement,
        ]);
    }

    #[Route('/remboursement/edit/{id}', name: 'app_remboursement_edit')]
    public function Edit(Remboursement $remboursement): Response
    {
        if ($remboursement) {
            return $this->render('remboursement/edit.html.twig', [
                'remboursement' => $remboursement,
            ]);
        }
        
        return $this->redirectToRoute('app_remboursement_list');
    }

    #[Route('/remboursement/update', name: 'app_remboursement_update')]
    public function update(EntityManagerInterface $em, Request $request): Response
    {
        $variable = $request->request->all('remboursements');
        foreach ($variable as $key => $value) {
            $remboursement = $em->getRepository(Remboursement::class)->find($key);

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
        return $this->redirectToRoute('app_remboursement_list');
    }

    #[Route('/remboursement/delete/{id}', name: 'app_remboursement_delete')]
    public function delete(EntityManagerInterface $em,Remboursement $remboursement): Response
    {
        if ($remboursement) {
            $em->remove($remboursement);
            $em->flush();
        }
        
        return $this->redirectToRoute('app_remboursement_list');
    }
}
