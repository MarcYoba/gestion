<?php

namespace App\Controller;

use App\Entity\TempAgence;
use App\Entity\Vente;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RapportController extends AbstractController
{
    /**
     * @Route(path="/rapport", name="app_rapport")
     */
    public function rapport(): Response
    {
        return $this->render('rapport/rapport.html.twig', [
            'controller_name' => 'RapportController',
        ]);
    }
    /**
     * @Route(path="/rapport/a", name="app_rapport_a")
     */
    public function rapportA(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();
        return $this->render('rapport/rapportA.html.twig',[
          'id' => $agence,  
        ]);
    }
    /**
     * @Route(path="/rapport/day" , name="rapport_day")
     */
    public function rapport_day(EntityManagerInterface $em): Response
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        $vente = $em->getRepository(Vente::class)->findAll();

        $html = $this->renderView('rapport/rapport_day.html.twig', [
        'data' => $vente
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
