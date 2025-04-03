<?php

namespace App\Controller;

use App\Entity\Vente;

use App\Entity\Facture;
use app\Entity\Clients;
use App\Entity\Produit;
use App\Entity\Quantiteproduit;
use App\Form\VenteType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VenteController extends AbstractController
{
    #[Route('/vente/create', name: 'app_vente')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vente = new Vente();
        $form = $this->createForm(VenteType::class, $vente);
        $form->handleRequest($request);
        $produit = $entityManager->getRepository(Produit::class)->findAll();

        if($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $data = json_decode($request->getContent(), true);
            if (isset($data)) {
                $lignevente = end($data);
                array_pop($data);
                $type = "";
                $date = null;
                $heure = date("H:i:s");
                $idclient = $lignevente['client'];
                $user = $this->getUser();
                $client = $entityManager->getRepository(Clients::class)->find($idclient);
                $vente->setUser($user);
                $vente->setClient($client);
                if($lignevente['momo'] > 0) 
                {
                    $type = "momo";
                } 
                if($lignevente['credit'] > 0)
                {
                    if(empty($type)){
                        $type = "credit";
                    }else{
                        $type += "credit";
                    }
                }
                if($lignevente['cash'] > 0)
                {
                    if(empty($type)){
                        $type = "cash";
                    }else{
                        $type += "cash";
                    }
                }
                if ($lignevente['Banque'] > 0) {  
                  if(empty($type)){
                    $type = "banque";
                  }else{
                    $type += "banque";
                  }
                }

                if(empty($lignevente['date']))
                {
                    $date = new \DateTimeImmutable();
                    $vente->setCreatedAt($date);
                }else{
                    $date = new \DateTimeImmutable($lignevente['date']);
                    $vente->setCreatedAt($date);
                }

                $vente->setType($type);
                $vente->setQuantite($lignevente['Qttotal']);
                $vente->setPrix($lignevente['Total']);
                $vente->setEsperce($lignevente['esperce']);
                $vente->setAliment($lignevente['aliment']);
                $vente->setHeure($heure);
                $vente->setStatusvente($lignevente['statusvente']);
                $vente->setMontantbanque($lignevente['Banque']);
                $vente->setMontantcash($lignevente['cash']);
                $vente->setMontantcredit($lignevente['credit']);
                $vente->setMontantmomo($lignevente['momo']);
                $vente->setReduction($lignevente['reduction']);

                $entityManager->persist($vente);
                
                    foreach ($data as $key => $value) {
                        $reste = 0;
                        $facture = new Facture();
                        $quantitereste = new Quantiteproduit;

                        $produit = $entityManager->getRepository(Produit::class)->find($value['produit']);
                        $facture->setQuantite($value['quantite']);
                        $facture->setPrix($value['prix']);
                        $facture->setMontant($value['total']);
                        $facture->setTypepaiement($type);
                        
                        if(empty($value['date'])){
                            $date = new \DateTimeImmutable;
                            $facture->setCreatedAt($date );
                        }else{
                            $date = new \DateTimeImmutable($value['date']);
                            $facture->setCreatedAt($date );
                        }

                        if ($produit) {
                            $reste = $produit->getQuantite();
                            $reste = $reste - $value['quantite'];
                           $quantitereste->setQuantiteRestant($reste);
                           $quantitereste->setCreatedAt($date);
                        }

                        $produit ->setQuantite($reste);

                        $quantitereste->setUser($user);
                        $quantitereste->setVente($vente);
                        $quantitereste->setProduit($produit);
                        $facture->setUser($user);
                        $facture->setClient($client);
                        $facture->setProduit($produit);
                        $facture->setVente($vente);
                        
                        $entityManager->persist($facture);
                        $entityManager->persist($quantitereste);
                     }
                
                try {
                    $entityManager->flush();
                } catch (\Exception $e) {
                    return $this->json([
                        'error' => $e->getMessage(),
                        'success' => false
                        ]
                        , 500);
                }

                    return $this->json([
                        'success'=>true,
                        'message' =>$vente->getId(),
                        ]
                        , 200);
            }
        }
        

        return $this->render('vente/index.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    }

    #[Route('/vente/list', name: 'vente_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $vente = $entityManager->getRepository(Vente::class)->findAll();
        $produit = $entityManager->getRepository(Produit::class)->findAll();
        $client = $entityManager->getRepository(Clients::class)->findAll();
        return $this->render('vente/list.html.twig', [
            'vente' => $vente,
            'produit' => $produit,
            'client' => $client,
            ]);
    }

    
}