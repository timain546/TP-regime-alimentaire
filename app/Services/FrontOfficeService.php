<?php

namespace App\Services;

class FrontOfficeService {

    private $transactionModel;
    private $clientModel;

    public function __construct() {
        $this->transactionModel = model('TransactionModel');
        $this->clientModel = model('ClientModel');
    }

    public function getSoldeClient($id_client) {
        $transactions = $this->transactionModel->where('id_client', $id_client)->findAll();
        $solde = 0;
        foreach($transactions as $transaction) {
            if($transaction['type'] == "debit") {
                $solde += (float)($transaction['montant'] ?? 0.0);
            } else {
                $solde -= (float)($transaction['montant'] ?? 0.0);
            }
        }
        return $solde > 0.0 ? $solde : 0.0;
    }

    public function procederAchatGold($id_client, $montant) {
        $db = $db = \Config\Database::connect();

        $db->transStart();
        $this->transactionModel->insert([
            "id_client" => $id_client,
            "montant" => $montant,
            "type" => "credit"
        ]);

        $this->clientModel->where('id_client', session()->get('client')['id_client'])
                          ->set('is_gold', 1,  false)
                          ->update();

        $db->transComplete();
        return $db->transStatus();
    }

}