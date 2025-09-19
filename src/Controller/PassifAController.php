<?php

namespace App\Controller;

use App\Entity\PassifA;
use App\Entity\TempAgence;
use App\Form\PassifAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PassifAController extends AbstractController
{
    #[Route('/passif/a/creat', name: 'app_passif_a')]
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $passif = new PassifA();
        $user = $this->getUser();
        $form = $this->createForm(PassifAType::class,$passif);
        $form->handleRequest($request);
        $agence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $agence->getAgence()->getId();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $agence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);

            $ordre = $entityManager->getRepository(PassifA::class)->findOneBy(['agence' => $id]);
            if ($ordre) {
                $passif->setOrdre(($ordre->getOrdre() + 1));
            } else {
                $passif->setOrdre(1);
            }
            
            $passif->setUser($user);
            $passif->setAgence($agence->getAgence());
            $entityManager->persist($passif);
            $entityManager->flush();

            return $this->redirectToRoute("app_passif_a_list",['date'=>date('Y')]);
        }
        return $this->render('passif_a/index.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }

    #[Route('passif/a/list/{date}', name:'app_passif_a_list')]
    public function list(EntityManagerInterface $entityManager,int $date) : Response
    {
        $user = $this->getUser();
        $agence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $agence->getAgence()->getId();

        $passif = $entityManager->getRepository(PassifA::class)->findBy(["agence" => $id]);

        return $this->render('passif_a/list.html.twig', [
            'passifs' => $passif,
            'date' => $date,
            'id' => $id
        ]);
    }

    #[Route('passif/a/edit/{id}', name:'app_passif_a_edit')]
    public function Edit(Request $request,EntityManagerInterface $entityManager, int $id) : Response 
    {
        $user = $this->getUser();
        $agence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $agence->getAgence()->getId();

        $passif = $entityManager->getRepository(PassifA::class)->findOneBy(["agence" => $id]);

        $form = $this->createForm(PassifAType::class,$passif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute("app_passif_a_list",['date'=>date('Y')]);
        }
        return $this->render('passif_a/edite.html.twig', [
            "form" => $form->createView(),
            "passif" => $passif,
            'id' => $id
        ]);
    }

    #[Route('/passif/delete/{id}', name: 'app_passif_a_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id) : Response
    {   
        $passif = $entityManager->getRepository(PassifA::class)->find($id);
        if ($passif) {
            $entityManager->remove($passif);
            $entityManager->flush();
        }
        
      return $this->redirectToRoute("app_passif_a_list",['date'=>date('Y')]);   
    }
}
