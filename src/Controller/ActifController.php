<?php

namespace App\Controller;

use App\Entity\Actif;
use App\Entity\TempAgence;
use App\Form\ActifType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActifController extends AbstractController
{
    #[Route('/actif/create', name: 'app_actif')]
    public function index(EntityManagerInterface $em, Request $Request): Response
    {
        $Actif = new Actif();
        $form = $this->createForm(ActifType::class, $Actif);
        $form->handleRequest($Request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tempAgence = $em->getRepository(TempAgence::class)->findOneBy(["user"=> $this->getUser()]) ;
            $id = $tempAgence->getAgence()->getId();
            $ordre = 0;
            $nombre = $em->getRepository(Actif::class)->findOneBy([], ['id' => 'DESC']);
            if ($nombre) {
                $ordre = $nombre->getId();
            }
            

            $Actif->setOrdre($ordre+1 );
            $Actif->setAgence($tempAgence->getAgence());
            $Actif->setUser($this->getUser());

            $em->persist($Actif);
            $em->flush();

            $this->addFlash('success','');
            return $this->redirectToRoute('app_actif_list', ['date' => date("Y")]);
        }
        return $this->render('actif/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/actif/list/{date}', name:'app_actif_list')]
    public function list(EntityManagerInterface $em,string $date): Response
    {
        $tempAgence = $em->getRepository(TempAgence::class)->findOneBy(["user"=> $this->getUser()]) ;
        $id = $tempAgence->getAgence()->getId();
        $Actif =  $em->getRepository(Actif::class)->findAll(["agence"=> $id]);
        return $this->render('actif/list.html.twig', [
            'Actifs' => $Actif,
            'date' => $date,
        ]);
    }

    #[Route('/actif/update', name:'app_actif_update')]
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

    #[Route('/actif/edit/{id}', name: 'app_actif_edite')]
    public function edite(EntityManagerInterface $entityManager, Request $request, int $id){
        $Actif = $entityManager->getRepository(Actif::class)->find(["id" => $id]);
        $form = $this->createForm(ActifType::class, $Actif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($Actif);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_actif_list', ['date' => date("Y")]);
        }

        return $this->render('actif/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/actif/delete/{id}', name: 'app_actif_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id) : Response {
       $Actif = $entityManager->getRepository(Actif::class)->findOneBy(["id" => $id]);
       $entityManager->remove($Actif);
       $entityManager->flush();
        return $this->redirectToRoute('app_actif_list', ['date' => date("Y")]);
    }
}
