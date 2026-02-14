<?php

namespace App\Controller;

use App\Entity\HistoriqueA;
use App\Entity\TempAgence;
use App\Entity\Clients;
use App\Entity\ProduitA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HistoriqueAController extends AbstractController
{
    #[Route('/historique/a/create', name: 'app_historique_a')]
    public function index(EntityManagerInterface $em ): Response
    {
        $tempAgence = $em->getRepository(TempAgence::class)->findOneBy(["user"=> $this->getUser()]) ;
        $id = $tempAgence->getAgence()->getId();
        $client = $em->getRepository(Clients::class)->findAll();

        $historique = $em->getRepository(HistoriqueA::class)->findAll(["agance"=> $id]);

        return $this->render('historique_a/index.html.twig', [
            'client' => $client,
            'historiques'=> $historique
        ]);
    }

    #[Route('/historique/a/download', name: 'app_historique_a_download')]
    public function download(EntityManagerInterface $em, Request $request)
    {
        if ($request->isMethod('POST')) {
            $user = $this->getUser();
            $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            $id = $tempagence->getAgence()->getId();

            $date_debut = $request->request->get('datedebut');
            $date_fin = $request->request->get('datefin');

            if (empty($date_debut) && empty($date_fin)) {
                $date_debut = date("Y-m-d", strtotime(date("Y")."-01-04"));
                $date_fin = date("Y-m-d");
            }
           
            $spreadsheet = new Spreadsheet();
            // Sélectionner la feuille active (par défaut, la première)
            $sheet = $spreadsheet->getActiveSheet();
            $lastedate = 0;
            // Écrire des données dans une cellule
            $sheet->setCellValue('A1', 'Produit');

                $i = 2;
                $ii = 1;
                $data = [];
                $lettre = ord('B');
                $colString  = 0;
                $firstcase = 1;

                $historique = $em->getRepository(HistoriqueA::class)->findByHistoriquePeriode(new \DateTime($date_debut), new \DateTime($date_fin), $id);
                foreach ($historique as $key => $value) {
                    $attrdate = $value->getCreatetAd()->format('Y-m-d');
                    if ($lastedate != $attrdate) {
                        $lastedate = $attrdate;
                        $colString = chr($lettre);
                        $fiscolString  = $colString . '1';
                        array_push($data,$fiscolString);
                        $sheet->setCellValue($fiscolString, $attrdate);
                        $lettre ++;
                        $i = 2;
                    }
                    
                    $sheet->setCellValue('A'.$i, $value->getProduitA()->getNom());
                    $sheet->setCellValue($colString.$i, $value->getQuantite());
                    $i =$i+1;
                }
                
            $nom = "historique".date("Y-m-d");
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
}
