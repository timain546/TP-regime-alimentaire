<?php
class Client{
    public function get_poids_actuel(int $id_client){
        return $this->db->table('Sante')
            ->where('id_client', $id_client)
            ->orderBy('date', 'DESC')
            ->get()
            ->getRowArray()['poids'];
    }
    public function get_taille(int $id_client){
        $i = 0;
        return $this->db->table('Sante')
            ->where('id_client', $id_client)
            ->orderBy('date', 'DESC')
            ->get()
            ->getRowArray()['taille'];
    }
}
?>