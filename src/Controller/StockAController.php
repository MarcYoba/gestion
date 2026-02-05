<?php

namespace App\Controller;

use App\Entity\AchatA;
use App\Entity\FactureA;
use App\Entity\HistoriqueA;
use App\Entity\MagasinA;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StockAController extends AbstractController
{
    #[Route('/stock/a/recapitulatif', name: 'app_stock_a_recapitulatif')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $date = date("Y"."-01"."-04");
        
        $produits = [];
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $produit = $em->getRepository(ProduitA::class)->findBy(["agence" => $id]);
        foreach ($produit as $key => $value) {
            $historiquequatite = 0;
            $historiques = $em->getRepository(HistoriqueA::class)->findByProduitAgence($value,$id,$date);
            $sommeachat = $em->getRepository(AchatA::class)->findBySommeAchatProduitDay($date,$value,$id);
            $sommeventejour = $em->getRepository(FactureA::class)->findByQuantiteProduitVendu(new \DateTimeImmutable(), $value->getid(), $id);
            $sommevente = $em->getRepository(FactureA::class)->findByQuantiteProduitVenduAnne($date, $value, $id);
            $magasinQt = $em->getRepository(MagasinA::class)->findOneBy(['produit' => $value, 'agence' => $id]);
            if ($historiques) {
                $historiquequatite = $historiques->getQuantite();
            }
            array_push($produits, [$value->getNom(),$historiquequatite, $sommeachat,$sommeventejour,$sommevente,$value->getQuantite(),empty($magasinQt)?0:$magasinQt->getQuantite()]);
        }
        
        return $this->render('stock_a/recapitulatif.html.twig', [
            'produits' => $produit,
            'produitsData' => $produits,

        ]);
    }

    #[Route('/stock/a/perte', name: 'app_stock_a_perte')]
    public function perte(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $date = date("Y"."-01"."-04");
        
        $produits = [];
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $produit = $em->getRepository(ProduitA::class)->findBy(["agence" => $id]);
        foreach ($produit as $key => $value) {
            $historiquequatite = 0;
            $historiques = $em->getRepository(HistoriqueA::class)->findByProduitAgence($value,$id,$date);
            $sommeachat = $em->getRepository(AchatA::class)->findBySommeAchatProduitDay($date,$value,$id);
            $sommeventejour = $em->getRepository(FactureA::class)->findByQuantiteProduitVendu(new \DateTimeImmutable(), $value->getid(), $id);
            $sommevente = $em->getRepository(FactureA::class)->findByQuantiteProduitVenduAnne($date, $value, $id);
            $magasinQt = $em->getRepository(MagasinA::class)->findOneBy(['produit' => $value, 'agence' => $id]);
            if ($historiques) {
                $historiquequatite = $historiques->getQuantite();
            }
            $stocktreel = $value->getQuantite() + (empty($magasinQt)?0:$magasinQt->getQuantite());
            $perte = ($sommevente + $stocktreel) - ($historiquequatite + $sommeachat);
            array_push($produits, [$value->getNom(),$historiquequatite + $sommeachat,$sommevente,$stocktreel,$perte,$value->getPrixvente()]);
        }
        
        return $this->render('stock_a/perte.html.twig', [
            'produits' => $produit,
            'produitsData' => $produits,

        ]);
    }

    #[Route('/stock/a/perte/download', name: 'app_stock_a_download')]
    public function download(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'Produit');
        $sheet->setCellValue('B1', 'Stock initial');
        $sheet->setCellValue('C1', 'Somme Acaht');
        $sheet->setCellValue('D1', 'Somme Vente');
        $sheet->setCellValue('E1', 'quantite en stock');
        $sheet->setCellValue('F1', 'ecart');
        $sheet->setCellValue('G1', 'Prix de vente');
        $sheet->setCellValue('H1', 'Montant perte');

            $i = 2;
            $produit = $em->getRepository(ProduitA::class)->findBy(["agence" => $id]);
            $date = date("Y"."-01"."-04");
            foreach ($produit as $key => $value) {
                $historiquequatite = 0;
                $historiques = $em->getRepository(HistoriqueA::class)->findByProduitAgence($value,$id,$date);
                $sommeachat = $em->getRepository(AchatA::class)->findBySommeAchatProduitDay($date,$value,$id);
                $sommeventejour = $em->getRepository(FactureA::class)->findByQuantiteProduitVendu(new \DateTimeImmutable(), $value->getid(), $id);
                $sommevente = $em->getRepository(FactureA::class)->findByQuantiteProduitVenduAnne($date, $value, $id);
                $magasinQt = $em->getRepository(MagasinA::class)->findOneBy(['produit' => $value, 'agence' => $id]);
                if ($historiques) {
                    $historiquequatite = $historiques->getQuantite();
                }
                $stocktreel = $value->getQuantite() + (empty($magasinQt)?0:$magasinQt->getQuantite());
                $perte =   ($sommevente + $stocktreel) -($historiquequatite + $sommeachat);

                $sheet->setCellValue('A'.$i, $value->getNom());
                $sheet->setCellValue('B'.$i, $historiquequatite);
                $sheet->setCellValue('C'.$i, $sommeachat );
                $sheet->setCellValue('D'.$i, $sommevente);
                $sheet->setCellValue('E'.$i, $stocktreel); 
                $sheet->setCellValue('F'.$i, $perte);
                $sheet->setCellValue('G'.$i, $value->getPrixvente());
                $sheet->setCellValue('H'.$i, $perte * $value->getPrixvente());
               $i =$i+1;
            }
        $nom = "inventaire".date("Y-m-d");
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);
        $nom = $nom.".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$nom.'"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;
    }
}
