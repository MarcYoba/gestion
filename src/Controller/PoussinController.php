<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Poussin;
use App\Form\PoussinType;
use App\Entity\TempAgence;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use chillerlan\QRCode\{QRCode, QROptions};
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PoussinController extends AbstractController
{
    #[Route('/poussin', name: 'app_poussin')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $poussin = new Poussin();
        $form = $this->createForm(PoussinType::class, $poussin);
        $form->handleRequest($request);
        $user = $this->getUser();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $agence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            $poussin->setAgence($agence->getAgence());
            $poussin->setStatus("EN COUR");
            $poussin->setUser($user);
            $em->persist($poussin);
            $em->flush();

            return $this->redirectToRoute('app_poussin_list');
        }
        return $this->render('poussin/index.html.twig', [
            'form' => $form->createView() ,
        ]);
    }

    #[Route('/poussin/list', name: 'app_poussin_list')]
    public function List(EntityManagerInterface $em, Request $request): Response
    {
        $anneeselect = $request->query->get('annee',date("Y"));
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();

        $poussin = $em->getRepository(Poussin::class)->findCommandPoussinYear($agence,$anneeselect);
        return $this->render('poussin/list.html.twig', [
            'poussins' => $poussin,
            'anneeselect' => $anneeselect,
        ]);
    }

    #[Route('/poussin/edit/{id}', name:'app_pousssin_edit')]
    public function Edite(EntityManagerInterface $em, int $id): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $client = $em->getRepository(Clients::class)->findAll();
        $poussin = $em->getRepository(Poussin::class)->find($id);
        return $this->render("poussin/Edit.html.twig", [
            "poussins" => $poussin,
            "clients" => $client,
        ]);

    }

    #[Route('/poussin/delete/{id}', name: 'app_poussin_delete')]
    public function delete(EntityManagerInterface $em, Poussin $poussin) : Response 
    {
        if($poussin){
            $em->remove($poussin);
            $em->flush();
        }

        return $this->redirectToRoute('app_poussin_list');
    }

    #[Route('/poussin/facture/{id}', name: 'app_poussin_facture')]
    public function facture(Poussin $poussin) : Response 
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true); 
        $dompdf = new Dompdf($options);
        $datecommande = $poussin->getDatecommande();

        $data   = 'M0822175619296A +237655271506 '.$poussin->getClient()->getNom()." ".$poussin->getClient()->getTelephone()." ".
        $datecommande->format('Y-m-d')." Commande Poussin : ".$poussin->getId();
        $qrcode = (new QRCode)->render($data);
        
        $html = $this->renderView('poussin\facture.html.twig', [
        'poussins' => $poussin,
        'qrcode' => $qrcode,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A6', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();

        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Facture_poussin"',
            ]
        );
    }

    #[Route('/poussin/download', name: 'app_poussin_download')]
    public function download(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true); 
        $dompdf = new Dompdf($options);
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence= $tempagence->getAgence();
        
        $poussins = $em->getRepository(Poussin::class)->findByCommandePoussin($agence);
        $html = $this->renderView('poussin\download.html.twig', [
            'commandepoussins' => $poussins,
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
                'Content-Disposition' => 'inline; filename="Command_poussin"',
            ]
        );
    }

    #[Route('/poussin/trie', name: 'app_poussin_trie')]
    public function Trie(EntityManagerInterface $em, Request $request) : Response 
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }

        $first_date= $request->request->get('datedette');
        $end_date = $request->request->get('datedett2');
        $type = $request->request->get('status');

        $options = new Options();
        $options->set('isRemoteEnabled', true); 
        $dompdf = new Dompdf($options);
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence= $tempagence->getAgence();

        if ($type == "ALL") {
            $poussins = $em->getRepository(Poussin::class)->findAllCommandPoussin($agence,$first_date,$end_date);
        }else {
            $poussins = $em->getRepository(Poussin::class)->findByCommandePoussinTrie($agence,$first_date,$end_date,$type);
        }      
        
        $html = $this->renderView('poussin\download.html.twig', [
            'commandepoussins' => $poussins,
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
                'Content-Disposition' => 'inline; filename="Command_poussin"',
            ]
        );
    }

    #[Route('/poussin/mise/a/jour', name: 'app_poussin_update', methods:['POST'])]
    public function update(EntityManagerInterface $em,Request $request) : Response 
    {
       $variable = $request->request->all('poussins');
        $user = $this->getUser();
        foreach ($variable as $key => $value) {
            $poussin = $em->getRepository(Poussin::class)->find($key);
            $client = $em->getRepository(Clients::class)->findOneBy(['nom'=>$value['client']]);
            if ($poussin) {
                $poussin->setClient($client);
                $poussin->setQuantite($value['quantite'] ?? 0);
                $poussin->setPrix($value['prix'] ?? 0);
                $poussin->setMontant($value['montant'] ?? 0);
                $poussin->setSouche($value['souche'] ?? 0);
                $poussin->setMobilepay($value['mobilepay'] ?? 0);
                $poussin->setCredit($value['credit'] ?? 0);
                $poussin->setCash($value['cash'] ?? 0);
                $poussin->setReste($value['reste'] ?? 0);
                $poussin->setStatus($value['status'] ?? 0);
                $poussin->setDatecommande(new DateTime($value['datecommande']) ?? 0);
                $poussin->setDatelivaison(new DateTime($value['datelivaison']) ?? 0);
                $poussin->setDaterapelle(new DateTime($value['daterapelle']) ?? 0);
                // $poussin->setUser($user);

                $em->persist($poussin);
                $em->flush();
            }
        }
        return $this->redirectToRoute('app_poussin_list');
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

    #[Route('/poussin/import', name: 'app_poussin_import')]
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
                        $predepense = $em->getRepository(Poussin::class)->findBy(['reference' => $value[0]]);
                        if ($predepense) {
                          $trouver = $trouver + 1;
                        }else {
                            $client = $em->getRepository(Clients::class)->findOneBy(['reference' => $value[14]]);
                            if (!$client) {
                                $this->addFlash('error', 'Clients non trouvée pour la référence: ' . $value[14]);
                                continue;
                            }

                            $poussin = new Poussin();
                            $poussin->setAgence($tempagence->getAgence());
                            $poussin->setBanque($value[15]);
                            $poussin->setCash($value[8]);
                            $poussin->setClient($client);
                            $poussin->setCredit($value[7]);
                            $poussin->setDatecommande(new \DateTime($value[11]));
                            $poussin->setDatelivaison(new \DateTime($value[12]));
                            $poussin->setDaterapelle(new \DateTime($value[13]));
                            $poussin->setMobilepay($value[6]);
                            $poussin->setMontant($value[4]);
                            $poussin->setPrix($value[3]);
                            $poussin->setQuantite($value[3]);
                            $poussin->setReste($value[9]);
                            $poussin->setSouche($value[5]);
                            $poussin->setStatus($value[10]);
                            $poussin->setReference($value[0]);
                            $poussin->setUser($user);

                            $em->persist($poussin);
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
                    
                    $this->addFlash('success', 'Importation terminée avec succès! Poussin trouver : '.$trouver);

                    return $this->redirectToRoute('app_poussin_import');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
           } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
           

        }
     
        return $this->render("poussin/import.html.twig",[
            "id" => $id,
        ]);
    }
}
