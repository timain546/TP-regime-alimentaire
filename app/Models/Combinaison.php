<?php
namespace App\Models;
class Combinaison{
    public $regimes;
    public $activites;

    private function yieldCombinations(array $items, int $k, int $start = 0, array $prefix = []): \Generator
    {
        if ($k === 0) {
            yield $prefix;
            return;
        }

        $n = count($items);
        for ($i = $start; $i <= $n - $k; $i++) {
            $next = $prefix;
            $next[] = $items[$i];
            yield from $this->yieldCombinations($items, $k - 1, $i + 1, $next);
        }
    }

    public function get_efficacite_combinaison($regimes, $activites, $poids_objectif){
        $poids_gain_regime = 0;
        $poids_gain_activite = 0;
        $activiteModel = new Activite();

        foreach($regimes as $regime){
            $poids_gain_regime += Regime::get_poids_gain_journalier($regime['id_regime']);
        }
        foreach($activites as $activite){
            $poids_gain_activite += $activiteModel->get_variation_poids_journalier($activite['id_activite']);
        }
        $poids_gagnes_journalier = $poids_gain_activite + $poids_gain_regime;
        $progression_par_jour = abs((float)$poids_gagnes_journalier);
        $poids_objectif = abs((float)$poids_objectif);

        if ($poids_objectif <= 0) {
            return 0;
        }

        if ($progression_par_jour <= 0.000001) {
            // Impossible à atteindre avec cette combinaison (ou données incohérentes).
            return 365;
        }

        $nb_jours = 0;
        $poids_increment = 0;
        while($poids_increment < $poids_objectif){
            $nb_jours += 1;
            $poids_increment += $progression_par_jour;

            if($nb_jours > 365){
                break;
            }
        }
        return $nb_jours;
    }
    public function get_scores_combinaison($regimes, $activites, $poids_objectif, $w1, $w2, $w3){
        $regimeModel = new Regime();
        $activiteModel = new Activite();
        $efficacite = $this->get_efficacite_combinaison($regimes, $activites, $poids_objectif);
        $cout_total = $regimeModel->get_cout_totals_regimes($regimes, $efficacite);
        $intensite_total = $activiteModel->get_intensites_somme($activites);

        // Plus petit = mieux (moins de jours, moins cher, moins intense).
        return ($w1 * (float)$efficacite) + ($w2 * (float)$cout_total) + ($w3 * (float)$intensite_total);
    }
    public function get_meilleure_combinaison($combinaisons, $poids_objectif, $w1, $w2, $w3){
        $meilleure_combinaison = null;
        $meilleur_score = INF;

        foreach($combinaisons as $combinaison){
            $score = $this->get_scores_combinaison($combinaison['regimes'], $combinaison['activites'], $poids_objectif, $w1, $w2, $w3);
            if($score < $meilleur_score){
                $meilleur_score = $score;
                $meilleure_combinaison = $combinaison;
            }
        }
        return $meilleure_combinaison;
    }

    public function get_meilleure_combinaison_robuste(array $regimes, array $activites, $poids_objectif, $w1, $w2, $w3, int $maxRegimes = 3, int $maxActivites = 2, int $maxCandidatsRegimes = 15, int $maxCandidatsActivites = 15)
    {
        // Hard caps anti-explosion (surtout si la table grossit).
        if (count($regimes) > $maxCandidatsRegimes) {
            $regimes = array_slice($regimes, 0, $maxCandidatsRegimes);
        }
        if (count($activites) > $maxCandidatsActivites) {
            $activites = array_slice($activites, 0, $maxCandidatsActivites);
        }

        $best = null;
        $bestScore = INF;

        for ($kr = 0; $kr <= min($maxRegimes, count($regimes)); $kr++) {
            foreach ($this->yieldCombinations($regimes, $kr) as $combReg) {
                for ($ka = 0; $ka <= min($maxActivites, count($activites)); $ka++) {
                    foreach ($this->yieldCombinations($activites, $ka) as $combAct) {
                        $score = $this->get_scores_combinaison($combReg, $combAct, $poids_objectif, $w1, $w2, $w3);
                        if ($score < $bestScore) {
                            $bestScore = $score;
                            $best = [
                                'regimes' => $combReg,
                                'activites' => $combAct,
                            ];
                        }
                    }
                }
            }
        }

        return $best;
    }
    public function get_combinaisons_filtres($allregimes, $allactivites){
        $activiteModel = new Activite();
        $combinaisons_regimes = Regime::get_combinaisons_regimes_recursives($allregimes);
        $combinaisons_activites = $activiteModel->get_combinaisons_activites_recursives($allactivites);
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