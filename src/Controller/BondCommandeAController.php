<?php

namespace App\Controller;

use App\Entity\AchatA;
use App\Entity\BondCommandeA;
use App\Entity\ProduitA;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BondCommandeAController extends AbstractController
{
    #[Route('/bond/commande/a/create', name: 'app_bond_commande_a')]
    public function index(EntityManagerInterface $em): Response
    {
        $bondCommande = $em->getRepository(BondCommandeA::class)->findAll();
        if (empty($bondCommande)) {
            $produit = $em->getRepository(ProduitA::class)->findAll();
            foreach ($produit as $prod) {
                $bond = new BondCommandeA();
                $bond->setProduit($prod);
                $bond->setStatut(0);
                $bond->setCreatetAt(new \DateTime());
                $bond->setLimite(0);
                $em->persist($bond);
            }
            $em->flush();
        }
        return $this->render('bond_commande_a/index.html.twig', [
            
        ]);
    }

    #[Route('/bond/commande/a/export/pdf', name: 'app_bond_commande_a_export_pdf')]
    public function export_pdf(EntityManagerInterface $em): Response
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true); //Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        $bondCommande = $em->getRepository(BondCommandeA::class)->findByProduitACommander();
        $achat = $em->getRepository(AchatA::class)->findAll();
        
        $html = $this->renderView('bond_commande_a/export_pdf.html.twig', [
          'bondCommandes' => $bondCommande,
          'achats' => $achat,
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
                'Content-Disposition' => 'inline; filename="bond_de_commande.pdf"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route('/bond/commande/a/import', name: 'app_bond_commande_a_import')]
    public function import(EntityManagerInterface $em,Request $request): Response
    {
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
                    $donnees = $donnees['Sheet1'];
                    array_shift($donnees);
                    $total = count($donnees);
                    $i = 0;
                    $trouver = 0;
                    $this->addFlash('success', 'Importation démarrée');
                    
                    foreach ($donnees as $key => $value) {
                        $produit = $em->getRepository(ProduitA::class)->findOneBy(["nom" => $value[0]]);
                        if ($produit){
                            $bondCommande = $em->getRepository(BondCommandeA::class)->findOneBy(['produit' => $produit]);
                            if ($bondCommande){
                                $bondCommande->setLimite((int)$value[1]);
                                $em->persist($bondCommande);
                                $em->flush();
                                $trouver++;
                            }
                        }
                        $processed++;
                        $progress = round(($i + 1) / $total * 100);
                        // Messages avec barre de progression ASCII
                        if ($progress % 20 === 0) {
                            $bar = str_repeat('█', $progress / 5) . str_repeat('░', 20 - ($progress / 5));
                            $this->addFlash('success', "[$bar] $progress% - Ligne " . ($i + 1) . "/$total");
                        }
                        $i++;
                    }
                    
                    $this->addFlash('success', 'Importation terminée avec succès! Produit trouver : '.$trouver);

                    return $this->redirectToRoute('app_produit_a_import_quantite');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
            } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
        }
        return $this->render('bond_commande_a/index.html.twig', [
            'controller_name' => 'BondCommandeAController',
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
