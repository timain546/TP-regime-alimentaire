<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Settings extends BaseController
{
    public function index()
    {
        return view('admin/settings/index', [
            'title' => 'Parametres',
            'settings' => db_connect()->table('ParametreGlobal')->orderBy('cle')->get()->getResultArray(),
        ]);
    }

    public function update()
    {
        $db = db_connect();
        foreach ((array) $this->request->getPost('settings') as $key => $value) {
            $db->table('ParametreGlobal')->where('cle', $key)->update(['valeur' => trim((string) $value)]);
        }

        return redirect()->to('/admin/settings')->with('success', 'Parametres mis a jour.');
    }
}
