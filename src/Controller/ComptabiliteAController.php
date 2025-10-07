<?php

namespace App\Controller;

use App\Entity\AchatA;
use App\Entity\TempAgence;
use App\Entity\VenteA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComptabiliteAController extends AbstractController
{
    #[Route('/comptabilite/a/journale', name: 'app_comptabilite_a_journale')]
    public function index(EntityManagerInterface $em): Response
    {
        $achat = [];
        $achatTrimestre = [];
        $venteTrimestre = [];
        $vente = [];
        $user = $this->getUser();
        $tempagence = $em->getRepository(TempAgence::class)->findOneBy(['user' => $user]);
        $id = $tempagence->getAgence()->getId();

        for ($i=1; $i <= 12 ; $i++) { 
           array_push($achat,$em->getRepository(AchatA::class)->findByMontantMonth(date("Y"),$i,$id));
        }

        for ($i=1; $i <= 4; $i++) { 
            array_push($achatTrimestre,$em->getRepository(AchatA::class)->findByMontantTrimestre($i,date("Y"),$id));
        }

        for ($i=1; $i <=2 ; $i++) { 
            array_push($achatTrimestre,$em->getRepository(AchatA::class)->findByMontantSemestre($i,date("Y"),$id));
        }

        for ($i=1; $i <=4 ; $i++) { 
            array_push($venteTrimestre,$em->getRepository(VenteA::class)->findByMontantTrimestre($i,date("Y"),$id));
        }

        for ($i=1; $i <=2 ; $i++) { 
            array_push($venteTrimestre,$em->getRepository(VenteA::class)->findByMontantSemestre($i,date("Y"),$id));
        }
        
        for ($i=1; $i <=12 ; $i++) { 
            array_push($vente,$em->getRepository(VenteA::class)->findByMontantMonth($i,date("Y"),$id));
        }
        
        return $this->render('comptabilite_a/journale.html.twig', [
            'achats' => $achat,
            'achatTrimestres' => $achatTrimestre,
            'venteTrimestres' => $venteTrimestre,
            'ventes' => $vente,
        ]);
    }
}
