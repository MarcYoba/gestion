<?php

namespace App\Controller;

use App\Entity\AchatA;
use App\Entity\CaisseA;
use App\Entity\Consultation;
use App\Entity\DepenseA;
use App\Entity\FactureA;
use App\Entity\HistoriqueA;
use App\Entity\Inventaire;
use App\Entity\InventaireA;
use App\Entity\InventaireCaisseA;
use App\Entity\MagasinA;
use App\Entity\Poussin;
use App\Entity\ProduitA;
use App\Entity\ProspectionA;
use App\Entity\Suivi;
use App\Entity\TempAgence;
use App\Entity\Vaccin;
use App\Entity\VenteA;
use App\Entity\VersementA;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class RapportAController extends AbstractController
{
    #[Route('/rapport/a/jour', name: 'app_rapport_a_jour')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        $date = date("Y-m-d");

        $sommeCaisse = $em->getRepository(CaisseA::class)->findBySommeCaisse(new \DateTime($date),$id);
        $inventaire = $em->getRepository(InventaireA::class)->findBy(['createtAt'=> new \DateTime($date)]);
        $inventaireCaisse = $em->getRepository(InventaireCaisseA::class)->findBy(['createtAt'=> new \DateTime($date)]);
        $date = new \DateTimeImmutable($date);
        $caisse = $em->getRepository(CaisseA::class)->findBy(["createAt" => $date]);
        $vente = $em->getRepository(VenteA::class)->findRapportToDay( $date);
        $achat = $em->getRepository(AchatA::class)->findByDay($date);
        $depense = $em->getRepository(DepenseA::class)->findByDay($date);
        $versement = $em->getRepository(VersementA::class)->findByDay($date);
        $poussin = $em->getRepository(Poussin::class)->findByDay($date->format("Y-m-d"));
        $consultation = $em->getRepository(Consultation::class)->findByDay($date);
        $suivi = $em->getRepository(Suivi::class)->findByDay($date);
        $vaccin = $em->getRepository(Vaccin::class)->findBy(['dateVaccin'=>$date]);
        $terrain = $em->getRepository(ProspectionA::class)->findBy(['createtAt'=>$date]);
        $sommeDepense = $em->getRepository(DepenseA::class)->findBySommeDepense($date,$id);
        $sommeVersement = $em->getRepository(VersementA::class)->findBySommeVersement($date,$id);
        

        
        
        $produit = $em->getRepository(FactureA::class)->findByProduitVendu($date,$id);
        $historiqueA = [];
        foreach ($produit as $key => $value) {
            $quantite = 0;
            $hist = $em->getRepository(HistoriqueA::class)->findByDate($date,$value->getProduit()->getId(),$id);
            $fact = $em->getRepository(FactureA::class)->findBySommeProduit($date,$value->getProduit()->getId(),$id);
            $magasin = $em->getRepository(MagasinA::class)->findOneBy(["produit" => $value->getProduit()->getId()]);
            if($magasin) {
                $quantite = $magasin->getQuantite();
            }
            array_push($historiqueA,[$value->getProduit()->getNom(),$hist,$fact,$value->getProduit()->getQuantite(),$quantite]);
        }
        
        $html = $this->renderView('rapport_a/jour_courante.html.twig', [
        'ventes' => $vente,
        'achats' => $achat,
        'caisses' => $caisse,
        'depenses' => $depense,
        'versements' => $versement,
        'poussins' => $poussin,
        'consultations' => $consultation,
        'suivis' => $suivi,
        'vaccins' => $vaccin,
        'terrains' => $terrain,
        'historiqueAs' => $historiqueA,
        'sommedepense' => $sommeDepense,
        'sommeVersement' => $sommeVersement,
        'sommeCaisse' => $sommeCaisse,
        'inventaires' => $inventaire,
        'inventaireCaisses' => $inventaireCaisse,
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
                'Content-Disposition' => 'inline; filename="document.pdf"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route('/rapport/a/hier', name: 'app_rapport_a_hier')]
    public function rapport_hier(EntityManagerInterface $em, Request $request) : Response 
    {
        $date = date("Y-m-d");
        if ($request->isMethod('POST')) {
           $date = $request->request->get('date');
           if(empty($date))
           {
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport_a");
           }
        }

        $user = $this->getUser();
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $sommeCaisse = $em->getRepository(CaisseA::class)->findBySommeCaisse($date,$id);
        $inventaire = $em->getRepository(InventaireA::class)->findBy(["createtAt" => new \DateTime($date)]);
        $inventaireCaisse = $em->getRepository(InventaireCaisseA::class)->findBy(['createtAt'=> new \DateTime($date)]);
        $date = new \DateTimeImmutable($date);
        $caisse = $em->getRepository(CaisseA::class)->findBy(["createAt" => $date]);
        $vente = $em->getRepository(VenteA::class)->findRapportToDay($date);
        $achat = $em->getRepository(AchatA::class)->findByDay($date);
        $depense = $em->getRepository(DepenseA::class)->findByDay($date);
        $versement = $em->getRepository(VersementA::class)->findByDay($date);
        $poussin = $em->getRepository(Poussin::class)->findByDay($date->format("Y-m-d"));
        $consultation = $em->getRepository(Consultation::class)->findByDay($date);
        $suivi = $em->getRepository(Suivi::class)->findByDay($date);
        $vaccin = $em->getRepository(Vaccin::class)->findBy(['dateVaccin'=>$date]);
        $terrain = $em->getRepository(ProspectionA::class)->findBy(['createtAt'=>$date]);
        $sommeDepense = $em->getRepository(DepenseA::class)->findBySommeDepense($date,$id);
        $sommeVersement = $em->getRepository(VersementA::class)->findBySommeVersement($date,$id);
        
        
        $produit = $em->getRepository(FactureA::class)->findByProduitVendu($date,$id);
        $historiqueA = [];
        foreach ($produit as $key => $value) {
            $quantite = 0;
            $hist = $em->getRepository(HistoriqueA::class)->findByDate($date,$value->getProduit()->getId(),$id);
            $fact = $em->getRepository(FactureA::class)->findByQuantiteProduitVendu($date,$value->getProduit()->getId(),$id);
            $lasthist = $em->getRepository(HistoriqueA::class)->findByLastDate(new \DateTime($date->format("Y-m-d")),$value->getProduit()->getId(),$id);
            $magasin = $em->getRepository(MagasinA::class)->findOneBy(["produit" => $value->getProduit()->getId()]);
            if($magasin) {
                $quantite = $magasin->getQuantite();
            }
            array_push($historiqueA,[$value->getProduit()->getNom(),$hist,$fact,$lasthist,$quantite]);
        }
        
        $html = $this->renderView('rapport_a/hier.html.twig', [
        'ventes' => $vente,
        'achats' => $achat,
        'caisses' => $caisse,
        'calandrier' => $date->format("Y-m-d"),
        'depenses' => $depense,
        'versements' => $versement,
        'poussins' => $poussin,
        'consultations' => $consultation,
        'suivis' => $suivi,
        'vaccins' => $vaccin,
        'terrains' => $terrain,
        'historiqueAs' => $historiqueA,
        'sommedepense' => $sommeDepense,
        'sommeVersement' => $sommeVersement,
        'sommeCaisse' => $sommeCaisse,
        'inventaires' => $inventaire,
        'inventaireCaisses' => $inventaireCaisse,
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
                'Content-Disposition' => 'inline; filename="document.pdf"', // 'inline' pour affichage navigateur
            ]
        );
    }
    #[Route('/rapport/a/semaine', name:'app_rapport_a_semaine')]
    public function rapport_semain(EntityManagerInterface $em, Request $request) : Response 
    {
        $date_debut = date("Y-m-d");
        $date_fin = date("Y-m-d");
        
        if ($request->isMethod('POST')) {
           $date_debut = $request->request->get('date_debut');
           $date_fin = $request->request->get('date_fin');
           
           if(empty($date_debut) && empty($date_fin))
           { 
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport_a");
           }
        }
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        $calandrier = $date_debut.'-'.$date_fin;
        $caisse = $em->getRepository(CaisseA::class)->findByCaisseSemaine($date_debut,$date_fin,$id);
        $achat = $em->getRepository(AchatA::class)->findByIntervaleDate($date_debut,$date_fin,$id);
        $sommeVersement = $em->getRepository(VersementA::class)->findBySommeVersementSemaine($date_debut,$date_fin,$id);
        $sommeDepense = $em->getRepository(DepenseA::class)->findBySommeDepenseSemain($date_debut,$date_fin,$id);
        $sommeCaisse = $em->getRepository(CaisseA::class)->findBySommeCaisseSemaine($date_debut,$date_fin,$id);
        $depenses = $em->getRepository(DepenseA::class)->findByDepenseSemain($date_debut,$date_fin,$id);
        $versement = $em->getRepository(VersementA::class)->findByVersementSemaine($date_debut,$date_fin,$id);
        $poussin = $em->getRepository(Poussin::class)->findAllCommandPoussin($id,$date_debut,$date_fin);

        $date_debut = new \DateTimeImmutable($date_debut);
        $date_fin = new \DateTimeImmutable($date_fin);
        $produit = $em->getRepository(FactureA::class)->findByProduitVenduSemaine($date_debut,$date_fin,$id);
        $historiqueA = [];
        foreach ($produit as $key => $value) {
            $hist = $em->getRepository(HistoriqueA::class)->findByDate($date_debut,$value->getProduit()->getId(),$id);
            $fact = $em->getRepository(FactureA::class)->findByQuantiteProduitVendu($date_debut,$value->getProduit()->getId(),$id);
            $lasthist = $em->getRepository(HistoriqueA::class)->findByLastDate(new \DateTime($date_debut->format("Y-m-d")),$value->getProduit()->getId(),$id);
            array_push($historiqueA,[$value->getProduit()->getNom(),$hist,$fact,$lasthist]);
        }
        $vente = $em->getRepository(VenteA::class)->findRapportVenteToWeek($date_debut,$date_fin,$id);
        
        //dd($date_debut,$date_fin,$vente);
        $html = $this->renderView('rapport_a/semaine.html.twig', [
        'calandrier' => $calandrier,
        'date_debut' => $date_debut,
        'date_fin' => $date_fin,
        'ventes' => $vente,
        'achats' => $achat,
        'caisses' => $caisse,
        'sommedepense' => $sommeDepense,
        'sommeVersement' => $sommeVersement,
        'sommeCaisse' => $sommeCaisse,
        'historiqueAs' => $historiqueA,
        'depenses' => $depenses,
        'versements' => $versement,
        'poussins' => $poussin,
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
                'Content-Disposition' => 'inline; filename="document.pdf"', // 'inline' pour affichage navigateur
            ]
        );        
    }

    #[Route('/rapport/a/moi', name:'app_rapport_a_moi')]
    public function rapport_moi(EntityManagerInterface $em, Request $request) : Response 
    {
        $date_debut = 0;
        $date_fin = 0;
        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $date_debut = $request->request->get('mois');
           $date_fin = $request->request->get('date');

           if (empty($date_debut)) {
                if (!empty($date_fin)) {
                    $date_fin = new DateTime($date_fin);
                    $date_debut = $date_fin->format("m");
                    $anne = $date_fin->format("Y");
                }
           }
           //$date_debut = date("Y".$date_debut."d");
           
           if(empty($date_debut) && empty($date_fin))
           {
                
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport_a");
           }
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        
        $caisse = $em->getRepository(CaisseA::class)->findRapportCaisseToWeek($date_debut,$anne);
        $vente = $em->getRepository(VenteA::class)->findRapportMensuel($date_debut,$anne);
        $achat = $em->getRepository(AchatA::class)->findByDate($date_debut,$anne);
        $depense = $em->getRepository(DepenseA::class)->findByMoi($date_debut,$anne);
        $versement = $em->getRepository(VersementA::class)->findByMoi($date_debut,$anne);
        $poussin = $em->getRepository(Poussin::class)->findByMoi($date_debut,$anne);
        $suivi = $em->getRepository(Suivi::class)->findByMoi($date_debut,$anne);
        $vaccin = $em->getRepository(Vaccin::class)->findByMoi($date_debut,$anne);
        $consultation = $em->getRepository(Consultation::class)->findByMoi($date_debut,$anne);
        
        $html = $this->renderView('rapport_a/moi.html.twig', [
        'date_debut' => $date_debut,
        'date_fin' => $date_fin,
        'ventes' => $vente,
        'achats' => $achat,
        'caisses' => $caisse,
        'depenses' => $depense,
        'versements' => $versement,
        'poussins' => $poussin,
        'suivis' => $suivi,
        'vaccins' => $vaccin, 
        'consultations' => $consultation,
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
                'Content-Disposition' => 'inline; filename="document.pdf"', // 'inline' pour affichage navigateur
            ]
        );        
    }

    #[Route('/rapport/a/trimestre', name:'app_rapport_trimestre')]
    public function rapport_trimestre(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
           if (empty($anne)) {
                if (!empty($anne)) {
                    $anne = date('Y');
                }
           }
           if(empty($anne))
           {
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport_a");
           }
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        $vente = [];
        $achat = [];
        $depense = [];
        $poussin = [];
        $versement = [];
        $achatsemetre = [];
        $ventesemetre = [];
        $depensesemetre = [];
        $poussinsemestre =[];
        $versementsemestre = [];

        $trimestre = 1;
        while ($trimestre <= 4) {
            array_push($vente,$em->getRepository(VenteA::class)->findByMontantTrimestre($trimestre,$anne,$id));
            array_push($depense,$em->getRepository(DepenseA::class)->findByDepensesTrimestre($trimestre,$anne,$id));
            array_push($achat,$em->getRepository(AchatA::class)->findByMontantTrimestre($trimestre,$anne,$id));
            array_push($versement,$em->getRepository(VersementA::class)->findByVersementTrimestre($trimestre,$anne,$id));
            $array_poussin = $em->getRepository(Poussin::class)->findByPoussinTrimestre($trimestre,$anne,$id) ;
            array_push($poussin,$array_poussin[0]);
            if ($trimestre <= 2) {
                array_push($achatsemetre,$em->getRepository(AchatA::class)->findByMontantSemestre($trimestre,$anne,$id));
                array_push($ventesemetre,$em->getRepository(VenteA::class)->findByMontantSemestre($trimestre,$anne,$id));
                array_push($depensesemetre,$em->getRepository(DepenseA::class)->findByDepensesSemestre($trimestre,$anne,$id));
                array_push($versementsemestre,$em->getRepository(VersementA::class)->findByVersementSemestre($trimestre,$anne,$id));
                $array_poussin = $em->getRepository(Poussin::class)->findByPoussinSemestre($trimestre,$anne,$id);
                array_push($poussinsemestre,$array_poussin[0]);
            }
            $trimestre ++;
        }
        
        $html = $this->renderView('rapport_a/trimestre.html.twig', [
        'annees' => $anne,
        'ventes' => $vente,
        'ventessemetres' => $ventesemetre,
        'depenses' => $depense,
        'depensesemestres' => $depensesemetre,
        'achats' => $achat,
        'achatsemetres' => $achatsemetre,
        'versements' => $versement,
        'versementsemestre' => $versementsemestre,
        'poussins' => $poussin,
        'poussinsemeste' => $poussinsemestre,
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
                'Content-Disposition' => 'inline; filename="document.pdf"', // 'inline' pour affichage navigateur
            ]
        );        
    }

    #[Route('/rapport/a/anneul', name:'app_rapport_annuel')]
    public function rapport_annuel(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
           if (empty($anne)) {
                if (!empty($anne)) {
                    $anne = date('Y');
                }
           }
           if(empty($anne))
           {
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport_a");
           }
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        $ventemoi = [];
        $venteSemain = [];
        $moi = 1;
        while ($moi <= 12) {
            array_push($ventemoi,$em->getRepository(VenteA::class)->findByMontantMonth($moi,$anne,$id));
            $moi ++;
        }
        // Trouver le premier lundi de l'année
        $premierJour = new DateTime("$anne-01-01");
        
        // Ajuster au premier lundi de l'année
        if ($premierJour->format('N') != 1) { // N = 1 pour lundi
            $premierJour->modify('next monday');
        }
        
        // Calculer le nombre de semaines dans l'année
        $dernierJour = new DateTime("$anne-12-31");
        $nombreSemaines = $dernierJour->format('W');
        
        // Générer chaque semaine
        for ($semaine = 1; $semaine <= $nombreSemaines; $semaine++) {
            // Calculer le lundi de la semaine
            $lundi = clone $premierJour;
            $lundi->modify('+' . ($semaine - 1) . ' weeks');
            
            // Calculer le dimanche de la semaine
            $dimanche = clone $lundi;
            $dimanche->modify('+6 days');
            
            // Vérifier si la semaine est dans l'année
            if ($lundi->format('Y') <= $anne || $dimanche->format('Y') >= $anne) {
                $val = $em->getRepository(VenteA::class)->findBySommeVenteToWeek(
                    new \DateTimeImmutable($lundi->format('Y-m-d')), 
                    new \DateTimeImmutable($dimanche->format('Y-m-d')),
                    $id);
                array_push($venteSemain,[$semaine,$val]);
                
            }
        }
        $html = $this->renderView('rapport_a/anne.html.twig', [
        'annees' => $anne,
        'ventes' => $ventemoi,
        'semains' => $venteSemain,
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
                'Content-Disposition' => 'inline; filename="rapport_annuel.pdf"', // 'inline' pour affichage navigateur
            ]
        );        
    }

    #[Route('/rapport/a/dette/client', name:'app_rapport_dette_client')]
    public function rapport_dette_client(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'semestre');
        $sheet->setCellValue('B1', 'CLIENT');
        $sheet->setCellValue('C1', 'TYPE VENTE');
        $sheet->setCellValue('D1', 'TOTAL');

            $i = 2;

        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
           $semestre = $request->request->get('semestre');
           $speculation = $request->request->get('speculation');
            $ventespeculation = [];
            
           if (empty($anne)) {
                if (!empty($anne)) {
                    $anne = date('Y');
                }
           }
           if ($semestre == "ALL" || $speculation == "ALL") {
                $trimestre = 1;
                while ($trimestre <= 2) {
                    $ventesemetre = $em->getRepository(VenteA::class)->findByVenteDetteSemestre($trimestre,$anne,$id);
                    
                    foreach ($ventesemetre as $key => $value) {
                        $sheet->setCellValue('A'.$i, "semestre".$trimestre);
                        $sheet->setCellValue('B'.$i,  $value['nom']);
                        $sheet->setCellValue('C'.$i, $value['type']);
                        $sheet->setCellValue('D'.$i,  $value[1]);
                        $i =$i+1;
                    }
                    $trimestre ++;
                }
           }
        }

            
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export_dette_client.xlsx"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;       
    }
    
    #[Route('/rapport/a/depense/post', name:'app_rapport_depense_post')]
    public function rapport_depense_poste(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'semestre');
        $sheet->setCellValue('B1', 'Depense');
        $sheet->setCellValue('C1', 'Montant total');

            $i = 2;

        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
           $semestre = $request->request->get('semestre');
           $speculation = $request->request->get('poste_depense');
            $ventespeculation = [];
            
           if (empty($anne)) {
                if (!empty($anne)) {
                    $anne = date('Y');
                }
           }
           
           if ($semestre == "ALL" || $speculation == "ALL") {
                $trimestre = 1;
                while ($trimestre <= 2) {
                    $ventesemetre = $em->getRepository(DepenseA::class)->findByDepensesParPost($trimestre,$anne,$id);
                    
                    foreach ($ventesemetre as $key => $value) {
                        $sheet->setCellValue('A'.$i, "semestre".$trimestre);
                        $sheet->setCellValue('B'.$i,  $value['Montant']);
                        $sheet->setCellValue('C'.$i, $value['type']);
                        $i =$i+1;
                    }
                    $trimestre ++;
                }
           }
        }

            
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export_depesne_poste.xlsx"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;       
    }

    #[Route('/rapport/a/depense/globale', name:'app_rapport_depense_globale')]
    public function rapport_depense_globale(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'semestre');
        $sheet->setCellValue('B1', 'Description');
        $sheet->setCellValue('c1', 'Montant');
        $sheet->setCellValue('d1', 'Cthegorie');

            $i = 2;

        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
           
            $ventespeculation = [];
            
           if (empty($anne)) {
                if (!empty($anne)) {
                    $anne = date('Y');
                }
           }
                $trimestre = 1;
                while ($trimestre <= 2) {
                    $ventesemetre = $em->getRepository(DepenseA::class)->findByDepensesGlobale($trimestre,$anne,$id);
                    
                    foreach ($ventesemetre as $key => $value) {
                        $sheet->setCellValue('A'.$i, "semestre".$trimestre);
                        $sheet->setCellValue('B'.$i,  $value->getDescription());
                        $sheet->setCellValue('C'.$i, $value->getMontant());
                        $sheet->setCellValue('D'.$i, $value->getType());
                        $i =$i+1;
                    }
                    $trimestre ++;
                }
        }

            
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export_depesne_globale.xlsx"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;       
    }

    #[Route('/rapport/a/depense/detail', name:'app_rapport_depense_detail')]
    public function rapport_depense_detail(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'semestre');
        $sheet->setCellValue('B1', 'Description');
        $sheet->setCellValue('c1', 'Montant');
        $sheet->setCellValue('d1', 'Cthegorie');

            $i = 2;

        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
           
            $ventespeculation = [];
            
           if (empty($anne)) {
                if (!empty($anne)) {
                    $anne = date('Y');
                }
           }
                $trimestre = 1;
                while ($trimestre <= 2) {
                    $ventesemetre = $em->getRepository(DepenseA::class)->findByDepensesGlobale($trimestre,$anne,$id);
                    
                    foreach ($ventesemetre as $key => $value) {
                        $sheet->setCellValue('A'.$i, "semestre".$trimestre);
                        $sheet->setCellValue('B'.$i,  $value->getDescription());
                        $sheet->setCellValue('C'.$i, $value->getMontant());
                        $sheet->setCellValue('D'.$i, $value->getType());
                        $i =$i+1;
                    }
                    $trimestre ++;
                }
        }

            
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export_depesne_detail.xlsx"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;       
    }

    #[Route('/rapport/a/vente/client', name:'app_rapport_vente_client')]
    public function rapport_vente_client(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'Nom');
        $sheet->setCellValue('B1', 'Cash');
        $sheet->setCellValue('C1', 'Banque');
        $sheet->setCellValue('D1', 'Credit');
        $sheet->setCellValue('E1', 'Momo');
        $sheet->setCellValue('F1', 'om');
        $sheet->setCellValue('G1', 'Total');

            $i = 2;

        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
            
           if (empty($anne)) {
                if (!empty($anne)) {
                    $anne = date('Y');
                }
           }
                $vente  = $em->getRepository(VenteA::class)->findByVenteClient($anne);
                foreach ($vente as $key => $value) {

                    $sheet->setCellValue('A'.$i, $value['nom']);
                    $sheet->setCellValue('B'.$i,  $value['cash']);
                    $sheet->setCellValue('C'.$i, $value['banque']);
                    $sheet->setCellValue('D'.$i, $value['credit']);
                    $sheet->setCellValue('E'.$i, $value['momo']);
                    $sheet->setCellValue('F'.$i, $value['om']);
                    $sheet->setCellValue('G'.$i, $value['TotalVente']);

                    $i =$i+1;
                }
        }

            
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export_vente_part_client.xlsx"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;       
    }

    #[Route('/rapport/a/vente/client/credit', name:'app_rapport_vente_client_credit')]
    public function rapport_vente_client_credit(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'Nom');
        $sheet->setCellValue('B1', 'Cash');
        $sheet->setCellValue('C1', 'Banque');
        $sheet->setCellValue('D1', 'Credit');
        $sheet->setCellValue('E1', 'Momo');
        $sheet->setCellValue('F1', 'om');
        $sheet->setCellValue('G1', 'Total');

            $i = 2;

        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
            
           if (empty($anne)) {
                if (!empty($anne)) {
                    $anne = date('Y');
                }
           }
                $vente  = $em->getRepository(VenteA::class)->findVenteByClientDette($anne);
                foreach ($vente as $key => $value) {

                    $sheet->setCellValue('A'.$i, $value['nom']);
                    $sheet->setCellValue('B'.$i,  $value['cash']);
                    $sheet->setCellValue('C'.$i, $value['banque']);
                    $sheet->setCellValue('D'.$i, $value['credit']);
                    $sheet->setCellValue('E'.$i, $value['momo']);
                    $sheet->setCellValue('F'.$i, $value['om']);
                    $sheet->setCellValue('G'.$i, $value['TotalVente']);

                    $i =$i+1;
                }
        }

            
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export_vente_client_credit.xlsx"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;       
    }

    #[Route('/rapport/a/vente/client/credit/date', name:'app_rapport_vente_client_date')]
    public function rapport_vente_client_date(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'Nom');
        $sheet->setCellValue('B1', 'Cash');
        $sheet->setCellValue('C1', 'Banque');
        $sheet->setCellValue('D1', 'Credit');
        $sheet->setCellValue('E1', 'Momo');
        $sheet->setCellValue('F1', 'om');
        $sheet->setCellValue('G1', 'Total');
        $sheet->setCellValue('H1', 'Date');

            $i = 2;

        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
            
           if (empty($anne)) {
                if (!empty($anne)) {
                    $anne = date('Y');
                }
           }
                $vente  = $em->getRepository(VenteA::class)->findByVenteByClientDette($anne);
                foreach ($vente as $key => $value) {

                    $sheet->setCellValue('A'.$i, $value['nom']);
                    $sheet->setCellValue('B'.$i,  $value['cash']);
                    $sheet->setCellValue('C'.$i, $value['banque']);
                    $sheet->setCellValue('D'.$i, $value['credit']);
                    $sheet->setCellValue('E'.$i, $value['momo']);
                    $sheet->setCellValue('F'.$i, $value['om']);
                    $sheet->setCellValue('G'.$i, $value['TotalVente']);
                    $sheet->setCellValue('H'.$i, $value['dates']);

                    $i =$i+1;
                }
        }

            
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export_vente_client_date.xlsx"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;       
    }

    #[Route('/rapport/a/versement/client', name:'app_rapport_versement_client')]
    public function rapport_versement(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'Nom');
        $sheet->setCellValue('B1', 'Montant');
        
        $i = 2;

        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
            
           if (empty($anne)) {
                if (!empty($anne)) {
                    $anne = date('Y');
                }
           }
                $vente  = $em->getRepository(VersementA::class)->findByclientGlobale($anne);
                foreach ($vente as $key => $value) {

                    $sheet->setCellValue('A'.$i, $value['nom']);
                    $sheet->setCellValue('B'.$i,  $value['Montant']);
                    $i =$i+1;
                }
        }

            
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export_vente_versement.xlsx"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;       
    }

    #[Route('/rapport/a/versement/client/date', name:'app_rapport_versement_client_date')]
    public function rapport_versement_date(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'Nom');
        $sheet->setCellValue('B1', 'Montant');
        $sheet->setCellValue('C1', 'Montant');

        $i = 2;

        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
            
           if (empty($anne)) {
                if (!empty($anne)) {
                    $anne = date('Y');
                }
           }
                $vente  = $em->getRepository(VersementA::class)->findByclientDate($anne);
                foreach ($vente as $key => $value) {

                    $sheet->setCellValue('A'.$i, $value['nom']);
                    $sheet->setCellValue('B'.$i,  $value['Montant']);
                    $sheet->setCellValue('C'.$i,  $value['dates']);
                    $i =$i+1;
                }
        }

            
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export_vente_versement_date.xlsx"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;       
    }

    #[Route('/rapport/a/vente/client/mois', name:'app_rapport_vente_client_mois')]
    public function rapport_vente_client_month(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $mois =[
            1 => 'Janvier',
            2 => 'Fevrier',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Aout',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Decembre',
        ];
        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule

        $i = 1;
        $lastmoi = 0;
        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
            
           if (empty($anne)) {
                if (!empty($anne)) {
                    $anne = date('Y');
                }
           }
            foreach ($mois as $key => $val) {
                if ($lastmoi != $key) {
                    $lastmoi = $key;
                    $sheet->setCellValue('A'.$i, $val);
                    $sheet->getStyle('A'.$i)->getFont()
                        ->getColor()->setARGB('FFFF0000');
                    $i =$i+1;
                    $sheet->setCellValue('A'.$i, 'Nom');
                    $sheet->setCellValue('B'.$i, 'Cash');
                    $sheet->setCellValue('C'.$i, 'Banque');
                    $sheet->setCellValue('D'.$i, 'Credit');
                    $sheet->setCellValue('E'.$i, 'Momo');
                    $sheet->setCellValue('F'.$i, 'OM');
                    $sheet->setCellValue('G'.$i, 'Total');
                    $i =$i+1;
                    
                }
                $vente  = $em->getRepository(VenteA::class)->findByVenteClientMonth($anne,$key);
                
                foreach ($vente as $key => $value) {

                    $sheet->setCellValue('A'.$i, $value['nom']);
                    $sheet->setCellValue('B'.$i,  $value['cash']);
                    $sheet->setCellValue('C'.$i, $value['banque']);
                    $sheet->setCellValue('D'.$i, $value['credit']);
                    $sheet->setCellValue('E'.$i, $value['momo']);
                    $sheet->setCellValue('F'.$i, $value['om']);
                    $sheet->setCellValue('G'.$i, $value['TotalVente']);

                    $i =$i+1;
                }
            }
        }

            
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export_vente_part_client_mois.xlsx"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;       
    }
}
