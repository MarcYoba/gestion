<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\User;
use App\Entity\Agence;
use App\Entity\TempAgence;
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

           return $this->redirectToRoute("employer_list", ['id' => $emplyer->getId()]);
        }
        
        return $this->render('employer/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/employer/a/create', name: 'app_employer_a')]
    public function index_a(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emplyer = new Employer();
        $form = $this->createForm(EmployerType::class,$emplyer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role[] = $form->get('poste')->getData();
            $emplyer->setNom($emplyer->getUser()->getUsername());
            $user = $emplyer->getUser();
            $user->setRoles($role);
            $entityManager->persist($emplyer);
            $entityManager->flush();

           return $this->redirectToRoute("employer_list_a", ['id' => $emplyer->getId()]);
        }
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $id = $tempagence->getAgence()->getId();
        
        return $this->render('employer/index_a.html.twig', [
            'form' => $form->createView(),
            'id' => $id
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
     * @Route(path ="/employer/a/list", name="employer_list_a")
     */
    public function list_a(EntityManagerInterface  $entityManager): Response
    {
        $emplyer = $entityManager->getRepository(Employer::class)->findAll();
        $agence = $entityManager->getRepository(Agence::class)->findAll();
        $user = $entityManager->getRepository(User::class)->findAll();
        return $this->render('employer/list_a.html.twig', [
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

        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $id = $tempagence->getAgence()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('employer_list');
        }

        return $this->render('employer/edit.html.twig', [
            'form' => $form->createView(),
            'employer' => $employer,
            'id' => $id,
        ]);
    }

    /**
     * @Route(path = "/employer/a/edit/{id}", name = "employer_edit_a")
     */
    public function edit_a(Request $request, EntityManagerInterface $entityManager, Employer $employer): Response
    {
        $form = $this->createForm(EmployerType::class, $employer);
        $form->handleRequest($request);

        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $id = $tempagence->getAgence()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('employer_list_a');
        }

        return $this->render('employer/edit_a.html.twig', [
            'form' => $form->createView(),
            'employer' => $employer,
            'id' => $id,
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
    /**
     * @Route(path ="/agence/select/bascule", name="app_agence_bascule")
     */
    public function bascule(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $employer = $em->getRepository(Employer::class)->findOneBy(["user" => $user]);
        if ($employer) {
            return $this->redirectToRoute("app_home");
        }
        return $this->redirectToRoute("app_client");
    }
}
