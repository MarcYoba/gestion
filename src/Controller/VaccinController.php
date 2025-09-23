<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\TempAgence;
use App\Entity\Vaccin;
use App\Form\VaccinType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Return_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class VaccinController extends AbstractController
{
    #[Route('/vaccin/create', name: 'app_vaccin')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $vaccin = new Vaccin();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence = $tempagence->getAgence();

        $client = $entityManager->getRepository(Clients::class)->findAll();

        $form = $this->createForm(VaccinType::class,$vaccin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            

            $vaccin->setAgence($tempagence);
            $vaccin->setUser($user);
            $vaccin->setRappel(0);

            $entityManager->persist($vaccin);
            $entityManager->flush();

            return $this->redirectToRoute('app_vaccin_list');
        }
        return $this->render('vaccin/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'tempagence' => $tempagence,
        ]);
    }

    #[Route('/vaccin/list', name: 'app_vaccin_list')]
    public function list(EntityManagerInterface $entityManager) : Response {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence = $tempagence->getAgence();

        $vaccin = $entityManager->getRepository(Vaccin::class)->findBy(['agence' => $tempagence]);

        return $this->render('vaccin/list.html.twig',[
            'vaccins' => $vaccin,
            'tempagence' => $tempagence,
        ]);
    }

    #[Route('/vaccin/edite/{id}', name: 'app_vaccin_edit')]
    public function edite(Request $request,EntityManagerInterface $entityManager,int $id) : Response 
    {
       $user =$this->getUser();
        $vaccin = $entityManager->getRepository(Vaccin::class)->findOneBy(['id'=> $id]);
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence = $tempagence->getAgence();

        $form = $this->createForm(VaccinType::class,$vaccin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $vaccin->setUser($user);
            $entityManager->flush($vaccin);

            return $this->redirectToRoute('app_vaccin_list');
        }

       return $this->render('vaccin/edite.html.twig',[
        'form' => $form->createView(),
        'tempagence' => $tempagence,
       ]);
    }

    #[Route('/vaccin/rappel/{id}', name:'app_vaccin_rappel')]
    public function Rappel(Request $request, EntityManagerInterface $entityManager,int $id) : Response 
    {
        $user =$this->getUser();
        $vaccin = $entityManager->getRepository(Vaccin::class)->findOneBy(['id'=> $id]);
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence = $tempagence->getAgence();

        $form = $this->createForm(VaccinType::class,$vaccin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rappel = $vaccin->getRappel();
            $vaccin->setUser($user);
            $vaccin->setRappel($rappel+1);
            $entityManager->flush($vaccin);

            return $this->redirectToRoute('app_vaccin_list');
        }

       return $this->render('vaccin/rappel.html.twig',[
        'form' => $form->createView(),
        'tempagence' => $tempagence,
       ]);        
    }

    #[Route('/vaccin/delete/{id}', name:'app_vaccin_delete')]
    public function FunctionName(EntityManagerInterface $entityManager, int $id) : Response 
    {
        $vaccin = $entityManager->getRepository(Vaccin::class)->findOneBy(['id'=> $id]);
        if ($vaccin) {
            $entityManager->remove($vaccin);
            $entityManager->flush();
        }
          
        return $this->redirectToRoute('app_vaccin_list');

    }
}
