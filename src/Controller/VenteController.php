<?php

namespace App\Controller;

use App\Entity\Vente;
use App\Entity\Facture;
use app\Entity\Clients;
use App\Entity\Employer;
use App\Entity\Produit;
use App\Entity\Quantiteproduit;
use App\Entity\TempAgence;
use App\Form\VenteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        if($request->isXmlHttpRequest() || $request->getContentType()==='json') {
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

                    $vente->setAgence($tempagence->getAgence());
                    $entityManager->persist($vente);
                    
                        foreach ($data as $key => $value) {
                            $reste = 0;
                            $facture = new Facture();
                            $quantitereste = new Quantiteproduit;

                            $produit = $entityManager->getRepository(Produit::class)->findOneBy(["nom" =>$value['produit']]);
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
                            $produit->setQuantite($reste);
                            $quantitereste->setQuantiteRestant($reste);
                            $quantitereste->setCreatedAt($date);
                            }

                            $quantitereste->setUser($user);
                            $quantitereste->setVente($vente);
                            $quantitereste->setProduit($produit);
                            $facture->setUser($user);
                            
                            $facture->setAgence($tempagence->getAgence());

                            $facture->setClient($client);
                            $facture->setProduit($produit);
                            $facture->setVente($vente);
                            
                            $entityManager->persist($facture);
                            $entityManager->persist($quantitereste);
                        }
                    
                    
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
        $user = $this->getUser();
        $vente = new Vente();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user"=>$user]);
        $id = $tempagence->getAgence()->getId();
        if ($tempagence->isGenerale()== 1) {
            $vente = $entityManager->getRepository(Vente::class)->findAll();
        }else{
            $vente = $entityManager->getRepository(Vente::class)->findBy(["agence" => $id]);
        }
        $produit = $entityManager->getRepository(Produit::class)->findAll();
        $client = $entityManager->getRepository(Clients::class)->findAll();
        return $this->render('vente/list.html.twig', [
            'vente' => $vente,
            'produit' => $produit,
            'client' => $client,
            ]);
    }

    #[Route('/vente/edit', name: 'vente_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {

        try {
            $data = json_decode($request->getContent(), true);
            $id = $data;
            $vente = $entityManager->getRepository(Vente::class)->find($id);
            if ($vente) {
                $facture = $vente->getFacture();
                if (!$facture->isEmpty()) {
                    $lignevente = [];
                    foreach ($facture as $fact) {
                        $lignevente[] = [
                            'client' => $fact->getClient()->getId(),
                            'produit' => $fact->getProduit()->getNom(),
                            'quantite' => $fact->getQuantite(),
                            'prix' => $fact->getPrix(),
                            'montant' => $fact->getMontant(),
                            'typepaiement' => $fact->getTypepaiement(),
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

    #[Route('/vente/update', name: 'vente_update')]
    public function update(Request $request,EntityManagerInterface $em): JsonResponse 
    {
        $user = $this->getUser();
       try {
            $data = json_decode($request->getContent(), true);
            if (!empty($data)) {
                $idvente = end($data);
                array_pop($data);
                $donnevente = end($data);
                array_pop($data);
                $vente = $em->getRepository(Vente::class)->find($idvente["idvente"]);
                $facture = $em->getRepository(Facture::class)->findBy(['vente' => $vente]);

                $type ="";
                if ($donnevente["Banque"]>0) {
                    $type = "BANQUE";
                }
                if ($donnevente["cash"]>0) {
                    if (empty($type)) {
                        $type = "CASH";
                    }else{
                        $type += "CASH";
                    }
                }
                if ($donnevente["credit"]>0) {
                    if (empty($type)) {
                        $type = "CREDIT";
                    }else{
                        $type += "CREDIT";
                    }
                }
                if ($donnevente["momo"]>0) {
                    if (empty($type)) {
                        $type = "OM";
                    }else{
                        $type += "OM";
                    }
                }

                $vente->setMontantbanque($donnevente["Banque"]);
                $vente->setMontantcash($donnevente["cash"]);
                $vente->setMontantcredit($donnevente["credit"]);
                $vente->setMontantmomo($donnevente["momo"]);

                $vente->setType($type);
                $vente->setAliment($donnevente["aliment"]);
                $vente->setEsperce($donnevente["esperce"]);
                $vente->setStatusvente($donnevente["statusvente"]);

                $vente->setQuantite($donnevente["Qttotal"]);
                $vente->setPrix($donnevente["Total"]);
                $vente->setReduction($donnevente["reduction"]);

                if (!empty($donnevente["date"])) {
                    $vente->setCreatedAt( new \DateTimeImmutable($donnevente["date"]));
                }

                $produit = new Produit(); 
                $value = new Facture();
                foreach ($facture as $key => $value) {
                    $lignefacture = $data[0];
                    
                    $produit = $em->getRepository(Produit::class)->findOneBy(['nom' => $lignefacture["produit"]]);

                    if ($produit) {
                        
                        if ($produit->getId() == $value->getProduit()->getId()) {
                            
                            $quantite = $lignefacture["quantite"]-$value->getQuantite();
                            if ($quantite != 0) {
                                $quantite = ((-1*$quantite)+$produit->getQuantite());
                                $produit->setQuantite($quantite);
                                $value->setQuantite($lignefacture["quantite"]); 
                                $value->setMontant($lignefacture["total"]);
                                if ($lignefacture["quantite"] == 0) {
                                    $value->setPrix(0);
                                }else{
                                    $value->setPrix($lignefacture["prix"]);
                                }
                            }
                            
                            
                        }else{
                            $autreProduit = $em->getRepository(Produit::class)->find($value->getProduit());
                            $autreProduit->setQuantite(($autreProduit->getQuantite() + $value->getQuantite()));

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
                        $facture = new Facture();
                        $quantitereste = new Quantiteproduit();
                        $produit = $em->getRepository(Produit::class)->findOneBy(["nom" => $newfacture["produit"]]);
                        $client = $em->getRepository(Clients::class)->find($newfacture["client"]);

                        $facture->setUser($this->getUser());
                        $facture->setProduit($produit);
                        $facture->setClient($client);

                        $facture->setQuantite($newfacture["quantite"]);
                        $quantite = ($produit->getQuantite() - $newfacture["quantite"]);
                        $produit->setQuantite($quantite);
                        $facture->setMontant($newfacture["total"]);
                        $facture->setPrix($newfacture["prix"]);
                        $facture->setTypepaiement($type);
                        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
                        $facture->setAgence($tempagence->getAgence());

                        if (empty($newfacture["date"])) {
                            $facture->setCreatedAt(new \DateTimeImmutable());
                        }else{
                            $facture->setCreatedAt(new \DateTimeImmutable($newfacture["date"]));
                        }

                        $facture->setVente($vente);
                        $quantitereste->setUser($this->getUser());
                        $quantitereste->setProduit($produit);
                        $quantitereste->setVente($vente);
                        $quantitereste->setQuantiteRestant($quantite);
                        $quantitereste->setCreatedAt(new \DateTimeImmutable());

                        $em->persist($quantitereste);
                        $em->persist($facture);
                    }
                }  
                
                $em->flush();
            }
            return $this->json([
                'success'=>true,
                'message' =>$vente->getId(),
                ]
                , 200);
       } catch (\Throwable $th) {
        return $this->json([
            'error' => $th->getMessage(),
            'success' => false
            ]
            , 500);
       } 
    }

    /**
     * @Route(path="/vente/dashboard", name="vente_dashboard")
     */
    public function dashboardVente(EntityManagerInterface $entityManager): JsonResponse
    {
        $ventes = $entityManager->getRepository(Vente::class)->findAll();
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

    #[Route(path:"/vente/rapport/semaine/prix", name: "vente_rapport_semain_prix")]
    public function Vente_semain_prix(EntityManagerInterface $entityManager): Response
    {
        $datesSemaine = [];
        $today = new \DateTime();
        $monday = clone $today;
        $monday->modify('monday this week');
        for ($i = 0; $i < 7; $i++) {
            $date = clone $monday;
            $date->modify("+$i days");
            $datesSemaine[] = $entityManager->getRepository(Vente::class)->findVentesByWeekWithDaysPrix($date->format('Y-m-d'));
        }
        return $this->json(['message'=> $datesSemaine]);
    }

    #[Route(path:"/vente/rapport/semaine/quantite", name: "vente_rapport_semain_quantite")]
    public function Vente_semain_quantite(EntityManagerInterface $entityManager): Response
    {
         $datesSemaine = [];
        $today = new \DateTime();
        $monday = clone $today;
        $monday->modify('monday this week');
        for ($i = 0; $i < 7; $i++) {
            $date = clone $monday;
            $date->modify("+$i days");
            $datesSemaine[] = $entityManager->getRepository(Vente::class)->findVentesByWeekWithDaysQuantite($date->format('Y-m-d'));
        }

        return $this->json(['message'=> $datesSemaine]);
    }
    
}