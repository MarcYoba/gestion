<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Entity\Balance;
use App\Entity\Depenses;
use App\Entity\TempAgence;
use App\Entity\User;
use App\Form\DepensesType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepensesController extends AbstractController
{
    /**
     * @Route("/depenses/create", name= "app_depenses")
     */
    
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $depenses = new Depenses();
        $form = $this->createForm(DepensesType::class,$depenses);
        $form->handleRequest($request);
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $idagence = $tempagence->getAgence(); 

        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var UploadedFile $file */
            $file = $form->get('imageFile')->getData();

            if ($file) {
                $fillesize = $file->getSize();
                $filename = uniqid().'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('depenses_upload_directory'), // Défini dans services.yaml
                    $filename
                );
                $depenses->setImageName($filename);
                $depenses->setImageSize($fillesize);
            }else{
                $depenses->setImageName("pas d'image");
                $depenses->setImageSize(0);
            }

            $type = $form->get('type')->getData();
            $montant = $form->get('montant')->getData();
            if ($type == "Voyages") {
                // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 5111]);
                //         if ($balance) {
                //             $mouvement = $balance->getMouvementDebit();
                //             $mouvement = $mouvement + $montant;
                //             $balance->setMouvementDebit($mouvement);
                //             $entityManager->persist($balance);
                            
                //         }
                // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 6111]);
                //         if ($balance) {
                //             $mouvement = $balance->getMouvementCredit();
                //             $mouvement = $mouvement + $montant;
                //             $balance->setMouvementCredit($mouvement);
                //             $entityManager->persist($balance);
                //         }
            }else if($type == "Frais etablissement"){
                // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 2011]);
                //         if ($balance) {
                //             $mouvement = $balance->getMouvementDebit();
                //             $mouvement = $mouvement + $montant;
                //             $balance->setMouvementDebit($mouvement);
                //             $entityManager->persist($balance);
                //         }
            }else if($type == "Logiciels"){
                // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 2051]);
                //         if ($balance) {
                //             $mouvement = $balance->getMouvementDebit();
                //             $mouvement = $mouvement + $montant;
                //             $balance->setMouvementDebit($mouvement);
                //             $entityManager->persist($balance);
                //         }
            }else if($type == "impots et taxes"){
                // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 6311]);
                //         if ($balance) {
                //             $mouvement = $balance->getMouvementDebit();
                //             $mouvement = $mouvement + $montant;
                //             $balance->setMouvementDebit($mouvement);
                //             $entityManager->persist($balance);
                //         }
            }else if($type == "Constructions"){
                // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 2131]);
                //         if ($balance) {
                //             $mouvement = $balance->getMouvementDebit();
                //             $mouvement = $mouvement + $montant;
                //             $balance->setMouvementDebit($mouvement);
                //             $entityManager->persist($balance);
                //         }
            }else if($type == "Terrains"){
                // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 2111]);
                //         if ($balance) {
                //             $mouvement = $balance->getMouvementDebit();
                //             $mouvement = $mouvement + $montant;
                //             $balance->setMouvementDebit($mouvement);
                //             $entityManager->persist($balance);
                //         }
            }else if($type == "service exterieur"){
                // $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 6411]);
                //         if ($balance) {
                //             $mouvement = $balance->getMouvementDebit();
                //             $mouvement = $mouvement + $montant;
                //             $balance->setMouvementDebit($mouvement);
                //             $entityManager->persist($balance);
                //         }
            }
            
            $depenses->setUser($user);
            $depenses->setAgence($idagence);
            $entityManager->persist($depenses);
            $entityManager->flush();

            return $this->redirectToRoute('depenses_list');
        }
        return $this->render('depenses/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/depenses/list', name: 'depenses_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $depenses = $entityManager->getRepository(Depenses::class)->findAll();
        return $this->render('depenses/list.html.twig', [
            'depense' => $depenses,
        ]);
    }

    #[Route('/depenses/edit/{id}', name: 'depenses_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $depenses = $entityManager->getRepository(Depenses::class)->find($id);
        if (!$depenses) {
            throw $this->createNotFoundException('No depense found for id '.$id);
        }
        $form = $this->createForm(DepensesType::class, $depenses);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('imageFile')->getData();

            if ($file) {
                $fillesize = $file->getSize();
                $filename = uniqid().'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('depenses_upload_directory'), // Défini dans services.yaml
                    $filename
                );
                $depenses->setImageName($filename);
                $depenses->setImageSize($fillesize);
            }

            $entityManager->flush();

            return $this->redirectToRoute('depenses_list');
        }

        return $this->render('depenses/index.html.twig', [
            'form' => $form->createView(),
            'depense' => $depenses,
        ]);
    }

    #[Route('/depenses/delete/{id}', name: 'depenses_delete')]
    public function delete(EntityManagerInterface $entityManager, Depenses $depenses): Response
    {
        $entityManager->remove($depenses);
        $entityManager->flush();
        return $this->redirectToRoute('depenses_list');
    }

    #[Route('/depense/import/anne', name: 'depense_import_anne')]
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
                        
                        $preachat = $em->getRepository(Depenses::class)->findBy(['reference' => $value[0]]);
                        if ($preachat) {
                          $trouver = $trouver + 1;
                        }else {
                            $utilisateur = $em->getRepository(User::class)->findOneBy(['reference' => $value[3]]);
                            if (!$utilisateur) {
                                $this->addFlash('error', 'utilisateur non trouvée pour la référence: ' . $value[3]);
                                continue;
                            }
                            $depenses = new Depenses();
                            $depenses->setReference($value[0]);
                            $depenses->setType($value[5]);
                            $depenses->setMontant($value[2]);
                            $depenses->setUser($utilisateur);
                            $depenses->setCreatedAt(new \DateTimeImmutable($value[4]));
                            $depenses->setDescription($value[1]);
                            $depenses->setAgence($tempagence->getAgence());
                            $depenses->setImageName("pas d'image");
                            $depenses->setImageSize(0);
                            
                            $em->persist($depenses);
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

                    return $this->redirectToRoute('depense_import_anne');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
           } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
           

        }
     
        return $this->render("depenses/import.html.twig",[
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
