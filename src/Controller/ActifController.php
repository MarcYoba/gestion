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
            return $this->redirectToRoute('app_actif_list');
        }
        return $this->render('actif/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/actif/list', name:'app_actif_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $tempAgence = $em->getRepository(TempAgence::class)->findOneBy(["user"=> $this->getUser()]) ;
        $id = $tempAgence->getAgence()->getId();
        $list =  $em->getRepository(Actif::class)->findAll(["agence"=> $id]);
        return $this->render('actif/list.html.twig', [
            'listes' => $list,
        ]);
    }
}
