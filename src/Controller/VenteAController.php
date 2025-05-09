<?php

namespace App\Controller;

use App\Entity\VenteA;
use App\Entity\Clients;
use App\Form\VenteAType;
use App\Entity\FactureA;
use App\Entity\ProduitA;
use App\Entity\QuantiteproduitA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VenteAController extends AbstractController
{
    #[Route('/vente/a/create', name: 'app_vente_a')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $vente = new VenteA();
        $form = $this->createForm(VenteAType::class, $vente);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest() || $request->getContentType() === 'json') {
            $data = json_decode($request->getContent(), true);
            
            if (isset($data)) {
                try {
                    $lignevente = end($data);
                    array_pop($data);
                    $type = "";
                    $date = null;
                    $heure = date("H:i:s");
                    $idclient = $lignevente['client'];
                    $user = $this->getUser();
                    $client = $em->getRepository(Clients::class)->findOneBy(["nom" => $idclient]);
                    $vente->setUser($user);
                    $vente->setClient($client);
                    if ($lignevente['momo'] > 0) {
                        $type = "momo";
                    }
                    if ($lignevente['credit'] > 0) {
                        if (empty($type)) {
                            $type = "credit";
                        } else {
                            $type .= "credit";
                        }
                    }
                    if ($lignevente['cash'] > 0) {
                        if (empty($type)) {
                            $type = "cash";
                        } else {
                            $type .= "cash";
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
                        $vente->setCreateAt($date);
                    }else{
                        $date = new \DateTimeImmutable($lignevente['date']);
                        $vente->setCreateAt($date);
                    }

                    $vente->setType($type);
                    $vente->setHeure($heure);
                    $vente->setQuantite($lignevente['Qttotal']);
                    $vente->setPrix($lignevente['Total']);
                    $vente->setStatut($lignevente['statusvente']);
                    $vente->setBanque($lignevente['Banque']);
                    $vente->setCredit($lignevente['credit']);
                    $vente->setCash($lignevente['cash']);
                    $vente->setMomo($lignevente['momo']);
                    $vente->setReduction($lignevente['reduction']);
                    $vente->setAgence($user->getEmployer()->getAgence());
                    $vente->setUser($user);

                    $em->persist($vente);

                    foreach ($data as $key => $value) {
                        $facture = new FactureA();
                        $quantiterestant = new QuantiteproduitA();

                        $produit = $em->getRepository(ProduitA::class)->find($value['produit']);
                        $facture->setQuantite($value['quantite']);
                        $facture->setPrix($value['prix']);
                        $facture->setMontant($value['total']);
                        $facture->setType($type);

                        if(empty($value['date'])){
                            $date = new \DateTimeImmutable;
                            $facture->setCreateAt($date );
                        }else{
                            $date = new \DateTimeImmutable($value['date']);
                            $facture->setCreateAt($date );
                        }

                        if($produit)
                        {
                            $reste = $produit->getQuantite();
                            $reste = $reste - $value['quantite'];
                            $quantiterestant->setQuantite($reste);
                            $quantiterestant->setCreateAt($date);
                        }

                        $produit->setQuantite($reste);

                        $quantiterestant->setUser($user);
                        $quantiterestant->setVente($vente);
                        $quantiterestant->setProduit($produit);

                        $facture->setUser($user);
                        $facture->setAgence($user->getEmployer()->getAgence());

                        $facture->setClient($client);
                        $facture->setProduit($produit);
                        $facture->setVente($vente);

                        $em->persist($facture);
                        $em->persist($quantiterestant);

                    }

                    $em->flush();

                } catch (\Throwable $th) {
                    return $this->json([
                        'error' => $th->getMessage(),
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
        return $this->render('vente_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/vente/a/list', name: 'vente_a_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $ventes = $em->getRepository(VenteA::class)->findAll();
        $produit = $em->getRepository(ProduitA::class)->findAll();
        $client = $em->getRepository(Clients::class)->findAll();
        return $this->render('vente_a/list.html.twig', [
            'vente' => $ventes,
            'produit' => $produit,
            'client' => $client,
        ]);
    }
}
