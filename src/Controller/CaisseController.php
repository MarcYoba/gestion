<?php

namespace App\Controller;

use App\Entity\Caisse;
use App\Form\CaisseType;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CaisseController extends AbstractController
{
    #[Route('/caisse', name: 'app_caisse')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $caisse = new Caisse();
        $form = $this->createForm(CaisseType::class,$caisse);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user"=>$user]);
            $agence = $tempagence->getAgence();
            $caisse->setAgence($agence);
            $caisse->setUser($this->getUser());

            $entityManager->persist($caisse);
            $entityManager->flush();

            return $this->redirectToRoute('app_caisse_list');
        }

        return $this->render('caisse/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/caisse/list', name: 'app_caisse_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user"=>$user]);
        $id = $tempagence->getAgence()->getId();
        $caisse = $entityManager->getRepository(Caisse::class)->findAll(["agence" => $id]);
        return $this->render('caisse/list.html.twig', [
            'caisses' => $caisse,
        ]);
    }

    #[Route('/caisse/etat', name : 'etat de la caisse')]
    public function Etat_Caisse(){
         return $this->json(['success'=> true,'message'=> 'success']);
    }
}
