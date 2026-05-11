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

    private function yieldRegimeActiviteCombos(
        array $regimes,
        array $activites,
        int $minRegimes,
        int $maxRegimes,
        int $minActivites,
        int $maxActivites
    ): \Generator
    {
        for ($kr = $minRegimes; $kr <= min($maxRegimes, count($regimes)); $kr++) {
            foreach ($this->yieldCombinations($regimes, $kr) as $combReg) {
                for ($ka = $minActivites; $ka <= min($maxActivites, count($activites)); $ka++) {
                    foreach ($this->yieldCombinations($activites, $ka) as $combAct) {
                        yield [$combReg, $combAct];
                    }
                }
            }
        }
    }

    private function get_metrics_combinaison(array $regimes, array $activites, $poids_objectif): array
    {
        $regimeModel = new Regime();
        $activiteModel = new Activite();

        $jours = (float)$this->get_efficacite_combinaison($regimes, $activites, $poids_objectif);
        $cout = (float)$regimeModel->get_cout_totals_regimes($regimes, $jours);
        $intensite = (float)$activiteModel->get_intensites_somme($activites);

        return [$jours, $cout, $intensite];
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
        $delta_par_jour = (float)($poids_gain_activite + $poids_gain_regime);
        $poids_objectif = (float)$poids_objectif;

        if (abs($poids_objectif) <= 0.000001) {
            return 0;
        }

        if ($poids_objectif > 0 && $delta_par_jour <= 0.000001) {
            return 365;
        }
        if ($poids_objectif < 0 && $delta_par_jour >= -0.000001) {
            return 365;
        }

        $jours = (int)ceil(abs($poids_objectif) / max(abs($delta_par_jour), 0.000001));
        if ($jours < 1) {
            $jours = 1;
        }
        if ($jours > 365) {
            $jours = 365;
        }
        return $jours;
    }
    public function get_scores_combinaison($regimes, $activites, $poids_objectif, $w1, $w2, $w3){
        [$jours, $cout, $intensite] = $this->get_metrics_combinaison($regimes, $activites, $poids_objectif);
        return ($w1 * $jours) + ($w2 * $cout) + ($w3 * $intensite);
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

    public function get_meilleure_combinaison_robuste(
        array $regimes,
        array $activites,
        $poids_objectif,
        $w1,
        $w2,
        $w3,
        int $minRegimes = 1,
        int $minActivites = 0,
        int $maxRegimes = 3,
        int $maxActivites = 2,
        int $maxCandidatsRegimes = 15,
        int $maxCandidatsActivites = 15
    )
    {
        if (count($regimes) > $maxCandidatsRegimes) {
            $regimes = array_slice($regimes, 0, $maxCandidatsRegimes);
        }
        if (count($activites) > $maxCandidatsActivites) {
            $activites = array_slice($activites, 0, $maxCandidatsActivites);
        }

        $best = null;
        $bestScore = INF;

        $minRegimes = max(0, $minRegimes);
        $minActivites = max(0, $minActivites);

        $minJ = null; $maxJ = null;
        $minC = null; $maxC = null;
        $minI = null; $maxI = null;

        foreach ($this->yieldRegimeActiviteCombos($regimes, $activites, $minRegimes, $maxRegimes, $minActivites, $maxActivites) as $pair) {
            [$combReg, $combAct] = $pair;
            [$jours, $cout, $intensite] = $this->get_metrics_combinaison($combReg, $combAct, $poids_objectif);

            $minJ = ($minJ === null) ? $jours : min($minJ, $jours);
            $maxJ = ($maxJ === null) ? $jours : max($maxJ, $jours);
            $minC = ($minC === null) ? $cout : min($minC, $cout);
            $maxC = ($maxC === null) ? $cout : max($maxC, $cout);
            $minI = ($minI === null) ? $intensite : min($minI, $intensite);
            $maxI = ($maxI === null) ? $intensite : max($maxI, $intensite);
        }

        if ($minJ === null) {
            return null;
        }

        $rangeJ = max(1e-9, (float)($maxJ - $minJ));
        $rangeC = max(1e-9, (float)($maxC - $minC));
        $rangeI = max(1e-9, (float)($maxI - $minI));

        foreach ($this->yieldRegimeActiviteCombos($regimes, $activites, $minRegimes, $maxRegimes, $minActivites, $maxActivites) as $pair) {
            [$combReg, $combAct] = $pair;
            [$jours, $cout, $intensite] = $this->get_metrics_combinaison($combReg, $combAct, $poids_objectif);

            $nJ = ((float)$jours - (float)$minJ) / $rangeJ;
            $nC = ((float)$cout - (float)$minC) / $rangeC;
            $nI = ((float)$intensite - (float)$minI) / $rangeI;

            $score = ((float)$w1 * $nJ) + ((float)$w2 * $nC) + ((float)$w3 * $nI);
            if ($score < $bestScore) {
                $bestScore = $score;
                $best = [
                    'regimes' => $combReg,
                    'activites' => $combAct,
                ];
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