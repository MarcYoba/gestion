<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\BondCommande;
use App\Entity\Fournisseur;
use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;

class BondCommandeController extends AbstractController
{
    #[Route('/bond/commande/create', name: 'app_bond_commande')]
    public function index(EntityManagerInterface $em, Request $request): Response
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
                    $donnees = $donnees['Worksheet'];
                    array_shift($donnees);
                    $total = count($donnees);
                    $i = 0;
                    $trouver = 0;
                    $this->addFlash('success', 'Importation démarrée');
                    
                    foreach ($donnees as $key => $value) {
                        $produit = $em->getRepository(Produit::class)->findOneBy(["nom" => $value[0]]);
                        if ($produit){
                            $bondCommande = $em->getRepository(BondCommande::class)->findOneBy(['produit' => $produit]);
                            if ($bondCommande){
                                $bondCommande->setLimite((int)$value[1]);
                                $bondCommande->setStatut(1);
                                $em->persist($bondCommande);
                                $em->flush();
                                $trouver++;
                            }else{
                                $bondCommande = new BondCommande();
                                $bondCommande->setLimite((int)$value[1]);
                                $bondCommande->setStatut(1);
                                $bondCommande->setCreatetAt(new \DateTime());
                                $bondCommande->setProduit($produit);
                                $em->persist($bondCommande);
                                $em->flush();
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

                    return $this->redirectToRoute('app_bond_commande');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
            } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
        }
        return $this->render('bond_commande/index.html.twig', [
            'controller_name' => 'BondCommandeController',
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

    #[Route('/bond/commande/export/pdf', name: 'app_bond_commande_export_pdf')]
    public function export_pdf(EntityManagerInterface $em): Response
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true); //Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        $bondCommande = $em->getRepository(Produit::class)->FindByBonCommandAutre();
        $achat = $em->getRepository(Achat::class)->findAll();
        
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

    #[Route('/bond/commande/liste/fourisseur', name: 'app_bond_commande_liste_fourisseur')]
    public function ListeFourisseur(EntityManagerInterface $entityManager) : Response {

        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $id = $tempagence->getAgence()->getId();

        $foournisseurs = $entityManager->getRepository(Fournisseur::class)->findAll();
        return $this->render('bond_commande/liste_fourisseur.html.twig', [
            'id' => $id,
            'fournisseurs' => $foournisseurs,
        ]);
    }
    #[Route('/bond/commande/liste/fourisseur/{id}', name: 'app_bond_commande_liste_fourisseur_id')]
    public function ListeFourisseurId(EntityManagerInterface $entityManager, Fournisseur $fournisseur) : Response {
        $options = new Options();
        $options->set('isRemoteEnabled', true); //Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        $bondCommande = $entityManager->getRepository(Produit::class)->FindByBonCommandFournisseur($fournisseur);
        $achat = $entityManager->getRepository(Achat::class)->findAll();
        $magasin = $entityManager->getRepository(Magasin::class)->findAll();
        
        $html = $this->renderView('bond_commande_a/export_fournisseur_pdf.html.twig', [
          'bondCommande' => $bondCommande,
          'achats' => $achat,
          'fournisseur' => $fournisseur,
          'magasins' => $magasin,
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

    #[Route('/bond/commande/liste/fournisseur/excel/{id}', name: 'app_bond_command_list_fournisseur_id_excel')]
    public function ListeFourisseurExcel(EntityManagerInterface $em,Fournisseur $fournisseurs) : Response {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'produit');
        $sheet->setCellValue('B1', 'Quantite Magasin');
        $sheet->setCellValue('C1', 'Quantite contoire');
        $sheet->setCellValue('D1', 'foutnisseur');
        
      //  $sheet->setCellValue('R1', 'MOMO');

            $i = 2;
        $bondCommande = $em->getRepository(Produit::class)->FindByBonCommandFournisseur($fournisseurs);
   
        
            foreach ($bondCommande as $key => $value) {
                $produit = $em->getRepository(Produit::class)->findOneBy(['nom' => $value['nom']]);
                $magasin = $em->getRepository(Magasin::class)->findOneBy(['produit' => $produit]);
                $quantite = 0;
                if ($magasin) {
                    $quantite = $magasin->getQuantite();
                }
                $sheet->setCellValue('A'.$i, $value["nom"]);
                $sheet->setCellValue('B'.$i, $quantite );
                $sheet->setCellValue('C'.$i, $value["quantite"]);
                $sheet->setCellValue('D'.$i, $fournisseurs->getNom()); 
                
               // $sheet->setCellValue('R'.$i, $value->getMontantmomo());
                $i =$i+1;
            }
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);
        $nom = $fournisseurs->getNom().".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$nom.'"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;
    }

    #[Route('/bond/command/liste/fournisseur/axcel', name:'app_bond_commande_liste_fourisseur_excel')]
    public function ListeFournisseurAutreExcel(EntityManagerInterface $em) : Response {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $spreadsheet = new Spreadsheet();
        // Sélectionner la feuille active (par défaut, la première)
        $sheet = $spreadsheet->getActiveSheet();

        // Écrire des données dans une cellule
        $sheet->setCellValue('A1', 'produit');
        $sheet->setCellValue('B1', 'Quantite Magasin');
        $sheet->setCellValue('C1', 'Quantite contoire');
        $sheet->setCellValue('D1', 'foutnisseur');
        
      //  $sheet->setCellValue('R1', 'MOMO');

            $i = 2;
            $bondCommande = $em->getRepository(Produit::class)->FindByBonCommandAutre();
   
        
            foreach ($bondCommande as $key => $value) {
                $produit = $em->getRepository(Produit::class)->findOneBy(['nom' => $value['nom']]);
                $magasin = $em->getRepository(Magasin::class)->findOneBy(['produit' => $produit]);
                $quantite = 0;
                if ($magasin) {
                    $quantite = $magasin->getQuantite();
                }
                $sheet->setCellValue('A'.$i, $value["nom"]);
                $sheet->setCellValue('B'.$i, $quantite );
                $sheet->setCellValue('C'.$i, $value["quantite"]);
                $sheet->setCellValue('D'.$i, 0); 
                
               // $sheet->setCellValue('R'.$i, $value->getMontantmomo());
                $i =$i+1;
            }
        // Créer un writer pour le format XLSX
        $writer = new Xlsx($spreadsheet);
        $nom = "autre_bond_commande".".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$nom.'"'); 

        header('Cache-Control: max-age=0');

        // Sauvegarder le fichier directement dans la sortie
        $writer->save('php://output');
        exit;
    }
}
