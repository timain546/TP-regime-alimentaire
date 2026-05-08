<?php
namespace App\Models;
class Combinaison{
    public function get_efficacite_combinaison($regimes, $activites, $poids_objectif){
        $poids_gain_regime = 0;
        $poids_gain_activite = 0;

        foreach($regimes as $regime){
            $poids_gain_regime += Regime.get_poids_gain_journalier($regime['id_regime']);
        }
        foreach($activites as $activite){
            $poids_gain_activite += Activite.get_variation_poids_journalier($activite['id_activite']);
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
}
?>