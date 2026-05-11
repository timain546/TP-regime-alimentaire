<?php
namespace App\Models;
use CodeIgniter\Model;

class Activite extends Model{
    public $table = "ActiviteSportive";
    public $primaryKey = "id_activite";
    public $allowedFields = ['libelle', 'description', 'duree_minutes', 'objectif', 'intensite'];

    public function getAll(){
        return $this->db->table($this->table)->get()->getResultArray();
    }

    public function get_intensites_somme($activites){
        $somme = 0;

        foreach($activites as $activite){
            $intensite = $activite['intensite'] ?? 'moyenne';
            // Enum -> score numérique pour pouvoir sommer.
            $map = [
                'faible' => 1,
                'moyenne' => 2,
                'forte' => 3,
            ];
            $somme += $map[$intensite] ?? 2;
        }
        return $somme;
    }
    public function get_variation_poids_journalier($id_activite){
        static $cache = [];
        $id_activite = (int)$id_activite;
        if ($id_activite <= 0) {
            return 0.0;
        }
        if (array_key_exists($id_activite, $cache)) {
            return (float)$cache[$id_activite];
        }

        $row = $this->db->table('DureeActivite')
            ->where('id_activite', $id_activite)
            ->get()
            ->getRowArray();

        $nb_jours = (float)($row['nb_jours'] ?? 0);
        $variation_poids = (float)($row['variation_poids'] ?? 0);

        if ($nb_jours <= 0) {
            $cache[$id_activite] = 0.0;
            return 0.0;
        }

        $cache[$id_activite] = $variation_poids / $nb_jours;
        return (float)$cache[$id_activite];
    }
    public function get_combinaisons_activites_recursives($activites, $index = 0){
        if($index >= count($activites)){
            return [[]];
        }
        $combinaisons = [];
        $sous_combinaisons = $this->get_combinaisons_activites_recursives($activites, $index + 1);
        foreach($sous_combinaisons as $sous_combinaison){
            $combinaisons[] = array_merge([$activites[$index]], $sous_combinaison);
            
            if(count($sous_combinaison) <= 2){
            $combinaisons[] = $sous_combinaison;
            }
        }
        return $combinaisons;
    }
}
?>