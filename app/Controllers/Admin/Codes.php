<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Codes extends BaseController
{
    public function index()
    {
        $db = db_connect();

        return view('admin/codes/index', [
            'title' => 'Codes wallet',
            'codes' => $db->query("
                SELECT c.*, u.nom AS user_nom
                FROM CodeRecharge c
                LEFT JOIN Client u ON u.id_client = c.id_client
                ORDER BY c.id_code_recharge DESC
            ")->getResultArray(),
            'history' => $db->query("
                SELECT c.date_utilisation AS used_at, c.valeur AS montant, c.code, u.nom AS user_nom
                FROM CodeRecharge c
                JOIN Client u ON u.id_client = c.id_client
                WHERE c.is_utilise = 1
                ORDER BY c.date_utilisation DESC
            ")->getResultArray(),
        ]);
    }

    public function generate()
    {
        $amount = (float) $this->request->getPost('montant');
        $quantity = max(1, (int) $this->request->getPost('quantite'));
        $db = db_connect();

        for ($i = 0; $i < $quantity; $i++) {
            $db->table('CodeRecharge')->insert([
                'code' => 'REGIM-' . strtoupper(bin2hex(random_bytes(4))),
                'valeur' => $amount,
                'is_valide' => 1,
            ]);
        }

        return redirect()->to('/admin/codes')->with('success', 'Codes generes.');
    }

    public function validateCode(int $id)
    {
        $db = db_connect();
        $code = $db->table('CodeRecharge')->where('id_code_recharge', $id)->get()->getRowArray();
        if (! $code) {
            return redirect()->to('/admin/codes')->with('error', 'Code introuvable.');
        }

        $db->table('CodeRecharge')->where('id_code_recharge', $id)->update(['is_valide' => (int) ! $code['is_valide']]);

        return redirect()->to('/admin/codes')->with('success', 'Statut du code modifie.');
    }
}
