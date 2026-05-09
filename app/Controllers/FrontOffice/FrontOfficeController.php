<?php

namespace App\Controllers\FrontOffice;

use App\Controllers\BaseController;

class FrontOfficeController extends BaseController {

    private $clientModel;
    private $santeModel;

    public function __construct() {
        $this->clientModel = model('ClientModel');
        $this->santeModel = model('SanteModel');
    }

    public function login() {
        return view('frontoffice/login');
    }

    public function inscription() {
        return view('frontoffice/inscription');
    }

    public function inscriptionSante($id) {
        if(!session()->get('is_connected')) {
            return view('frontoffice/login');
        }

        $userEnSession = session()->get('client');
        if($userEnSession['id_client'] != $id) {
            return view('frontoffice/login');
        }

        return view('frontoffice/sante.php', ['identifiant_client' => $id]);
    }

    public function attemptLogin() {
        sleep(2);

        $json = $this->request->getJSON();

        $email = trim((string) ($json->email ?? ''));
        $mdp = (string) ($json->mdp ?? '');
        $client = $this->clientModel->where('email', $email)
                                    ->where('role', 'client')
                                    ->first();
                            
        $validPassword = $client && (password_verify($mdp, $client['mot_de_passe']) || $mdp === $client['mot_de_passe']);
        if(!$validPassword) {
            $response = [
                "succes" => false,
                "message" => "email ou mot de passe incorrect"
            ];
            return $this->response->setJSON($response);
        }

        session()->set([
            'is_connected' => true,
            'client' => $client
        ]);

        $response = [
            "succes" => true,
            "redirect" => base_url("dashboard")
        ];

        return $this->response->setJSON($response);
    }

    public function creerClient() {
        $json = $this->request->getJSON();
        $client = [
            "nom" => trim((string)($json->nom ?? '')),
            "email" => trim((string)($json->email ?? '')),
            "genre" => (string) ($json->genre ?? ''),
            "mot_de_passe" => (string) ($json->mdp ?? '')
        ];

        $this->clientModel->insert($client);
        $nouvelId = $this->clientModel->getInsertID();

        $client['id_client'] = $nouvelId;

        session()->set([
            'is_connected' => true,
            'client' => $client
        ]);

        return $this->response->setJSON([
            "succes" => true,
            "id" => $nouvelId,
            "redirect" => base_url("sante/form")
        ]);
    }

    public function creerSante() {
        $json = $this->request->getJSON();
        $sante = [
            "id_client" => (int) $json->id_client,
            "taille" => (float) ($json->taille ?? 0.0),
            "poids" => (float) ($json->poids ?? 0.0)
        ];
        $this->santeModel->insert($sante);
        return $this->response->setJSON([
            "succes" => true,
            "redirect" => base_url("dashboard")
        ]);
    }

}