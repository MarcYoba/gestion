<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\TempAgence;
use App\Entity\Vente;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComptabiliteController extends AbstractController
{
    #[Route('/comptabilite/journale', name: 'app_comptabilite_journale')]
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
           array_push($achat,$em->getRepository(Achat::class)->findByMontantMonth(date("Y"),$i,$id));
        }

        for ($i=1; $i <= 4; $i++) { 
            array_push($achatTrimestre,$em->getRepository(Achat::class)->findByMontantTrimestre($i,date("Y"),$id));
        }

        for ($i=1; $i <=2 ; $i++) { 
            array_push($achatTrimestre,$em->getRepository(Achat::class)->findByMontantSemestre($i,date("Y"),$id));
        }

        for ($i=1; $i <=4 ; $i++) { 
            array_push($venteTrimestre,$em->getRepository(Vente::class)->findByMontantTrimestre($i,date("Y"),$id));
        }

        for ($i=1; $i <=2 ; $i++) { 
            array_push($venteTrimestre,$em->getRepository(Vente::class)->findByMontantSemestre($i,date("Y"),$id));
        }
        
        for ($i=1; $i <=12 ; $i++) { 
            array_push($vente,$em->getRepository(Vente::class)->findByMontantMonth($i,date("Y"),$id));
        }
        return $this->render('comptabilite/journale.html.twig', [
            'achats' => $achat,
            'achatTrimestres' => $achatTrimestre,
            'venteTrimestres' => $venteTrimestre,
            'ventes' => $vente,
        ]);
    }

    #[Route('/comptabilite/Evolution/mensuel', name: 'app_comptabilite_EM')]
    public function Evolution_moi() : Response 
    {
        return $this->render('comptabilite/inventaire.html.twig',[

        ]);
    }

    #[Route('/comptabilite/Evolution/Hebdomadaire', name: 'app_comptabilite_week')]
    public function Evolution_week() : Response 
    {
        return $this->render('comptabilite/inventaire_week.html.twig',[

        ]);
    }

    #[Route('/comptabilite/Evolution/Marge/baneficier', name: 'app_comptabilite_Mage_beneficier')]
    public function Marge_beneficiere() : Response 
    {
        return $this->render('comptabilite/marge_beneficier.html.twig',[

        ]);
    }

    #[Route('/comptabilite/Evolution/Chiffre/affaire', name: 'app_comptabilite_Chiffre_affaire')]
    public function Chiffre_affaire() : Response 
    {
        return $this->render('comptabilite/Chiffre_affaire.html.twig',[

        ]);
    }

    #[Route('/comptabilite/Evolution/Produit/stocker', name: 'app_comptabilite_produit_stoker')]
    public function produit_stoker() : Response 
    {
        return $this->render('comptabilite/produit_stoker.html.twig',[

        ]);
    }
}
