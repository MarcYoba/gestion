<?php

namespace App\Controller;

use App\Entity\PassifA;
use App\Entity\TempAgence;
use App\Form\PassifAType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
class PassifAController extends AbstractController
{
    #[Route('/passif/a/creat', name: 'app_passif_a')]
    public function index(EntityManagerInterface $entityManager,Request $request): Response
    {
        $passif = new PassifA();
        $user = $this->getUser();
        $form = $this->createForm(PassifAType::class,$passif);
        $form->handleRequest($request);
        $agence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $agence->getAgence()->getId();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $agence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);

            $ordre = $entityManager->getRepository(PassifA::class)->findOneBy(['agence' => $id]);
            if ($ordre) {
                $passif->setOrdre(($ordre->getOrdre() + 1));
            } else {
                $passif->setOrdre(1);
            }
            
            $passif->setUser($user);
            $passif->setAgence($agence->getAgence());
            $entityManager->persist($passif);
            $entityManager->flush();

            return $this->redirectToRoute("app_passif_a_list",['date'=>date('Y')]);
        }
        return $this->render('passif_a/index.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
        ]);
    }

    #[Route('passif/a/list/{date}', name:'app_passif_a_list')]
    public function list(EntityManagerInterface $entityManager,int $date) : Response
    {
        $user = $this->getUser();
        $agence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $agence->getAgence()->getId();

        $passif = $entityManager->getRepository(PassifA::class)->findBy(["agence" => $id]);

        return $this->render('passif_a/list.html.twig', [
            'passifs' => $passif,
            'date' => $date,
            'id' => $id
        ]);
    }

    #[Route('passif/a/edit/{id}', name:'app_passif_a_edit')]
    public function Edit(Request $request,EntityManagerInterface $entityManager, int $id) : Response 
    {
        

        $passif = $entityManager->getRepository(PassifA::class)->findOneBy(["id" => $id]);
        $user = $this->getUser();
        $agence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $agence->getAgence()->getId();

        $form = $this->createForm(PassifAType::class,$passif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute("app_passif_a_list",['date'=>date('Y')]);
        }
        return $this->render('passif_a/edite.html.twig', [
            "form" => $form->createView(),
            "passif" => $passif,
            'id' => $id
        ]);
    }

    #[Route('/passif/delete/{id}', name: 'app_passif_a_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id) : Response
    {   
        $passif = $entityManager->getRepository(PassifA::class)->find($id);
        if ($passif) {
            $entityManager->remove($passif);
            $entityManager->flush();
        }
        
      return $this->redirectToRoute("app_passif_a_list",['date'=>date('Y')]);   
    }

    #[Route('/passif/a/update', name: 'app_passif_a_update')]
    public function update(EntityManagerInterface $em, Request $request) : Response 
    {
        if($request->isXmlHttpRequest() || $request->getContentType()==='json') {
            $json = $request->getContent();
            $donnees = json_decode($json, true);
            $az = [
                    'mont' => 0,
                ];
            if (isset($donnees)) {

                $cp = $em->getRepository(PassifA::class)->findBySomme($donnees,"Capital");
                if (is_array($cp)) {
                    $passif = $em->getRepository(PassifA::class)->findByRefDate("CP",$donnees);
                    if($passif)
                    {
                        foreach ($cp as $key => $value) {
                            $passif->setMontant($value[1]);
                            $az['mont'] = $az['mont'] + $value[1]; 
                        }
                        $em->persist($passif);
                        $em->flush();
                    }
                    
                }

                $cp = $em->getRepository(PassifA::class)->findBySomme($donnees,"DETTES");
                if (is_array($cp)) {
                    $passif = $em->getRepository(PassifA::class)->findByRefDate("DD",$donnees);
                    if($passif)
                    {
                        foreach ($cp as $key => $value) {
                            $passif->setMontant($value[1]);
                            $az['mont'] = $az['mont'] + $value[1];
                        }
                        $em->persist($passif);
                        $em->flush();
                    }
                    
                }

                $passif = $em->getRepository(PassifA::class)->findByRefDate("DF",$donnees);
                    if($passif)
                    {
                            $passif->setMontant($az['mont']);
                        
                        $em->persist($passif);
                        $em->flush();
                    }
                $cp = $em->getRepository(PassifA::class)->findBySomme($donnees,"circulant");
                if (is_array($cp)) {
                    $passif = $em->getRepository(PassifA::class)->findByRefDate("DP",$donnees);
                    if($passif)
                    {
                        foreach ($cp as $key => $value) {
                            $passif->setMontant($value[1]);
                            $az['mont'] = $az['mont'] + $value[1];
                        }

                        $em->persist($passif);
                        $em->flush();
                    }
                    
                }

                $cp = $em->getRepository(PassifA::class)->findBySomme($donnees,"TRESORERIE");
                if (is_array($cp)) {
                    $passif = $em->getRepository(PassifA::class)->findByRefDate("DT",$donnees);
                    if($passif)
                    {
                        foreach ($cp as $key => $value) {
                            $passif->setMontant($value[1]);
                        }
                        $em->persist($passif);
                        $em->flush();
                    }
                    
                }

                $cp = $em->getRepository(PassifA::class)->findBySomme($donnees,"Ecart");
                if (is_array($cp)) {
                    $passif = $em->getRepository(PassifA::class)->findByRefDate("DV",$donnees);
                    if($passif)
                    {
                        foreach ($cp as $key => $value) {
                            $passif->setMontant($value[1]);
                            $az['mont'] = $az['mont'] + $value[1];
                        }
                        $em->persist($passif);
                        $em->flush();
                    }
                    
                }
                    $passif = $em->getRepository(PassifA::class)->findByRefDate("DZ",$donnees);
                    if($passif)
                    {
                        foreach ($cp as $key => $value) {
                            $passif->setMontant($az['mont']);
                        }
                        $em->persist($passif);
                        $em->flush();
                    }
                    
                return $this->json(['success' => 'Mise a jour du bilan','donne'=>$donnees], 200);
            }
        }
        return $this->json(['error' => 'Erreur de bilan'], 404);
    }

    #[Route('/passif/a/download', name: 'passif_a_download')]
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
        $passif = $em->getRepository(PassifA::class)->findByYear($date);
        
        
        //dd($vente);
        $html = $this->renderView('passif_a/dwonload.html.twig', [
        'passifs' => $passif,
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
