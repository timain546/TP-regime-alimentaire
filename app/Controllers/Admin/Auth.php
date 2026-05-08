<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function login()
    {
        return view('admin/login');
    }

    public function attemptLogin()
    {
        $email = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');
        $user = db_connect()->table('Client')->where('email', $email)->where('role', 'admin')->get()->getRowArray();

        $validPassword = $user && (password_verify($password, $user['mot_de_passe']) || $password === $user['mot_de_passe']);
        if (! $validPassword) {
            return redirect()->back()->withInput()->with('error', 'Email ou mot de passe admin incorrect.');
        }

        session()->set([
            'admin_logged_in' => true,
            'admin_id' => $user['id_client'],
            'admin_name' => $user['nom'],
        ]);

        return redirect()->to('/admin/dashboard');
    }

    public function logout()
    {
        session()->remove(['admin_logged_in', 'admin_id', 'admin_name']);

        return redirect()->to('/admin/login')->with('success', 'Session admin fermee.');
    }
}
