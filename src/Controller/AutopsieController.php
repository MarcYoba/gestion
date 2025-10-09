<?php

namespace App\Controller;

use App\Entity\Autopsie;
use App\Entity\TempAgence;
use App\Form\AutopsieType;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutopsieController extends AbstractController
{
    #[Route('/autopsie/create', name: 'app_autopsie')]
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence();

        $autopsie = new Autopsie();
        $form = $this->createForm(AutopsieType::class,$autopsie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $autopsie->setUser($user);
            $autopsie->setAgence($tempagence);

            $entityManager->persist($autopsie);
            $entityManager->flush();

            return $this->redirectToRoute('app_autopsie_liste');
        }
        return $this->render('autopsie/index.html.twig', [
            'id' => $tempagence->getId(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/autopsie/list', name: 'app_autopsie_liste')]
    public function List(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence();

        $autopsie = $entityManager->getRepository(Autopsie::class)->findBy(['agence'=>$tempagence]);

        return $this->render('autopsie/list.html.twig', [
            'id' => $tempagence->getId(),
            'autopsies' => $autopsie,
        ]);
    }

    #[Route('/autopsie/edit/{id}', name:'app_autopsie_edite')]
    public function edit(EntityManagerInterface $entityManager, Request $request,$id) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence();

        $autopsie = $entityManager->getRepository(Autopsie::class)->findOneBy(['id'=>$id]);

        $form = $this->createForm(AutopsieType::class,$autopsie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $autopsie->setUser($user);
            $entityManager->persist($autopsie);
            $entityManager->flush();

            return $this->redirectToRoute('app_autopsie_liste');
        }

        return $this->render('autopsie/edit.html.twig', [
            'id' => $tempagence->getId(),
            'autopsies' => $autopsie,
            'form'=> $form->createView(),
        ]);
    }

    #[Route('/autopsie/delete/{id}', name: 'app_autopsie_delete')]
    public function delete(EntityManagerInterface $entityManager,$id) :Response 
    {
        $autopsie = $entityManager->getRepository(Autopsie::class)->findOneBy(['id'=>$id]);

        if ($autopsie) {
            $entityManager->remove($autopsie);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_autopsie_liste');
    }

    #[Route('/autopsie/detailes/{id}', name: 'app_autopsie_detailes')]
    public function detailes(EntityManagerInterface $entityManager,$id) :Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence();
        $autopsie = $entityManager->getRepository(Autopsie::class)->findOneBy(['id'=>$id]);

        if ($autopsie) {
            return $this->render('autopsie/details.html.twig', [
            'id' => $tempagence->getId(),
            'autopsies' => $autopsie,
        ]);
        }
        return $this->redirectToRoute('app_autopsie_liste');
    }

    #[Route('/autopsie/download/{id}', name: 'app_autopsie_telecharger')]
    public function rapport_hier(EntityManagerInterface $em,$id) : Response 
    {
        

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);

        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence();
        $autopsie = $em->getRepository(Autopsie::class)->findOneBy(['id'=>$id]);
        if (!$autopsie) {
            return $this->redirectToRoute('app_autopsie_liste');
        }
        
       $html = $this->render('autopsie/telecharger.html.twig', [
            'id' => $tempagence->getId(),
            'autopsies' => $autopsie,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();

        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="document.pdf"', // 'inline' pour affichage navigateur
            ]
        );
    }

}
