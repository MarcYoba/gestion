<?php

namespace App\Controller;

use App\Entity\Depenses;
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

            $entityManager->persist($depenses);
            $entityManager->flush();

            return $this->redirectToRoute('depenses_list');
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
}
