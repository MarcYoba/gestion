<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\Depenses;
use App\Entity\TempAgence;
use App\Form\DepensesType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepensesController extends AbstractController
{
    /**
     * @Route("/depenses/create", name= "app_depenses")
     */
    
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $depenses = new Depenses();
        $form = $this->createForm(DepensesType::class,$depenses);
        $form->handleRequest($request);
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $idagence = $tempagence->getAgence(); 

        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var UploadedFile $file */
            $file = $form->get('imageFile')->getData();

            if ($file) {
                $fillesize = $file->getSize();
                $filename = uniqid().'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('depenses_upload_directory'), // Défini dans services.yaml
                    $filename
                );
                $depenses->setImageName($filename);
                $depenses->setImageSize($fillesize);
            }else{
                $depenses->setImageName("pas d'image");
                $depenses->setImageSize(0);
            }
            
            $depenses->setUser($user);
            $depenses->setAgence($idagence);
            $entityManager->persist($depenses);
            $entityManager->flush();

            return $this->redirectToRoute('depenses_list', ['id' => $idagence->getId()]);
        }
        return $this->render('depenses/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/depenses/list', name: 'depenses_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $depenses = $entityManager->getRepository(Depenses::class)->findAll();
        return $this->render('depenses/list.html.twig', [
            'depense' => $depenses,
        ]);
    }

    #[Route('/depenses/edit/{id}', name: 'depenses_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $depenses = $entityManager->getRepository(Depenses::class)->find($id);
        if (!$depenses) {
            throw $this->createNotFoundException('No depense found for id '.$id);
        }
        $form = $this->createForm(DepensesType::class, $depenses);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('imageFile')->getData();

            if ($file) {
                $fillesize = $file->getSize();
                $filename = uniqid().'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('depenses_upload_directory'), // Défini dans services.yaml
                    $filename
                );
                $depenses->setImageName($filename);
                $depenses->setImageSize($fillesize);
            }

            $entityManager->flush();

            return $this->redirectToRoute('depenses_list');
        }

        return $this->render('depenses/index.html.twig', [
            'form' => $form->createView(),
            'depense' => $depenses,
        ]);
    }

    #[Route('/depenses/delete/{id}', name: 'depenses_delete')]
    public function delete(EntityManagerInterface $entityManager, Depenses $depenses): Response
    {
        $entityManager->remove($depenses);
        $entityManager->flush();
        return $this->redirectToRoute('depenses_list');
    }
}
