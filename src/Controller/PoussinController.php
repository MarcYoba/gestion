<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Poussin;
use App\Form\PoussinType;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use chillerlan\QRCode\{QRCode, QROptions};
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
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
            $em->persist($poussin);
            $em->flush();

            return $this->redirectToRoute('app_poussin_list');
        }
        return $this->render('poussin/index.html.twig', [
            'form' => $form->createView() ,
        ]);
    }

    #[Route('/poussin/list', name: 'app_poussin_list')]
    public function List(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $poussin = $em->getRepository(Poussin::class)->findAll(["agence" => $id]);
        return $this->render('poussin/list.html.twig', [
            'poussins' => $poussin,
        ]);
    }

    #[Route('/poussin/edit/{id}', name:'app_pousssin_edit')]
    public function Edite(EntityManagerInterface $em, Poussin $poussin): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $client = $em->getRepository(Clients::class)->findAll();

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

    #[Route('/poussin/update', name: 'app_poussin_update', methods:['POST'])]
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
}
