<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Entity\Retrait;
use App\Entity\TempAgence;
use App\Form\RetraitType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RetraitController extends AbstractController
{
    #[Route('/retrait/create', name: 'app_retrait')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $retrait = new Retrait();
        $form = $this->createForm(RetraitType::class,$retrait);
        $form->handleRequest($request);

        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            
            $retrait->setAgence($tempagence->getAgence());
            $retrait->setUser($user);

            $em->persist($retrait);
            $em->flush();

            $balance = $em->getRepository(Balance::class)->findOneBy(['Compte' => 5111]);
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

            return $this->redirectToRoute('app_retrait_list');
        }

        return $this->render('retrait/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/retrait/list', name: 'app_retrait_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $retrait = $em->getRepository(Retrait::class)->findBy(['Agence' =>$id]);

        return $this->render('retrait/list.html.twig', [
            'retraits' => $retrait,
        ]);
    }

    #[Route('/retrait/delete/{id}', name: 'app_retrait_delete')]
    public function delete(EntityManagerInterface $em,Retrait $retrait): Response
    {
        if ($retrait) {
            $em->remove($retrait);
            $em->flush();
        }
        return $this->redirectToRoute('app_retrait_list');
    }

    #[Route('/retrait/edit/{id}', name: 'app_retrait_edit')]
    public function edit(EntityManagerInterface $em,Retrait $retrait): Response
    {
        if ($retrait) {
            return $this->render('retrait/edit.html.twig', [
                'retraits' => $retrait,
            ]); 
        }
        return $this->redirectToRoute('app_retrait_list');
    }

    #[Route('/retrait/update', name: 'app_retrait_update')]
    public function update(EntityManagerInterface $em,Request $request): Response
    {
       $variable = $request->request->all('retraits');
       
       foreach ($variable as $key => $value) {
            $retrait = $em->getRepository(Retrait::class)->find($key);
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
        return $this->redirectToRoute('app_retrait_list');
    }
}
