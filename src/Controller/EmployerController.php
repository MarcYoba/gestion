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
           $emplyer->setNom($emplyer->getUser()->getUsername());
            $entityManager->persist($emplyer);
            $entityManager->flush();

           return $this->redirectToRoute("employer_list");
        }
        
        return $this->render('employer/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path ="/employer/list", name="employer_list")
     */
    public function list(EntityManagerInterface  $entityManager): Response
    {
        $emplyer = $entityManager->getRepository(Employer::class)->findAll();
        $agence = $entityManager->getRepository(Agence::class)->findAll();
        $user = $entityManager->getRepository(User::class)->findAll();
        return $this->render('employer/list.html.twig', [
            'employers' => $emplyer,
            'agence' => $agence,
            'user' => $user,
        ]);
    }

    /**
     * @Route(path = "/employer/edit/{id}", name = "employer_edit")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, Employer $employer): Response
    {
        $form = $this->createForm(EmployerType::class, $employer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('employer_list');
        }

        return $this->render('employer/edit.html.twig', [
            'form' => $form->createView(),
            'employer' => $employer,
        ]);
    }

    /**
     * @Route(path = "/employer/delete/{id}", name = "employer_delete")
     */
    public function delete(EntityManagerInterface $entityManager, Employer $employer): Response
    {
        $entityManager->remove($employer);
        $entityManager->flush();

        return $this->redirectToRoute('employer_list');
    }
}
