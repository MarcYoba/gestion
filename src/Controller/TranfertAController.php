<?php

namespace App\Controller;

use App\Entity\MagasinA;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use App\Entity\TransfertA;
use App\Form\TransfertAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TranfertAController extends AbstractController
{
    #[Route('/tranfert/a/{id}', name: 'app_tranfert_a')]
    public function index(EntityManagerInterface $em,Request $request,int $id): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $transfert = new TransfertA();
        
        $form = $this->createForm(TransfertAType::class,$transfert);
        $form->handleRequest($request);
        $magasin = $em->getRepository(MagasinA::class)->findOneBy(['id' => $id]);
        $produits = $em->getRepository(ProduitA::class)->findOneBy(['id' => $magasin->getProduit()->getId()]);
        $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);

        $numero = str_pad(random_int(0, 99), 3, '0', STR_PAD_LEFT);
        $datePart = date('Ymd');
        $lettres = chr(random_int(65, 90)) . chr(random_int(65, 90));
            
        $matricule = $numero . $datePart . $lettres;
        $transfert->setMatricule($matricule);

        if ($form->isSubmitted() && !$form->isValid()) {
            //$errors = $form->getErrors(true);
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
            return $this->redirectToRoute('app_transfert_a_list');
        }
        return $this->render('transfert_a/index.html.twig', [
            'form' => $form->createView(),
            'magasins' => $magasin,
            'matricule' => $matricule,
            'Agences' => $agence->getAgence(),
        ]);
    }
    #[Route('/transfert/a/list', name: 'app_transfert_a_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $transferts = $em->getRepository(TransfertA::class)->findBy(['agence' => $agence->getAgence()]);

        return $this->render('transfert_a/list.html.twig', [
            'transferts' => $transferts,
        ]);
    }
    #[Route('/transfert/a/detransfert/{id}', name: 'app_transfert_a_edit')]
    public function edit(EntityManagerInterface $em,TransfertA $transfert): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        if ($transfert) {
            if ($transfert->getUser() == $this->getUser()) {
                $produit = $em->getRepository(ProduitA::class)->findOneBy(['id' => $transfert->getProduit()->getId()]);
                $produit->setQuantite($produit->getQuantite()-$transfert->getQuantite());
                $em->persist($produit);

                $magasin = $em->getRepository(MagasinA::class)->findOneBy(['produit' => $transfert->getProduit()->getId()]);
                $magasin->setQuantite($magasin->getQuantite()+$transfert->getQuantite());
                $em->persist($magasin);
                
                $transfert->setStatut("Détransféré");

                $em->persist($transfert);
                $em->flush();
            }
        }
        
        return $this->redirectToRoute('app_transfert_a_list');
    }

    #[Route('/transfert/a/valider/{id}', name: 'app_transfert_a_valider')]
    public function valider(EntityManagerInterface $em,TransfertA $transfert,Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
         $data = $request->request->all('transferts');
        if ($data) {
            if ($transfert) {
                if ($transfert->getUser() == $this->getUser()) {
                    foreach ($data as $key => $value) {
                        if (isset($value["transferer"]) == "Transféré") {
                            $produit = $em->getRepository(ProduitA::class)->findOneBy(['id' => $value['produit']]);
                            $produit->setQuantite($produit->getQuantite()+$transfert->getQuantite());
                            $em->persist($produit);
                            $transfert->setStatut($value["transferer"]);
                            $em->persist($transfert);
                            $em->flush();
                        }else{
                           $transfert->setStatut($value["Annuler"]);
                            $em->persist($transfert);
                            $em->flush(); 
                        }
                    }
                }
                return $this->redirectToRoute('app_transfert_a_list'); 
            }
        }
        return $this->render('transfert_a/valider.html.twig', [
            'transferts' => $transfert,
        ]);
    }

    #[Route('/transfert/a/details/{id}', name: 'app_transfert_a_details')]
    public function details(EntityManagerInterface $em,TransfertA $transfert) : Response {
        $transferts = $em->getRepository(TransfertA::class)->findBy(['matricule' => $transfert->getMatricule()]);

        return $this->render('transfert_a/detailles.html.twig', [
            'transferts' => $transferts,
        ]); 
    }
}
