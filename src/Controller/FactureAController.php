<?php

namespace App\Controller;

use App\Entity\FactureA;
use App\Entity\TempAgence;
use App\Entity\VenteA;
use BaconQrCode\Common\ErrorCorrectionLevel as CommonErrorCorrectionLevel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class FactureAController extends AbstractController
{
    #[Route('/facture/a/view/{id}', name: 'app_facture_a')]
    public function index(EntityManagerInterface $em, $id): Response
    {
        $vente = $em->getRepository(VenteA::class)->find($id);
        $facture = $em->getRepository(FactureA::class)->findBy(["vente"=>$vente]);
        $client = null;
        $vente = null;
        if (is_array($facture) && count($facture) > 0) {
            $client = $facture[0]->getClient();
            $vente = $facture[0]->getVente();
        }
        return $this->render('facture_a/index.html.twig', [
            'facture' => $facture,
            'id' => $id,
            'client' => $client,
            'vente' => $vente,
        ]);
    }

    #[Route('/facture/a/print/{id}', name:'app_print_facture_a')]
    public function Print(EntityManagerInterface $entityManger, int $id, string $filename = 'facture.pdf')
    {
        $tempagence = $entityManger->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $agence =  $tempagence->getAgence();

        $facture = $entityManger->getRepository(FactureA::class)->findBy(['vente'=>$id]);
        $client = null;
        $vente = null;
        if (is_array($facture) && count($facture) > 0) {
            $client = $facture[0]->getClient();
            $vente = $facture[0]->getVente();
        }
        $data = "RCCM:" .$agence->getRccm().
                " Adresse:".$agence->getAdress()." Tel:".
                $agence->getTelephone()."vente:".$vente->getId()."Montant:".$vente->getPrix().
                "FCFA Client:".$client->getNom()."telephone:".$client->getTelephone();

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh()) // <-- Notez le "new" et le nom complet
            ->size(200)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build();
        $base64 = $result->getDataUri();

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);

        $html = $this->renderView('facture_a/print.html.twig', [
        'vente' => $vente,
        'client' => $client,
        'factures' => $facture,
        'agences' => $agence,
        'qrCode' => $base64,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A5', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();

        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Facture.pdf"', // 'inline' pour affichage navigateur
            ]
        );
    }
}
