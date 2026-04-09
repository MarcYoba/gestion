<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Caisse;
use App\Entity\Depenses;
use App\Entity\Facture;
use App\Entity\Historique;
use App\Entity\Inventaire;
use App\Entity\Magasin;
use App\Entity\TempAgence;
use App\Entity\Transfert;
use App\Entity\Vente;
use App\Entity\Versement;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class RapportController extends AbstractController
{
    /**
     * @Route(path="/rapport", name="app_rapport")
     */
    public function rapport(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();
        return $this->render('rapport/rapport.html.twig', [
            'id' => $agence,
        ]);
    }
    /**
     * @Route(path="/rapport/a", name="app_rapport_a")
     */
    public function rapportA(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();
        return $this->render('rapport/rapportA.html.twig',[
          'id' => $agence,  
        ]);
    }
    /**
     * @Route(path="/rapport/day" , name="rapport_day")
     */
    public function rapport_day(EntityManagerInterface $em,Request $request): Response
    {
        $user = $this->getUser();
        $date = date("Y-m-d");
        if ($request->isMethod('POST')) {
           $date = $request->request->get('date');
           if(empty($date))
           {
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport");
           }
        }
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        $inventaire = [];

        $vente = $em->getRepository(Vente::class)->findByDay($date);
        $depense = $em->getRepository(Depenses::class)->findByDay($date);
        $sommeDepense = $em->getRepository(Depenses::class)->findBySommeDay($date);
        $sommeversement = $em->getRepository(Versement::class)->findBysommeDay($date);
        $achat = $em->getRepository(Achat::class)->findByRapportDay($date,$agence);
        $tableau = $em->getRepository(Inventaire::class)->findBy(["createtAt" => new \DateTime($date)]);
        foreach ($tableau as $key => $value) {
            $ecar = $em->getRepository(Inventaire::class)->findBy(['produit' => $value->getProduit()->getId()],['id' => 'DESC'],2);
            $ecar = array_pop($ecar);
            array_push($inventaire,[$value,$ecar->getEcart()]);
        }
        
        
        $date  = new DateTime($date);
        $sommecaisse = $em->getRepository(Caisse::class)->findBySommeCaisseDay($date,$agence);
        $transfert = $em->getRepository(Transfert::class)->findBy(['createtAt' => $date]);
        

        $histoiques = [];
        $benefice = [];
        $histoique = $em->getRepository(Facture::class)->findByProduitVendu($date,$agence);
            foreach ($histoique as $key => $value) {
                $quantite = 0;
                $hist = $em->getRepository(Historique::class)->findByDate($date,$value->getProduit()->getId(),$agence);
                $fact = $em->getRepository(Facture::class)->findByQuantiteProduitVendu($date,$value->getProduit()->getId(),$agence);
                $prix = $em->getRepository(Facture::class)->findByPrixProduitVendu($date, $value->getProduit()->getId(), $agence);
                $lasthist = $em->getRepository(Historique::class)->findByLastDate(new \DateTime($date->format("Y-m-d")),$value->getProduit()->getId(),$agence);
                $magasin = $em->getRepository(Magasin::class)->findOneBy(["produit" => $value->getProduit()->getId()]);
                $achat = $em->getRepository(Achat::class)->findByPrixAchatProduit($value->getProduit()->getId(),$agence);
                if($magasin) {
                    $quantite = $magasin->getQuantite();
                }
                array_push($histoiques,[$value->getProduit()->getNom(),$hist,$fact,$value->getProduit()->getQuantite(),$quantite]);
                foreach ($prix as $key => $val) {
                    array_push($benefice,[$value->getProduit()->getNom(),$val["quantites"],$val["prix"],$achat,($val["prix"] - $achat) * $val["quantites"]]);
                }
                
            }
        
        $caisse = $em->getRepository(Caisse::class)->findBy(['createAt' => $date]);
        $versement = $em->getRepository(Versement::class)->findBy(['createdAd' => $date]);

        $totalversement = 0;
        foreach ($sommeversement as $key => $value) {
            $totalversement = $totalversement + $value[1] + $value[2] + $value[3];
        }
        

        
        $html = $this->renderView('rapport/rapport_day.html.twig', [
        'ventes' => $vente,
        'date' => $date->format("Y-m-d"),
        'caisses' => $caisse,
        'versements' => $versement,
        'depenses' => $depense,
        'sommeDepense' => $sommeDepense,
        'sommeversement' => $sommeversement,
        'totalversement' => $totalversement,
        'sommecaisse' => $sommecaisse,
        'histoiques' => $histoiques,
        'magasins' => $transfert,
        'benefices' => $benefice,
        'achats' => $achat,
        'inventaires' => $inventaire,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();
        $document = "rapport_du_".$date->format("Y-m-d").".pdf";
        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$document.'"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route(path: '/rapport/week', name: 'rapport_week')]
    public function rapport_week(EntityManagerInterface $em,Request $request): Response
    {
        $user = $this->getUser();
        $date = date("Y-m-d");
        if ($request->isMethod('POST')) {
           $date = $request->request->get('date');
           if(empty($date))
           {
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport");
           }
        }
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        $inventaire = [];

        $vente = $em->getRepository(Vente::class)->findByDay($date);
        $depense = $em->getRepository(Depenses::class)->findByDay($date);
        $sommeDepense = $em->getRepository(Depenses::class)->findBySommeDay($date);
        $sommeversement = $em->getRepository(Versement::class)->findBysommeDay($date);
        $achat = $em->getRepository(Achat::class)->findByRapportDay($date,$agence);
        $tableau = $em->getRepository(Inventaire::class)->findBy(["createtAt" => new \DateTime($date)]);
        foreach ($tableau as $key => $value) {
            $ecar = $em->getRepository(Inventaire::class)->findBy(['produit' => $value->getProduit()->getId()],['id' => 'DESC'],2);
            $ecar = array_pop($ecar);
            array_push($inventaire,[$value,$ecar->getEcart()]);
        }
        
        
        
        $date  = new DateTime($date);
        $sommecaisse = $em->getRepository(Caisse::class)->findBySommeCaisseDay($date,$agence);
        $transfert = $em->getRepository(Transfert::class)->findBy(['createtAt' => $date]);

        $histoiques = [];
        $benefice = [];
        $histoique = $em->getRepository(Facture::class)->findByProduitVendu($date,$agence);
            foreach ($histoique as $key => $value) {
                $quantite = 0;
                $hist = $em->getRepository(Historique::class)->findByDate($date,$value->getProduit()->getId(),$agence);
                $fact = $em->getRepository(Facture::class)->findByQuantiteProduitVendu($date,$value->getProduit()->getId(),$agence);
                $lasthist = $em->getRepository(Historique::class)->findByLastDate(new \DateTime($date->format("Y-m-d")),$value->getProduit()->getId(),$agence);
                $magasin = $em->getRepository(Magasin::class)->findOneBy(["produit" => $value->getProduit()->getId()]);
                $achat = $em->getRepository(Achat::class)->findByPrixAchatProduit($value->getProduit()->getId(),$agence);
                $prix = $em->getRepository(Facture::class)->findByPrixProduitVendu($date, $value->getProduit()->getId(), $agence);
                if($magasin) {
                    $quantite = $magasin->getQuantite();
                }
                array_push($histoiques,[$value->getProduit()->getNom(),$hist,$fact,$value->getProduit()->getQuantite(),$quantite]);
                foreach ($prix as $key => $val) {
                    array_push($benefice,[$value->getProduit()->getNom(),$val["quantites"],$val["prix"],$achat,($val["prix"] - $achat) * $val["quantites"]]);
                }
            }
        
        $caisse = $em->getRepository(Caisse::class)->findBy(['createAt' => $date]);
        $versement = $em->getRepository(Versement::class)->findBy(['createdAd' => $date]);

        $totalversement = 0;
        foreach ($sommeversement as $key => $value) {
            $totalversement = $totalversement + $value[1] + $value[2] + $value[3];
        }
        

        
        $html = $this->renderView('rapport/rapport_week.html.twig', [
        'ventes' => $vente,
        'date' => $date->format("Y-m-d"),
        'caisses' => $caisse,
        'versements' => $versement,
        'depenses' => $depense,
        'sommeDepense' => $sommeDepense,
        'sommeversement' => $sommeversement,
        'totalversement' => $totalversement,
        'sommecaisse' => $sommecaisse,
        'histoiques' => $histoiques,
        'magasins' => $transfert,
        'benefices' => $benefice,
        'achats' => $achat,
        'inventaires' => $inventaire,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();
        $document = "rapport_du_".$date->format("Y-m-d").".pdf";
        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$document.'"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route(path: '/rapport/semain', name: 'rapport_semain')]
    public function rapport_semain(EntityManagerInterface $em,Request $request): Response
    {
        $user = $this->getUser();
        $date = date("Y-m-d");
        $datedebutsemain = date("Y-m-d");
        $datefinsemain = date("Y-m-d");
        if ($request->isMethod('POST')) {
           $datedebutsemain = $request->request->get('datedebutsemain');
           $datefinsemain = $request->request->get('datefinsemain');
           if(empty($datedebutsemain) && empty($datefinsemain))
           {
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport");
           }
        }
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        

        $vente = $em->getRepository(Vente::class)->findRapportVenteToSemain(new \DateTimeImmutable($datedebutsemain), new \DateTimeImmutable($datefinsemain),$agence);
        $depense = $em->getRepository(Depenses::class)->findBySommeDepenseSemaine(new \DateTimeImmutable($datedebutsemain),new \DateTimeImmutable($datefinsemain),$agence);
        $sommeDepense = $em->getRepository(Depenses::class)->findBySommeSemaine(new \DateTimeImmutable($datedebutsemain),new \DateTimeImmutable($datefinsemain),$agence);
        $sommeversement = $em->getRepository(Versement::class)->findBysommeSomme(new \DateTime($datedebutsemain),new \DateTime($datefinsemain),$agence);
        $achat = $em->getRepository(Achat::class)->findByFirstAndLastDay(($datedebutsemain),($datefinsemain),$agence);
        
        $sommecaisse = $em->getRepository(Caisse::class)->findBySommeCaisseSemaine(new \DateTime($datedebutsemain),new \DateTime($datefinsemain),$agence);
        $transfert = $em->getRepository(Transfert::class)->findByTransfertSemaine(new \DateTime($datedebutsemain),new \DateTime($datefinsemain),$agence);
        

        $histoiques = [];
        $benefice = [];
        $histoique = $em->getRepository(Facture::class)->findByProduitVenduSemaine(new \DateTimeImmutable($datedebutsemain),new \DateTimeImmutable($datefinsemain),$agence);
            foreach ($histoique as $key => $value) {
                $quantite = 0;
                $hist = $em->getRepository(Historique::class)->findByDate(new \DateTime($datedebutsemain),$value->getProduit()->getId(),$agence);
                
                $fact = $em->getRepository(Facture::class)->findByQuantiteProduitVenduSemaine(new \DateTimeImmutable($datedebutsemain), new \DateTimeImmutable($datefinsemain), $value->getProduit()->getId(), $agence);
               // $lasthist = $em->getRepository(Historique::class)->findByLastDate(new \DateTime($date->format("Y-m-d")),$value->getProduit()->getId(),$agence);
                $magasin = $em->getRepository(Magasin::class)->findOneBy(["produit" => $value->getProduit()->getId()]);
                $achat = $em->getRepository(Achat::class)->findByPrixAchatProduit($value->getProduit()->getId(),$agence);
                if($magasin) {
                    $quantite = $magasin->getQuantite();
                }
                array_push($histoiques,[$value->getProduit()->getNom(),$hist,$fact,$value->getProduit()->getQuantite(),$quantite]);
                array_push($benefice,[$value->getProduit()->getNom(),$fact,$value->getProduit()->getPrixvente(),$achat,($value->getProduit()->getPrixvente() - $achat) * $fact]);
            }
        
        $caisse = $em->getRepository(Caisse::class)->findByCaisseAgence(new \DateTime($datedebutsemain),new \DateTime($datefinsemain), $agence);
        $versement = $em->getRepository(Versement::class)->findByVersementSemaine(new \DateTime($datedebutsemain),new \DateTime($datefinsemain),$agence);

        $totalversement = 0;
        foreach ($sommeversement as $key => $value) {
            $totalversement = $totalversement + $value[1] + $value[2] + $value[3];
        }
        

        
        $html = $this->renderView('rapport/rapport_semaine.html.twig', [
        'ventes' => $vente,
        'date_debut' => $datedebutsemain,
        'date_fin' => $datefinsemain,
        'caisses' => $caisse,
        'versements' => $versement,
        'depenses' => $depense,
        'sommeDepense' => $sommeDepense,
        'sommeversement' => $sommeversement,
        'totalversement' => $totalversement,
        'sommecaisse' => $sommecaisse,
        'histoiques' => $histoiques,
        'magasins' => $transfert,
        'benefices' => $benefice,
        'achats' => $achat,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();
        $document = "rapport_du_".$datedebutsemain."au".$datefinsemain.".pdf";
        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$document.'"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route(path: '/rapport/month', name: 'rapport_month')]
    public function rapport_month(EntityManagerInterface $em,Request $request): Response
    {
        $user = $this->getUser();
        
        if ($request->isMethod('POST')) {
           $mois = $request->request->get('mois');
           $anne = $request->request->get('anne');
        }else{
            $mois = date("m");
            $anne = date("Y");
        }

        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();

        $vente = $em->getRepository(Vente::class)->findByMonthAgence($mois, $anne, $agence);
        $achat = $em->getRepository(Achat::class)->findByMonthAgence($mois,$anne,$agence);
        $caisse = $em->getRepository(Caisse::class)->findByMonthAgence($mois, $anne, $agence);
        $depense = $em->getRepository(Depenses::class)->findByMonthAgence($mois,$anne,$agence);
        $versement = $em->getRepository(Versement::class)->findByMonthAgence($mois,$anne,$agence);

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);

        $html = $this->renderView('rapport/month.html.twig', [
            'ventes' => $vente,
            'achats' => $achat,
            'caisses' => $caisse,
            'depenses' => $depense,
            'versements' => $versement,
            'mois' => $mois,
            'anne' => $anne,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();
        $document = "rapport_du_mois_".$mois."_".$anne.".pdf";
        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$document.'"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route(path:'/rapport/trimestre', name:'rapport_trimestre')]
    public function rapport_trimestre(EntityManagerInterface $em,Request $request): Response
    {
        $user = $this->getUser();
        
        if ($request->isMethod('POST')) {
           $anne = $request->request->get('anne');
        }else{
            $anne = date("Y");
        }

        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();
        $id = $agence;
        $vente = [];
        $achat = [];
        $depense = [];
        $versement = [];
        $achatsemetre = [];
        $ventesemetre = [];
        $depensesemetre = [];
        $versementsemestre = [];

        $trimestre = 1;
        while ($trimestre <= 4) {
            array_push($vente,$em->getRepository(Vente::class)->findByMontantTrimestre($trimestre,$anne,$id));
            array_push($depense,$em->getRepository(Depenses::class)->findByDepensesTrimestre($trimestre,$anne,$id));
            array_push($achat,$em->getRepository(Achat::class)->findByMontantTrimestre($trimestre,$anne,$id));
            array_push($versement,$em->getRepository(Versement::class)->findByVersementTrimestre($trimestre,$anne,$id));
            if ($trimestre <= 2) {
                array_push($achatsemetre,$em->getRepository(Achat::class)->findByMontantSemestre($trimestre,$anne,$id));
                array_push($ventesemetre,$em->getRepository(Vente::class)->findByMontantSemestre($trimestre,$anne,$id));
                array_push($depensesemetre,$em->getRepository(Depenses::class)->findByDepensesSemestre($trimestre,$anne,$id));
                array_push($versementsemestre,$em->getRepository(Versement::class)->findByVersementSemestre($trimestre,$anne,$id));
            }
            $trimestre ++;
        }

        // Générer le PDF comme dans les autres méthodes
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);

        $html = $this->renderView('rapport/trimestre.html.twig', [
            'annees' => $anne,
            'ventes' => $vente,
            'achats' => $achat,
            'depenses' => $depense,
            'versements' => $versement,
            'achatsemetres' => $achatsemetre,
            'ventessemetres' => $ventesemetre,
            'depensesemestres' => $depensesemetre,
            'versementsemestre' => $versementsemestre,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();
        $document = "rapport_du_mois_".$anne."_".$anne.".pdf";
        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$document.'"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route(path: '/rapport/annee', name: 'rapport_annee')]
    public function rapport_annee(EntityManagerInterface $em,Request $request): Response
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
            array_push($ventemoi,$em->getRepository(Vente::class)->findByMontantMonth($moi,$anne,$id));
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
                $val = $em->getRepository(Vente::class)->findBySommeVenteToWeek(
                    new \DateTimeImmutable($lundi->format('Y-m-d')), 
                    new \DateTimeImmutable($dimanche->format('Y-m-d')),
                    $id);
                array_push($venteSemain,[$semaine,$val]);
                
            }
        }
        $html = $this->renderView('rapport/anne.html.twig', [
        'annees' => $anne,
        'ventes' => $ventemoi,
        'semains' => $venteSemain,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();
        $document = "rapport_annee_".$anne.".pdf";
        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$document.'"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route('/rapport/dette/client', name:'rapport_dette_client_prov')]
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
                    $ventesemetre = $em->getRepository(Vente::class)->findByVenteDetteSemestre($trimestre,$anne,$id);
                    foreach ($ventesemetre as $key => $value) {
                        $sheet->setCellValue('A'.$i, "semestre".$trimestre);
                        $sheet->setCellValue('B'.$i,  $value['nom']);
                        $sheet->setCellValue('C'.$i, $value['type']);
                        $sheet->setCellValue('D'.$i,  $value[1]);
                        $i =$i+1;
                    }
                    $trimestre ++;
                }
           }else{
                $ventespeculation = $em->getRepository(Vente::class)->findByVenteDetteSemestreSpeculation($semestre,$speculation,$anne,$id);
                foreach ($ventespeculation as $key => $value) {
                    $sheet->setCellValue('A'.$i, "semestre".$semestre);
                    $sheet->setCellValue('B'.$i,  $value['nom']);
                    $sheet->setCellValue('C'.$i, $value['type']);
                    $sheet->setCellValue('D'.$i,  $value[1]);
                    $i =$i+1;
                }
           }
        }
        
        $fileName = "Export_dette_".$anne.".xlsx";  
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;       
    }
}
