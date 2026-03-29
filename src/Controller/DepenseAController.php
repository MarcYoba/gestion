<?php

namespace App\Controller;

use App\Entity\BalanceA;
use App\Entity\DepenseA;
use App\Entity\TempAgence;
use App\Entity\User;
use App\Form\DepenseAType;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepenseAController extends AbstractController
{
    #[Route('/depense/a/create', name: 'app_depense_a')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $depense = new DepenseA();
        $form = $this->createForm(DepenseAType::class,$depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type = $form->get('type')->getData();
            $montant = $form->get('montant')->getData();

            $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
            $agence = $tempagence->getAgence(); 

            if ($type == "Voyages") {
                // $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 5111]);
                //         if ($balance) {
                //             $mouvement = $balance->getMouvementDebit();
                //             $mouvement = $mouvement + $montant;
                //             $balance->setMouvementDebit($mouvement);
                //             $em->persist($balance);
                            
                //         }
                // $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 6111]);
                //         if ($balance) {
                //             $mouvement = $balance->getMouvementCredit();
                //             $mouvement = $mouvement + $montant;
                //             $balance->setMouvementCredit($mouvement);
                //             $em->persist($balance);
                //         }
            }else if($type == "Frais etablissement"){
                $balance = $em->getRepository(BalanceA::class)->findByCompteYearAgence(2919100,date('Y'),$tempagence->getAgence()->getId());
                        if ($balance) {
                            $mouvement = $balance->getMouvementDebit();
                            $mouvement = $mouvement + $montant;
                            $balance->setMouvementDebit($mouvement);
                            $em->persist($balance);
                        }
            }else if($type == "Logiciels"){
                $balance = $em->getRepository(BalanceA::class)->findByCompteYearAgence(8112000,date('Y'),$tempagence->getAgence()->getId());
                        if ($balance) {
                            $mouvement = $balance->getMouvementDebit();
                            $mouvement = $mouvement + $montant;
                            $balance->setMouvementDebit($mouvement);
                            $em->persist($balance);
                        }
            }else if($type == "impots et taxes"){
                $balance = $em->getRepository(BalanceA::class)->findByCompteYearAgence(4421000,date('Y'),$tempagence->getAgence()->getId());
                        if ($balance) {
                            $mouvement = $balance->getMouvementDebit();
                            $mouvement = $mouvement + $montant;
                            $balance->setMouvementDebit($mouvement);
                            $em->persist($balance);
                        }
                $balance = $em->getRepository(BalanceA::class)->findByCompteYearAgence(4313000,date('Y'),$tempagence->getAgence()->getId());
                        if ($balance) {
                            $mouvement = $balance->getMouvementCredit();
                            $mouvement = $mouvement + $montant;
                            $balance->setMouvementCredit($mouvement);
                            $em->persist($balance);
                        }
                $balance = $em->getRepository(BalanceA::class)->findByCompteYearAgence(2784000,date('Y'),$tempagence->getAgence()->getId());
                        if ($balance) {
                            $mouvement = $balance->getMouvementCredit();
                            $mouvement = $mouvement + $montant;
                            $balance->setMouvementCredit($mouvement);
                            $em->persist($balance);
                        }
            }else if($type == "Constructions"){
                // $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 2131]);
                //         if ($balance) {
                //             $mouvement = $balance->getMouvementDebit();
                //             $mouvement = $mouvement + $montant;
                //             $balance->setMouvementDebit($mouvement);
                //             $em->persist($balance);
                //         }
            }else if($type == "Terrains"){
                $balance = $em->getRepository(BalanceA::class)->findByCompteYearAgence(8121000,date('Y'),$tempagence->getAgence()->getId());
                        if ($balance) {
                            $mouvement = $balance->getMouvementDebit();
                            $mouvement = $mouvement + $montant;
                            $balance->setMouvementDebit($mouvement);
                            $em->persist($balance);
                        }
            }else if($type == "service exterieur"){
                // $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 6411]);
                //         if ($balance) {
                //             $mouvement = $balance->getMouvementDebit();
                //             $mouvement = $mouvement + $montant;
                //             $balance->setMouvementDebit($mouvement);
                //             $em->persist($balance);
                //         }
            }

            $user = $this->getUser();
            $depense->setUser($user);
            $depense->setAgence($agence);

            $em->persist($depense);
            $em->flush();

           return $this->redirectToRoute('depense_a_list');
        }

        return $this->render('depense_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/depense/a/list', name: 'depense_a_list')]
    public function list(EntityManagerInterface $em,Request $request): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $anneeselect = $request->query->get('annee',date('Y'));
        $depenses = $em->getRepository(DepenseA::class)->findByDepenseYearAgence($anneeselect,$id);

        return $this->render('depense_a/list.html.twig', [
            'depenses' => $depenses,
            'anneeselect' => $anneeselect,
        ]);
    }
    /**
     * @Route(path="/depense/a/delete/{id}", name="depense_a_delete")
     */
    public function delete(DepenseA $depense, EntityManagerInterface $em): Response
    {
        $em->remove($depense);
        $em->flush();

        return $this->redirectToRoute('depense_a_list');
    }
    /**
     * @Route(path="/depense/a/edit/{id}", name="depense_a_edit")
     */
    public function edit(DepenseA $depense, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DepenseAType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($depense);
            $em->flush();

            return $this->redirectToRoute('depense_a_list');
        }

        return $this->render('depense_a/index.html.twig', [
            'form' => $form->createView(),
            'depense' => $depense,
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

    #[Route('/depense/a/import/all', name:'depense_import_excel')]
    public function import_depense(EntityManagerInterface $em, Request $request) : Response 
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
                        $predepense = $em->getRepository(DepenseA::class)->findBy(['reference' => $value[0]]);
                        if ($predepense) {
                          $trouver = $trouver + 1;
                        }else {
                            $utilisateur = $em->getRepository(User::class)->findOneBy(['reference' => $value[4]]);
                            if (!$utilisateur) {
                                $this->addFlash('error', 'utilisateur non trouvée pour la référence: ' . $value[4]);
                                continue;
                            }

                            $depense = new DepenseA();
                            $depense->setAgence($tempagence->getAgence());
                            $depense->setCreatedAt(new \DateTimeImmutable($value[1]));
                            $depense->setDescription($value[2]);
                            $depense->setMontant($value[3]);
                            $depense->setReference($value[0]);
                            $depense->setType($value[5]);
                            $depense->setUser($user);

                            $em->persist($depense);
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
                    
                    $this->addFlash('success', 'Importation terminée avec succès! Depense trouver : '.$trouver);

                    return $this->redirectToRoute('depense_a_list');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
           } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
           

        }
     
        return $this->render("depense_a/import.html.twig",[
            "id" => $id,
        ]);
    }
}
