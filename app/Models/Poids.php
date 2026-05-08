<?php
class Poids{
    public static function get_poids_objectif(float $poids_actuel, float $poids_desire){
        return $poids_desire - $poids_actuel;
    }
    public static function get_poids_imc_ideal(float $poids_actuel, float $taille){
        $imc_ideal = 22.5;
        $poids_ideal = $imc_ideal * ($taille * $taille);
        return $poids_ideal - $poids_actuel;
    }
}
?>