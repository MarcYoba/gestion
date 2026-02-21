<?php

namespace App\Controller;

use App\Entity\InventaireA;
use App\Entity\ProduitA;
use App\Entity\TempAgence;
use App\Form\InventaireAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InventaireAController extends AbstractController
{
    #[Route('/inventaire/a/create', name: 'app_inventaire_a')]
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $inventaire = new InventaireA();
        $form = $this->createForm(InventaireAType::class, $inventaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            $id = $tempagence->getAgence()->getId();
            $inventaire->setAgence($tempagence->getAgence());
            $inventaire->setUser($this->getUser());
            $entityManager->persist($inventaire);
            $entityManager->flush();
            return $this->redirectToRoute('app_inventaire_a_list');
        }
        return $this->render('inventaire_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/inventaire/a/list', name: 'app_inventaire_a_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $agence = $tempagence->getAgence();

        $inventaires = $entityManager->getRepository(InventaireA::class)->findAll();

        return $this->render('inventaire_a/list.html.twig', [
            'inventaires' => $inventaires,
            'id' =>$agence->getId(),
        ]);
    }
    #[Route('/inventaire/a/delete/{id}', name: 'app_inventaire_a_delete')]
    public function delete(EntityManagerInterface $entityManager,InventaireA $inventaire): Response
    {
        if ($inventaire) {
            $entityManager->remove($inventaire);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_inventaire_a_list');
    }
    #[Route('/inventaire/a/edit/{id}', name: 'app_inventaire_a_edit')]
    public function edit(EntityManagerInterface $entityManager,InventaireA $inventaire): Response
    {
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $agence = $tempagence->getAgence();
        $produit = $entityManager->getRepository(ProduitA::class)->findAll();

        return $this->render('inventaire_a/edit.html.twig', [
            'inventaires' => $inventaire,
            'id' => $agence->getId(),
            'produits' => $produit,
        ]);
    }
    #[Route('/inventaire/a/update', name: 'app_inventaire_a_update')]
    public function Update(EntityManagerInterface $entityManager, Request $request): Response
    {
        $data = $request->request->all('inventaires');
        foreach ($data as $id => $inventaireData) {
            $inventaire = $entityManager->getRepository(InventaireA::class)->find($id);
            if ($inventaire) {
                $inventaire->setCreatetAt(new \DateTime($inventaireData['date']));
                $produit = $entityManager->getRepository(ProduitA::class)->find($inventaireData['produit']);
                $inventaire->setProduit($produit);
                $inventaire->setQuantite($inventaireData['quantite']);
                $inventaire->setInventaire($inventaireData['inventaire']);
                $inventaire->setEcart($inventaireData['ecart']);
                $entityManager->persist($inventaire);
            }
        }
        $entityManager->flush();
        return $this->redirectToRoute('app_inventaire_a_list');
    }
    #[Route('/inventaire/a/excel', name: 'app_inventaire_excel')]
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
        $produits = $em->getRepository(InventaireA::class)->findByproduit($id,$moi,$anne);
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

        $liste = $em->getRepository(InventaireA::class)->findBy(['agence' => $id],['createtAt' => 'ASC']);
        $row = 2;
        $letter = ord('B');
        $lastdate = 0;
        // Remplir les données des produits
        foreach ($liste as $key => $value) {
            $newdate = $value->getCreatetAt()->format("Y-m-d");
            if ($lastdate != $newdate) {
                $lastdate = $value->getCreatetAt()->format("Y-m-d");
                $colString = chr($letter);
                $fiscolString  = $colString . '1';
                $sheet->setCellValue($fiscolString, $lastdate);
                $letter ++;
                $row = 2;
            }
            $cle = array_search($value->getProduit()->getNom(),$inventaire);
            $cle = $cle + 2;
            $sheet->setCellValue($colString.$cle, $value->getEcart());
            $row++;
        }

        // Générer le fichier Excel et le retourner en réponse
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $excelContent = ob_get_clean();
        $name = "stock_perte_" . date('Y-m-d') . ".xlsx";
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
