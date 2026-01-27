<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\Employer;
use App\Entity\User;
use App\Entity\Vente;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgenceController extends AbstractController
{
    #[Route('/agence/home', name: 'app_agence')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agence = new Agence();
        $nbagence = $entityManager->getRepository(Agence::class)->findAll();
        $form = $this->createForm(AgenceType::class,$agence);
        $form->handleRequest($request);
        $user = $this->getUser();
        $user = $entityManager->getRepository(User::class)->find($user);
        if($user->getLastLogin() === null) {
            $user->setLastLogin(new \DateTime());
            $entityManager->flush();
        }
        if ($form->isSubmitted() && $form->isValid()) {
           // $user = $this->getUser();
            $agence->setCreatedBY($agence->getUser()->getId());

            $entityManager->persist($agence);
            $entityManager->flush();

           return $this->redirectToRoute("app_home");
        }

        if (count($nbagence) <= 0) {
            return $this->render('agence/index.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        $agence = $entityManager->getRepository(Agence::class)->findAll();
        if ($this->isGranted("ROLE_ADMIN_ADMIN") || $this->isGranted("ROLE_CLIENTS")) {
            $agence = $entityManager->getRepository(Agence::class)->findAll();
        }else{
            $user = $this->getUser();
            $employer = $entityManager->getRepository(Employer::class)->findOneBy(['user' => $user]);
            $agence = $entityManager->getRepository(Agence::class)->findBy(['id'=>$employer->getAgence()->getId()]);
        }
        
        return $this->render('home/index.html.twig', [
            'agence' => $agence,
        ]);
    }

    #[Route('/agence/client/', name: 'app_client')]
    public function client(EntityManagerInterface $em): Response
    {
        $vente = $em->getRepository(Vente::class)->findAll(["client"=>$this->getUser()]);
        return $this->render('agence/client.html.twig', [
            'vente' => $vente,
            'user' => $this->getUser(),
        ]);
    }
    #[Route('/agence/create/new', name: 'app_agence_new')]
    public function Create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agence->setCreatedBY($agence->getUser()->getId());

            $entityManager->persist($agence);
            $entityManager->flush();

            return$this->redirectToRoute("app_agence_list");
        }

        return $this->render('agence/index.html.twig', [
                'form' => $form->createView(),
            ]);
    }
    #[Route('/agence/a/create/new', name: 'app_agence_new_a')]
    public function Create_A(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agence->setCreatedBY($agence->getUser()->getId());

            $entityManager->persist($agence);
            $entityManager->flush();

            return$this->redirectToRoute("app_agence_list");
        }

        return $this->render('agence/index_a.html.twig', [
                'form' => $form->createView(),
            ]);
    }
    /**
     * @Route(path="/agence/list", name="app_agence_list")
     */
    public function list(AgenceRepository $agenceRepository): Response
    {
        $agences = $agenceRepository->findAll();
        return $this->render('agence/list.html.twig', [
            'agences' => $agences,
        ]);
    }
    /**
     * @Route(path="/agence/a/list", name="app_agence_list_a")
     */
    public function list_a(AgenceRepository $agenceRepository): Response
    {
        $agences = $agenceRepository->findAll();
        return $this->render('agence/list_a.html.twig', [
            'agences' => $agences,
        ]);
    }
    /**
     * @Route(path="/agence/delete/{id}", name="app_agence_delete")
     */
    public function delete(Agence $agence, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($agence);
        $entityManager->flush();

        return $this->redirectToRoute('app_agence_list');
    }
    /**
     * @Route(path="/agence/a/delete/{id}", name="app_agence_delete_a")
     */
    public function delete_a(Agence $agence, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($agence);
        $entityManager->flush();

        return $this->redirectToRoute('app_agence_list_a');
    }
    /**
     * @Route(path="/agence/edit/{id}", name="app_agence_edit")
     */
    public function edit(Agence $agence, Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('agence/edit.html.twig', [
            'agences' => $agence,
        ]);
    }
    #[Route('/agence/a/edit/{id}', name: 'app_agence_edit_a')]
    public function edit_a(Agence $agence): Response
    { 
        return $this->render('agence/edit_a.html.twig', [
            'agences' => $agence,
        ]);
    }
    #[Route('/agence/a/update', name: 'update_agence_a')]
    public function update_a(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agences = $request->request->all('agences');
        
        foreach ($agences as $key => $value) {
            $agence = $entityManager->getRepository(Agence::class)->find($key);
            $agence->setNom($value['nom']);
            $agence->setAdress($value['adress']);
            $agence->setActivite($value['activite']);
            $agence->setContribuable($value['contribuable']);
            $agence->setRccm($value['rccm']);
            $agence->setTelephone($value['telephone']);
            $agence->setPresentation($value['presentation']);
            $entityManager->persist($agence);
        }
        $entityManager->flush();
        return $this->redirectToRoute('app_agence_list_a');
    }
    #[Route('/agence/update', name: 'update_agence')]
    public function update(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agences = $request->request->all('agences');
        
        foreach ($agences as $key => $value) {
            $agence = $entityManager->getRepository(Agence::class)->find($key);
            $agence->setNom($value['nom']);
            $agence->setAdress($value['adress']);
            $agence->setActivite($value['activite']);
            $agence->setContribuable($value['contribuable']);
            $agence->setRccm($value['rccm']);
            $agence->setTelephone($value['telephone']);
            $agence->setPresentation($value['presentation']);
            $entityManager->persist($agence);
        }
        $entityManager->flush();
        return $this->redirectToRoute('app_agence_list');
    }
}
