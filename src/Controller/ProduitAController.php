<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\HistoriqueA;
use App\Entity\Lots;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use App\Form\ProduitAType;
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

class ProduitAController extends AbstractController
{
    #[Route('/produit/a/create', name: 'app_produit_a')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $produitA = new ProduitA();
        $form = $this->createForm(ProduitAType::class, $produitA);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $form->get('nom')->getData();
            $nom = mb_strtoupper($nom, 'UTF-8');
            $produitExite = $em->getRepository(ProduitA::class)->findBy(['nom' => $nom]);
            if (!empty($produitExite)) {
               return $this->redirectToRoute("produit_a_list"); 
            }
            $produitA->setNom($nom);
            $produitA->setUser($this->getUser());
            $produitA->setGain(0);
            $produitA->setStockdebut($produitA->getQuantite());

            $employer = new Employer();
            $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            if ($tempagence->getAgence()) {
               $employer = $tempagence->getAgence();
            }else{
                $this->addFlash('error', 'Agence introuvable pour cet utilisateur vous ne pouvez enregistrer de produit.');
                return $this->redirectToRoute("produit_a_list");
            }
            $produitA->setAgence($employer);
            $em->persist($produitA);
            $em->flush();

            return $this->redirectToRoute('produit_a_list');
        }
        return $this->render('produit_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/a/list', name: 'produit_a_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $produits = $em->getRepository(ProduitA::class)->findAll(["agence" => $id]);
        return $this->render('produit_a/list.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/produit/a/edit/{id}', name: 'produit_a_edit')]
    public function edite(Request $request, EntityManagerInterface $em,int $id): Response
    {

        $produits = $em->getRepository(ProduitA::class)->find($id);
        if (!$produits) {
            $this->addFlash('error','Le produit que vous recherche n\'existe pas');
        }
        $form = $this->createForm(ProduitAType::class,$produits);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('produit_a_list');
        }

        return $this->render('produit_a/index.html.twig', [
            'form' => $form->createView(),
            'produit' => $produits,
        ]);
    }

    #[Route('/produit/a/delete/{id}', name: 'produit_a_delete')]
    public function delete(EntityManagerInterface $em, int $id): Response
    {
        $produit = $em->getRepository(ProduitA::class)->find($id);
        if (!$produit) {
            $this->addFlash('error','Le produit que vous recherche n\'existe pas');
        }else{
            $historiques = $produit->getHistoriqueAs(); // Supposant que vous avez une méthode getHistoriquesA() dans l'entité ProduitA
            foreach ($historiques as $historique) {
                $em->remove($historique);
            }

            $em->remove($produit);
            $em->flush();
            $this->addFlash('success','Produit supprimer avec success');
        }
        return $this->redirectToRoute('produit_a_list');
    }

    #[Route('/produit/a/recherche/prix', name: 'produit_prix_a_recherche')]
    public function RecherchePrix(EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $json = $request->getContent();
            $donnees = json_decode($json, true);
            if (isset($donnees['nom'])) {
                $produit = $entityManager->getRepository(ProduitA::class)->findBy(['nom' => $donnees['nom']]);
                if ($produit) {
                    return $this->json([
                        'success' => true,
                        'message' => $produit[0]->getPrixvente(),
                        'quantite' => $produit[0]->getQuantite(),
                    ]);
                } else {
                    return $this->json(['error' => 'Produit non trouvé'], 404);
                }
            }
        }
        return $this->json(['error' => 'Prix non spécifié'], 404);
    }

    #[Route('/produit/a/impot', name:'app_produit_a_import')]
    public function FunctionName(Request $request, EntityManagerInterface $entityManager) : Response 
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
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
                        $produit = $entityManager->getRepository(ProduitA::class)->findOneBy(["nom" => $value[0]]);
                        if($produit){
                            //$produits = $entityManager->getRepository(ProduitA::class)->findOneBy(["nom" => $value[0]]);
                            $trouver +=1;
                            $produit->setReference(empty($value[6]) ? 0 : $value[6]);

                            $entityManager->persist($produit);
                            $entityManager->flush();
                        }else{
                            $produits = new ProduitA();
                            $produits->setAgence($tempagence->getAgence());
                            $produits->setUser($user);
                            $produits->setNom($value[0]);
                            $produits->setQuantite(0);
                            $produits->setStockdebut(0);
                            $produits->setPrixachat(0);
                            $produits->setPrixvente($value[2]);
                            $produits->setGain(0);
                            $produits->setType($value[4]);
                            $produits->setCathegorie($value[5]);
                            $produits->setCreatedAt(new \DateTimeImmutable(date("Y-m-d")));
                            $produits->setReference(empty($value[6]) ? 0 : $value[6]);

                            $entityManager->persist($produits);
                            $entityManager->flush();

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

                    return $this->redirectToRoute('app_produit_a_import');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
           } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
           

        }
        return $this->render('produit_a/import.html.twig', [
            
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

    #[Route('/produit/a/sans/date/peremption', name:'app_produit_sans_date_peramtion')]
    public function produit_sans_date(EntityManagerInterface $em) : Response 
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        $produit = $em->getRepository(ProduitA::class)->findBy(['expiration' => '0']);
        $lots = $em->getRepository(Lots::class)->findBy(['expiration' => '0']);
        $doublon = $em->getRepository(ProduitA::class)->findByDoublon();
        
        $html = $this->renderView('produit_a/peramption.html.twig', [
            'produits' => $produit,
            'lots'=> $lots,
            'doublons'=> $doublon,
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

    #[Route('/produit/a/perimer', name:'app_produit_perimer')]
    public function produit_perimer(EntityManagerInterface $em) : Response 
    {
         
        
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
       
        $doublon = $em->getRepository(ProduitA::class)->findByDoublon();
        $peramption = $em->getRepository(ProduitA::class)->findByDateExpiration(6);
        $lots = $em->getRepository(Lots::class)->findByDateExpirationLots(6);

        $html = $this->renderView('produit_a/peramption.html.twig', [
            'doublons'=> $doublon,
            'produits' => $peramption,
            'lots' => $lots,
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

    #[Route('/produit/a/download', name:('produit_download_a'))]
    public function download(EntityManagerInterface $em) : Response 
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        $produit = $em->getRepository(ProduitA::class)->findBy(['agence' => $id]);
        //dd($vente);
        $html = $this->renderView('produit_a/dwonload.html.twig', [
        'produits' => $produit,
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
                'Content-Disposition' => 'inline; filename="Inventaire.pdf"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route('/produit/a/impot/quantite', name:'app_produit_a_import_quantite')]
    public function Import_Quantite(Request $request, EntityManagerInterface $entityManager) : Response 
    {
        
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
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
                    $donnees = $donnees['Sheet1'];
                    array_shift($donnees);
                    $total = count($donnees);
                    $i = 0;
                    $trouver = 0;
                    $this->addFlash('success', 'Importation démarrée');
                    
                    foreach ($donnees as $key => $value) {
                        $produit = $entityManager->getRepository(ProduitA::class)->findOneBy(["nom" => $value[0]]);
                        if($produit){ 
                            $trouver +=1;

                            $quantite = $produit->getQuantite();

                            $produit->setQuantite($quantite-$value[1]);
                            //$produit->setStockdebut($value[1]);

                            $entityManager->persist($produit);
                            $entityManager->flush();
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
        return $this->render('produit_a/import_quantite.html.twig', [
            
        ]);
    }
}
