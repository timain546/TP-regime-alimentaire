<?php
namespace App\Models;
class Combinaison{
    public $regimes;
    public $activites;
    public function get_efficacite_combinaison($regimes, $activites, $poids_objectif){
        $poids_gain_regime = 0;
        $poids_gain_activite = 0;

        foreach($regimes as $regime){
            $poids_gain_regime += Regime::get_poids_gain_journalier($regime['id_regime']);
        }
        foreach($activites as $activite){
            $poids_gain_activite += Activite::get_variation_poids_journalier($activite['id_activite']);
        }
        $poids_gagnes_journalier = $poids_gain_activite + $poids_gain_regime;
        $nb_jours = 0;
        $poids_increment = 0;
        while($poids_increment < $poids_objectif){
            $nb_jours += 1;
            $poids_increment += $poids_gagnes_journalier;

            if($nb_jours > 100){
                break;
            }
        }
        return $nb_jours;
    }
    public function get_scores_combinaison($regimes, $activites, $poids_objectif, $w1, $w2, $w3){
        $efficacite = $this->get_efficacite_combinaison($regimes, $activites, $poids_objectif);
        $cout_total = Regime::get_cout_totals_regimes($regimes, $efficacite);
        $intensite_total = Activite::get_intensites_somme($activites);

        $score = $w1 * $efficacite + $w2 * $cout_total + $w3 * $intensite_total;
        return $score;
    }
    public function get_meilleure_combinaison($combinaisons, $poids_objectif, $w1, $w2, $w3){
        $meilleure_combinaison = null;
        $meilleur_score = -INF;

        foreach($combinaisons as $combinaison){
            $score = $this->get_scores_combinaison($combinaison['regimes'], $combinaison['activites'], $poids_objectif, $w1, $w2, $w3);
            if($score > $meilleur_score){
                $meilleur_score = $score;
                $meilleure_combinaison = $combinaison;
            }
        }
        return $meilleure_combinaison;
    }
    public function get_combinaisons_filtres($allregimes, $allactivites){
        $combinaisons_regimes = Regime::get_combinaisons_regimes_recursives($allregimes);
        $combinaisons_activites = Activite::get_combinaisons_activites_recursives($allactivites);
        $i = 0;
        $combinaisons = [];
        foreach($combinaisons_regimes as $combinaison_regime){
            foreach($combinaisons_activites as $combinaison_activite){
                    $combinaisons[] = [
                        'regimes' => $combinaison_regime,
                        'activites' => $combinaison_activite
                    ];
            }
        }
        return $combinaisons;
    }
}
?>