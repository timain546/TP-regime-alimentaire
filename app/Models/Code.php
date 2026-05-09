<?php
namespace app\Model;
class Code{
    public $table = "CodeRecharge";
    public $primaryKey = "id_code_recharge";

    public $allowedFields = ['valeur', 'is_valide', 'is_utilise', 'id_client', 'date_utilisation'];

    public function code_exists($code){
        return $this->db->table('CodeRecharge')
            ->where('code', $code)
            ->get()
            ->getRowArray() !== null;
    }
    public function code_deja_utilise($code){
        return $this->db->table('CodeRecharge')
            ->where('code', $code)
            ->where('is_utilise', 1)
            ->get()
            ->getRowArray() !== null;
    }
    public function get_info_code($code){
        return $this->db->table('CodeRecharge')
            ->where('code', $code)
            ->get()
            ->getRowArray();
    }
} 

?>