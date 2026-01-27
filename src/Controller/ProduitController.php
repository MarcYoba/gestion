<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\Produit;
use App\Entity\TempAgence;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProduitController extends AbstractController
{
    #[Route('/produit/create', name: 'app_produit')]
    public function index(Request $request,EntityManagerInterface $entityManager,Security $security): Response
    {
        $produit = new Produit();
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute("app_logout");
        }
        $form = $this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $form->get('nom')->getData();
            $stock = $form->get('stockdebut')->getData();
            $nom = mb_strtoupper($nom, 'UTF-8');
            $produitExite = $entityManager->getRepository(Produit::class)->findBy(['nom' => $nom]);
            if (!empty($produitExite)) {
               return $this->redirectToRoute("produit_list"); 
            }
            $produit->setNom($nom);
            $produit->setPrixachat(0);
            $produit->setGain(0);
            $produit->setQuantite($stock);
            $produit->setUser($security->getUser());
            $employer = new Employer();
            $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            if ($tempagence) {
               $employer = $tempagence->getAgence();
            }else{
                $this->addFlash('error', 'Agence introuvable pour cet utilisateur vous ne pouvez enregistrer de produit.');
                return $this->redirectToRoute("produit_list");
            }
            $produit->setAgence($employer);

            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->redirectToRoute("produit_list");
        }
        return $this->render('produit/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/list', name: 'produit_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $produit = new Produit();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
         if ($tempagence->isGenerale()== 1) {
            $produit = $entityManager->getRepository(Produit::class)->findAll();
        }else{
            $produit = $entityManager->getRepository(Produit::class)->findBy(["agence" => $id]);
        }
        
        return $this->render('produit/list.html.twig', [
            'produits' => $produit,
        ]);
    }

    #[Route('/produit/recherche/prix', name: 'produit_prix_recherche')]
    public function RecherchePrix(EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $json = $request->getContent();
            $donnees = json_decode($json, true);
            if (isset($donnees['nom'])) {
                $produit = $entityManager->getRepository(Produit::class)->findBy(['nom' => $donnees['nom']]);
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
    #[Route('/produit/edit/{id}', name: 'produit_edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute("produit_list");
        }
        return $this->render('produit/edit.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    }

    #[Route('/produit/delete/{id}', name: 'produit_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
        $entityManager->remove($produit);
        $entityManager->flush();
        return $this->redirectToRoute("produit_list");
    }

    #[Route('/produit/impot', name:'app_produit_import')]
    public function FunctionName(Request $request, EntityManagerInterface $entityManager) : Response 
    {
        $user = $this->getUser();
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
                        $produit = $entityManager->getRepository(Produit::class)->findBy(["nom" => $value[0]]);
                        if($produit){
                            $trouver +=1;
                        }else{
                            $produits = new Produit();
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

                    return $this->redirectToRoute('app_produit_import');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
           } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
           

        }
        return $this->render('produit/import.html.twig', [
            
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

    #[Route('/produit/download', name:('produit_download'))]
    public function download(EntityManagerInterface $em) : Response 
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        $produit = $em->getRepository(Produit::class)->findBy(['agence' => $id]);
        //dd($vente);
        $html = $this->renderView('produit/dwonload.html.twig', [
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
}
