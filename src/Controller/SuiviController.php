<?php

namespace App\Controller;

use App\Entity\Suivi;
use App\Entity\TempAgence;
use App\Form\SuiviType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuiviController extends AbstractController
{
    #[Route('/suivi/create', name: 'app_suivi')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $suivi = new Suivi();
        $form = $this->createForm(SuiviType::class,$suivi);
        $form->handleRequest($request);

        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence = $tempagence->getAgence();

        if ($form->isSubmitted() && $form->isValid()) {
            $suivi->setUser($user);
            $suivi->setAgence($tempagence);

            $entityManager->persist($suivi);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_list');
        }
        return $this->render('suivi/index.html.twig', [
            'form' => $form->createView(),
            'tempagence' => $tempagence
        ]);
    }

    #[Route('/suivi/list', name:'app_suivi_list')]
    public function list(EntityManagerInterface $entityManager) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence = $tempagence->getAgence();

        $suivi = $entityManager->getRepository(Suivi::class)->findBy(['agence'=>$tempagence]);

        return $this->render('suivi/list.html.twig',[
            'suivis' => $suivi,
            'tempagence' => $tempagence,
        ]);
    }

    #[Route('suivi/edite/{id}', name:'app_suivi_edite')]
    public function Edit(EntityManagerInterface $entityManager,Request $request, int $id) : Response 
    {
        $suivi = $entityManager->getRepository(Suivi::class)->find(['id' => $id]);
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence = $tempagence->getAgence();

        if (!$suivi) {
          return $this->redirectToRoute('app_suivi_list');    
        }

        $form = $this->createForm(SuiviType::class,$suivi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $suivi->setUser($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_list'); 
        }

       return $this->render('suivi/edit.html.twig',[
            'form' => $form->createView(),
            'tempagence' => $tempagence,
        ]);    
    }

    #[Route('suivi/delete/{id}', name:'app_suivi_delete')]
    public function delete(EntityManagerInterface $entityManager,int $id) : Response 
    {
        $suivi = $entityManager->getRepository(Suivi::class)->find(['id' => $id]);
        if ($suivi) {
            $entityManager->remove($suivi);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_suivi_list');
    }
}
