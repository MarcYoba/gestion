<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Entity\Immobilisation;
use App\Entity\TempAgence;
use App\Form\ImmobilisationType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImmobilisationController extends AbstractController
{
    #[Route('/immobilisation/creat', name: 'app_immobilisation')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("app_logout");
        }
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $immobilisation = new Immobilisation();
        $form = $this->createForm(ImmobilisationType::class,$immobilisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get('intitulel')->getData();
            $date = $form->get('createtAt')->getData();
            if ($fichier) {
                $nomOriginal = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = $nomOriginal.'-'.uniqid().'.'.$fichier->guessExtension();

                // Déplacer le fichier
                $fichier->move(
                    $this->getParameter('balance_upload_directory'),
                    $nouveauNom
                );

                // Lire le fichier Excel avec PhpSpreadsheet
                try {
                    $spreadsheet = IOFactory::load($this->getParameter('balance_upload_directory').'/'.$nouveauNom);
                    $worksheet = $spreadsheet->getActiveSheet();
                    
                    // Traiter les données Excel ici
                    $data = [];
                    foreach ($worksheet->getRowIterator() as $row) {
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);
                        
                        $rowData = [];
                        foreach ($cellIterator as $cell) {
                            $rowData[] = $cell->getValue();
                        }
                        $data[] = $rowData;
                    }
                    array_shift($data);
                    $total = count($data);
                    $i = 0;
                    $trouver = 0;
                    $defautDate = new DateTime("0000-00-00");
                    $this->addFlash('success', 'Importation démarrée');
                    foreach ($data as $key => $value) {
                        $doublonExit = $entityManager->getRepository(Immobilisation::class)->findOneBy(['Compte'=>$value[1]]);
                        if ($doublonExit) {
                            $trouver = $trouver +1;
                        }else{
                            $immobilisation = new Immobilisation();
                            $immobilisation->setClasse($value[0]);
                            $immobilisation->setCompte($value[1]);
                            $immobilisation->setLibelle($value[2]);
                            $immobilisation->setPrixAcquisition(0);
                            $immobilisation->setDateAcquisition($defautDate);
                            $immobilisation->setCumulN(0);
                            $immobilisation->setDotationN(0);
                            $immobilisation->setCessionsSorties(0);
                            $immobilisation->setCumulN1(0);
                            $immobilisation->setValeurNetN(0);
                            $immobilisation->setCreatetAt($date);
                            $immobilisation->setAgence($tempagence->getAgence());
                            $immobilisation->setUser($user);

                            $entityManager->persist($immobilisation);
                            $entityManager->flush();
                        }
                            $progress = round(($i + 1) / $total * 100);
            
                            // Messages avec barre de progression ASCII
                            if ($progress % 20 === 0) {
                                $bar = str_repeat('█', $progress / 5) . str_repeat('░', 20 - ($progress / 5));
                                $this->addFlash('success', "[$bar] $progress% - Ligne " . ($i + 1) . "/$total");
                            }
                            $i++;
                    }

                    $this->addFlash('success', 'Importation terminée avec succès!  : '.$trouver);

                        return $this->redirectToRoute('app_immobilisation_list');
                } catch (Exception $e) {
                    $this->addFlash('error', 'Erreur lors de la lecture du fichier Excel: '.$e->getMessage());
                }
            }
        }
        return $this->render('immobilisation/index.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }

    #[Route('immobilsatiion/list', name: "app_immobilisation_list")]
    public function list(EntityManagerInterface $entityManager) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $immobilisation = $entityManager->getRepository(Immobilisation::class)->findBy(['agence'=>$id]);

        return $this->render('immobilisation/list.html.twig', [
            'immobilisations' => $immobilisation,
            'id' => $id,
        ]);
    }

    #[Route('immobilsatiion/edit/{id}', name: "app_immobilisation_edit")]
    public function Edit(EntityManagerInterface $entityManager,Immobilisation $immobilisation) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        return $this->render('immobilisation/edit.html.twig', [
            'immobilisation' => $immobilisation,
            'id' => $id,
        ]);
    }

    #[Route('immobilsatiion/delete/{id}', name: "app_immobilisation_delete")]
    public function delete(EntityManagerInterface $entityManager,Immobilisation $immobilisation) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        if ($immobilisation) {
            $entityManager->remove($immobilisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_immobilisation_list');
    }

    #[Route('immobilsatiion/download', name: "app_immobilisation_download")]
    public function download(EntityManagerInterface $entityManager) : Response 
    {
        $user  = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $immobilisation = $entityManager->getRepository(Immobilisation::class)->findBy(['agence'=>$id]);

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);

        $html = $this->renderView('immobilisation/download.html.twig', [
        'immobilisations' => $immobilisation,
        
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
                'Content-Disposition' => 'inline; filename="Immobilisation.pdf"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route('immobilisation/update/immobilisation', name: 'update_immobilisations', methods: ['POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $immobilisationsData = $request->request->all('immobilisations');
        $PrixAcquisition = 0;
        $compte = 0;
        foreach ($immobilisationsData as $key => $value) {
            $immobilisation = $entityManager->getRepository(Immobilisation::class)->find($key);
            if ($immobilisation) {
                $immobilisation->setClasse($value['Classe'] ?? 0);
                $immobilisation->setCompte($value['Compte'] ?? 0);
                $immobilisation->setLibelle($value['libelle'] ?? 0);
                $immobilisation->setDateAcquisition( (new DateTime($value['DateAcquisition']) ?? "000-00-00"));
                $immobilisation->setPrixAcquisition($value['PrixAcquisition'] ?? 0);
                $immobilisation->setCumulN((float) ($value['CumulN'] ?? 0));
                $immobilisation->setDotationN((float) ($value['DotationN'] ?? 0));
                $immobilisation->setCessionsSorties((float) ($value['CessionsSorties'] ?? 0));
                $immobilisation->setCumulN1((float) ($value['CumulN1'] ?? 0));
                $immobilisation->setValeurNetN((float) ($value['ValeurNetN'] ?? 0));
                $immobilisation->setUser($user);

                $PrixAcquisition = $value['PrixAcquisition'];
                $compte = $value['Compte'];
            }
        }
            
        $entityManager->persist($immobilisation);
        $entityManager->flush();

        $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => $compte]);
            if ($balance) {
                $mouvement = $balance->getMouvementDebit();
                $mouvement = $mouvement + $PrixAcquisition;
                $balance->setMouvementDebit($mouvement);
                $entityManager->persist($balance);
                $entityManager->flush();
            }

            $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 5121]);
            if ($balance) {
                $mouvement = $balance->getMouvementCredit();
                $mouvement = $mouvement + $PrixAcquisition;
                $balance->setMouvementCredit($mouvement);
                $entityManager->persist($balance);
                $entityManager->flush();
            }
        
        $this->addFlash('success', 'immobilisations mises à jour avec succès');
        return $this->redirectToRoute('app_immobilisation_list');
    }
}
