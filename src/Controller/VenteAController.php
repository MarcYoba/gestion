<?php

namespace App\Controller;

use App\Entity\BalanceA;
use App\Entity\VenteA;
use App\Entity\Clients;
use App\Form\VenteAType;
use App\Entity\FactureA;
use App\Entity\HistoriqueA;
use App\Entity\Lots;
use App\Entity\ProduitA;
use App\Entity\QuantiteproduitA;
use App\Entity\TempAgence;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
                    if ($lignevente['om'] > 0) {
                        $type = "OM";
                    }
                    if ($lignevente['credit'] > 0) {
                        if (empty($type)) {
                            $type = "credit";
                        } else {
                            $type = $type.'/'."credit";
                        }
                    }
                    if ($lignevente['cash'] > 0) {
                        if (empty($type)) {
                            $type = "cash";
                        } else {
                            $type = $type.'/'."cash";
                        }
                    }
                    if ($lignevente['Banque'] > 0) {  
                        if(empty($type)){
                        $type = "banque";
                        }else{
                        $type = $type.'/'."banque";
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
                    $vente->setOm($lignevente['om']);
                    $vente->setEsperce($lignevente['esperce']);
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
                        if ($reste <= 0) {
                            $produit->setExpiration(1);
                            $lots = $em->getRepository(Lots::class)->findOneBy(['produit' => $produit]);
                            if ($lots) {
                                // $em->remove($lots);
                                // $em->flush();
                            }
                            
                        }
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

                    if ($lignevente['credit'] > 0) {
                        $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 4111]);
                        if ($balance) {
                            $mouvement = $balance->getMouvementDebit();
                            $mouvement = $mouvement + $lignevente['credit'];
                            $balance->setMouvementDebit($mouvement);
                            $em->persist($balance);
                            $em->flush();
                        }

                        // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 7021]);
                        // if ($balance) {
                        //     $mouvement = $balance->getMouvementCredit();
                        //     $mouvement = $mouvement + $lignevente['credit'];
                        //     $balance->setMouvementCredit($mouvement);
                        //     $entityManager->persist($balance);
                        //     $entityManager->flush();
                        // }
                    }

                    if ($lignevente['cash'] > 0) {
                        $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 5111]);
                        if ($balance) {
                            $mouvement = $balance->getMouvementDebit();
                            $mouvement = $mouvement + $lignevente['cash'];
                            $balance->setMouvementDebit($mouvement);
                            $em->persist($balance);
                            $em->flush();
                        }

                        // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 7021]);
                        // if ($balance) {
                        //     $mouvement = $balance->getMouvementCredit();
                        //     $mouvement = $mouvement + $lignevente['cash'];
                        //     $balance->setMouvementCredit($mouvement);
                        //     $entityManager->persist($balance);
                        //     $entityManager->flush();
                        // }
                    }

                    if ($lignevente['Banque'] > 0) {
                        $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 5121]);
                        if ($balance) {
                            $mouvement = $balance->getMouvementDebit();
                            $mouvement = $mouvement + $lignevente['Banque'];
                            $balance->setMouvementDebit($mouvement);
                            $em->persist($balance);
                            $em->flush();
                        }
                    }

                    $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 7021]);
                        if ($balance) {
                            $mouvement = $balance->getMouvementCredit();
                            $mouvement = $mouvement + $lignevente['Banque'] + $lignevente['credit'] + $lignevente['cash'];
                            $balance->setMouvementCredit($mouvement);
                            $em->persist($balance);
                            $em->flush();
                        }

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
        $vente = new VenteA();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $id = $tempagence->getAgence()->getId();
        if ($tempagence->isGenerale()== 1) {
            $ventes = $em->getRepository(VenteA::class)->findAll();
        }else{
            $ventes = $em->getRepository(VenteA::class)->findBy(["agence" => $id]);
        }
        
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

    #[Route(path:"/vente/a/rapport/semaine/prix", name: "vente_a_rapport_semain_prix")]
    public function Vente_semain_prix(EntityManagerInterface $entityManager): Response
    {
        $datesSemaine = [];
        $today = new \DateTime();
        $monday = clone $today;
        $monday->modify('monday this week');
        for ($i = 0; $i < 7; $i++) {
            $date = clone $monday;
            $date->modify("+$i days");
            $datesSemaine[] = $entityManager->getRepository(VenteA::class)->findVentesByWeekWithDaysPrix($date->format('Y-m-d'));
        }

        return $this->json(['message'=> $datesSemaine]);
    }

    #[Route(path:"/vente/a/rapport/semaine/quantite", name: "vente_a_rapport_semain_quantite")]
    public function Vente_semain_quantite(EntityManagerInterface $entityManager): Response
    {
         $datesSemaine = [];
        $today = new \DateTime();
        $monday = clone $today;
        $monday->modify('monday this week');
        for ($i = 0; $i < 7; $i++) {
            $date = clone $monday;
            $date->modify("+$i days");
            $datesSemaine[] = $entityManager->getRepository(VenteA::class)->findVentesByWeekWithDaysQuantite($date->format('Y-m-d'));
        }

        return $this->json(['message'=> $datesSemaine]);
    }

    #[Route('/vente/a/trie', name: "vente_a_trie")]
    public function Trie(EntityManagerInterface $em, Request $request) : Response  
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $date = new \DateTime(date("Y-m-d"));
        $vente = [];
       if ($request->isMethod('POST')) {
            $produit = $request->request->All();
            
            if (!empty($produit['OM']) || !empty($produit['credit']) || !empty($produit['cash'])) {
                
                if (isset($produit['OM']) && isset($produit['credit']) && isset($produit['cash'])) {
                    
                    if(!empty($produit['date']) && !empty($produit['date2'])){
                        $vente = $em->getRepository(VenteA::class)->findRapportVenteToWeek(new \DateTime($produit['date']),new \DateTime($produit['date2']),$id);
                    }else{
                        $vente = $em->getRepository(VenteA::class)->findRapportToDay($date);
                    }
                }else if (isset($produit['credit']) && isset($produit['OM'])) {   
                    if(!empty($produit['date']) && !empty($produit['date2'])){
                        $vente = $em->getRepository(VenteA::class)->findRapportVenteToWeekCreditOm(new \DateTime($produit['date']),new \DateTime($produit['date2']),$id);
                    }else{
                        $vente = $em->getRepository(VenteA::class)->findRapportToDayCreditOm($date,$id);
                    }
                }else if (isset($produit['OM'])) {
                
                    if(!empty($produit['date']) && !empty($produit['date2'])){
                        $vente = $em->getRepository(VenteA::class)->findRapportVenteToWeekOm(new \DateTime($produit['date']),new \DateTime($produit['date2']),$id); 
                    }else{
                        $vente = $em->getRepository(VenteA::class)->findRapportToDayOm($date,$id);
                    }
                } else if (isset($produit['credit'])) {
                    
                    if(!empty($produit['date']) && !empty($produit['date2'])){
                        $vente = $em->getRepository(VenteA::class)->findRapportVenteToWeekCredit(new \DateTime($produit['date']),new \DateTime($produit['date2']),$id); 
                    }else{
                        $vente = $em->getRepository(VenteA::class)->findRapportToDayCredit($date,$id);
                    }
                } else if(isset($produit['cash'])) { 
                    if(!empty($produit['date']) && !empty($produit['date2'])){
                        $vente = $em->getRepository(VenteA::class)->findRapportVenteToWeekCash(new \DateTime($produit['date']),new \DateTime($produit['date2']),$id); 
                    }else{
                        $vente = $em->getRepository(VenteA::class)->findRapportToDayCash($date,$id);
                    }
                } else {
                    if(!empty($produit['date']) && !empty($produit['date2'])){
                        $vente = $em->getRepository(VenteA::class)->findRapportVenteToWeek(new \DateTime($produit['date']),new \DateTime($produit['date2']),$id);
                    }else{
                        $vente = $em->getRepository(VenteA::class)->findRapportToDay($date);
                    } 
                }
                
            } else {
                if(!empty($produit['date']) && !empty($produit['date2'])){
                    $vente = $em->getRepository(VenteA::class)->findRapportVenteToWeek($produit['date'],$produit['date2'],$id);
                }else{
                    $vente = $em->getRepository(VenteA::class)->findRapportToDay($date);
                }   
            }

       }

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);

        $produit = $em->getRepository(FactureA::class)->findByProduitVendu($date,$id);
        $historiqueA = [];
        foreach ($produit as $key => $value) {
            $hist = $em->getRepository(HistoriqueA::class)->findByDate($date,$value->getProduit()->getId(),$id);
            $fact = $em->getRepository(FactureA::class)->findBySommeProduit($date,$value->getProduit()->getId(),$id);
            array_push($historiqueA,[$value->getProduit()->getNom(),$hist,$fact,$value->getProduit()->getQuantite()]);
        }

        $html = $this->renderView('vente_a/tri.html.twig', [
            'ventes' => $vente,
            'date' => $date,
            'historiqueAs' => $historiqueA,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();

        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="tri.pdf"', // 'inline' pour affichage navigateur
            ]
        );   
    }

    #[Route('/vente/export/excel/a', name: 'vente_excel_a')]
    public function expert_excel(EntityManagerInterface $em) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'id');
        $sheet->setCellValue('B1', 'TYPE VENTE');
        $sheet->setCellValue('C1', 'QUANTITE');
        $sheet->setCellValue('D1', 'PRIX');
        $sheet->setCellValue('E1', 'CLIENT');
        $sheet->setCellValue('F1', 'USER');
        $sheet->setCellValue('G1', 'DATE VENTE');
        $sheet->setCellValue('H1', 'ESPERCE');
        $sheet->setCellValue('I1', 'ALIMENT');
        $sheet->setCellValue('G1', 'HEURE');
        $sheet->setCellValue('K1', 'STATUS');
        $sheet->setCellValue('L1', 'CREDIT');
        $sheet->setCellValue('M1', 'CASH');
        $sheet->setCellValue('N1', 'BANQUE');
        $sheet->setCellValue('O1', 'MOMO');
        $sheet->setCellValue('P1', 'REDUCTION');
        $sheet->setCellValue('Q1', 'OM');
      //  $sheet->setCellValue('R1', 'MOMO');

            $i = 2;
            $vente = $em->getRepository(VenteA::class)->findBy(['agence'=>$id]);
            foreach ($vente as $key => $value) {
                $sheet->setCellValue('A'.$i, $value->getId());
                $sheet->setCellValue('B'.$i, $value->getType());
                $sheet->setCellValue('C'.$i, $value->getQuantite());
                $sheet->setCellValue('D'.$i, $value->getPrix());
                $sheet->setCellValue('E'.$i, $value->getClient()->getNom());
                $sheet->setCellValue('F'.$i, $value->getUser()->getUsername());
                $sheet->setCellValue('G'.$i, $value->getCreateAt());
                $sheet->setCellValue('H'.$i, $value->getEsperce());
                $sheet->setCellValue('I'.$i, 0);
                $sheet->setCellValue('G'.$i, $value->getHeure());
                $sheet->setCellValue('K'.$i, $value->getStatut());
                $sheet->setCellValue('L'.$i, $value->getCredit());
                $sheet->setCellValue('M'.$i, $value->getCash());
                $sheet->setCellValue('N'.$i, $value->getBanque());
                $sheet->setCellValue('O'.$i, $value->getMomo());
                $sheet->setCellValue('P'.$i, $value->getReduction());
                $sheet->setCellValue('Q'.$i, $value->getOm());
               // $sheet->setCellValue('R'.$i, $value->getMontantmomo());
                $i =$i+1;
            }
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export_vente.xlsx"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;
        
    }

    #[Route('/vente/import', name:'vente_a_import')]
    public function import_vente(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $processed = 0;
        if ($request->isMethod('POST')) {
           $file =  $request->files->get('ficher');
           if ($file && $file->isValid()) {
                   try {
                    $extension = strtolower($file->getClientOriginalExtension());
                    $extensionsAutorisees = ['xlsx', 'xls', 'csv'];

                    if (!in_array($extension, $extensionsAutorisees)) {
                        throw new \Exception('Seuls les fichiers Excel (XLSX, XLS) et CSV sont autorisés');
                    }

                    $spreadsheet = IOFactory::load($file->getPathname());
                    $spreadsheet = IOFactory::load($file->getPathname());
        
                    $donnees = $this->lireFichierExcel($spreadsheet);
                    $donnees = $donnees['Worksheet'];
                    array_shift($donnees);
                    $total = count($donnees);
                    $i = 0;
                    $trouver = 0;
                    
                    $this->addFlash('success', 'Importation démarrée');
                    foreach ($donnees as $key => $value) {
                        $prevente = $em->getRepository(VenteA::class)->findBy(['reference' => $value[0]]);
                        if ($prevente) {
                          $trouver = $trouver + 1;
                        }else {
                            $vente = new VenteA();
                            $utilisateur = $em->getRepository(User::class)->findOneBy(['reference' => $value[16]]);
                            $client = $em->getRepository(Clients::class)->findOneBy(['reference' => $value[17]]);
                            $agence = $tempagence->getAgence();

                            $vente->setReference($value[0]);
                            $vente->setType($value[1]);
                            $vente->setQuantite($value[2]);
                            $vente->setPrix($value[3]);
                            $vente->setCreateAt(new \DateTimeImmutable($value[6]));
                            $vente->setEsperce($value[13]);
                            $vente->setCash($value[8]);
                            $vente->setReduction($value[11]);
                            $vente->setBanque($value[12]);
                            $vente->setCredit($value[9]);
                            $vente->setHeure($value[14]);
                            $vente->setStatut($value[15]);
                            $vente->setMomo($value[18]);
                            $vente->setOm($value[10]);

                            $vente->setUser($utilisateur);
                            $vente->setClient($client);
                            $vente->setAgence($agence);

                            $em->persist($vente);
                            $em->flush();

                            $processed++;
                            $progress = round(($i + 1) / $total * 100);
                            // Messages avec barre de progression ASCII
                            if ($progress % 20 === 0) {
                                $bar = str_repeat('█', $progress / 5) . str_repeat('░', 20 - ($progress / 5));
                                $this->addFlash('success', "[$bar] $progress% - Ligne " . ($i + 1) . "/$total");
                            }
                            $i++;
                        }
                        
                    }
                    
                    $this->addFlash('success', 'Importation terminée avec succès! Vente trouver : '.$trouver);

                    return $this->redirectToRoute('vente_a_import');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
           } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
           

        }
     
        return $this->render("vente_a/import.html.twig",[
            "id" => $id,
        ]);
    }

    private function lireFichierExcel($spreadsheet): array
    {
        $donneesCompletes = [];
        
        // Parcourir toutes les feuilles
        foreach ($spreadsheet->getSheetNames() as $sheetIndex => $sheetName) {
            $worksheet = $spreadsheet->getSheet($sheetIndex);
            $donneesCompletes[$sheetName] = $this->lireFeuilleExcel($worksheet);
        }
        
        return $donneesCompletes;
    }

    private function lireFeuilleExcel($worksheet): array
    {
        $donnees = [];
    
    // Méthode plus simple avec toArray()
    $donnees = $worksheet->toArray();
    
    return $donnees;
    }
}
