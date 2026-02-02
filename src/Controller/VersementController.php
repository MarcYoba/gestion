<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Entity\Versement;
use App\Form\VersementType;
use App\Entity\Clients;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VersementController extends AbstractController
{
    /**
     * @Route( path ="/versement", name="app_versement")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $versement = new Versement();
        $form = $this->createForm(VersementType::class,$versement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $versement->setUser($user);
            $entityManager->persist($versement);
            $entityManager->flush();

            if ($form->get('montant')->getData() > 0) {
                $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 5111]);
                if ($balance) {
                    $mouvement = $balance->getMouvementDebit();
                    $mouvement = $mouvement + $form->get('montant')->getData();
                    $balance->setMouvementDebit($mouvement);
                    $entityManager->persist($balance);
                    $entityManager->flush();
                }

            }

            if ($form->get('banque')->getData() > 0) {
                $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 5121]);
                if ($balance) {
                    $mouvement = $balance->getMouvementDebit();
                    $mouvement = $mouvement + $form->get('banque')->getData();
                    $balance->setMouvementDebit($mouvement);
                    $entityManager->persist($balance);
                    $entityManager->flush();
                }
            }
            
            $balance = $entityManager->getRepository(Balance::class)->findOneBy(['Compte' => 4111]);
                if ($balance) {
                    $mouvement = $balance->getMouvementCredit();
                    $mouvement = $mouvement + $form->get('montant')->getData() + $mouvement + $form->get('banque')->getData();
                    $balance->setMouvementCredit($mouvement);
                    $entityManager->persist($balance);
                    $entityManager->flush();
                }
            
            return $this->redirectToRoute('versement_list');
        }
        return $this->render('versement/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route( path ="/versement/list", name="versement_list")
     */
    public function list(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $clients = new Clients();
        $versement = new Versement();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user" => $user]);
        $id = $tempagence->getAgence()->getId();
        if ($tempagence->isGenerale()== 1) {
            $clients = $entityManager->getRepository(Clients::class)->findAll();
            $versement = $entityManager->getRepository(Versement::class)->findAll(["id" => $id]);
        }else{
            $clients = $entityManager->getRepository(Clients::class)->findAll();
           $versement = $entityManager->getRepository(Versement::class)->findAll(["id" => $id]);
        }
        
        $clients = $entityManager->getRepository(Clients::class)->findAll();
        return $this->render('versement/list.html.twig', [
            'versement' => $versement,
            'clients' => $clients,
        ]);
    }

    /**
     * @Route( path ="/versement/edit/{id}", name="versement_edit")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, Versement $versement): Response
    {
        $form = $this->createForm(VersementType::class, $versement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('versement_list');
        }
        return $this->render('versement/index.html.twig', [
            'form' => $form->createView(),
            'versement' => $versement,
        ]);
    }
    /**
     * @Route( path ="/versement/delete/{id}", name="versement_delete")
     */
    public function delete(EntityManagerInterface $entityManager, Versement $versement): Response
    {
        $entityManager->remove($versement);
        $entityManager->flush();
        return $this->redirectToRoute('versement_list');
    }

    #[Route('/versement/download/{id}', name: 'versement_dwonload')]
    public function dwonload(EntityManagerInterface $entityManager,Versement $versement) : Response 
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);

        $user = $this->getUser();
        $tempagence = $entityManager->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        

        $html = $this->render('versement/download.html.twig', [
           'versements' => $versement,
           'agences' =>  $tempagence->getAgence(),
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A7', 'portrait');

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
