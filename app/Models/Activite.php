<?
namespace App\Models;
class Activite{
    public $table = "ActiviteSportive";
    public $primaryKey = "id_activite";
    public $allowedFields = ['libelle', 'description', 'duree_minutes', 'objectif', 'intensite'];

    public function getAll(){
        return $this->db->table($this->table)->get()->getResultArray();
    }

    public function get_intensites_somme($activites){
        $somme = 0;

        foreach($activites as $activite){
            $intensite = $activite['intensite'];
            $somme += $intensite;
        }
        return $somme;
    }
    public function get_variation_poids_journalier($id_activite){
        $nb_jours = this->db->table('DureeActivite')
        ->where('id_activite', $id_activite)
        ->get()
        ->getRowArray()['nb_jours'];
        
        $variation_poids = this->db->table('DureeActivite')
        ->where('id_activite', $id_activite)
        ->get()
        ->getRowArray()['variation_poids'];
        
        return $variation_poids / $variation_poids;
    }
}
?>