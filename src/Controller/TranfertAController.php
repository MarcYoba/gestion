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
        if ($form->isSubmitted() && $form->isValid()) {
            $quantite = $form->get('quantite')->getData();
            $produit = $form->get('produit')->getData();
            
            $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            $magasin = $em->getRepository(MagasinA::class)->findOneBy(['id' => $id]);
            $produits = $em->getRepository(ProduitA::class)->findOneBy(['id' => $produit->getId()]);
            $reste = $magasin->getQuantite()-$quantite;
            $magasin->setQuantite($reste);


            $numero = str_pad(random_int(0, 99), 3, '0', STR_PAD_LEFT);
            $datePart = date('Ymd');
            $lettres = chr(random_int(65, 90)) . chr(random_int(65, 90));
            
            $matricule = $numero . $datePart . $lettres;
            
            $transfert->setUser($user);
            $transfert->setReste($reste);
            $transfert->setAgence($agence->getAgence());
            $transfert->setStatut("Transféré");
            $transfert->setMatricule($matricule);

            $produits->setQuantite($produits->getQuantite()+$quantite);

            $em->persist($magasin);
            $em->persist($transfert);
            $em->persist($produits);
            $em->flush();
            return $this->redirectToRoute('app_transfert_a_list');
        }
        return $this->render('transfert_a/index.html.twig', [
            'form' => $form->createView(),
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
        
        return $this->redirectToRoute('app_transfert_a_list');
    }
}
