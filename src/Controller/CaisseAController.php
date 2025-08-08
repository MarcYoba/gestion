<?php

namespace App\Controller;

use App\Entity\CaisseA;
use App\Form\CaisseAType;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CaisseAController extends AbstractController
{
    #[Route('/caisse/a', name: 'app_caisse_a')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $caisseA = new CaisseA();
        $form = $this->createForm(CaisseAType::class,$caisseA);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user"=>$user]);
            $agence = $tempagence->getAgence();
            $caisseA->setAgence($agence);
            $caisseA->setUser($user);

            $entityManager->persist($caisseA);
            $entityManager->flush();
            return $this->redirectToRoute('app_caisse_a_list');
        }
        return $this->render('caisse_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/caisse/a/liste', name: 'app_caisse_a_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user"=>$user]);
        $id = $tempagence->getAgence()->getId();
        $caisseA = $entityManager->getRepository(CaisseA::class)->findAll(["agence" => $id]);
       return $this->render('caisse_a/list.html.twig', [
            'caisseAs' => $caisseA,
        ]); 
    }
}
