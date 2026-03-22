<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\TempAgence;
use App\Entity\User;
use App\Entity\Vaccin;
use App\Form\VaccinType;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpParser\Node\Stmt\Return_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class VaccinController extends AbstractController
{
    #[Route('/vaccin/create', name: 'app_vaccin')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $vaccin = new Vaccin();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence = $tempagence->getAgence();

        $client = $entityManager->getRepository(Clients::class)->findAll();

        $form = $this->createForm(VaccinType::class,$vaccin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            

            $vaccin->setAgence($tempagence);
            $vaccin->setUser($user);
            $vaccin->setRappel(0);

            $entityManager->persist($vaccin);
            $entityManager->flush();

            return $this->redirectToRoute('app_vaccin_list');
        }
        return $this->render('vaccin/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'tempagence' => $tempagence,
        ]);
    }

    #[Route('/vaccin/list', name: 'app_vaccin_list')]
    public function list(EntityManagerInterface $entityManager, Request $request) : Response {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $tempagence = $tempagence->getAgence();

        $anneeselect = $request->query->get('annee',date("Y"));

        $vaccin = $entityManager->getRepository(Vaccin::class)->findByVaccinYear($id,$anneeselect);

        return $this->render('vaccin/list.html.twig',[
            'vaccins' => $vaccin,
            'tempagence' => $tempagence,
            'anneeselect' => $anneeselect,
        ]);
    }

    #[Route('/vaccin/edite/{id}', name: 'app_vaccin_edit')]
    public function edite(Request $request,EntityManagerInterface $entityManager,int $id) : Response 
    {
       $user =$this->getUser();
        $vaccin = $entityManager->getRepository(Vaccin::class)->findOneBy(['id'=> $id]);
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence = $tempagence->getAgence();

        $form = $this->createForm(VaccinType::class,$vaccin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $vaccin->setUser($user);
            $entityManager->flush($vaccin);

            return $this->redirectToRoute('app_vaccin_list');
        }

       return $this->render('vaccin/edite.html.twig',[
        'form' => $form->createView(),
        'tempagence' => $tempagence,
       ]);
    }

    #[Route('/vaccin/rappel/{id}', name:'app_vaccin_rappel')]
    public function Rappel(Request $request, EntityManagerInterface $entityManager,int $id) : Response 
    {
        $user =$this->getUser();
        $vaccin = $entityManager->getRepository(Vaccin::class)->findOneBy(['id'=> $id]);
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence = $tempagence->getAgence();

        $form = $this->createForm(VaccinType::class,$vaccin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rappel = $vaccin->getRappel();
            $vaccin->setUser($user);
            $vaccin->setRappel($rappel+1);
            $entityManager->flush($vaccin);

            return $this->redirectToRoute('app_vaccin_list');
        }

       return $this->render('vaccin/rappel.html.twig',[
        'form' => $form->createView(),
        'tempagence' => $tempagence,
       ]);        
    }

    #[Route('/vaccin/delete/{id}', name:'app_vaccin_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id) : Response 
    {
        $vaccin = $entityManager->getRepository(Vaccin::class)->findOneBy(['id'=> $id]);
        if ($vaccin) {
            $entityManager->remove($vaccin);
            $entityManager->flush();
        }
          
        return $this->redirectToRoute('app_vaccin_list');

    }

    #[Route('/vaccin/rapelle/download', name:'app_vaccin_download')]
    public function download(EntityManagerInterface $em) : Response
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true); 
        $dompdf = new Dompdf($options);
        $vaccin = $em->getRepository(Vaccin::class)->findByRapelleVaccin();
        
        $html = $this->renderView('vaccin/download.html.twig', [
           'vaccins' => $vaccin,
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
                'Content-Disposition' => 'inline; filename="telecharger_vaccin.pdf"', // 'inline' pour affichage navigateur
            ]
        );
    }

    #[Route('/vaccin/import/anne', name: 'app_vaccin_import')]
    public function Import_poussin(EntityManagerInterface $em, Request $request) : Response 
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
                        $predvaccin = $em->getRepository(Vaccin::class)->findBy(['reference' => $value[0]]);
                        if ($predvaccin) {
                          $trouver = $trouver + 1;
                        }else {
                            $client = $em->getRepository(Clients::class)->findOneBy(['reference' => $value[4]]);
                            $utilisateur = $em->getRepository(User::class)->findOneBy(['reference' => $value[5]]);
                            if (!$client) {
                                $this->addFlash('error', 'Clients non trouvée pour la référence: ' . $value[4]);
                                continue;
                            }
                            if (!$utilisateur) {
                                $this->addFlash('error', 'Utilisateur non trouvée pour la référence: ' . $value[5]);
                                continue;
                            }
                            $vaccin = new Vaccin();

                            $vaccin->setAge($value[2]);
                            $vaccin->setAgence($tempagence->getAgence());
                            $vaccin->setClient($client);
                            $vaccin->setCreatetAD(new \DateTime($value[8]));
                            $vaccin->setDateRapel(new \DateTime($value[7]));
                            $vaccin->setDateVaccin(new \DateTime($value[6]));
                            $vaccin->setLieux($value[13]);
                            $vaccin->setMontant($value[10]);
                            $vaccin->setMontantNet($value[11]);
                            $vaccin->setRappel($value[14]);
                            $vaccin->setReference($value[0]);
                            $vaccin->setResteMontant($value[12]);
                            $vaccin->setSujet($value[1]);
                            $vaccin->setTypeSujet($value[3]);
                            $vaccin->setTypeVaccin($value[9]);
                            $vaccin->setUser($utilisateur);

                            $em->persist($vaccin);
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
                    
                    $this->addFlash('success', 'Importation terminée avec succès! Vaccin trouver : '.$trouver);

                    return $this->redirectToRoute('app_vaccin_import');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
           } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
           

        }
     
        return $this->render("vaccin/import.html.twig",[
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
