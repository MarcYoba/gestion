<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\SalaireA;
use App\Entity\TempAgence;
use App\Form\SalaireAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalaireAController extends AbstractController
{
    #[Route('/salaire/a', name: 'app_salaire_a')]
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $salaire = new SalaireA();
        $form = $this->createForm(SalaireAType::class,$salaire);
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

            return $this->redirectToRoute('app_salaire_list_a');
        }
        $employer = $entityManager->getRepository(Employer::class)->findBy(['agence'=>$id]);
        return $this->render('salaire_a/index.html.twig', [
            'form' => $form->createView(),
            'employer' => $employer,
        ]);
    }

    #[Route('/salaire/a/list', name: 'app_salaire_list_a')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $salaire = $entityManager->getRepository(SalaireA::class)->findBy(['agence' => $id]);
        return $this->render('salaire_a/list.html.twig', [
            'salaires' => $salaire,
        ]);
    }

    #[Route('/salaire/a/edit/{id}', name: 'app_salaire_edit_a')]
    public function edit(EntityManagerInterface $entityManager,SalaireA $salaire): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $employer = $entityManager->getRepository(Employer::class)->findBy(['agence'=>$id]);
        if ($salaire) {
             return $this->render('salaire_a/edit.html.twig', [
                'salaire' => $salaire,
                'employer' => $employer,
            ]);
        }
        return $this->redirectToRoute('app_salaire_list_a');
    }

    #[Route('/salaire/a/update', name: 'app_salaire_update_a', methods: ['POST'])]
    public function update(EntityManagerInterface $entityManager,Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        
        $update = $request->request->all('salaires');

        foreach ($update as $key => $value) {
            $salaire = $entityManager->getRepository(SalaireA::class)->find($key);
            if ($salaire) {
                $salaire->setMontant($value['montant'] ?? 0);
                $salaire->setCreatetAt(new \DateTime($value['createAt']));
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

        return $this->redirectToRoute('app_salaire_list_a');
    }

    #[Route('/salaire/a/delete/{id}', name: 'app_salaire_delete_a')]
    public function delete(EntityManagerInterface $entityManager, SalaireA $salaire) : Response 
    {
        if ($salaire) {
            $entityManager->remove($salaire);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_salaire_list_a');
    }

}
