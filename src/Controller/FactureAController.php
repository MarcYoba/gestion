<?php

namespace App\Controller;

use App\Entity\FactureA;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use App\Entity\VenteA;
use BaconQrCode\Common\ErrorCorrectionLevel as CommonErrorCorrectionLevel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Request;

class FactureAController extends AbstractController
{
    #[Route('/facture/a/view/{id}', name: 'app_facture_a')]
    public function index(EntityManagerInterface $em, $id): Response
    {
        $vente = $em->getRepository(VenteA::class)->find($id);
        $facture = $em->getRepository(FactureA::class)->findBy(["vente"=>$vente]);
        $client = null;
        $vente = null;
        if (is_array($facture) && count($facture) > 0) {
            $client = $facture[0]->getClient();
            $vente = $facture[0]->getVente();
        }
        return $this->render('facture_a/index.html.twig', [
            'facture' => $facture,
            'id' => $id,
            'client' => $client,
            'vente' => $vente,
        ]);
    }

    #[Route('/facture/a/print/{id}', name:'app_print_facture_a')]
    public function Print(EntityManagerInterface $entityManger, int $id, string $filename = 'facture.pdf')
    {
        $tempagence = $entityManger->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $agence =  $tempagence->getAgence();

        $facture = $entityManger->getRepository(FactureA::class)->findBy(['vente'=>$id]);
        $client = null;
        $vente = null;
        if (is_array($facture) && count($facture) > 0) {
            $client = $facture[0]->getClient();
            $vente = $facture[0]->getVente();
        }
        $data = "RCCM:" .$agence->getRccm().
                " Adresse:".$agence->getAdress()." Tel:".
                $agence->getTelephone()."vente:".$vente->getId()."Montant:".$vente->getPrix().
                "FCFA Client:".$client->getNom()."telephone:".$client->getTelephone();

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh()) // <-- Notez le "new" et le nom complet
            ->size(130)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build();
        $base64 = $result->getDataUri();

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);

        $html = $this->renderView('facture_a/print.html.twig', [
        'vente' => $vente,
        'client' => $client,
        'factures' => $facture,
        'agences' => $agence,
        'qrCode' => $base64,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A5', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();

        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Facture.pdf"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route('/facture/a/import', name:'app_import_facture_a')]
    public function import(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

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
                        $prefacture = $em->getRepository(FactureA::class)->findBy(['reference' => $value[0]]);
                        if ($prefacture) {
                          $trouver = $trouver + 1;
                        }else {
                            $vente = $em->getRepository(VenteA::class)->findOneBy(['reference' => $value[9]]);
                            $produit = $em->getRepository(ProduitA::class)->findOneBy(['nom' => $value[1]]);
                            if (!$vente) {
                                $this->addFlash('error', 'Vente non trouvée pour la référence: ' . $value[9]);
                                continue;
                            }
                            if (!$produit) {
                                $this->addFlash('error', 'Produit non trouvé pour le nom: ' . $value[1]);
                                $produit =$em->getRepository(ProduitA::class)->findOneBy(['nom' => "IMPORTATION"]);
                            }
                            $facture = new FactureA();
                            $facture->setReference($value[0]);
                            $facture->setProduit($produit);
                            $facture->setQuantite($value[2]);
                            $facture->setPrix($value[3]);
                            $facture->setMontant($value[4]);
                            $facture->setClient($vente->getClient());
                            $facture->setAgence($vente->getAgence());
                            $facture->setUser($vente->getUser());
                            $facture->setType($value[5]);
                            $facture->setCreateAt(new \DateTimeImmutable($value[8]));
                            $facture->setVente($vente);

                            $em->persist($facture);
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
                    
                    $this->addFlash('success', 'Importation terminée avec succès! Facture trouver : '.$trouver);

                    return $this->redirectToRoute('app_import_facture_a');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
           } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
           

        }
     
        return $this->render("facture_a/import.html.twig",[
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
