<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Facture;
use App\Entity\Historique;
use App\Entity\Inventaire;
use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\TempAgence;
use App\Form\InventaireType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InventaireController extends AbstractController
{
    #[Route('/inventaire/creat/{id}', name: 'app_inventaire_produit')]
    public function index(EntityManagerInterface $em, Request $request, Produit $produit): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $date = new DateTime();
        $inventaire = new Inventaire();
        
        $fact = $em->getRepository(Facture::class)->findByQuantiteProduitVendu($date,$produit->getId(),$id);
        $preinventaire = $em->getRepository(Inventaire::class)->findOneBy(['produit' => $produit->getId()]);
        $inventaire->setProduit($produit);
        $inventaire->setQuantite($produit->getQuantite());
        $inventaire->setCreatetAt($date);

        $form = $this->createForm(InventaireType::class, $inventaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $inventaire->setAgence($tempagence->getAgence());
            $inventaire->setUser($this->getUser());
            $inventaire->setEcart($form->get('ecart')->getData());
            $em->persist($inventaire);
            $em->flush();
            return $this->redirectToRoute('app_inventaire_list');
        }
        return $this->render('inventaire/index.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
            'preinventaire' => $preinventaire ? $preinventaire->getInventaire() : 0,
            'fact' => $fact ? $fact : 0,
        ]);
    }

    #[Route('/inventaire/list', name: 'app_inventaire_list')]
    public function list(EntityManagerInterface $entityManager,Request $request): Response
    {
        $anneeselect = $request->query->get('annee', date('Y'));
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $agence = $tempagence->getAgence();

        $inventaires = $entityManager->getRepository(Inventaire::class)->findAll();

        return $this->render('inventaire/list.html.twig', [
            'inventaires' => $inventaires,
            'id' =>$agence->getId(),
            'anneeselect' => $anneeselect,
        ]);
    }

    #[Route('/inventaire/list/vente', name: 'app_inventaire_list_vente')]
    public function listVente(EntityManagerInterface $em, Request $request): Response
    {
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $agence = $tempagence->getAgence();

        $anneeselect = $request->query->get('annee', date('Y'));

        $date  = new DateTime($anneeselect.'-'.date('m-d'));

        $histoiques = [];
        $histoique = $em->getRepository(Facture::class)->findByProduitVendu($date,$agence);
            foreach ($histoique as $key => $value) {
                $quantite = 0;
                $hist = $em->getRepository(Historique::class)->findByDate($date,$value->getProduit()->getId(),$agence);
                $fact = $em->getRepository(Facture::class)->findByQuantiteProduitVendu($date,$value->getProduit()->getId(),$agence);
                //$prix = $em->getRepository(Facture::class)->findByPrixProduitVendu($date, $value->getProduit()->getId(), $agence);
                //$lasthist = $em->getRepository(Historique::class)->findByLastDate(new \DateTime($date->format("Y-m-d")),$value->getProduit()->getId(),$agence);
                $magasin = $em->getRepository(Magasin::class)->findOneBy(["produit" => $value->getProduit()->getId()]);
                $achat = $em->getRepository(Achat::class)->findByPrixAchatProduit($value->getProduit()->getId(),$agence);
                if($magasin) {
                    $quantite = $magasin->getQuantite();
                }
                array_push($histoiques,[$value->getProduit()->getId(),$value->getProduit()->getNom(),$hist,$fact,$value->getProduit()->getQuantite(),$quantite]);
            }
        
        return $this->render('inventaire/list_vente.html.twig', [
            'histoiques' => $histoiques,
            'anneeselect' => $anneeselect,
        ]);
    }

    #[Route('/inventaire/delete/{id}', name: 'app_inventaire_delete')]
    public function delete(EntityManagerInterface $entityManager,Inventaire $inventaire): Response
    {
        if ($inventaire) {
            $entityManager->remove($inventaire);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_inventaire_list');
    }
    #[Route('/inventaire/edit/{id}', name: 'app_inventaire_edit')]
    public function edit(EntityManagerInterface $entityManager,Inventaire $inventaire): Response
    {
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $agence = $tempagence->getAgence();
        $produit = $entityManager->getRepository(Produit::class)->findAll();

        return $this->render('inventaire/edit.html.twig', [
            'inventaires' => $inventaire,
            'id' => $agence->getId(),
            'produits' => $produit,
        ]);
    }
    #[Route('/inventaire/update/view', name: 'app_inventaire_update')]
    public function Update(EntityManagerInterface $entityManager, Request $request): Response
    {
        $data = $request->request->all('inventaires');
        foreach ($data as $id => $inventaireData) {
            $inventaire = $entityManager->getRepository(Inventaire::class)->find($id);
            if ($inventaire) {
                $inventaire->setCreatetAt(new \DateTime($inventaireData['date']));
                $produit = $entityManager->getRepository(Produit::class)->find($inventaireData['produit']);
                $inventaire->setProduit($produit);
                $inventaire->setQuantite($inventaireData['quantite']);
                $inventaire->setInventaire($inventaireData['inventaire']);
                $inventaire->setEcart($inventaireData['ecart']);
                $entityManager->persist($inventaire);
            }
        }
        $entityManager->flush();
        return $this->redirectToRoute('app_inventaire_list');
    }

    #[Route('/inventaire/excel/provenderie', name: 'app_inventaire_excel_provenderie')]
    public function Excel(EntityManagerInterface $em,Request $request) : Response {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $moi = 0;
        $anne = 0;
        if ($request->getMethod('POST')) {
            $anne = $request->request->get('date_debut');
            $moi = $request->request->get('date_fin');
            if (empty($moi) || empty($anne)) {
                $moi = date('m');
                $anne = date('Y');
            }
        }
        $produits = $em->getRepository(Inventaire::class)->findByproduit($id,$moi,$anne);
        $inventaire = [];
        // Créer un nouveau fichier Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Produit');
        $row = 2;
        // Ajouter les en-têtes de colonnes
        foreach ($produits as $key => $value) {
            $sheet->setCellValue('A'.$row, $value->getProduit()->getNom());
            array_push($inventaire,$value->getProduit()->getNom());
            $row++;
        }

        $liste = $em->getRepository(Inventaire::class)->findByQuantiteProduit($id,$moi,$anne);
        $row = 2;
        $letter = ord('B');
        $lastdate = 0;
        //Remplir les données des produits
        foreach ($liste as $key => $value) {
            $newdate = $value->getCreatetAt()->format("Y-m-d");
            if ($lastdate != $newdate) {
                $lastdate = $value->getCreatetAt()->format("Y-m-d");
                $colString = chr($letter);
                $fiscolString  = $colString . '1';
                $sheet->setCellValue($fiscolString, $lastdate);
                $letter ++;
            }
            $cle = array_search($value->getProduit()->getNom(),$inventaire);
            $cle = $cle + 2;
            $sheet->setCellValue($colString.$cle, $value->getEcart());
        }

        $colString = chr($letter);
        $fiscolString  = $colString . '1';
        $sheet->setCellValue($fiscolString, "Total generale");

        foreach ($produits as $key => $value) {
            $cle = array_search($value->getProduit()->getNom(),$inventaire);
            $cle = $cle + 2;
            $quantite = $em->getRepository(Inventaire::class)->findBySommeMois($id,$moi,$anne,$value->getProduit()->getId());
            $sheet->setCellValue($colString.$cle, $quantite);
        }
        
        $letter ++;
        $colString = chr($letter);
        $fiscolString  = $colString . '1';
        $sheet->setCellValue($fiscolString, "Prix de vente");

        foreach ($produits as $key => $value) {
            $cle = array_search($value->getProduit()->getNom(),$inventaire);
            $cle = $cle + 2;
            $sheet->setCellValue($colString.$cle, $value->getProduit()->getPrixvente());
        }

        $letter ++;
        $colString = chr($letter);
        $fiscolString  = $colString . '1';
        $sheet->setCellValue($fiscolString, "Total perte");

        foreach ($produits as $key => $value) {
            $cle = array_search($value->getProduit()->getNom(),$inventaire);
            $cle = $cle + 2;
            $quantite = $em->getRepository(Inventaire::class)->findBySommeMois($id,$moi,$anne,$value->getProduit()->getId());
            $sheet->setCellValue($colString.$cle, ($value->getProduit()->getPrixvente() * $quantite));
        }

        $row = count($produits) + 2;
        $fiscolString  = 'A' . $row;
        $sheet->setCellValue($fiscolString, "Total Journaliere");

        $tabjour = $em->getRepository(Inventaire::class)->findBySommeDate($id,$moi,$anne);
        
        $letter = ord('B');

        foreach ($tabjour as $key => $value) {
            
            $colString = chr($letter);
            $fiscolString  = $colString . $row;
            $sheet->setCellValue($fiscolString, $value[1]);
            $letter ++;
        }

        // Générer le fichier Excel et le retourner en réponse
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $excelContent = ob_get_clean();
        $name = "stock_perte_provenderie_" . date('Y-m-d') . ".xlsx";
        return new Response(
            $excelContent,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $name . '"',
            ]
        );
    }
}
