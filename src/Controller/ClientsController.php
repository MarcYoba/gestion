<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Clients;
use App\Entity\TempAgence;
use App\Form\ClientsType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Func;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientsController extends AbstractController
{
    #[Route('/clients/create', name: 'app_clients')]
    public function index(Request $request, EntityManagerInterface $em,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $clients = new Clients();
        $form = $this->createForm(ClientsType::class, $clients);
        $form->handleRequest($request);
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(["user"=>$user]);
        $agence = $tempagence->getAgence()->getId();
        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $data = $form->getData();
            
            $defaulpass = "123456789";
                $heure = date("s");
                $nom = str_replace(" ","", $data->getNom());
                $defaultEmail = $nom.$heure.'@gmail.com';

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $defaulpass
                    )
                );
            
            $user->setUsername($data->getNom());
                $user->setRoles(['ROLE_CLIENTS']);
                $user->setEmail($defaultEmail);
                $user->setCreatedAt(new \DateTimeImmutable());
                $user->setTelephone($data->getTelephone());
                $user->setLocalisation($data->getLocalisation());
                $user->setSpeculation('000');

                $user->getClients()->setNom( $data->getNom());
                $user->getClients()->setTelephone( $data->getTelephone() );
                $user->getClients()->setCreatedAt( new \DateTimeImmutable);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute("clients_list");
            
        }
        return $this->render('clients/index.html.twig', [
            'form' => $form->createView(),
            'id' => $agence,
        ]);
    }

    #[Route('/clients/list', name: 'clients_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $clients = $em->getRepository(Clients::class)->findAll();
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(["user"=>$user]);
        $agence = $tempagence->getAgence()->getId();

        return $this->render('clients/list.html.twig', [
            'clients' => $clients,
            'id' => $agence,
        ]);
    }

    #[Route('/clients/edit', name: 'clients_edit')]
    public function edit(): Response
    {
        return $this->render('clients/edit.html.twig', [
            'controller_name' => 'ClientsController',
        ]);
    }
    #[Route('/clients/delete', name: 'clients_delete')]
    public function delete(): Response
    {
        return $this->render('clients/delete.html.twig', [
            'controller_name' => 'ClientsController',
        ]);
    }
    #[Route('/clients/recherche', name: 'clients_recherche')]
    public function view(Request $request, EntityManagerInterface $entityManager): Response
    {
        if($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $json = $request->getContent();
            $donnees = json_decode($json, true);
            if (isset($donnees)) {
                $client = $entityManager->getRepository(Clients::class)->findBy(['nom' => $donnees]);
                if ($client) {
                    return $this->json([
                        'success' => true,
                        'nom' => $client[0]->getId(), 
                    ]);
                } else {
                    return $this->json(['error' => 'Client non trouvé','donne'=>$donnees], 200);
                }
            }

        }
        return $this->json(['error' => 'Client non spécifié'], 404);
    }

    #[Route('/clients/add/client', name:'clients_add')]
    public function add_client(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $clients = new Clients();
        if ($request->isXmlHttpRequest() || $request->getContentType()=== 'json') {

            $user = new User();
            $json = $request->getContent();
            $tab = json_decode($json, true);
            if (!empty($tab['nom']) && !empty($tab['telephone'])) {
                $defaulpass = "123456789";
                $heure = date("s");
                $nom = str_replace(" ","", $tab["nom"]);
                $defaultEmail = $tab['nom'].$heure.'@gmail.com';

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $defaulpass
                    )
                );
                $user->setUsername($tab['nom']);
                $user->setRoles(['ROLE_CLIENTS']);
                $user->setEmail($defaultEmail);
                $user->setCreatedAt(new \DateTimeImmutable());
                $user->setTelephone($tab['telephone']);
                $user->setLocalisation('000');
                $user->setSpeculation('000');


                $user->getClients()->setNom( $tab['nom'] );
                $user->getClients()->setTelephone( $tab['telephone'] );
                $user->getClients()->setCreatedAt( new \DateTimeImmutable);

            $entityManager->persist($user);
                $entityManager->flush();
                return $this->json(['success'=> true,'message'=> 'success']);
            } else {
                return $this->json(['error' => false, 'message' => 'Vous deviez entrer les informations du clients']);
            }
            
        }
        return $this->json(['error' => false, 'message' => 'Vous deviez entrer les informations du clients']);
    }

    #[Route('/clients/a/list', name:'app_cleints_a_list')]
    public function List_a(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
            $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user"=>$user]);
            $agence = $tempagence->getAgence()->getId();
        $client = $entityManager->getRepository(Clients::class)->findAll();

        return $this->render("clients/list_a.html.twig",[
            "clients" => $client,
            "id" => $agence
        ]);
    }

    #[Route('/clients/a/creat', name:'app_client_a_create')]
    public function create(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher) : Response 
    {

        $user = $this->getUser();
            $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user"=>$user]);
            $agence = $tempagence->getAgence()->getId();
        
        $clients = new Clients();
        $form = $this->createForm(ClientsType::class,$clients);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {   
            $user = new User();
            $data = $form->getData();
            
            $defaulpass = "123456789";
                $heure = date("s");
                $nom = str_replace(" ","", $data->getNom());
                $defaultEmail = $nom.$heure.'@gmail.com';

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $defaulpass
                    )
                );
            
            $user->setUsername($data->getNom());
                $user->setRoles(['ROLE_CLIENTS']);
                $user->setEmail($defaultEmail);
                $user->setCreatedAt(new \DateTimeImmutable());
                $user->setTelephone($data->getTelephone());
                $user->setLocalisation($data->getLocalisation());
                $user->setSpeculation('000');

                $user->getClients()->setNom( $data->getNom());
                $user->getClients()->setTelephone( $data->getTelephone() );
                $user->getClients()->setCreatedAt( new \DateTimeImmutable);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("app_cleints_a_list");
        }
        return $this->render("clients/index_a.html.twig",[
            "form" => $form->createView(),
            "id" => $agence
        ]);
    }

    #[Route('/clients/a/import', name:'app_client_a_import')] 
    public function import_a(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $processed = 0;
        if ($request->isMethod('POST')) {
           $file =  $request->files->get('file_excel');
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
                        
                        $client = $entityManager->getRepository(Clients::class)->findBy(['nom' => $value[1]]);
                        if ($client) {
                          $trouver = $trouver + 1;
                        }else {
                            $user = new User();
                            $defaulpass = "123456789";
                            $heure = date("s");
                            $nom = str_replace(" ","", $value[1]);
                            $defaultEmail = $nom.$heure.'@gmail.com';
                            $user->setPassword(
                                $userPasswordHasher->hashPassword(
                                    $user,
                                    $defaulpass
                                )
                            );
                            $user->setUsername(empty($value[1]) ? 0 : $value[1]);
                            $user->setRoles(['ROLE_CLIENTS']);
                            $user->setEmail($defaultEmail);
                            $user->setCreatedAt(new \DateTimeImmutable($value[4]));
                            $user->setTelephone(empty($value[3]) ? 0 : $value[3]);
                            $user->setLocalisation(empty($value[2])? 0 : $value[2]);
                            $user->setSpeculation('000');


                            $user->getClients()->setNom(empty($value[1]) ? 0 : $value[1]);
                            $user->getClients()->setTelephone(empty($value[3]) ? 0 : $value[3]);
                            $user->getClients()->setReference($value[0]);
                            $user->getClients()->setLocalisation(empty($value[2])? 0 : $value[2]);
                            $user->getClients()->setCreatedAt(new \DateTimeImmutable($value[4]));

                            $entityManager->persist($user);
                            $entityManager->flush();

                            $processed++;
                            $progress = round(($i + 1) / $total * 100);
                            // Messages avec barre de progression ASCII
                            if ($progress % 20 === 0) {
                                $bar = str_repeat('█', $progress / 5) . str_repeat('░', 20 - ($progress / 5));
                                $this->addFlash('success', "[$bar] $progress% - Ligne " . ($i + 1) . "/$total");
                            }
                            $i++;
                        }
                        
                    }
                    
                    $this->addFlash('success', 'Importation terminée avec succès! Client trouver : '.$trouver);

                    return $this->redirectToRoute('app_client_a_import');
                } catch (\Exception $e) {
                    $this->addFlash("error", 'Erreur lors de la lecture du fichier: ' . $e->getMessage() );
                }
           } else {
            $this->addFlash("error", "echec de chargement du fichier");
           }
           

        }

        return $this->render("clients/import.html.twig",[
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
    
    #[Route('/clients/import', name:'app_client_import')] 
    public function import(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user"=>$user]);
            $agence = $tempagence->getAgence()->getId();
        return $this->render("clients/import.html.twig",[
            "id" => $agence
        ]);
    }

}
