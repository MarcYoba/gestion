<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\Clients;
use App\Entity\Employer;
use App\Entity\Facture;
use App\Entity\FactureA;
use App\Entity\Lots;
use App\Entity\Produit;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use App\Entity\User;
use App\Entity\Vente;
use App\Entity\VenteA;
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
        if ($user) {
            // $user = $user->getId();
            $user = $entityManager->getRepository(User::class)->find($user);
        }
        if ($this->isGranted("ROLE_ADMIN_ADMIN") || $this->isGranted("ROLE_CLIENTS")) {
            $agence = $entityManager->getRepository(Agence::class)->findAll();
        }else{
            $user = $this->getUser();
            $employer = $entityManager->getRepository(Employer::class)->findOneBy(['user' => $user]);
            $agence = $entityManager->getRepository(Agence::class)->findBy(['id'=>$employer->getAgence()->getId()]);
        }
        
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
        if ($user) {
            $this->redirectToRoute('app_logout');
        }
        
        if ($id == 0) {
            $agence = $entityManager->getRepository(Agence::class)->findAll();
            $temoporayagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
            if ($temoporayagence) {
                $temoporayagence->setGenerale(1);
                $entityManager->flush();
            }else{
                $idagence = $entityManager->getRepository(Agence::class)->find(1);
                $temoporayagence = new TempAgence();
                $temoporayagence->setUser($user);
                $temoporayagence->setAgence($idagence);
                $temoporayagence->setGenerale(1);
                $entityManager->persist($temoporayagence);
                $entityManager->flush();
            }
            
        }else{
            $agence = $entityManager->getRepository(Agence::class)->findAll();
            $temoporayagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
            if ($temoporayagence) {
                $idagence = $entityManager->getRepository(Agence::class)->find($id);
                $temoporayagence->setGenerale(0);
                $temoporayagence->setAgence($idagence);
                $entityManager->flush();
            }else{
                $idagence = $entityManager->getRepository(Agence::class)->find($id);
                $temoporayagence = new TempAgence();
                $temoporayagence->setUser($user);
                $temoporayagence->setAgence($idagence);
                $temoporayagence->setGenerale(0);
                $entityManager->persist($temoporayagence);
                $entityManager->flush();
            }
        }

        $produi = $entityManager->getRepository(Produit::class)->findAll();
        $temoporayagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        $agence = $temoporayagence->getAgence();
        $client = $entityManager->getRepository(Vente::class)->findBy20FirstClient($agence);
        $produitfacturer = $entityManager->getRepository(Facture::class)->findByProduitplusVendu($agence);

        return $this->render('home/dashboard.html.twig', [
            'agence' => $agence,
            'prosuits' => $produi,
            'clients' => $client,
            'produitvendu' => $produitfacturer
        ]);
    }

    #[Route('/home/dashboardA/{id}', name: 'app_home_dashboardA')]
    public function dashboardA(EntityManagerInterface $entityManager,int $id): Response
    {
        $user = $this->getUser();
        $agence = 0;
        if (!$this->getUser()) {
            $this->redirectToRoute('app_logout');
        }
        if ($id == 0) {
            $agence = $entityManager->getRepository(Agence::class)->findAll();
            $temoporayagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
            $agence = $temoporayagence->getAgence();
            if ($temoporayagence) {
                $temoporayagence->setGenerale(1);
                $entityManager->flush();
            }else{
                $idagence = $entityManager->getRepository(Agence::class)->find(1);
                $temoporayagence = new TempAgence();
                $temoporayagence->setUser($user);
                $temoporayagence->setAgence($idagence);
                $temoporayagence->setGenerale(1);
                $entityManager->persist($temoporayagence);
                $entityManager->flush();
            } 
            
        }else{
            $temoporayagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
            $agence = $temoporayagence->getAgence();
            if ($temoporayagence) {
                $idagence = $entityManager->getRepository(Agence::class)->find($id);
                $temoporayagence->setGenerale(0);
                $temoporayagence->setAgence($idagence);
                $entityManager->flush();
            }else{
                $idagence = $entityManager->getRepository(Agence::class)->find($id);
                $temoporayagence = new TempAgence();
                $temoporayagence->setUser($user);
                $temoporayagence->setAgence($idagence);
                $temoporayagence->setGenerale(0);
                $entityManager->persist($temoporayagence);
                $entityManager->flush();
            }
            
        }
        $produi = $entityManager->getRepository(ProduitA::class)->findAll();
        $client = $entityManager->getRepository(VenteA::class)->findBy20FirstClient($agence);
        $dateexpiration = $entityManager->getRepository(ProduitA::class)->findByDatePeremption($agence);
        $agence = $entityManager->getRepository(Agence::class)->findAll();
        $produitplusvendu = $entityManager->getRepository(FactureA::class)->FindByProduitPlusVendu();
        $doublon = $entityManager->getRepository(ProduitA::class)->findByDoublon();
        $expiration = $entityManager->getRepository(ProduitA::class)->findBy(['expiration' => '0']);
        $lots = $entityManager->getRepository(Lots::class)->findBy(['expiration' => '0']);
        $peramption = $entityManager->getRepository(ProduitA::class)->findByDateExpiration(6);
        $lotsperemtion = $entityManager->getRepository(Lots::class)->findByDateExpirationLots(6);
        
        if (empty($expiration)) {
            $expiration = [];
        }  # code...
        if ($doublon) {
            $doublon = [];
        }
        if ($peramption) {
            $peramption =[];
        }
        return $this->render('home/dashboardA.html.twig', [
            'agence' => $agence,
            'produits' => $produi,
            'produitplusvendu' => $produitplusvendu,
            'doublons' => $doublon,
            'expiration' => $expiration,
            'lots' => $lots,
            'perantions' => $peramption,
            'lotsperemtion' => $lotsperemtion,
            'clients' => $client,
            'dateexpiration' => $dateexpiration,
        ]);
    }
}