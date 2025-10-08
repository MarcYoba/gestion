<?php

namespace App\Controller;

use App\Entity\Prospection;
use App\Entity\TempAgence;
use App\Form\ProspectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProspectionController extends AbstractController
{
    #[Route('/prospection/create', name: 'app_prospection')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $prospection = new Prospection();
        $form = $this->createForm(ProspectionType::class,$prospection);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $prospection->setAgence($tempagence->getAgence());
            $prospection->setUser($user);
            $em->persist($prospection);
            $em->flush();

            return $this->redirectToRoute('app_propection_list');
        }
        return $this->render('prospection/index.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }

    #[Route('/prosoection/list', name:'app_propection_list')]
    public function list(EntityManagerInterface $em) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $prospection = $em->getRepository(Prospection::class)->findBy(['agence'=>$id]);

        return $this->render('prospection/list.html.twig', [
            'prospections' => $prospection,
            'id' => $id,
        ]);
    }

    #[Route('/prosoection/edit/{id}', name:'app_propection_edit')]
    public function edit(EntityManagerInterface $em,Request $request, $id) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();
        $prospection = $em->getRepository(Prospection::class)->find(['id'=>$id]);
        $form  = $this->createForm(ProspectionType::class,$prospection);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $prospection->setUser($user);
            $em->persist($prospection);
            $em->flush();

            return $this->redirectToRoute('app_propection_list');
        }

        return $this->render('prospection/edit.html.twig', [
            'prospections' => $prospection,
            'id' => $agence,
            'form' => $form->createView(),
        ]); 
    }

    #[Route('/prosoection/delete/{id}', name:'app_propection_delete')]
    public function delete(EntityManagerInterface $em,$id) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $prospection = $em->getRepository(Prospection::class)->find(['id'=>$id]);
        if ($prospection) {
            $em->remove($prospection);
            $em->flush();
        }
        return $this->redirectToRoute('app_propection_list');
        return $this->render('prospection/list.html.twig', [
            'prospections' => $prospection,
            'id' => $id,
        ]);
    }
}
