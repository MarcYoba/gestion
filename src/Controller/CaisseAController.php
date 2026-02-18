<?php

namespace App\Controller;

use App\Entity\CaisseA;
use App\Form\CaisseAType;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CaisseAController extends AbstractController
{
    #[Route('/caisse/a', name: 'app_caisse_a')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $caisseA = new CaisseA();
        $form = $this->createForm(CaisseAType::class,$caisseA);
        $form->handleRequest($request);
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user"=>$user]);
            $agence = $tempagence->getAgence();
        if ($form->isSubmitted() && $form->isValid()) {
            $libelle = $form->get('operation')->getData();
            $montant = $form->get('montant')->getData();
            
            if ($libelle == "sortie en caisse") {
                $montant = $montant * -1;
                
                $caisseA->setMontant($montant);
            }
            $caisseA->setAgence($agence);
            $caisseA->setUser($user);

            $entityManager->persist($caisseA);
            $entityManager->flush();
            return $this->redirectToRoute('app_caisse_a_list');
        }
        return $this->render('caisse_a/index.html.twig', [
            'form' => $form->createView(),
            'id' => $agence->getId(),
        ]);
    }

    #[Route('/caisse/a/liste', name: 'app_caisse_a_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user"=>$user]);
        $id = $tempagence->getAgence()->getId();
        $caisseA = $entityManager->getRepository(CaisseA::class)->findAll(["agence" => $id]);
       return $this->render('caisse_a/list.html.twig', [
            'caisseAs' => $caisseA,
            'id' => $id,
        ]); 
    }

    #[Route('/caisse/a/etat', name : 'etat_a_caisse')]
    public function Etat_Caisse(){
         return $this->json(['success'=> true,'message'=> 'success']);
    }

    #[Route('/caisse/a/download', name: 'caisse_a_download')]
    public function download(EntityManagerInterface $em, Request $request) : Response {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        if ($request->isMethod('POST')) {
            $first_date = $request->request->get('date1');
            $end_date = $request->request->get('date2');
            
            if (empty($first_date) || empty($end_date)) {
                $first_date = new \DateTime();
                $end_date = new \DateTime();
            }
        }
        
        $caisse = $em->getRepository(CaisseA::class)->findByCaisseSemaine($first_date, $end_date, $tempagence->getAgence());
        $html = $this->renderView('caisse_a/download.html.twig', [
            'caisses' => $caisse,
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
                'Content-Disposition' => 'inline; filename="Inventaire.pdf"', // 'inline' pour affichage navigateur
            ]
        );
    }
}
