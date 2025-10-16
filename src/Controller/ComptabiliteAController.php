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
    #[Route('/comptabilite/a/Evolution/mensuel', name: 'app_comptabilite_a_EM')]
    public function Evolution_moi() : Response 
    {
        return $this->render('comptabilite_a/inventaire.html.twig',[

        ]);
    }

    #[Route('/comptabilite/a/Evolution/Hebdomadaire', name: 'app_comptabilite_a_week')]
    public function Evolution_week() : Response 
    {
        return $this->render('comptabilite_a/inventaire_week.html.twig',[

        ]);
    }

    #[Route('/comptabilite/a/Evolution/Marge/baneficier', name: 'app_comptabilite_a_Mage_beneficier')]
    public function Marge_beneficiere() : Response 
    {
        return $this->render('comptabilite_a/marge_beneficier.html.twig',[

        ]);
    }

    #[Route('/comptabilite/a/Evolution/Chiffre/affaire', name: 'app_comptabilite_a_Chiffre_affaire')]
    public function Chiffre_affaire() : Response 
    {
        return $this->render('comptabilite_a/Chiffre_affaire.html.twig',[

        ]);
    }

    #[Route('/comptabilite/a/Evolution/Produit/stocker', name: 'app_comptabilite_a_produit_stoker')]
    public function produit_stoker() : Response 
    {
        return $this->render('comptabilite_a/produit_stoker.html.twig',[

        ]);
    }
}
