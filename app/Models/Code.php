<?php
namespace App\Models;

use CodeIgniter\Model;

class Code extends Model
{
    protected $table = 'CodeRecharge';
    protected $primaryKey = 'id_code_recharge';
    protected $allowedFields = ['code', 'valeur', 'is_valide', 'is_utilise', 'id_client', 'date_utilisation'];

    public function code_exists(string $code): bool
    {
        return $this->builder()
            ->where('code', $code)
            ->get()
            ->getRowArray() !== null;
    }

    public function code_deja_utilise(string $code): bool
    {
        return $this->builder()
            ->where('code', $code)
            ->where('is_utilise', 1)
            ->get()
            ->getRowArray() !== null;
    }

    public function get_info_code(string $code): ?array
    {
        $row = $this->builder()
            ->where('code', $code)
            ->get()
            ->getRowArray();
        return is_array($row) ? $row : null;
    }
}
