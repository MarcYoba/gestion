<?php

namespace App\Controller;

use App\Entity\ActifA;
use App\Entity\TempAgence;
use App\Form\ActifAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class ActifAController extends AbstractController
{
    #[Route('/actif/a/create', name: 'app_actif_a')]
    public function index(EntityManagerInterface $em, Request $Request): Response
    {
        $Actif = new ActifA();
        $form = $this->createForm(ActifAType::class, $Actif);
        $form->handleRequest($Request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tempAgence = $em->getRepository(TempAgence::class)->findOneBy(["user"=> $this->getUser()]) ;
            $id = $tempAgence->getAgence()->getId();
            $ordre = 0;
            $nombre = $em->getRepository(ActifA::class)->findOneBy([], ['id' => 'DESC']);
            if ($nombre) {
                $ordre = $nombre->getId();
            }
            $Actif->setOrdre($ordre+1 );
            $Actif->setAgence($tempAgence->getAgence());
            $Actif->setUser($this->getUser());

            $em->persist($Actif);
            $em->flush();

            $this->addFlash('success','');
            return $this->redirectToRoute('app_actif_a_list', ["date" => date("Y")]);
        }
        return $this->render('actif_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/actif/a/list/{date}', name:'app_actif_a_list')]
    public function list(EntityManagerInterface $em,string $date): Response
    {
        $tempAgence = $em->getRepository(TempAgence::class)->findOneBy(["user"=> $this->getUser()]) ;
        $id = $tempAgence->getAgence()->getId();
        $Actif =  $em->getRepository(ActifA::class)->findBy(["agence"=> $id]);
        return $this->render('actif_a/list.html.twig', [
            'Actifs' => $Actif,
            'date' => $date,
        ]);
    }

    #[Route('/actif/a/update', name:'app_actif_a_update')]
    public function update(EntityManagerInterface $entityManager, Request $request): Response
    {
        if($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $json = $request->getContent();
            $donnees = json_decode($json, true);
            $az = [
                    'brut' => 0,
                    'amort' => 0,
                    'net' => 0,
                ];

            if (isset($donnees)) {
                $ad = $entityManager->getRepository(ActifA::class)->findBySomme($donnees,"Incorporelles");
                if (is_array($ad)) {
                    $actif = $entityManager->getRepository(ActifA::class)->findByRefDate("AD", $donnees);
                    if (!empty($actif)) {
                        foreach ($ad as $key => $value) {
                            $actif->setBrut($value[1]);
                            $actif->setAmortissement($value[2]);
                            $actif->setNet($value[3]);
                            $az['brut'] = $az['brut'] + $value[1];
                            $az['amort'] = $az['amort'] + $value[2];
                            $az['net'] = $az['net'] + $value[3];
                        }
                        $entityManager->persist($actif);
                        $entityManager->flush();
                    }
                }

                $ai = $entityManager->getRepository(ActifA::class)->findBySomme($donnees,"corporelles");
                if (is_array($ai)) {
                    $actif = $entityManager->getRepository(ActifA::class)->findByRefDate("AI", $donnees);
                    if (!empty($actif)) {
                        foreach ($ai as $key => $value) {
                            $actif->setBrut($value[1]);
                            $actif->setAmortissement($value[2]);
                            $actif->setNet($value[3]);

                            $az['brut'] = $az['brut'] + $value[1];
                            $az['amort'] = $az['amort'] + $value[2];
                            $az['net'] = $az['net'] + $value[3];
                        }
                        $entityManager->persist($actif);
                        $entityManager->flush();
                    }
                }

                $aq = $entityManager->getRepository(ActifA::class)->findBySomme($donnees,"financières");
                if (is_array($aq)) {
                    $actif = $entityManager->getRepository(ActifA::class)->findByRefDate("AQ", $donnees);
                    if (!empty($actif)) {
                        foreach ($aq as $key => $value) {
                            $actif->setBrut($value[1]);
                            $actif->setAmortissement($value[2]);
                            $actif->setNet($value[3]);

                            $az['brut'] = $az['brut'] + $value[1];
                            $az['amort'] = $az['amort'] + $value[2];
                            $az['net'] = $az['net'] + $value[3];
                        }
                        $entityManager->persist($actif);
                        $entityManager->flush();
                    }
                }

                $actif = $entityManager->getRepository(ActifA::class)->findByRefDate("AZ", $donnees);
                if ($actif) {
                    $actif->setBrut($az['brut']);
                    $actif->setAmortissement($az['amort']);
                    $actif->setNet($az['net']);
                }

                $bg = $entityManager->getRepository(ActifA::class)->findByCreanceAssimiles($donnees,"CIRCULANT");
                if (is_array($bg)) {
                    $actif = $entityManager->getRepository(ActifA::class)->findByRefDate("BG", $donnees);
                    if (($actif)) {
                        foreach ($bg as $key => $value) {
                            $actif->setBrut($value[1]);
                            $actif->setAmortissement($value[2]);
                            $actif->setNet($value[3]);

                            $az['brut'] = $az['brut'] + $value[1];
                            $az['amort'] = $az['amort'] + $value[2];
                            $az['net'] = $az['net'] + $value[3];
                        }
                        $entityManager->persist($actif);
                        $entityManager->flush();
                    }
                }

                $bk = $entityManager->getRepository(ActifA::class)->findBySommeCirculan($donnees,"CIRCULANT");
                if (is_array($bk)) {
                    $actif = $entityManager->getRepository(ActifA::class)->findByRefDate("BK", $donnees);
                    if (($actif)) {
                        foreach ($bk as $key => $value) {
                            $actif->setBrut($value[1]);
                            $actif->setAmortissement($value[2]);
                            $actif->setNet($value[3]);

                            $az['brut'] = $az['brut'] + $value[1];
                            $az['amort'] = $az['amort'] + $value[2];
                            $az['net'] = $az['net'] + $value[3];
                        }
                        $entityManager->persist($actif);
                        $entityManager->flush();
                    }
                }

                $bt = $entityManager->getRepository(ActifA::class)->findBySomme($donnees,"tresorerie");
                if (is_array($bt)) {
                    $actif = $entityManager->getRepository(ActifA::class)->findByRefDate("BT", $donnees);
                    if (!empty($actif)) {
                        foreach ($bt as $key => $value) {
                            $actif->setBrut($value[1]);
                            $actif->setAmortissement($value[2]);
                            $actif->setNet($value[3]);

                            $az['brut'] = $az['brut'] + $value[1];
                            $az['amort'] = $az['amort'] + $value[2];
                            $az['net'] = $az['net'] + $value[3];
                        }
                        $entityManager->persist($actif);
                        $entityManager->flush();
                    }
                }

                $bu = $entityManager->getRepository(ActifA::class)->findBySomme($donnees,"tresorerie");
                if (is_array($bu)) {
                    $actif = $entityManager->getRepository(ActifA::class)->findByRefDate("BU", $donnees);
                    if (!empty($actif)) {
                        foreach ($bu as $key => $value) {
                            $actif->setBrut($value[1]);
                            $actif->setAmortissement($value[2]);
                            $actif->setNet($value[3]);

                            $az['brut'] = $az['brut'] + $value[1];
                            $az['amort'] = $az['amort'] + $value[2];
                            $az['net'] = $az['net'] + $value[3];
                        }
                        $entityManager->persist($actif);
                        $entityManager->flush();
                    }
                }

                $actif = $entityManager->getRepository(ActifA::class)->findByRefDate("BZ", $donnees);
                if ($actif) {
                    $actif->setBrut($az['brut']);
                    $actif->setAmortissement($az['amort']);
                    $actif->setNet($az['net']);
                    $entityManager->persist($actif);
                    $entityManager->flush();
                }
                return $this->json(['success' => 'Mise a jour du bilan','donne'=>$donnees], 200);
            }
        }
        return $this->json(['error' => 'Erreur de bilan'], 404);
    }

    #[Route('/actif/a/edit/{id}', name: 'app_actif_a_edite')]
    public function edite(EntityManagerInterface $entityManager, Request $request, int $id){
        $Actif = $entityManager->getRepository(ActifA::class)->find(["id" => $id]);
        $form = $this->createForm(ActifAType::class, $Actif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($Actif);
            $entityManager->flush();
            return $this->redirectToRoute('app_actif_a_list', ["date" => date("Y")]);

        }

        return $this->render('actif/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/actif/a/delete/{id}', name: 'app_actif_a_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id) : Response {
       $Actif = $entityManager->getRepository(ActifA::class)->find($id);
       $entityManager->remove($Actif);
       $entityManager->flush();
        return $this->redirectToRoute('app_actif_a_list', ["date" => date("Y")]);
    }

    #[Route('/actif/a/download', name: 'actif_a_download')]
    public function download(EntityManagerInterface $em, Request $request) : Response 
    {
        $date = date("Y-m-d");
        if ($request->isMethod('POST')) {
           $date = $request->request->get('date');
           if(empty($date))
           {
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_actif_list");
           }
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        $actif = $em->getRepository(ActifA::class)->findByYear($date);
        
        
        //dd($vente);
        $html = $this->renderView('actif_a/dwonload.html.twig', [
        'actifs' => $actif,
        'date' => $date,
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
                'Content-Disposition' => 'inline; filename="document.pdf"', // 'inline' pour affichage navigateur
            ]
        );
    }
}
