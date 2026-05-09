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

    public function inscriptionSante() {
        return view('frontoffice/sante');
    }

    public function dashboard() {
        if(!session()->get('is_connected')) {
            return redirect()->to(base_url('auth/login'));
        }

        $client = session()->get('client');
        $sante = $this->santeModel->where('id_client', $client['id_client'])
                                  ->orderBy('date', 'DESC')
                                  ->first();

        $imc_actuel = $sante['poids'] / ($sante['taille'] / 100 * $sante['taille'] / 100);

        return view('frontoffice/dashboard', [
            'poids' => $sante['poids'],
            'taille' => $sante['taille'],
            'imc_actuel' => $imc_actuel
        ]);
    }

    public function profil() {
        if(!session()->get('is_connected')) {
            return redirect()->to(base_url('auth/login'));
        }

        $client = session()->get('client');
        $sante = $this->santeModel->where('id_client', $client['id_client'])
                                  ->orderBy('date', 'ASC')
                                  ->findAll();
        $len = count($sante);
        $sante_actuel = $sante[$len-1];

        return view('frontoffice/profil', [
            'client' => $client,
            'sante' => $sante,
            'taille' => $sante_actuel['taille'],
            'poids' => $sante_actuel['poids']
        ]);
    }

    public function logout() {
        session()->destroy();
        return redirect()->to(base_url("auth/login"));
    }

    public function attemptLogin() {
        $json = $this->request->getJSON();
        $email = trim((string)($json->email ?? ''));
        $mdp = (string)($json->mdp ?? '');

        $client = $this->clientModel->where('email', $email)
                                    ->where('role', 'client')
                                    ->first();

        $validPassword = $client && (password_verify($mdp, $client['mot_de_passe']) || $mdp === $client['mot_de_passe']);
        if (!$validPassword) {
            return $this->response->setJSON([
                "succes" => false,
                "message" => "Email ou mot de passe incorrect"
            ]);
        }

        session()->set([
            'is_connected' => true,
            'client' => $client
        ]);

        return $this->response->setJSON([
            "succes" => true,
            "redirect" => base_url("dashboard")
        ]);
    }

    public function creerClient() {
        $json = $this->request->getJSON();

        $rules = [
            'email' => 'required|valid_email|is_unique[Client.email]',
            'nom'   => 'required|min_length[2]',
            'mdp'   => 'required|min_length[6]',
            'genre' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                "succes" => false, 
                "errors" => $this->validator->getErrors()
            ]);
        }

        $data = [
            "nom"          => trim((string)$json->nom),
            "email"        => trim((string)$json->email),
            "genre"        => (string)$json->genre,
            "mot_de_passe" => password_hash((string)$json->mdp, PASSWORD_DEFAULT),
            "role"         => "client"
        ];

        $this->clientModel->insert($data);
        $data['id_client'] = $this->clientModel->getInsertID();

        session()->set([
            'is_connected' => true,
            'client' => $data
        ]);

        return $this->response->setJSON([
            "succes" => true,
            "redirect" => base_url("sante/form/")
        ]);
    }

    public function creerSante() {
        if(!session()->get('is_connected')) {
            return $this->response->setStatusCode(403)->setJSON(["message" => "Accès refusé"]);
        }

        $json = $this->request->getJSON();
        $userSession = session()->get('client');

        $sante = [
            "id_client" => $userSession['id_client'],
            "taille"    => (float)($json->taille ?? 0.0),
            "poids"     => (float)($json->poids ?? 0.0)
        ];

        $this->santeModel->insert($sante);

        return $this->response->setJSON([
            "succes" => true,
            "redirect" => base_url("dashboard")
        ]);
    }

    public function editSante() {
        $userSession = session()->get('client');
        $data = [
            "id_client" => $userSession['id_client'],
            "taille" => (float) ($this->request->getPost('taille') ?? 0.0),
            "poids" => (float) ($this->request->getPost('poids') ?? 0.0)
        ];

        $this->santeModel->insert($data);
        return redirect()->to(base_url('profil'));
    }
}