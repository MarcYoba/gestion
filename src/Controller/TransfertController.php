<?php

namespace App\Controller;

use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\TempAgence;
use App\Entity\Transfert;
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
        if ($form->isSubmitted() && $form->isValid()) {
            $quantite = $form->get('quantite')->getData();
            $produit = $form->get('produit')->getData();
            
            $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            $magasin = $em->getRepository(Magasin::class)->findOneBy(['id' => $id]);
            $produits = $em->getRepository(Produit::class)->findOneBy(['id' => $produit->getId()]);
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
            return $this->redirectToRoute('app_transfert_list');
        }
        return $this->render('transfert/index.html.twig', [
            'form' => $form->createView(),
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
}
