<?php

namespace App\Controller;

use App\Entity\Caisse;
use App\Entity\Depenses;
use App\Entity\TempAgence;
use App\Entity\Vente;
use App\Entity\Versement;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RapportController extends AbstractController
{
    /**
     * @Route(path="/rapport", name="app_rapport")
     */
    public function rapport(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_logout');
        }
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();
        return $this->render('rapport/rapport.html.twig', [
            'id' => $agence,
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
    public function rapport_day(EntityManagerInterface $em,Request $request): Response
    {

        $date = date("Y-m-d");
        if ($request->isMethod('POST')) {
           $date = $request->request->get('date');
           if(empty($date))
           {
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport");
           }
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);

        $vente = $em->getRepository(Vente::class)->findByDay($date);
        $depense = $em->getRepository(Depenses::class)->findByDay($date);

        $date  = new DateTime($date);
        $caisse = $em->getRepository(Caisse::class)->findBy(['createAt' => $date]);
        $versement = $em->getRepository(Versement::class)->findBy(['createdAd' => $date]);
        

        
        $html = $this->renderView('rapport/rapport_day.html.twig', [
        'ventes' => $vente,
        'date' => $date->format("Y-m-d"),
        'caisses' => $caisse,
        'versements' => $versement,
        'depenses' => $depense,
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
