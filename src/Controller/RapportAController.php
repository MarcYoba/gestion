<?php

namespace App\Controller;

use App\Entity\VenteA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class RapportAController extends AbstractController
{
    #[Route('/rapport/a/jour', name: 'app_rapport_a_jour')]
    public function index(EntityManagerInterface $em): Response
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        $date = date("Y-m-d");
        $date = new \DateTimeImmutable($date);
        $vente = $em->getRepository(VenteA::class)->findRapportToDay( $date);
        //dd($vente);
        $html = $this->renderView('rapport_a/jour_courante.html.twig', [
        'ventes' => $vente
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
