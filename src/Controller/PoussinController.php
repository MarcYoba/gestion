<?php

namespace App\Controller;

use App\Entity\Poussin;
use App\Form\PoussinType;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use chillerlan\QRCode\{QRCode, QROptions};
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
    public function Edite(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }

        $poussin = new Poussin();
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();
        $poussin = $em->getRepository(Poussin::class)->findAll(["agence" => $id]);
        
        $form = $this->createForm(PoussinType::class,$poussin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tempagence);
            $em->flush();
        }
        return $this->render("poussin/Edit.html.twig", [
            "Poussin"=> $poussin,
            "form"=> $form->createView(),
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

        $datte1 = $request->request->get('datedette');
        $datte2 = $request->request->get('datedett2');
        $type = $request->request->get('status');

        dd($datte1."date".$datte2);
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
}
