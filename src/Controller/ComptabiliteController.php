<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\TempAgence;
use App\Entity\Vente;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function Evolution_moi(EntityManagerInterface $em,Request $request) : Response 
    {
        $tab = [
            "montantN" => 0,
            "montantN1" => 0,
            "ClientN" => 0,
            "ClientN1" => 0,
            "Nbuclient" => 0,
            "Nbaclient" => 0,
            "Moclient" => 0,
            "MoAclient" => 0
        ];

        $date = $request->get("nombre");
        $mois_num = date('n', strtotime($date));

        $vente = $em->getRepository(Vente::class)->findVenteInventaire($mois_num);
        $vente = $vente[0];
        $tab["montantN"] = $vente[1];
        $tab["ClientN"] = $vente[2];

        $mois_num = $mois_num + 1;

        $vente = $em->getRepository(Vente::class)->findVenteInventaire($mois_num);
        $vente = $vente[0];
        $tab["montantN1"] = $vente[1];
        $tab["ClientN1"] = $vente[2];
        
        $client = $em->getRepository(Vente::class)->findByMontantByClientByMonth($date);
        
        dd($client);
        return $this->render('comptabilite/inventaire.html.twig',[
            'montantN' => $tab["montantN"],
            'montantN1' => $tab["montantN1"],
            'ClientN' => $tab["ClientN"],
            'ClientN1' => $tab["ClientN1"],
            'Nbuclient' => $tab["Nbuclient"],
            'Nbaclient' => $tab["Nbaclient"],
            'Moclient' => $tab["Moclient"],
            'MoAclient' => $tab["MoAclient"],
        ]);
    }

    #[Route('/comptabilite/Evolution/Hebdomadaire', name: 'app_comptabilite_week')]
    public function Evolution_week(EntityManagerInterface $em,Request $request) : Response 
    {
        $dateCible = $request->get('nombre');
        $resultat = $this->getDatesSemaineByDateMoi($dateCible);
        $vente = [];

        foreach ($resultat as $key => $value) {
            $dateCible = $value;
            $dateCible = new DateTime($dateCible);
            $temp = [];
            
            for ($i=1; $i <=7 ; $i++) { 
                array_push(
                    $temp,
                    [ 
                        $em->getRepository(Vente::class)->findVentesByWeek($dateCible->format("Y-m-d")), 
                        $dateCible->format("Y-m-d")
                    ]
                );
                $dateCible = $dateCible->modify('+1 day');
            }
            array_push($vente,$temp);
        }
        
        return $this->render('comptabilite/inventaire_week.html.twig',[
            'ventes' => $vente,
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

    public function getDatesSemaineByDate(string $dateCible): array
    {
        // 1. Créer un objet DateTime à partir de la date fournie
        $cible = new \DateTime($dateCible);

        // 2. Déterminer le lundi de la semaine
        // Le format 'N' retourne le jour de la semaine (1 pour lundi, 7 pour dimanche).
        // On retire le nombre de jours nécessaires pour revenir au lundi (jour 1).
        $decalageLundi = $cible->format('N') - 1;
        
        // Cloner la date cible pour ne pas modifier l'objet original
        $lundi = clone $cible;
        
        // Soustraire le décalage pour arriver au lundi (ex: mercredi (3) -> on soustrait 2 jours)
        $lundi->modify("-{$decalageLundi} days");

        
        // 3. Déterminer le dimanche de la semaine
        // Le format 'N' retourne le jour de la semaine. On ajoute le nombre de jours jusqu'à dimanche (jour 7).
        $decalageDimanche = 7 - $cible->format('N');
        
        // Cloner la date cible pour travailler sur une base propre
        $dimanche = clone $cible;
        
        // Ajouter le décalage pour arriver au dimanche (ex: mercredi (3) -> on ajoute 4 jours)
        $dimanche->modify("+{$decalageDimanche} days");

        
        // 4. Retourner les résultats
        return [
            'debut_semaine' => $lundi->format('Y-m-d'), // Lundi
            'fin_semaine' => $dimanche->format('Y-m-d')  // Dimanche
        ];
    }

    public function getDatesSemaineByDateMoi($date_depart) : array 
    {

        // Extrait l'année et le mois de la date de départ
        $annee_mois = date('Y-m', strtotime($date_depart));

        // Crée un objet DateTime pour le premier jour du mois
        $premier_jour_du_mois = new DateTime("first day of $annee_mois");

        // Tableau pour stocker les dates de début de semaine (les lundis)
        $dates_debut_semaine = [];

        // Clone la date du premier jour du mois pour la manipulation
        $date_courante = clone $premier_jour_du_mois;

        $jour_semaine_1er = (int) $date_courante->format('N');

        // Si le 1er n'est pas un lundi (N != 1), on va au lundi suivant (ou le lundi de la semaine en cours si c'est déjà après)
        if ($jour_semaine_1er != 1) {
            
            $jours_a_ajouter = (8 - $jour_semaine_1er) % 7;
            
            
            $date_courante->modify("+$jours_a_ajouter days");
        }

        $date_courante = new DateTime($annee_mois . '-01'); // Recommence au 1er jour du mois
        $date_courante->modify('last Monday'); // Va au dernier lundi avant ou le jour même si c'est lundi

        if ($date_courante->format('Y-m') != $annee_mois) {
            // Si on est tombé sur le mois précédent, on avance d'une semaine
            $date_courante->modify('+7 days');
        }

        // Maintenant, on boucle tant qu'on est dans le même mois
        while ($date_courante->format('Y-m') == $annee_mois) {
            $dates_debut_semaine[] = $date_courante->format('Y-m-d');
            
            // Passe au lundi suivant (une semaine plus tard)
            $date_courante->modify('+1 week');
        }

        return $dates_debut_semaine;

    }
}
