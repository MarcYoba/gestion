<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Agence;
use App\Entity\Balance;
use App\Entity\TempAgence;
use App\Entity\Fournisseur;
use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\User;
use App\Form\AchatType;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AchatController extends AbstractController
{
    #[Route('/achat/create', name: 'app_achat')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $achat = new Achat();
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);
        $type = 0;
        if($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $data = json_decode($request->getContent(), true);
            
            try {
                foreach ($data as $key) {

                    $achat = new Achat(); 

                    try {
                        $date = empty($key['datevalue']) 
                            ? new \DateTimeImmutable()
                            : new \DateTimeImmutable($key['datevalue']);
                    } catch (\Exception $e) {
                        $date = new \DateTimeImmutable();
                    }
                    $achat->setCreatedAt($date);
                    

                    $fournisseur = $entityManager->getReference(Fournisseur::class, $key['fournisseur']);
                    $produit = $entityManager->getReference(Produit::class, $key['produit']);
                    $magasin = $entityManager->getRepository(Magasin::class)->findOneBy(['produit' => $produit->getId()]);
                    
                    
                    
                    $type = $key["type"];
                    $achat->setPrix($key["prix"]);
                    $achat->setQuantite($key["quantite"]);
                    $achat->setMontant($key["total"]);
                    $achat->setType($key["type"]);
                    $achat->setUser($this->getUser());
                    $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $this->getUser()]);
                    $achat->setAgence($tempagence->getAgence());
                    $achat->setFournisseur($fournisseur);
                    $achat->setProduit($produit);
                    $achat->setReference(0);
                    $fournisseur->addProduit($produit);
                    $entityManager->persist($fournisseur);

                    if ($magasin) {
                        $magasin->setQuantite($magasin->getQuantite() + $key["quantite"]);
                        $operation = $magasin->getOperation();
                        $operation[] = $date->format('Y-m-d')." "."Achat";
                        $magasin->setOperation($operation);
                        $entityManager->persist($magasin);
                    } else {
                        $newMagasin = new Magasin();
                        $newMagasin->setProduit($produit);
                        $newMagasin->setQuantite($key["quantite"]);
                        $newMagasin->setAgence($tempagence->getAgence());
                        $newMagasin->setCreatetAt(new \DateTime());
                        $newMagasin->setUser($this->getUser());
                        $newMagasin->setOperation([$date->format('Y-m-d')." "."Achat"]);
                        $entityManager->persist($newMagasin);
                    }
                    $produit->setPrixachat($key["prix"]);
                    $entityManager->persist($produit);
                    $entityManager->persist($achat);
                    
                }
                $entityManager->flush();

                if ($type == "CASH") {
                    // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 6021]);
                    // if ($balance) {
                    //     $montant = $balance->getMouvementDebit();
                    //     $balance->setMouvementDebit($montant + $key["total"]);
                    // }
                    // $entityManager->persist($balance);
                    // $entityManager->flush();
                    // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 5111]);
                    // if ($balance) {
                    //     $montant = $balance->getMouvementCredit();
                    //     $balance->setMouvementCredit($montant + $key["total"]);
                    // }
                    // $entityManager->persist($balance);
                    // $entityManager->flush();
                }elseif ($type == "BANQUE") {
                    // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 6021]);
                    // if ($balance) {
                    //     $montant = $balance->getMouvementDebit();
                    //     $balance->setMouvementDebit($montant + $key["total"]);
                    // }
                    // $entityManager->persist($balance);
                    // $entityManager->flush();
                    // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 5121]);
                    // if ($balance) {
                    //     $montant = $balance->getMouvementCredit();
                    //     $balance->setMouvementCredit($montant + $key["total"]);
                    // }
                    // $entityManager->persist($balance);
                    // $entityManager->flush();
                }
                return $this->json(['success' => true], 200);
            } catch (\Throwable $th) {
                return $this->json(['errors' => $th], 500);
            }
            
            
        }
        return $this->render('achat/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/achat/list', name: 'achat_list')]
    public function list(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $produit = 0;
        $achat = 0;
        $fournisseur = 0;
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" =>$user]);
       
        // $id = $tempagence?->getAgence()?->getId();
        // if (Empty($tempagence)) {
        //     $this->redirectToRoute('app_logout');
        // }
        $anneeselect = $request->query->get('annee',date('Y'));

        $id = $tempagence->getAgence()->getId();
        if ($tempagence->isGenerale()== 1) {
            $produit = $entityManager->getRepository(Achat::class)->findAll();
            $achat = $entityManager->getRepository(Achat::class)->findByYearAgence($anneeselect,$id);
            $fournisseur = $entityManager->getRepository(Fournisseur::class)->findAll();
        }else{
            $produit = $entityManager->getRepository(Achat::class)->findBy(["agence" => $id]);
            $achat = $entityManager->getRepository(Achat::class)->findByYearAgence($anneeselect,$id);
            $fournisseur = $entityManager->getRepository(Fournisseur::class)->findBy(["agence" => $id]);
        }

        $produits = $entityManager->getRepository(Produit::class)->findBy(["agence" => $id]);
        return $this->render('achat/list.html.twig', [
            'achat' => $achat,
            'produit' => $produit,
            'produits' => $produits,
            'fournisseur' => $fournisseur,
            'anneeselect' => $anneeselect,
        ]);
    }

    #[Route('/achat/list/direction', name: 'achat_list_direction')]
    public function listDirection(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $produit = 0;
        $achat = 0;
        $fournisseur = 0;
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" =>$user]);
        
        $id = $tempagence->getAgence()->getId();
        
        $produit = $entityManager->getRepository(Achat::class)->findAll();
        $achat = $entityManager->getRepository(Achat::class)->findAll();
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->findAll();
        
        $produits = $entityManager->getRepository(Produit::class)->findAll();
        return $this->render('achat/list_direction.html.twig', [
            'achat' => $achat,
            'produit' => $produit,
            'produits' => $produits,
            'fournisseur' => $fournisseur,
        ]);
    }

    #[Route('/achat/edit/{id}', name: 'app_achat_edit')]
    public function Edit(EntityManagerInterface $entityManager, Achat $achat) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" =>$user]);
        $id = $tempagence->getAgence()->getId();        
        if (!$achat) {
            return $this->redirectToRoute('achat_list');
        }
        $produit = $entityManager->getRepository(Produit::class)->findAll();
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->findBy(["agence" => $id]);

        return $this->render('achat/edit.html.twig', [
            'achats' => $achat, 
            'produit' => $produit,
            'fournisseur' => $fournisseur,
        ]);
    }

    #[Route('/achat/update',name:'achat_update', methods:'POST' )]
    public function update(EntityManagerInterface $entityManager, Request $request) : Response 
    {
       $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" =>$user]);
        $id = $tempagence->getAgence()->getId(); 
        $achats = $request->request->all('achats');
        foreach ($achats as $key => $value) {
            $achat = $entityManager->getRepository(Achat::class)->find($key);
            $produit = $entityManager->getRepository(Produit::class)->find($value['produit']); 

            if (!empty($value['quantite_nouvelle']) && !empty($value['prix_nouvelle'])) {
                $quantitestock =  $produit->getQuantite() - $value['quantite_precedent'];
            
                $produit->setQuantite($quantitestock + $value['quantite_nouvelle']);

                $achat->setQuantite($value['quantite_nouvelle']);
                $achat->setPrix($value['prix_nouvelle']);
                $achat->setMontant($value['quantite_nouvelle'] * $value['prix_nouvelle']);

                $entityManager->persist($produit);
                $entityManager->flush();
            }

            $achat->settype($value['type']);
            $achat->setUser($user);

            $entityManager->persist($achat);
            $entityManager->flush();
            
        }

        return $this->redirectToRoute('achat_list');
    }

    #[Route('achat/delete/{id}', name:'achat_delete')]
    public function delete(EntityManagerInterface $entityManager, Achat $achat) : Response 
    {
        if ($achat) {
            $produit = $entityManager->getRepository(Produit::class)->find($achat->getProduit()->getId());
            if($produit){
                $quantite = $produit->getQuantite();
                $produit->setQuantite($quantite- $achat->getQuantite());

                $entityManager->persist($produit);
                $entityManager->flush();
            }
            
        }
        return $this->redirectToRoute('achat_list');
    }

    #[Route('/achat/download', name:'achat_download', methods:['POST'])]
    public function download(EntityManagerInterface $em, Request $request) : Response 
    {
        $achat= $request->request->all();
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(["user" =>$user]);
        $id = $tempagence->getAgence()->getId();
        
        $produit = $achat['nomProduite'];
        $first_date = $achat['date1'];
        $date_end = $achat['date2'];
        
            if ((!empty($date_end)) && (!empty($first_date)) && ($produit == "ALL")) {
                $achat =$em->getRepository(Achat::class)->findByFirstAndLastDay($first_date,$date_end,$id);
            }else if ((!empty($date_end)) && (!empty($first_date)) && ($produit != "ALL")) {
                $achat = $em->getRepository(Achat::class)->findByFirstAndLastDayProduit($first_date,$date_end,$produit,$id);
            }else if (!empty($first_date) && $produit == "ALL" && empty($date_end)) {
                $achat = $em->getRepository(Achat::class)->findByDay($first_date,$id);
            }else if($produit == "ALL" && !empty($date_end)  && empty($first_date)){
                $achat = $em->getRepository(Achat::class)->findByDay($date_end,$id);
            }else if($produit == "ALL" && empty($first_date) && empty($date_end)){
                $achat = $em->getRepository(Achat::class)->findBy(['agence' => $id]);
            }else if($produit != "ALL" && empty($first_date) && empty($date_end)){
                $achat = $em->getRepository(Achat::class)->findByProduit($produit,$id);
            }else{
                $achat = $em->getRepository(Achat::class)->findBy(['agence' => $id]);
            }

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        $produit = $em->getRepository(Achat::class)->findBy(['agence' => $id]);
        
        $html = $this->renderView('achat/dwonload.html.twig', [
        'achats' => $achat,
        'first_date' => $first_date,
        'date_end' => $date_end,
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

    #[Route('/achat/import/anne', name: 'achat_import_anne')]
    public function Achat_import(EntityManagerInterface $em,Request $request) : Response {
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
                        $preachat = $em->getRepository(Achat::class)->findBy(['reference' => $value[0]]);
                        if ($preachat) {
                          $trouver = $trouver + 1;
                        }else {
                            $mot = $value[1];
                            $resultat = ltrim($mot);
                            $produit = $em->getRepository(Produit::class)->findOneBy(['nom' => $resultat]);
                            $fournisseur = $em->getRepository(Fournisseur::class)->findOneBy(['reference' => $value[8]]);
                            $utilisateur = $em->getRepository(User::class)->findOneBy(['reference' => $value[9]]);
                            if (!$produit) {
                                $this->addFlash('error', 'produit non trouvée pour la référence: ' . $value[1]);
                                continue;
                            }
                            if (!$fournisseur) {
                                $this->addFlash('error', 'fournisseur non trouvée pour la référence: ' . $value[8]);
                                continue;
                            }
                            if (!$utilisateur) {
                                $this->addFlash('error', 'utilisateur non trouvée pour la référence: ' . $value[9]);
                                continue;
                            }

                            $achat = new Achat();
                            $achat->setAgence($tempagence->getAgence());
                            $achat->setCreatedAt(new \DateTimeImmutable($value[6]));
                            $achat->setFournisseur($fournisseur);
                            $achat->setProduit($produit);
                            $achat->setMontant($value[4]);
                            $achat->setPrix($value[2]);
                            $achat->setQuantite($value[3]);
                            $achat->setType("CASH");
                            $achat->setUser($utilisateur);
                            $achat->setReference($value[0]);

                            $em->persist($achat);
                            $em->flush();

                            $processed++;
                            $progress = round(($i + 1) / $total * 100);
                            
                            if ($progress % 20 === 0) {
                                $bar = str_repeat('█', $progress / 5) . str_repeat('░', 20 - ($progress / 5));
                                $this->addFlash('success', "[$bar] $progress% - Ligne " . ($i + 1) . "/$total");
                            }
                            $i++;
                        }
                        
                    }
                    
                    $this->addFlash('success', 'Importation terminée avec succès! Achat trouver : '.$trouver);

                    return $this->redirectToRoute('achat_import_anne');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
           } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
           

        }
     
        return $this->render("achat/import.html.twig",[
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
