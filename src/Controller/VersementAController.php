<?php

namespace App\Controller;

use App\Entity\BalanceA;
use App\Entity\Clients;
use App\Entity\TempAgence;
use App\Entity\VersementA;
use App\Form\VersementAType;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VersementAController extends AbstractController
{
    #[Route('/versement/a/create', name: 'app_versement_a')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $versement = new VersementA();
        $form = $this->createForm(VersementAType::class,$versement);
        $form->handleRequest($request);
        $user = $this->getUser();
        if ($form->isSubmitted() && $form->isValid())
        {
            $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
            $id = $tempagence->getAgence()->getId();
            $versement->setUser($user);
            $versement->setAgence($tempagence->getAgence());
            $em->persist($versement);
            $em->flush();

            if ($form->get('montant')->getData() > 0) {
                $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 5111]);
                if ($balance) {
                    $mouvement = $balance->getMouvementDebit();
                    $mouvement = $mouvement + $form->get('montant')->getData();
                    $balance->setMouvementDebit($mouvement);
                    $em->persist($balance);
                    $em->flush();
                }

            }

            if ($form->get('banque')->getData() > 0) {
                $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 5121]);
                if ($balance) {
                    $mouvement = $balance->getMouvementDebit();
                    $mouvement = $mouvement + $form->get('banque')->getData();
                    $balance->setMouvementDebit($mouvement);
                    $em->persist($balance);
                    $em->flush();
                }
            }
            
            $balance = $em->getRepository(BalanceA::class)->findOneBy(['Compte' => 4111]);
                if ($balance) {
                    $mouvement = $balance->getMouvementCredit();
                    $mouvement = $mouvement + $form->get('montant')->getData() + $mouvement + $form->get('banque')->getData();
                    $balance->setMouvementCredit($mouvement);
                    $em->persist($balance);
                    $em->flush();
                }

            return $this->redirectToRoute("versement_a_list");
        }
        return $this->render('versement_a/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/versement/a/list', name: 'versement_a_list')]
    public function list(EntityManagerInterface $em) : Response {
        $versement = $em->getRepository(VersementA::class)->findAll();
        $client = $em->getRepository(Clients::class)->findAll();

        return $this->render('versement_a/list.html.twig',[
            'versement' => $versement,
            'client' => $client,
        ]);
    }
    /**
     * @Route(path="/versement/a/delete/{id}", name="versement_a_delete")
     */
    public function delete(VersementA $versement, EntityManagerInterface $em): Response
    {
        $em->remove($versement);
        $em->flush();

        return $this->redirectToRoute('versement_a_list');
    }
    /**
     * @Route(path="/versement/a/edit/{id}", name="versement_a_edit")
     */
    public function edit(VersementA $versement, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(VersementAType::class, $versement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($versement);
            $em->flush();

            return $this->redirectToRoute('versement_a_list');
        }

        return $this->render('versement_a/index.html.twig', [
            'form' => $form->createView(),
            'versement' => $versement,
        ]);
    }

    #[Route('/versement/a/download', name:'versement_a_download')]
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
            $client = $request->request->get('client');
            if (empty($first_date) || empty($end_date)) {
                $first_date = new \DateTimeImmutable();
                $end_date = new \DateTimeImmutable();
            }
        }
        if ($client == "ALL") {
            $versement = $em->getRepository(VersementA::class)->findByVersementSemaine($first_date,$end_date,$id);

        }else{
            $versement = $em->getRepository(VersementA::class)->findByVersementClient($first_date,$end_date,$id,$client);
        }

        $html = $this->renderView('versement_a/dwonload.html.twig', [
            'versements' => $versement,
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
