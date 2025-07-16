<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\TempAgence;
use App\Entity\User;
use App\Entity\Vente;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $agence = $entityManager->getRepository(Agence::class)->findAll();
        $user = $this->getUser();
        $user = $entityManager->getRepository(User::class)->find($user);
        if($user->getLastLogin() === null) {
            $user->setLastLogin(new \DateTime());
            $entityManager->flush();
        }
        
        return $this->render('home/index.html.twig', [
            'agence' => $agence,
        ]);
    }

    #[Route('/home/dashboard/{id}', name: 'app_home_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager,int $id): Response
    {
        $user = $this->getUser();
        
        if ($id == 0) {
            $agence = 0;
                return $this->render('home/dashboard.html.twig', [
                'agence' => $agence,
                //'sommevente' => $sommevente,
            ]);
        }

        $agence = $entityManager->getRepository(Agence::class)->findAll();
        //$sommevente = $entityManager->getRepository(Vente::class)->findTotalPriceByYear(date('Y'));
        $temoporayagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        if ($temoporayagence) {
            $idagence = $entityManager->getRepository(Agence::class)->find($id);
            //$temoporayagence = new TempAgence();
            $temoporayagence->setAgence($idagence);
            $entityManager->flush();
        }else{
            $idagence = $entityManager->getRepository(Agence::class)->find($id);
            $temoporayagence = new TempAgence();
            $temoporayagence->setUser($user);
            $temoporayagence->setAgence($idagence);
            $entityManager->persist($temoporayagence);
            $entityManager->flush();
        }
        return $this->render('home/dashboard.html.twig', [
            'agence' => $agence,
            //'sommevente' => $sommevente,
        ]);
    }

    #[Route('/home/dashboardA/{id}', name: 'app_home_dashboardA')]
    public function dashboardA(EntityManagerInterface $entityManager,int $id): Response
    {
        $user = $this->getUser();
        $temoporayagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        if ($temoporayagence) {
            $idagence = $entityManager->getRepository(Agence::class)->find($id);
            //$temoporayagence = new TempAgence();
            $temoporayagence->setAgence($idagence);
            $entityManager->flush();
        }else{
            $idagence = $entityManager->getRepository(Agence::class)->find($id);
            $temoporayagence = new TempAgence();
            $temoporayagence->setUser($user);
            $temoporayagence->setAgence($idagence);
            $entityManager->persist($temoporayagence);
            $entityManager->flush();
        }
        $agence = $entityManager->getRepository(Agence::class)->findAll();
        return $this->render('home/dashboardA.html.twig', [
            'agence' => $agence,
        ]);
    }
}