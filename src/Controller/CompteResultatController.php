<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Entity\BalanceA;
use App\Entity\TempAgence;
use App\Entity\VenteA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Dompdf\Dompdf;
use Dompdf\Options;

class CompteResultatController extends AbstractController
{
    #[Route('/compte/resultat', name: 'app_compte_resultat')]
    public function index(Request $request,EntityManagerInterface $entityManager, string $filename = 'facture.pdf'): Response
    {
        $tempAgence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user"=> $this->getUser()]) ;
        $id = $tempAgence->getAgence()->getId();
        if ($request->isMethod('POST')) {
            $year = $request->get('annee');
            
            $sommevente = $entityManager->getRepository(Balance::class)->findOneBy(["Compte" => 7021]);
            $sommeachat = $entityManager->getRepository(Balance::class)->findOneBy(["Compte" => 6021]);
            $transport = $entityManager->getRepository(Balance::class)->findOneBy(["Compte" => 6111]);
            $serviceexterieur = $entityManager->getRepository(Balance::class)->findOneBy(["Compte" => 6411]);
            $chargepersonnel = $entityManager->getRepository(Balance::class)->findOneBy(["Compte" => 4211]);
            $impots = $entityManager->getRepository(Balance::class)->findOneBy(["Compte" => 6311]);

            $options = new Options();
            $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
            $dompdf = new Dompdf($options);

            $html = $this->renderView('compte_resultat/compte.html.twig', [
                'year' => $year,
                'sommeventes' => $sommevente,
                'sommeacahts' => $sommeachat,
                'transports' => $transport,
                'serviceexterieur' => $serviceexterieur,
                'chargepersonnel' => $chargepersonnel,
                'impots' => $impots,
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
                    'Content-Disposition' => 'inline; filename="Compte_resultat.pdf"', // 'inline' pour affichage navigateur
                ]
            );
        }
        return $this->render('compte_resultat/index.html.twig', [
            'id' => $id,
        ]);
    }

    #[Route('/compte/resultat/a', name: 'app_compte_resultat_a')]
    public function CompteResultat(Request $request,EntityManagerInterface $entityManager): Response
    {
        $tempAgence = $entityManager->getRepository(TempAgence::class)->findOneBy(["user"=> $this->getUser()]) ;
        $id = $tempAgence->getAgence()->getId();

        if ($request->isMethod('POST')) {
            $year = $request->get('annee');

            $vente = $entityManager->getRepository(VenteA::class)->findVenteMontantYear($id,$year);
            $lastvente = $entityManager->getRepository(VenteA::class)->findVenteMontantLastYear($id,$year);
            $sommevente = $entityManager->getRepository(BalanceA::class)->findOneBy(["Compte" => 7021]);
            $sommeachat = $entityManager->getRepository(BalanceA::class)->findOneBy(["Compte" => 6021]);
            $transport = $entityManager->getRepository(BalanceA::class)->findOneBy(["Compte" => 6111]);
            $serviceexterieur = $entityManager->getRepository(BalanceA::class)->findOneBy(["Compte" => 6411]);
            $chargepersonnel = $entityManager->getRepository(BalanceA::class)->findOneBy(["Compte" => 4211]);
            $impots = $entityManager->getRepository(BalanceA::class)->findOneBy(["Compte" => 6311]);

             // Calculs intermédiaires

            $options = new Options();
            $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
            $dompdf = new Dompdf($options);

            $html = $this->renderView('compte_resultat/compte_a.html.twig', [
                'year' => $year,
                'vente' => $vente,
                'lastvente' => $lastvente,
                'sommeventes' => $sommevente,
                'sommeacahts' => $sommeachat,
                'transports' => $transport,
                'serviceexterieur' => $serviceexterieur,
                'chargepersonnel' => $chargepersonnel,
                'impots' => $impots,
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
                    'Content-Disposition' => 'inline; filename="Compte_resultat.pdf"', // 'inline' pour affichage navigateur
                ]
            );
        }
        return $this->render('compte_resultat/index_a.html.twig', [
            'id' => $id,
        ]);
    }
}
