<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\User;
use App\Entity\Agence;
use App\Form\EmployerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployerController extends AbstractController
{
    #[Route('/employer/create', name: 'app_employer')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emplyer = new Employer();
        $form = $this->createForm(EmployerType::class,$emplyer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $entityManager->persist($emplyer);
            $entityManager->flush();
        }
        
        return $this->render('employer/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/employer/list/', name: 'employer_list')]
    public function list(): Response
    {
        return $this->render('employer/list.html.twig', [
            'controller_name' => 'EmployerController',
        ]);
    }
}
