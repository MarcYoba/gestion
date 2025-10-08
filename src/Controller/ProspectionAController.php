<?php

namespace App\Controller;

use App\Entity\ProspectionA;
use App\Entity\TempAgence;
use App\Form\ProspectionAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProspectionAController extends AbstractController
{
    #[Route('/prospection/a/create', name: 'app_prospection_a')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $prospection = new ProspectionA();
        $form = $this->createForm(ProspectionAType::class,$prospection);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $prospection->setAgence($tempagence->getAgence());
            $prospection->setUser($user);
            $em->persist($prospection);
            $em->flush();

            return $this->redirectToRoute('app_propection_a_list');
        }
        
        return $this->render('prospection_a/index.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }
    #[Route('/prosoection/a/list', name:'app_propection_a_list')]
    public function list(EntityManagerInterface $em) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $prospection = $em->getRepository(ProspectionA::class)->findBy(['agence'=>$id]);

        return $this->render('prospection_a/list.html.twig', [
            'prospections' => $prospection,
            'id' => $id,
        ]);
    }

    #[Route('/prosoection/a/edit/{id}', name:'app_propection_a_edit')]
    public function edit(EntityManagerInterface $em,Request $request, $id) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();
        $prospection = $em->getRepository(ProspectionA::class)->find(['id'=>$id]);
        $form  = $this->createForm(ProspectionAType::class,$prospection);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $prospection->setUser($user);
            $em->persist($prospection);
            $em->flush();

            return $this->redirectToRoute('app_propection_a_list');
        }

        return $this->render('prospection_a/edit.html.twig', [
            'prospections' => $prospection,
            'id' => $agence,
            'form' => $form->createView(),
        ]); 
    }

    #[Route('/prosoection/a/delete/{id}', name:'app_propection_a_delete')]
    public function delete(EntityManagerInterface $em,$id) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $prospection = $em->getRepository(ProspectionA::class)->find(['id'=>$id]);
        if ($prospection) {
            $em->remove($prospection);
            $em->flush();
        }
        return $this->redirectToRoute('app_propection_a_list');
        return $this->render('prospection_a/list.html.twig', [
            'prospections' => $prospection,
            'id' => $id,
        ]);
    }
}
