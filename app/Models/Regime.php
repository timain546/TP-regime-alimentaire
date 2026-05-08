<?php
namespace App\Models;
class Regime extends BaseModel
{
    public $table = 'RegimeMere';
    public $primaryKey = 'id_regime';
    public $allowedFields = ['libelle', 'description', 'objectif'];

    public function get_details_regime(int $id)
    {
        return $this->db->table('RegimeFille')
            ->where('id_regime', $id)
            ->get()
            ->getResultArray();
    }
    public function get_prix_regime(int $id)
    {
        return $this->db->table('PrixRegime')
            ->where('id_regime', $id)
            ->get()
            ->getRowArray();
    }
    public function get_duree_regime(int $id)
    {
        return $this->db->table('DureeRegime')
            ->where('id_regime', $id)
            ->get()
            ->getRowArray();
    }
    public function get_cout_totals_regimes($regimes, $nb_jours)
    {
        $prix_total = 0;
        foreach($regimes as $regime){
            $prix = get_duree_regime($regime['id'])['prix'] / get_duree_regime($regime['id'])['nb_jours'] * $nb_jours;
            $prix_total += $prix;
        }
        return $prix_total;
    }
    public function getAll(){
        return $this->regimeQuery()->get()->getResultArray();
    }
    public static function get_poids_gain_journalier($id_regime){
        $variation_poids = get_duree_regime($id_regime)['variation_poids'];
        $nb_jours = get_duree_regime($id_regime)['nb_jours'];

        return $variation_poids / $nb_jours;
    }
    public static function get_combinaisons_regimes_recursives($regimes, $index = 0){
        if($index >= count($regimes)){
            return [[]];
        }
        $combinaisons = [];
        $sous_combinaisons = get_combinaisons_regimes_recursives($regimes, $index + 1);
        foreach($sous_combinaisons as $sous_combinaison){
            $combinaisons[] = array_merge([$regimes[$index]], $sous_combinaison);
            if(count($sous_combinaison) <= 3 && count($sous_combinaison) >= 0){
            $combinaisons[] = $sous_combinaison;
            }
        }
        return $combinaisons;
    }
}
?>