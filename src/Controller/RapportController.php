<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Caisse;
use App\Entity\Depenses;
use App\Entity\Facture;
use App\Entity\Historique;
use App\Entity\Magasin;
use App\Entity\TempAgence;
use App\Entity\Transfert;
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
        $user = $this->getUser();
        $date = date("Y-m-d");
        if ($request->isMethod('POST')) {
           $date = $request->request->get('date');
           if(empty($date))
           {
                $this->addFlash("error", "Vous deviez selectiion aune date valide");
                return $this->redirectToRoute("app_rapport");
           }
        }
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $agence = $tempagence->getAgence()->getId();

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Permet les assets distants (CSS/images)
        $dompdf = new Dompdf($options);

        $vente = $em->getRepository(Vente::class)->findByDay($date);
        $depense = $em->getRepository(Depenses::class)->findByDay($date);
        $sommeDepense = $em->getRepository(Depenses::class)->findBySommeDay($date);
        $sommeversement = $em->getRepository(Versement::class)->findBysommeDay($date);
        
        
        
        $date  = new DateTime($date);
        $sommecaisse = $em->getRepository(Caisse::class)->findBySommeCaisseDay($date,$agence);
        $transfert = $em->getRepository(Transfert::class)->findBy(['createtAt' => $date]);

        $histoiques = [];
        $benefice = [];
        $histoique = $em->getRepository(Facture::class)->findByProduitVendu($date,$agence);
            foreach ($histoique as $key => $value) {
                $quantite = 0;
                $hist = $em->getRepository(Historique::class)->findByDate($date,$value->getProduit()->getId(),$agence);
                $fact = $em->getRepository(Facture::class)->findByQuantiteProduitVendu($date,$value->getProduit()->getId(),$agence);
                $lasthist = $em->getRepository(Historique::class)->findByLastDate(new \DateTime($date->format("Y-m-d")),$value->getProduit()->getId(),$agence);
                $magasin = $em->getRepository(Magasin::class)->findOneBy(["produit" => $value->getProduit()->getId()]);
                $achat = $em->getRepository(Achat::class)->findByPrixAchatProduit($value->getProduit()->getId(),$agence);
                if($magasin) {
                    $quantite = $magasin->getQuantite();
                }
                array_push($histoiques,[$value->getProduit()->getNom(),$hist,$fact,$value->getProduit()->getQuantite(),$quantite]);
                array_push($benefice,[$value->getProduit()->getNom(),$fact,$value->getProduit()->getPrixvente(),$achat,($value->getProduit()->getPrixvente() - $achat) * $fact]);
            }
        
        $caisse = $em->getRepository(Caisse::class)->findBy(['createAt' => $date]);
        $versement = $em->getRepository(Versement::class)->findBy(['createdAd' => $date]);

        $totalversement = 0;
        foreach ($sommeversement as $key => $value) {
            $totalversement = $totalversement + $value[1] + $value[2] + $value[3];
        }
        

        
        $html = $this->renderView('rapport/rapport_day.html.twig', [
        'ventes' => $vente,
        'date' => $date->format("Y-m-d"),
        'caisses' => $caisse,
        'versements' => $versement,
        'depenses' => $depense,
        'sommeDepense' => $sommeDepense,
        'sommeversement' => $sommeversement,
        'totalversement' => $totalversement,
        'sommecaisse' => $sommecaisse,
        'histoiques' => $histoiques,
        'magasins' => $transfert,
        'benefices' => $benefice,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // 5. Rendre le PDF
        $dompdf->render();
        $document = "rapport_du_".$date->format("Y-m-d").".pdf";
        // 6. Retourner le PDF dans la réponse
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$document.'"', // 'inline' pour affichage navigateur
            ]
        );
    }
}
