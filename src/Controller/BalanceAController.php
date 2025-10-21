<?php

namespace App\Controller;

use App\Entity\BalanceA;
use App\Entity\TempAgence;
use App\Form\BalanceAType;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BalanceAController extends AbstractController
{
    #[Route('/balance/a/create', name: 'app_balance_a')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $balance = new BalanceA();
        $form = $this->createForm(BalanceAType::class,$balance);
        $form->handleRequest($request);
        $user  = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

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
                    $this->addFlash('success', 'Importation démarrée');
                    foreach ($data as $key => $value) {
                        $doublonExit = $entityManager->getRepository(BalanceA::class)->findOneBy(['Compte'=>$value[1]]);
                        if ($doublonExit) {
                            $trouver = $trouver +1;
                        }else{
                            $balance = new BalanceA();
                            $balance->setClasse($value[0]);
                            $balance->setCompte($value[1]);
                            $balance->setIntitule($value[2]);
                            $balance->setSoldeInitialDebit(0);
                            $balance->setSoldeInitialCredit(0);
                            $balance->setMouvementDebit(0);
                            $balance->setMouvementCredit(0);
                            $balance->setSoldeFinalDebit(0);
                            $balance->setSoldFinalCredit(0);
                            $balance->setSoldeGlobal(0);
                            $balance->setCreatetAt($date);
                            $balance->setAgence($tempagence->getAgence());
                            $balance->setUser($user);

                            $entityManager->persist($balance);
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

                        return $this->redirectToRoute('app_balance_a');
                } catch (Exception $e) {
                    $this->addFlash('error', 'Erreur lors de la lecture du fichier Excel: '.$e->getMessage());
                }
            }
        }

        return $this->render('balance_a/index.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }

    #[Route('/balance/a/list', name:'app_balance_list_a')]
    public function lis(EntityManagerInterface $entityManager) : Response 
    {
        $user  = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $balance = $entityManager->getRepository(BalanceA::class)->findBy(['agence'=>$id]);

        return $this->render('balance_a/list.html.twig', [
            'id' => $id,
            'balances' => $balance,
        ]);
    }

    #[Route('/balance/a/Edit/{id}', name:'app_balance_edit_a')]
    public function Edit(EntityManagerInterface $entityManager,BalanceA $balance,Request $request) : Response 
    {
        $user  = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        if (!$balance) {
            return $this->redirectToRoute('app_balance_list_a');
        }

        return $this->render('balance_a/edit.html.twig', [
            'id' => $id,
            'balance' => $balance,
        ]);

    }

    #[Route('/balance/a/delete/{id}', name:'app_balance_delete_a')]
    public function delete(EntityManagerInterface $entityManager,BalanceA $balance) : Response 
    {
        $user  = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        
        if ($balance) {
            $entityManager->remove($balance);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_balance_list_a');
    }

    #[Route('/balance/a/dwonload', name:'app_balance_dwonload_a')]
    public function Dwonload(EntityManagerInterface $entityManager) : Response 
    {
        $user  = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $balance = $entityManager->getRepository(BalanceA::class)->findBy(['agence'=>$id]);

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);

        $html = $this->renderView('balance_a/download.html.twig', [
        'balances' => $balance,
        
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
                'Content-Disposition' => 'inline; filename="Blanace.pdf"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route('balance/a/update/balances', name: 'update_balances_a', methods: ['POST'])]
    public function updateBalances(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $balancesData = $request->request->all('balances');
        
        foreach ($balancesData as $key => $value) {
            $balance = $entityManager->getRepository(BalanceA::class)->find($key);
            if ($balance) {
                $balance->setClasse($value['Classe'] ?? 0);
                $balance->setCompte($value['Compte'] ?? 0);
                $balance->setIntitule($value['intitule'] ?? 0);
                $balance->setSoldeInitialDebit((float) ($value['SoldeInitialDebit'] ?? 0));
                $balance->setSoldeInitialCredit((float) ($value['SoldeInitialCredit'] ?? 0));
                $balance->setMouvementDebit((float) ($value['MouvementDebit'] ?? 0));
                $balance->setMouvementCredit((float) ($value['MouvementCredit'] ?? 0));
                $balance->setSoldeFinalDebit((float) ($value['SoldeFinalDebit'] ?? 0));
                $balance->setSoldFinalCredit((float) ($value['SoldFinalCredit'] ?? 0));
                $balance->setSoldeGlobal((float) ($value['SoldeGlobal'] ?? 0));
                $balance->setUser($user);
            }
        }
            
        $entityManager->persist($balance);
        $entityManager->flush();
        
        $this->addFlash('success', 'Balances mises à jour avec succès');
        return $this->redirectToRoute('app_balance_list_a');
    }
}
