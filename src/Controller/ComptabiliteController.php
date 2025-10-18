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
    public function Evolution_moi() : Response 
    {
        return $this->render('comptabilite/inventaire.html.twig',[

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

        // Trouve le premier lundi du mois (ou le lundi de la semaine en cours si le 1er est un lundi)
        // 'first monday of' fonctionne bien, mais pour plus de précision en partant d'une date spécifique,
        // on peut chercher le lundi précédent ou le jour même s'il est lundi.

        // On commence par se positionner sur le premier lundi du mois ou *avant* si la semaine
        // a commencé le mois précédent. En PHP, 'monday this week' (ou simplement 'monday')
        // est le début de la semaine ISO (lundi).

        // 1. Positionnement sur le premier lundi à inclure.
        // On cherche le lundi le plus proche ou égal à la date de début du mois.

        // Détermine le jour de la semaine du 1er du mois (1=Lundi, 7=Dimanche)
        $jour_semaine_1er = (int) $date_courante->format('N');

        // Si le 1er n'est pas un lundi (N != 1), on va au lundi suivant (ou le lundi de la semaine en cours si c'est déjà après)
        if ($jour_semaine_1er != 1) {
            // Calcule combien de jours ajouter pour atteindre le premier lundi : 8 - jour_semaine_1er
            // Ex: Si c'est Mardi (2), il faut 6 jours. 8-2 = 6.
            // Ex: Si c'est Dimanche (7), il faut 1 jour. 8-7 = 1.
            $jours_a_ajouter = (8 - $jour_semaine_1er) % 7;
            
            // Si $jours_a_ajouter est 0, c'est déjà lundi, mais on a vérifié qu'il n'est pas 1.
            // Si $jour_semaine_1er est 1 (lundi), la condition est false.
            // Si le 1er est un jour > 1 (Mardi à Dimanche), on avance au Lundi suivant.
            $date_courante->modify("+$jours_a_ajouter days");
        }

        // $date_courante est maintenant sur le premier lundi du mois (ou au plus tard le 7e jour).
        // Cependant, la norme ISO dit que la semaine commence le lundi.
        // Si le 1er du mois est un jeudi, la semaine 1 commence le lundi précédent.
        // L'approche la plus simple est d'utiliser le constructeur de `DateTime` avec des formats relatifs:

        $date_courante = new DateTime($annee_mois . '-01'); // Recommence au 1er jour du mois
        $date_courante->modify('last Monday'); // Va au dernier lundi avant ou le jour même si c'est lundi

        // Si 'last Monday' donne une date du mois précédent, on avance d'une semaine.
        // Ce cas se produit si le 1er est Lundi (le "dernier lundi" est le 1er), mais aussi
        // si le 1er est Mardi, Mercredi, Jeudi, Vendredi, Samedi, Dimanche (le "dernier lundi" est dans le mois précédent).
        // On doit toujours **inclure** la semaine où le 1er du mois tombe.
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
