<?php

namespace App\Controller;

use App\Entity\VenteA;
use App\Entity\Clients;
use App\Form\VenteAType;
use App\Entity\FactureA;
use App\Entity\ProduitA;
use App\Entity\QuantiteproduitA;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
                    $client = $em->getRepository(Clients::class)->findOneBy(["id" => $idclient]);
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
                    $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
                    $vente->setAgence($tempagence->getAgence());
                    $vente->setUser($user);

                    $em->persist($vente);

                    foreach ($data as $key => $value) {
                        $facture = new FactureA();
                        $quantiterestant = new QuantiteproduitA();

                        $produit = $em->getRepository(ProduitA::class)->findOneBy(["nom" => $value['produit']]);
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
                        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
                        $facture->setAgence($tempagence->getAgence());

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
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $id = $tempagence->getAgence()->getId();
        $ventes = $em->getRepository(VenteA::class)->findAll(["agence" => $id]);
        $produit = $em->getRepository(ProduitA::class)->findAll(["agence" => $id]);
        $client = $em->getRepository(Clients::class)->findAll();
        return $this->render('vente_a/list.html.twig', [
            'vente' => $ventes,
            'produit' => $produit,
            'client' => $client,
        ]);
    }

    #[Route('/vente/a/edit', name: 'vente_a_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {

        try {
            $data = json_decode($request->getContent(), true);
            $id = $data;
            $vente = $entityManager->getRepository(VenteA::class)->find($id);
            if ($vente) {
                $facture = $vente->getFactureAs();
                if (!$facture->isEmpty()) {
                    $lignevente = [];
                    foreach ($facture as $fact) {
                        $lignevente[] = [
                            'client' => $fact->getClient()->getId(),
                            'produit' => $fact->getProduit()->getNom(),
                            'quantite' => $fact->getQuantite(),
                            'prix' => $fact->getPrix(),
                            'montant' => $fact->getMontant(),
                            'typepaiement' => $fact->getType(),
                            'id' => $fact->getId(),
                            'idvente' => $vente->getId(),
                            // 'prixtotal' => $vente->getPrix(),
                            // 'quantiteTotal' => $vente->getQuantite(),
                        ];
                        //array_push($data, $lignevente);
                    }
                    return $this->json(
                        $lignevente,
                    );
                }else{
                    return $this->json([
                        'facture' =>0,
                    ]);
                }
               
                
                 
            }
            return $this->json([
                'success' => false,
                'message' => 'Vente Not found',
            ], 200);
        
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage(),
                'success' => false
                ]
                , 500);
        }
        
    }

    #[Route("/vente/a/update", name:'vente_a_update')]
    public function update(Request $request, EntityManagerInterface $em): JsonResponse {
        $user = $this->getUser();
        try {
            $data = json_decode($request->getContent(),true);
            if (!empty($data)) {
                $idvente = end($data);
                array_pop($data);
                $vente = $em->getRepository(VenteA::class)->find($idvente["idvente"]);
                $facture = $em->getRepository(FactureA::class)->findBy(["vente" => $vente]);

                if ($facture) {
                    $lignevente = end($data);
                    array_pop($data);
                    $type ="";
                    if ($lignevente["Banque"] > 0) {
                        $type = "BANQUE";
                    }
                    if ($lignevente["cash"] > 0) {
                        if (empty($type)) {
                            $type = "CASH";
                        }else{
                            $type += "CASH";
                        }
                    }
                    if ($lignevente["momo"] > 0) {
                        if (empty($type)) {
                            $type = "OM";
                        }else{
                            $type += "OM";
                        }
                    }
                    if ($lignevente["credit"] > 0) {
                        if (empty($type)) {
                            $type = "CREDIT";
                        }else{
                            $type += "CREDIT";
                        }
                    }

                    $vente->setType($type);
                    $vente->setQuantite($lignevente["Qttotal"]);
                    $vente->setPrix($lignevente["Total"]);

                    $vente->setCash($lignevente["cash"]);
                    $vente->setBanque($lignevente["Banque"]);
                    $vente->setCredit($lignevente["credit"]);
                    $vente->setMomo($lignevente["momo"]);
                    $vente->setReduction($lignevente["reduction"]);
                    $vente->setStatut($lignevente["statusvente"]);

                    if (!empty($lignevente["date"])) {
                        $vente->setCreateAt(new \DateTimeImmutable($lignevente["date"]));
                    }

                    $value = new FactureA();
                    $produit = new ProduitA(); 
                    foreach ($facture as $key => $value) {
                        $lignefacture = $data[0];

                        $produit = $em->getRepository(ProduitA::class)->findOneBy(["nom" => $lignefacture["produit"]]);
                        
                        if ($produit) {
                            
                            if ($produit->getId() == $value->getProduit()->getId()) {
                                $quantite = $lignefacture["quantite"] - $value->getQuantite() ;
                                if ($quantite != 0) {
                                    $quantite = ((-1 * $quantite ) + $produit->getQuantite());
                                    $value->setQuantite($lignefacture["quantite"]);
                                    $value->setMontant($lignefacture["total"]);
                                    $produit->setQuantite($quantite);
                                    if ($lignefacture["quantite"] == 0) {
                                        $value->setPrix(0);
                                    }else{
                                        $value->setPrix($lignefacture["prix"]);
                                    }
                                }
                            }else{
                                
                                $autreproduit = $em->getRepository(ProduitA::class)->find($value->getProduit());
                                $autreproduit->setQuantite($autreproduit->getQuantite() + $value->getQuantite());

                                $value->setProduit($produit);
                                $value->setPrix($lignefacture["prix"]);
                                $value->setMontant($lignefacture["total"]);
                                $value->setQuantite($lignefacture["quantite"]);

                                $produit->setQuantite(($produit->getQuantite() - $lignefacture["quantite"]));
                                $em->flush();
                            }
                        }
                        
                        array_shift($data);
                    }

                    if (!empty($data)) {
                        foreach ($data as $key => $newfacture) {
                           $produit = new ProduitA();
                           $facture = new FactureA();
                           $quantiterestant = new QuantiteproduitA();

                           $produit = $em->getRepository(ProduitA::class)->findOneBy(["nom" => $newfacture["produit"]]);
                            $client = $em->getRepository(Clients::class)->find($newfacture["client"]);

                            $facture->setUser($this->getUser());
                            $facture->setProduit($produit);
                            $facture->setClient($client);

                            $facture->setQuantite($newfacture["quantite"]);
                            $quantite = ($produit->getQuantite() - $newfacture["quantite"]);
                            $produit->setQuantite($quantite);
                            $facture->setMontant($newfacture["total"]);
                            $facture->setPrix($newfacture["prix"]);
                            $facture->setType($type);
                            $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
                            $facture->setAgence($tempagence->getAgence());

                            if (empty($newfacture["date"])) {
                                $facture->setCreateAt(new \DateTimeImmutable());
                            }else{
                                $facture->setCreateAt(new \DateTimeImmutable($newfacture["date"]));
                            }
    
                            $facture->setVente($vente);
                            $quantiterestant->setUser($this->getUser());
                            $quantiterestant->setProduit($produit);
                            $quantiterestant->setVente($vente);
                            $quantiterestant->setQuantite($quantite);
                            $quantiterestant->setCreateAt(new \DateTimeImmutable());
    
                            $em->persist($quantiterestant);
                            $em->persist($facture);

                        }
                    }
                }

                $em->flush();
            }
           return $this->json([
            'success' => true,
            'message' => $vente->getId(),
            ],
            200
            );
        } catch (\Throwable $th) {
           return $this->json([
                'success' => false,
                'message' => $th
            ],
            500);
        }
    }

    /**
     * @Route(path="/vente/dashboard/A", name="vente_dashboard")
     */
    public function dashboardVenteA(EntityManagerInterface $entityManager): JsonResponse
    {
        $ventes = $entityManager->getRepository(VenteA::class)->findAll();
        $totalVente = 0;

        foreach ($ventes as $vente) {
            $totalVente += $vente->getPrix();
        }

        // If you want to return a JSON response
        return new JsonResponse([
            'ventes' => count($ventes),
            'totalVente' => $totalVente,
        ]);
    }
}
