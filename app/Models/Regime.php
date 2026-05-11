<?php
namespace App\Models;

use CodeIgniter\Model;
class Regime extends Model
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

    public function get_prix_journalier_min(int $id): float
    {
        static $cache = [];
        if (array_key_exists($id, $cache)) {
            return (float)$cache[$id];
        }

        $rows = $this->db->table('PrixRegime')
            ->where('id_regime', $id)
            ->get()
            ->getResultArray();

        $min = null;
        foreach ($rows as $row) {
            $duree = (float)($row['duree_jours'] ?? 0);
            $prix = (float)($row['prix'] ?? 0);
            if ($duree <= 0) {
                continue;
            }
            $parJour = $prix / $duree;
            if ($min === null || $parJour < $min) {
                $min = $parJour;
            }
        }

        $cache[$id] = $min ?? 0.0;
        return (float)$cache[$id];
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
        $prix_total = 0.0;
        $nb_jours = (float)$nb_jours;
        if ($nb_jours <= 0) {
            return 0.0;
        }

        foreach ($regimes as $regime) {
            $id = (int)($regime['id_regime'] ?? $regime['id'] ?? 0);
            if ($id <= 0) {
                continue;
            }
            $prix_total += $this->get_prix_journalier_min($id) * $nb_jours;
        }

        return $prix_total;
    }
    public function getAll(){
        return $this->db->table($this->table)->get()->getResultArray();
    }
    public static function get_poids_gain_journalier($id_regime){
        static $cache = [];
        $id_regime = (int)$id_regime;
        if ($id_regime <= 0) {
            return 0.0;
        }
        if (array_key_exists($id_regime, $cache)) {
            return (float)$cache[$id_regime];
        }
        $regimeModel = new Regime();
        $row = $regimeModel->get_duree_regime($id_regime);
        $variation_poids = (float)($row['variation_poids'] ?? 0);
        $nb_jours = (float)($row['nb_jours'] ?? 0);

        $cache[$id_regime] = ($nb_jours > 0) ? ($variation_poids / $nb_jours) : 0.0;
        return (float)$cache[$id_regime];
    }
    public static function get_combinaisons_regimes_recursives($regimes, $index = 0){
        if($index >= count($regimes)){
            return [[]];
        }
        $combinaisons = [];
        $sous_combinaisons = Regime::get_combinaisons_regimes_recursives($regimes, $index + 1);
        foreach($sous_combinaisons as $sous_combinaison){
            $combinaisons[] = array_merge([$regimes[$index]], $sous_combinaison);
            if(count($sous_combinaison) <= 3 && count($sous_combinaison) >= 0){
            $combinaisons[] = $sous_combinaison;
            }
        }
        return $combinaisons;
    }
    public function cout_plus_gold($regimes, $nb_jours, $remise){
        $cout_total = $this->get_cout_totals_regimes($regimes, $nb_jours);
        $cout_reduit = $cout_total * (1 - $remise);
        return $cout_reduit;
    }
}
?>