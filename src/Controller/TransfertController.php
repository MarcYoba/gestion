<?php

namespace App\Controller;

use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\TempAgence;
use App\Entity\Transfert;
use App\Entity\User;
use App\Form\TransfertType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransfertController extends AbstractController
{
    #[Route('/transfert/create/{id}', name: 'app_transfert')]
    public function index(EntityManagerInterface $em,Request $request, int $id): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }

        $transfert = new Transfert();
        $form = $this->createForm(TransfertType::class,$transfert);
        $form->handleRequest($request);
        $magasin = $em->getRepository(Magasin::class)->findOneBy(['id' => $id]);
        $produits = $em->getRepository(Produit::class)->findOneBy(['id' => $magasin->getProduit()->getId()]);
        $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);

        $numero = str_pad(random_int(0, 99), 3, '0', STR_PAD_LEFT);
        $datePart = date('Ymd');
        $lettres = chr(random_int(65, 90)) . chr(random_int(65, 90));
            
        $matricule = $numero . $datePart . $lettres;
        $transfert->setMatricule($matricule);

        if ($form->isSubmitted() && !$form->isValid()) {
            $quantite = $form->get('quantite')->getData();
            $reste = $magasin->getQuantite()-$quantite;
            $magasin->setQuantite($reste);

            $transfert->setUser($user);
            $transfert->setReste($reste);
            $transfert->setAgence($agence->getAgence());
            $transfert->setStatut("Attente");
            $transfert->setProduit($produits);

            $em->persist($magasin);
            $em->persist($transfert);
            $em->flush();
            return $this->redirectToRoute('app_transfert_list');
        }
        return $this->render('transfert/index.html.twig', [
            'form' => $form->createView(),
            'magasins' => $magasin,
            'matricule' => $matricule,
        ]);
    }
    #[Route('/transfert/valider/{id}', name: 'app_transfert_valider')]
    public function valider(EntityManagerInterface $em,Transfert $transfert,Request $request): Response
    {
        $user = $this->getUser();
        $user = $em->getRepository(User::class)->findOneBy(['id' => $user]);
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
         $data = $request->request->all('transferts');
        if ($data) {
            if ($transfert) {
                
                    foreach ($data as $key => $value) {
                        if (isset($value["transferer"]) == "Transféré") {
                            $produit = $em->getRepository(Produit::class)->findOneBy(['id' => $value['produit']]);
                            $produit->setQuantite($produit->getQuantite()+$transfert->getQuantite());
                            $em->persist($produit);
                            $transfert->setStatut($value["transferer"]);
                            $transfert->setEmployer($user->getEmployer());
                            $em->persist($transfert);
                            $em->flush();
                        }else{
                           $transfert->setStatut($value["Annuler"]);
                            $em->persist($transfert);
                            $em->flush(); 
                        }
                    }
                
                return $this->redirectToRoute('app_transfert_list'); 
            }
        }
        return $this->render('transfert/valider.html.twig', [
            'transferts' => $transfert,
        ]);
    }
    #[Route('/transfert/list', name: 'app_transfert_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $transferts = $em->getRepository(Transfert::class)->findBy(['agence' => $agence->getAgence()]);

        return $this->render('transfert/list.html.twig', [
            'transferts' => $transferts,
        ]);
    }
    #[Route('/transfert/list/direction', name: 'app_transfert_list_direction')]
    public function listDirection(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $transferts = $em->getRepository(Transfert::class)->findAll();

        return $this->render('transfert/list_direction.html.twig', [
            'transferts' => $transferts,
        ]);
    }
    #[Route('/transfert/detransfert/{id}', name: 'app_transfert_edit')]
    public function edit(EntityManagerInterface $em,Transfert $transfert): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        if ($transfert) {
            $produit = $em->getRepository(Produit::class)->findOneBy(['id' => $transfert->getProduit()->getId()]);
            $produit->setQuantite($produit->getQuantite()-$transfert->getQuantite());
            $em->persist($produit);

            $magasin = $em->getRepository(Magasin::class)->findOneBy(['produit' => $transfert->getProduit()->getId()]);
            $magasin->setQuantite($magasin->getQuantite()+$transfert->getQuantite());
            $em->persist($magasin);
            
            $transfert->setStatut("Détransféré");

            $em->persist($transfert);
            $em->flush();
        }
        
        return $this->redirectToRoute('app_transfert_list');
    }

    #[Route('/transfert/details/{id}', name: 'app_transfert_details')]
    public function details(EntityManagerInterface $em,int $id) : Response {
        $transfert = $em->getRepository(Transfert::class)->findOneBy(['id' => $id]);
        if (!$this->getUser() || !$transfert) {
            return $this->redirectToRoute('app_logout');
        }
        $transferts = $em->getRepository(Transfert::class)->findBy(['matricule' => $transfert->getMatricule()]);
        
        $route = $em->getRepository(Transfert::class)->findOneBy(['id' => ($id-1)]);
        if ($route) {
            $route = $route->getId();
        }else{
            $route = -1;
        }
        $next = $em->getRepository(Transfert::class)->findOneBy(['id' => ($id+1)]);
        if ($next) {
            $next = $next->getId();
        }else{
            $next = -1;
        }
        return $this->render('transfert/detailles.html.twig', [
            'transferts' => $transferts,
            'route' => $route,
            'next' => $next,
        ]); 
    }
}
