<?php

namespace App\Controller;

use App\Entity\Passif;
use App\Entity\TempAgence;
Use App\Form\PassifType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Return_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PassifController extends AbstractController
{
    #[Route('/passif/creat', name: 'app_passif')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $passif = new Passif();
        $user = $this->getUser();
        $form = $this->createForm(PassifType::class,$passif);
        $form->handleRequest($request);
        $agence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $agence->getAgence()->getId();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $agence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);

            $ordre = $entityManager->getRepository(Passif::class)->findOneBy(['agence' => $id]);
            if ($ordre) {
                $passif->setOrdre(($ordre->getOrdre() + 1));
            } else {
                $passif->setOrdre(1);
            }
            
            $passif->setUser($user);
            $passif->setAgence($agence->getAgence());
            $entityManager->persist($passif);
            $entityManager->flush();

            return $this->redirectToRoute("app_passif_list",['date'=>date('Y')]);
        }
        return $this->render('passif/index.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }

    #[Route('passif/list/{date}', name:'app_passif_list')]
    public function list(EntityManagerInterface $entityManager,int $date) : Response
    {
        $user = $this->getUser();
        $agence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $agence->getAgence()->getId();

        $passif = $entityManager->getRepository(Passif::class)->findBy(["agence" => $id]);

        return $this->render('passif/list.html.twig', [
            'passifs' => $passif,
            'date' => $date,
            'id' => $id
        ]);
    }

    #[Route('passif/edit/{id}', name:'app_passif_edit')]
    public function Edit(Request $request,EntityManagerInterface $entityManager, int $id) : Response 
    {
        

        $passif = $entityManager->getRepository(Passif::class)->findOneBy(["id" => $id]);

        $form = $this->createForm(PassifType::class,$passif);
        $form->handleRequest($request);
        $user = $this->getUser();
        $agence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $agence->getAgence()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute("app_passif_list",['date'=>date('Y')]);
        }
        return $this->render('passif/edite.html.twig', [
            "form" => $form->createView(),
            "passif" => $passif,
            'id' => $id
        ]);
    }

    #[Route('/passif/delete/{id}', name: 'app_passif_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id) : Response
    {   
        $passif = $entityManager->getRepository(Passif::class)->find($id);
        if ($passif) {
            $entityManager->remove($passif);
            $entityManager->flush();
        }
        
      return $this->redirectToRoute("app_passif_list",['date'=>date('Y')]);   
    }
}
