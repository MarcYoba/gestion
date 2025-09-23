<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Entity\TempAgence;
use App\Form\ConsultationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsultationController extends AbstractController
{
    #[Route('/consultation', name: 'app_consultation')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class,$consultation);
        $form->handleRequest($request);

        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence();

        if ($form->isSubmitted() && $form->isValid()) {
            $consultation->setUser($user);
            $consultation->setAgence($tempagence);

            $entityManager->persist($consultation);
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_list');
        }

        return $this->render('consultation/index.html.twig', [
            'form' => $form->createView(),
            'tempagence' => $tempagence,
        ]);
    }

    #[Route('/consultation/list', name: 'app_consultation_list')]
    public function list(EntityManagerInterface $entityManager) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence()->getId();

        $consultation = $entityManager->getRepository(Consultation::class)->findBy(['agence' => $tempagence]);

        return $this->render('consultation/list.html.twig',[
            'consultations' => $consultation,
        ]);
    }

    #[Route('/consultation/edite/{id}', name: 'app_consultation_edit')]
    public function edite(Request $request,EntityManagerInterface $entityManager,int $id) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence();

        
        $consultation = $entityManager->getRepository(Consultation::class)->findOneBy(['id' => $id]);
        $form = $this->createForm(ConsultationType::class,$consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultation->setUser($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_list');
        }
        
        return $this->render('consultation/edite.html.twig',[
            'form' => $form->createView(),
            'consultations' => $consultation,
            'tempagence' => $tempagence,
        ]);
    }

    #[Route('/consultation/delete/{id}', name:'app_consultation_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id) : Response 
    {
        $consultation = $entityManager->getRepository(Consultation::class)->findOneBy(['id' => $id]);
        if ($consultation) {
            $entityManager->remove($consultation);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('app_consultation_list');
    }

    #[Route('/consultation/details/{id}', name:'app_consultation_details')]
    public function details(EntityManagerInterface $entityManager, int $id) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence();
        
        $consultation = $entityManager->getRepository(Consultation::class)->findOneBy(['id' => $id]);

        if ($consultation) {
            return $this->render('consultation/detail.html.twig',[
            'consultations' => $consultation,
            'tempagence' => $tempagence,
        ]);
        }

        return $this->redirectToRoute('app_consultation_list');
        
    }
}
