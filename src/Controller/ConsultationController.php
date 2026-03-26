<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Consultation;
use App\Entity\TempAgence;
use App\Form\ConsultationType;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsultationController extends AbstractController
{
    #[Route('/consultation', name: 'app_consultation')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class,$consultation);
        $form->handleRequest($request);

        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence();

        if ($form->isSubmitted() && $form->isValid()) {
            $consultation->setUser($user);
            $consultation->setAgence($tempagence);

            $entityManager->persist($consultation);
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_list');
        }

        return $this->render('consultation/index.html.twig', [
            'form' => $form->createView(),
            'tempagence' => $tempagence,
        ]);
    }

    #[Route('/consultation/list', name: 'app_consultation_list')]
    public function list(EntityManagerInterface $entityManager, Request $request) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence()->getId();

        $anneeselect = $request->query->get('annee',date('Y'));

        $consultation = $entityManager->getRepository(Consultation::class)->findByAgenceYear($tempagence,$anneeselect);

        return $this->render('consultation/list.html.twig',[
            'consultations' => $consultation,
            'anneeselect' => $anneeselect,
        ]);
    }

    #[Route('/consultation/edite/{id}', name: 'app_consultation_edit')]
    public function edite(Request $request,EntityManagerInterface $entityManager,int $id) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence();

        
        $consultation = $entityManager->getRepository(Consultation::class)->findOneBy(['id' => $id]);
        $form = $this->createForm(ConsultationType::class,$consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultation->setUser($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_list');
        }
        
        return $this->render('consultation/edite.html.twig',[
            'form' => $form->createView(),
            'consultations' => $consultation,
            'tempagence' => $tempagence,
        ]);
    }

    #[Route('/consultation/delete/{id}', name:'app_consultation_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id) : Response 
    {
        $consultation = $entityManager->getRepository(Consultation::class)->findOneBy(['id' => $id]);
        if ($consultation) {
            $entityManager->remove($consultation);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('app_consultation_list');
    }

    #[Route('/consultation/details/{id}', name:'app_consultation_details')]
    public function details(EntityManagerInterface $entityManager, int $id) : Response 
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $tempagence= $tempagence->getAgence();
        
        $consultation = $entityManager->getRepository(Consultation::class)->findOneBy(['id' => $id]);

        if ($consultation) {
            return $this->render('consultation/detail.html.twig',[
            'consultations' => $consultation,
            'tempagence' => $tempagence,
        ]);
        }

        return $this->redirectToRoute('app_consultation_list');
        
    }

    #[Route('/consultation/import/year', name:'app_consultation_import_year')]
    public function download(EntityManagerInterface $em, Request $request) : Response 
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
                        $predvaccin = $em->getRepository(Consultation::class)->findBy(['reference' => $value[0]]);
                        if ($predvaccin) {
                          $trouver = $trouver + 1;
                        }else {
                            $client = $em->getRepository(Clients::class)->findOneBy(['reference' => $value[8]]);
                            
                            if (!$client) {
                                $this->addFlash('error', 'Clients non trouvée pour la référence: ' . $value[8]);
                                continue;
                            }

                            $consultation = new Consultation();

                            $consultation->setAge($value[2]);
                            $consultation->setAgence($tempagence->getAgence());
                            $consultation->setClient($client);
                            $consultation->setCreatetAd(new \DateTime($value[21]));
                            $consultation->setDateRappel(new \DateTime());
                            $consultation->setDateVermufige(new \DateTime());
                            $consultation->setDianostique($value[15]);
                            $consultation->setDocteur(0);
                            $consultation->setEsperce($value[5]);
                            $consultation->setExamain(0);
                            $consultation->setIndication($value[19]);
                            $consultation->setMotifConsultation($value[12]);
                            $consultation->setNom($value[1]);
                            $consultation->setNomtant($value[20]);
                            $consultation->setPoid($value[4]);
                            $consultation->setPronostique($value[17]);
                            $consultation->setProphylaxe($value[18]);
                            $consultation->setRace($value[7]);
                            $consultation->setRegime($value[11]);
                            $consultation->setSexe($value[3]);
                            $consultation->setSymtome($value[14]);
                            $consultation->setTemperature($value[13]);
                            $consultation->setTraitement($value[16]);
                            $consultation->setUser($user);
                            $consultation->setVaccin($value[9]);
                            $consultation->setVermufuge($value[10]);
                            $consultation->setReference($value[0]);
                            $consultation->setRobe($value[6]);
                            
                            $em->persist($consultation);
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
                    
                    $this->addFlash('success', 'Importation terminée avec succès! consultation trouver : '.$trouver);

                    return $this->redirectToRoute('app_consultation_list');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
           } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
           

        }
     
        return $this->render("consultation/import.html.twig",[
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
