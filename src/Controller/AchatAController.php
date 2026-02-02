<?php

namespace App\Controller;

use App\Entity\AchatA;
use App\Entity\Balance;
use App\Entity\BalanceA;
use App\Form\AchatAType;
use App\Entity\FournisseurA;
use App\Entity\Lots;
use App\Entity\MagasinA;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AchatAController extends AbstractController
{
    #[Route('/achat/a/create', name: 'app_achat_a')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $achatA = new AchatA();
        $type =0;
        if ($request->isXmlHttpRequest() || $request->getContentType() === 'json') {
            $data = json_decode($request->getContent(), true);
            
            try {
                foreach ($data as $key) {
                    $achatA = new AchatA();
                    $lots = new Lots();
                    $date = empty($key['datevalue']) ? new \DateTimeImmutable() : new \DateTimeImmutable($key['datevalue']);
                    $achatA->setCreatedAt($date);
    
                    $fournisseurA = $em->getReference(FournisseurA::class, $key['fournisseur']);
                    $idproduit = $em->getRepository(ProduitA::class)->findBy(['nom' => $key['produit']]);
                    $produitA = $em->getReference(ProduitA::class, $idproduit[0]->getId());
                    $magasin = $em->getRepository(MagasinA::class)->findOneBy([
                        'produit' => $produitA,
                    ]);
    
                    $ajout = $produitA->getQuantite();
                    $type = $key['type'];
                    $achatA->setPrix($key["prix"]);
                    $achatA->setQuantite($key["quantite"]);
                    $achatA->setMontant($key["total"]);
                    $achatA->setType($key['type']);
                    $achatA->setUser($this->getUser());
                    $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
                    $achatA->setAgence($tempagence->getAgence());
                    $achatA->setForunisseur($fournisseurA);
                    $produitA->setPrixachat($key["prix"]);
                    $achatA->setProduit($produitA);
    
                    $ajout += $key["quantite"];
                    

                    if ($produitA->getExpiration() <> "1") {
                        $lots->setProduit($produitA);
                        $lots->setExpiration($key['datepera']);
                        $lots->setCreatetAt(new \DateTime());
                        $lots->setAgance($tempagence->getAgence());
                        $em->persist($lots);
                        $em->flush();
                    }
                    if ($magasin) {
                        $magasin->setQuantite($magasin->getQuantite() + $key["quantite"]);
                        $operation = $magasin->getOperation();
                        $operation[] = $date->format('Y-m-d')." "."Achat";
                        $magasin->setOperation($operation);
                        $em->persist($magasin);
                    } else {
                        $newMagasin = new MagasinA();
                        $newMagasin->setProduit($produitA);
                        $newMagasin->setQuantite($key["quantite"]);
                        $newMagasin->setAgence($tempagence->getAgence());
                        $newMagasin->setCreatetAt(new \DateTime());
                        $newMagasin->setUser($this->getUser());
                        $newMagasin->setOperation([$date->format('Y-m-d')." "."Achat"]);
                        $em->persist($newMagasin);
                    }
                    $produitA->setPrixachat($key["prix"]);
                    $produitA->setExpiration($key['datepera']);
                    $em->persist($achatA);
                }
                $em->flush();

                if ($type == "CASH") {
                    $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 6021]);
                    if ($balance) {
                        $montant = $balance->getMouvementDebit();
                        $balance->setMouvementDebit($montant + $key["total"]);
                    }
                    $em->persist($balance);
                    $em->flush();
                    $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 5111]);
                    if ($balance) {
                        $montant = $balance->getMouvementCredit();
                        $balance->setMouvementCredit($montant + $key["total"]);
                    }
                    $em->persist($balance);
                    $em->flush();
                }elseif ($type == "BANQUE") {
                    $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 6021]);
                    if ($balance) {
                        $montant = $balance->getMouvementDebit();
                        $balance->setMouvementDebit($montant + $key["total"]);
                    }
                    $em->persist($balance);
                    $em->flush();
                    $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 5121]);
                    if ($balance) {
                        $montant = $balance->getMouvementCredit();
                        $balance->setMouvementCredit($montant + $key["total"]);
                    }
                    $em->persist($balance);
                    $em->flush();
                }
                $data = [
                    'success' => 200,
                    'message' => 'Achat enregistré avec succès',
                ];
            return $this->json($data);
            } catch (\Throwable $th) {
                $data = [
                    'success' => 500,
                    'message' => 'Erreur lors de l\'enregistrement de l\'achat',
                    'error' => $th->getMessage(),
                ];
                return $this->json($data);
            }
            
        }
        $fournisseurA = $em->getRepository(FournisseurA::class)->findAll();
        $produitA = $em->getRepository(ProduitA::class)->findAll();
        return $this->render('achat_a/index.html.twig', [
            'produit' => $produitA,
            'fournisseur' => $fournisseurA,
        ]);
    }

    #[Route('/achat/a/list', name: 'achat_a_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $id = $tempagence->getAgence()->getId();
        $achatA = $em->getRepository(AchatA::class)->findAll(["agence" => $id]);
        $produit = $em->getRepository(ProduitA::class)->findBy(['agence' => $id]);
        return $this->render('achat_a/list.html.twig', [
            'achats' => $achatA,
            'produits' => $produit,
        ]);
    }

    #[Route('/achat/a/edit/{id}', name: 'app_achat_edit_a')]
    public function Edit(EntityManagerInterface $entityManager, AchatA $achat) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" =>$user]);
        $id = $tempagence->getAgence()->getId();        
        if (!$achat) {
            return $this->redirectToRoute('achat_a_list');
        }
        $produit = $entityManager->getRepository(ProduitA::class)->findAll();
        $fournisseur = $entityManager->getRepository(FournisseurA::class)->findBy(["agence" => $id]);

        return $this->render('achat_a/edit.html.twig', [
            'achats' => $achat, 
            'produit' => $produit,
            'fournisseur' => $fournisseur,
        ]);
    }

    #[Route('/achat/a/update',name:'achat_update_a', methods:'POST' )]
    public function update(EntityManagerInterface $entityManager, Request $request) : Response 
    {
       $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" =>$user]);
        $id = $tempagence->getAgence()->getId(); 
        $achats = $request->request->all('achats');
        foreach ($achats as $key => $value) {
            $achat = $entityManager->getRepository(AchatA::class)->find($key);
            $produit = $entityManager->getRepository(ProduitA::class)->find($value['produit']); 

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

        return $this->redirectToRoute('achat_a_list');
    }

    #[Route('achat/a/delete/{id}', name:'app_achat_delete_a')]
    public function delete(EntityManagerInterface $entityManager, AchatA $achat) : Response 
    {
        if ($achat) {
            $produit = $entityManager->getRepository(ProduitA::class)->find($achat->getProduit()->getId());
            if($produit){
                $quantite = $produit->getQuantite();
                $produit->setQuantite($quantite- $achat->getQuantite());

                $entityManager->persist($produit);
                $entityManager->remove($achat);
                $entityManager->flush();
            }
            
        }
        return $this->redirectToRoute('achat_a_list');
    }

    #[Route('/achat/a/download', name:'achat_download_a', methods:['POST'])]
    public function download(EntityManagerInterface $em,Request $request) : Response 
    {
        $achat= $request->request->all();
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(["user" =>$user]);
        $id = $tempagence->getAgence()->getId();
        
        $produit = $achat['nomProduite'];
        $first_date = $achat['date1'];
        $date_end = $achat['date2'];
        
            if ((!empty($date_end)) && (!empty($first_date)) && ($produit == "ALL")) {
                $achat =$em->getRepository(AchatA::class)->findByFirstAndLastDay($first_date,$date_end,$id);
            }else if ((!empty($date_end)) && (!empty($first_date)) && ($produit != "ALL")) {
                $achat = $em->getRepository(AchatA::class)->findByFirstAndLastDayProduit($first_date,$date_end,$produit,$id);
            }else if (!empty($first_date) && $produit == "ALL" && empty($date_end)) {
                $achat = $em->getRepository(AchatA::class)->findByDayAgence($first_date,$id);
            }else if($produit == "ALL" && !empty($date_end)  && empty($first_date)){
                $achat = $em->getRepository(AchatA::class)->findByDayAgence($date_end,$id);
            }else if($produit == "ALL" && empty($first_date) && empty($date_end)){
                $achat = $em->getRepository(AchatA::class)->findBy(['agence' => $id]);
            }else if($produit != "ALL" && empty($first_date) && empty($date_end)){
                $achat = $em->getRepository(AchatA::class)->findByProduit($produit,$id);
            }else{
                $achat = $em->getRepository(AchatA::class)->findBy(['agence' => $id]);
            }

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        $produit = $em->getRepository(AchatA::class)->findBy(['agence' => $id]);
        
        $html = $this->renderView('achat_a/dwonload.html.twig', [
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

    #[Route('/achat/a/import/bond', name: 'achat_a_import_bond')]
    public function Import_Bond(EntityManagerInterface $em, Request $request,SluggerInterface $slugger) : Response 
    {
        // Dans votre méthode de contrôleur
        if ($request->isMethod('POST'))  {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $request->files->get('image');
            $date = $request->request->get('date');

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename); 
                
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                try {
                    $brochureFile->move(
                        $this->getParameter('image_upload_directory'),
                        $newFilename
                    );
                    $date = new \DateTimeImmutable($date);
                    $achatAs = $em->getRepository(AchatA::class)->findBy(['createdAt' => $date]);
                    if($achatAs){
                        foreach($achatAs as $achatA){

                        $imagesActuelles = $achatA->getImage() ?? [];
                        $imagesActuelles[] = $newFilename;
                        $achatA->setImage($imagesActuelles);
                        $em->persist($achatA);
                        $em->flush();
                        }

                    }
                    // On enregistre le nom en BDD
                    
                    $this->addFlash('success', 'Fichier téléchargé avec succès');

                } catch (FileException $e) {
                    $this->addFlash('success', 'Echec de téléchargé du fichier');
                }
                return $this->redirectToRoute('achat_a_list');
            }
        }

        return $this->render('achat_a/import_facture.html.twig', [
            'controller_name' => 'AchatAController',
        ]);
        
    }



    #[Route('/achat/a/view/{id}', name: 'achat_a_view')]
    public function view(EntityManagerInterface $em, AchatA $achat): Response
    {
        return $this->render('achat_a/view.html.twig', [
            'achat' => $achat,
        ]);
    }
}
