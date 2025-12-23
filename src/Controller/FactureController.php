<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\TempAgence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;

class FactureController extends AbstractController
{
    #[Route('/facture/view/{id}', name: 'app_facture')]
    public function index(EntityManagerInterface $entityManger,int $id): Response
    {
        $facture = $entityManger->getRepository(Facture::class)->findBy(['vente'=>$id]);
        $client = null;
        $vente = null;
        if (is_array($facture) && count($facture) > 0) {
            $client = $facture[0]->getClient();
            $vente = $facture[0]->getVente();
        }
        return $this->render('facture/index.html.twig', [
            'facture' => $facture,
            'id' => $id,
            'client' => $client,
            'vente' => $vente,
        ]);
    }

    #[Route('/facture/print/{id}', name:'app_print_facture')]
    public function Print(EntityManagerInterface $entityManger, int $id, string $filename = 'facture.pdf')
    {
        $tempagence = $entityManger->getRepository(TempAgence::class)->findOneBy(['user' => $this->getUser()]);
        $agence =  $tempagence->getAgence();

        $facture = $entityManger->getRepository(Facture::class)->findBy(['vente'=>$id]);
        $client = null;
        $vente = null;
        if (is_array($facture) && count($facture) > 0) {
            $client = $facture[0]->getClient();
            $vente = $facture[0]->getVente();
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);

        $html = $this->renderView('facture/print.html.twig', [
        'vente' => $vente,
        'client' => $client,
        'factures' => $facture,
        'agences' => $agence,
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
