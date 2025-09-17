<?php

namespace App\Controller;

use App\Entity\ActifA;
use App\Entity\TempAgence;
use App\Form\ActifAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActifAController extends AbstractController
{
    #[Route('/actif/a/create', name: 'app_actif_a')]
    public function index(EntityManagerInterface $em, Request $Request): Response
    {
        $Actif = new ActifA();
        $form = $this->createForm(ActifAType::class, $Actif);
        $form->handleRequest($Request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tempAgence = $em->getRepository(TempAgence::class)->findOneBy(["user"=> $this->getUser()]) ;
            $id = $tempAgence->getAgence()->getId();
            $ordre = 0;
            $nombre = $em->getRepository(ActifA::class)->findOneBy([], ['id' => 'DESC']);
            if ($nombre) {
                $ordre = $nombre->getId();
            }
            $Actif->setOrdre($ordre+1 );
            $Actif->setAgence($tempAgence->getAgence());
            $Actif->setUser($this->getUser());

            $em->persist($Actif);
            $em->flush();

            $this->addFlash('success','');
            return $this->redirectToRoute('app_actif_a_list', ["date" => date("Y")]);
        }
        return $this->render('actif_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/actif/a/list/{date}', name:'app_actif_a_list')]
    public function list(EntityManagerInterface $em,string $date): Response
    {
        $tempAgence = $em->getRepository(TempAgence::class)->findOneBy(["user"=> $this->getUser()]) ;
        $id = $tempAgence->getAgence()->getId();
        $Actif =  $em->getRepository(ActifA::class)->findBy(["agence"=> $id]);
        return $this->render('actif_a/list.html.twig', [
            'Actifs' => $Actif,
            'date' => $date,
        ]);
    }

    #[Route('/actif/a/update', name:'app_actif_a_update')]
    public function update(EntityManagerInterface $entityManager, Request $request): Response
    {
        if($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $json = $request->getContent();
            $donnees = json_decode($json, true);
            if (isset($donnees)) {
                return $this->json(['error' => 'Mise a jour du bilan','donne'=>$donnees], 200);
            }
        }
        return $this->json(['error' => 'Erreur de bilan'], 404);
    }

    #[Route('/actif/a/edit/{id}', name: 'app_actif_a_edite')]
    public function edite(EntityManagerInterface $entityManager, Request $request, int $id){
        $Actif = $entityManager->getRepository(ActifA::class)->find(["id" => $id]);
        $form = $this->createForm(ActifAType::class, $Actif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
        }

        return $this->render('actif/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/actif/a/edit/{id}', name: 'app_actif_a_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id) : Response {
       $Actif = $entityManager->getRepository(ActifA::class)->find(["id" => $id]);
       $entityManager->remove($Actif);
        return $this->redirectToRoute('app_actif_list', ["date" => date("Y")]);
    }
}
