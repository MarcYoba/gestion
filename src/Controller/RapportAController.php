<?php

namespace App\Controller;

use App\Entity\AchatA;
use App\Entity\CaisseA;
use App\Entity\Consultation;
use App\Entity\DepenseA;
use App\Entity\Poussin;
use App\Entity\ProspectionA;
use App\Entity\Suivi;
use App\Entity\Vaccin;
use App\Entity\VenteA;
use App\Entity\VersementA;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

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
        $caisse = $em->getRepository(CaisseA::class)->findBy(["createAt" => $date]);
        $vente = $em->getRepository(VenteA::class)->findRapportToDay( $date);
        $achat = $em->getRepository(AchatA::class)->findByDay($date);
        $depense = $em->getRepository(DepenseA::class)->findByDay($date);
        $versement = $em->getRepository(VersementA::class)->findByDay($date);
        $poussin = $em->getRepository(Poussin::class)->findByDay($date->format("Y-m-d"));
        $consultation = $em->getRepository(Consultation::class)->findByDay($date);
        $suivi = $em->getRepository(Suivi::class)->findByDay($date);
        $vaccin = $em->getRepository(Vaccin::class)->findBy(['dateVaccin'=>$date]);
        $terrain = $em->getRepository(ProspectionA::class)->findBy(['createtAt'=>$date]);

        
        $html = $this->renderView('rapport_a/jour_courante.html.twig', [
        'ventes' => $vente,
        'achats' => $achat,
        'caisses' => $caisse,
        'depenses' => $depense,
        'versements' => $versement,
        'poussins' => $poussin,
        'consultations' => $consultation,
        'suivis' => $suivi,
        'vaccins' => $vaccin,
        'terrains' => $terrain,
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

    #[Route('/rapport/a/hier', name: 'app_rapport_a_hier')]
    public function rapport_hier(EntityManagerInterface $em, Request $request) : Response 
    {
        $date = date("Y-m-d");
        if ($request->isMethod('POST')) {
           $date = $request->request->get('date');
           if(empty($date))
           {
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport_a");
           }
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        $date = new \DateTimeImmutable($date);
        $caisse = $em->getRepository(CaisseA::class)->findBy(["createAt" => $date]);
        $vente = $em->getRepository(VenteA::class)->findRapportToDay($date);
        $achat = $em->getRepository(AchatA::class)->findByDay($date);
        $depense = $em->getRepository(DepenseA::class)->findByDay($date);
        $versement = $em->getRepository(VersementA::class)->findByDay($date);
        $poussin = $em->getRepository(Poussin::class)->findByDay($date->format("Y-m-d"));
        $consultation = $em->getRepository(Consultation::class)->findByDay($date);
        $suivi = $em->getRepository(Suivi::class)->findByDay($date);
        $vaccin = $em->getRepository(Vaccin::class)->findBy(['dateVaccin'=>$date]);
        $terrain = $em->getRepository(ProspectionA::class)->findBy(['createtAt'=>$date]);
        
        $html = $this->renderView('rapport_a/hier.html.twig', [
        'ventes' => $vente,
        'achats' => $achat,
        'caisses' => $caisse,
        'calandrier' => $date->format("Y-m-d"),
        'depenses' => $depense,
        'versements' => $versement,
        'poussins' => $poussin,
        'consultations' => $consultation,
        'suivis' => $suivi,
        'vaccins' => $vaccin,
        'terrains' => $terrain,
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
    #[Route('/rapport/a/semaine', name:'app_rapport_a_semaine')]
    public function rapport_semain(EntityManagerInterface $em, Request $request) : Response 
    {
        $date_debut = date("Y-m-d");
        $date_fin = date("Y-m-d");
        if ($request->isMethod('POST')) {
           $date_debut = $request->request->get('date_debut');
           $date_fin = $request->request->get('date_fin');
           if(empty($date_debut) && empty($date_fin))
           {
                
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport_a");
           }
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        $date_debut = new \DateTimeImmutable($date_debut);
        $date_fin = new \DateTimeImmutable($date_fin);
        $caisse = $em->getRepository(CaisseA::class)->findRapportCaisseToWeek($date_debut,$date_fin);
        $vente = $em->getRepository(VenteA::class)->findRapportVenteToWeek($date_debut,$date_fin);
        $achat = $em->getRepository(AchatA::class)->findByDate($date_debut);
        
        //dd($vente);
        $html = $this->renderView('rapport_a/semaine.html.twig', [
        'date_debut' => $date_debut,
        'date_fin' => $date_fin,
        'ventes' => $vente,
        'achats' => $achat,
        'caisses' => $caisse
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

    #[Route('/rapport/a/moi', name:'app_rapport_a_moi')]
    public function rapport_moi(EntityManagerInterface $em, Request $request) : Response 
    {
        $date_debut = 0;
        $date_fin = 0;
        $anne = date("Y");
        if ($request->isMethod('POST')) {
           $date_debut = $request->request->get('mois');
           $date_fin = $request->request->get('date');

           if (empty($date_debut)) {
                if (!empty($date_fin)) {
                    $date_fin = new DateTime($date_fin);
                    $date_debut = $date_fin->format("m");
                    $anne = $date_fin->format("Y");
                }
           }
           //$date_debut = date("Y".$date_debut."d");
           
           if(empty($date_debut) && empty($date_fin))
           {
                
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport_a");
           }
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);
        
        
        $caisse = $em->getRepository(CaisseA::class)->findRapportCaisseToWeek($date_debut,$anne);
        $vente = $em->getRepository(VenteA::class)->findRapportMensuel($date_debut,$anne);
        $achat = $em->getRepository(AchatA::class)->findByDate($date_debut,$anne);
        $depense = $em->getRepository(DepenseA::class)->findByMoi($date_debut,$anne);
        
        $html = $this->renderView('rapport_a/moi.html.twig', [
        'date_debut' => $date_debut,
        'date_fin' => $date_fin,
        'ventes' => $vente,
        'achats' => $achat,
        'caisses' => $caisse,
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
