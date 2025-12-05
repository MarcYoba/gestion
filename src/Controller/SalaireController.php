<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Entity\Employer;
use App\Entity\Salaire;
use App\Entity\TempAgence;
use App\Form\SalaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalaireController extends AbstractController
{
    #[Route('/salaire/creat', name: 'app_salaire')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $salaire = new Salaire();
        $form = $this->createForm(SalaireType::class,$salaire);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $employer = $request->request->get('employer');
            $employer = $entityManager->getRepository(Employer::class)->findOneBy(['nom' => $employer]);
           
            $salaire->setEmployer($employer);
            $salaire->setUser($user);
            $salaire->setAgence($tempagence->getAgence());

            $entityManager->persist($salaire);
            $entityManager->flush();

            $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 4211]);
                if ($balance) {
                    $mouvement = $balance->getMouvementDebit();
                    $mouvement = $mouvement + $form->get('montant')->getData();
                    $balance->setMouvementDebit($mouvement);
                    $entityManager->persist($balance);
                    $entityManager->flush();
                }

            if ($form->get('type')->getData() == "CASH") {
                $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 5111]);
                if ($balance) {
                    $mouvement = $balance->getMouvementCredit();
                    $mouvement = $mouvement + $form->get('montant')->getData();
                    $balance->setMouvementCredit($mouvement);
                    $entityManager->persist($balance);
                    $entityManager->flush();
                }
            }

            if ($form->get('type')->getData() == "BANQUE") {

                $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 5121]);
                if ($balance) {
                    $mouvement = $balance->getMouvementCredit();
                    $mouvement = $mouvement + $form->get('montant')->getData();
                    $balance->setMouvementCredit($mouvement);
                    $entityManager->persist($balance);
                    $entityManager->flush();
                }
            }
            
            return $this->redirectToRoute('app_salaire_list');
        }
        $employer = $entityManager->getRepository(Employer::class)->findBy(['agence'=>$id]);
        return $this->render('salaire/index.html.twig', [
            'form' => $form->createView(),
            'employer' => $employer,
        ]);
    }

    #[Route('/salaire/list', name: 'app_salaire_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $salaire = $entityManager->getRepository(Salaire::class)->findBy(['agence' => $id]);
        return $this->render('salaire/list.html.twig', [
            'salaires' => $salaire,
        ]);
    }

    #[Route('/salaire/edit/{id}', name: 'app_salaire_edit')]
    public function edit(EntityManagerInterface $entityManager,Salaire $salaire): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $employer = $entityManager->getRepository(Employer::class)->findBy(['agence'=>$id]);
        if ($salaire) {
             return $this->render('salaire/edit.html.twig', [
                'salaire' => $salaire,
                'employer' => $employer,
            ]);
        }
        return $this->redirectToRoute('app_salaire_list');
    }

    #[Route('/salaire/update', name: 'app_salaire_update', methods: ['POST'])]
    public function update(EntityManagerInterface $entityManager,Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        
        $update = $request->request->all('salaires');

        foreach ($update as $key => $value) {
            $salaire = $entityManager->getRepository(Salaire::class)->find($key);
            if ($salaire) {
                $salaire->setMontant($value['montant'] ?? 0);
                $salaire->setCreatedAd(new \DateTime($value['createAt']));
                $salaire->setUser($user);
                $salaire->setStatus($value['satuts'] ?? 0);
                $employer = $entityManager->getRepository(Employer::class)->findOneBy(['nom'=>$value['employer']]);
                $salaire->setEmployer($employer);
                $salaire->setSalaireBrut($value['salaireBrut'] ?? 0);
                $salaire->setCotisationSociales($value['cotisationSociales'] ?? 0);
                $salaire->setImpots($value['impots'] ?? 0);
                $salaire->setType($value['type'] ?? 0);

                $entityManager->persist($salaire);
                $entityManager->flush();
            }
            
        }

        return $this->redirectToRoute('app_salaire_list');
    }

    #[Route('/salaire/delete/{id}', name: 'app_salaire_delete')]
    public function delete(EntityManagerInterface $entityManager, Salaire $salaire) : Response 
    {
        if ($salaire) {
            $entityManager->remove($salaire);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_salaire_list');
    }
}
