<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\TempAgence;
use App\Entity\User;
use App\Form\FournisseurType;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class FournisseurController extends AbstractController
{
    #[Route('/fournisseur/create', name: 'app_fournisseur')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $fournisseur->setUser($this->getUser());
            $tempagence =$entityManager->getRepository(TempAgence::class)->findOneBy(["user"=>$this->getUser()]);
            $fournisseur->setAgence($tempagence->getAgence());
            $entityManager->persist($fournisseur);
            $entityManager->flush();
            return $this->redirectToRoute('fournisseur_list');
        }

        return $this->render('fournisseur/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/fournisseur/list', name: 'fournisseur_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {   
        $user = $this->getUser();
        $fournisseur = new Fournisseur();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        $id = $tempagence->getAgence()->getId();
        if ($tempagence->isGenerale()== 1) {
            $fournisseur = $entityManager->getRepository(Fournisseur::class)->findAll();
        }else{
            $fournisseur = $entityManager->getRepository(Fournisseur::class)->findBy(["agence" => $id]);
        }
        
        return $this->render('fournisseur/list.html.twig', [
            'fournisseurs' => $fournisseur,
        ]);
    }

    #[Route('/fournisseur/list/direction', name: 'fournisseur_list_direction')]
    public function listDirection(EntityManagerInterface $entityManager): Response
    {   
        $user = $this->getUser();
        $fournisseur = new Fournisseur();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        $id = $tempagence->getAgence()->getId();
        
        $fournisseur = $entityManager->getRepository(Fournisseur::class)->findAll();
        return $this->render('fournisseur/list_direction.html.twig', [
            'fournisseurs' => $fournisseur,
        ]);
    }

    #[Route('/fournisseur/edit/{id}', name: 'fournisseur_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Fournisseur $fournisseur): Response
    {
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('fournisseur_list');
        }

        return $this->render('fournisseur/index.html.twig', [
            'form' => $form->createView(),
            'fournisseur' => $fournisseur,
        ]);
    }
    #[Route('/fournisseur/delete/{id}', name: 'fournisseur_delete')]
    public function delete(EntityManagerInterface $entityManager, Fournisseur $fournisseur): Response
    {
        $entityManager->remove($fournisseur);
        $entityManager->flush();
        return $this->redirectToRoute('fournisseur_list');
    }

    #[Route('/fournisseur/import/anne', name: 'fournisseur_import_anne')]
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
                        $preachat = $em->getRepository(Fournisseur::class)->findBy(['reference' => $value[0]]);
                        if ($preachat) {
                          $trouver = $trouver + 1;
                        }else {
                            // $utilisateur = $em->getRepository(User::class)->findOneBy(['reference' => $value[8]]);
                            // if (!$utilisateur) {
                            //     $this->addFlash('error', 'utilisateur non trouvée pour la référence: ' . $value[8]);
                            //     continue;
                            // }
                            $fournisseur = new Fournisseur();

                            $fournisseur->setReference($value[0]);
                            $fournisseur->setNom($value[1]);
                            $fournisseur->setEmail($value[4]);
                            $fournisseur->setTelephone($value[3]);
                            $fournisseur->setAddress($value[2]);
                            $fournisseur->setUser($this->getUser());
                            $fournisseur->setAgence($tempagence->getAgence());
                            $fournisseur->setCreatedAt(new \DateTimeImmutable($value[5]));
                            $fournisseur->setDatefacture(new \DateTime($value[5]));
                            $fournisseur->setNumfacture(0);
                            
                            $em->persist($fournisseur);
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

                    return $this->redirectToRoute('fournisseur_list');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
           } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
           

        }
     
        return $this->render("fournisseur/import.html.twig",[
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
