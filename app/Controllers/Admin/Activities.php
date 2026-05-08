<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Activities extends BaseController
{
    public function index()
    {
        return view('admin/activities/index', [
            'title' => 'Activites sportives',
            'activities' => db_connect()->table('ActiviteSportive')->orderBy('id_activite', 'DESC')->get()->getResultArray(),
        ]);
    }

    public function create()
    {
        return view('admin/activities/form', ['title' => 'Ajouter une activite', 'activity' => null]);
    }

    public function store()
    {
        $db = db_connect();
        $data = $this->payload();
        $db->transStart();
        $db->table('ActiviteSportive')->insert($data['activite']);
        $db->table('DureeActivite')->insert([
            'id_activite' => $db->insertID(),
            'variation_poids' => $data['variation_poids'],
            'nb_jours' => $data['nb_jours'],
        ]);
        $db->transComplete();

        return redirect()->to('/admin/activities')->with('success', 'Activite ajoutee.');
    }

    public function edit(int $id)
    {
        $activity = $this->findActivity($id);
        if (! $activity) {
            return redirect()->to('/admin/activities')->with('error', 'Activite introuvable.');
        }

        return view('admin/activities/form', ['title' => 'Modifier une activite', 'activity' => $activity]);
    }

    public function update(int $id)
    {
        $db = db_connect();
        $data = $this->payload();
        $db->transStart();
        $db->table('ActiviteSportive')->where('id_activite', $id)->update($data['activite']);
        $db->table('DureeActivite')->where('id_activite', $id)->delete();
        $db->table('DureeActivite')->insert([
            'id_activite' => $id,
            'variation_poids' => $data['variation_poids'],
            'nb_jours' => $data['nb_jours'],
        ]);
        $db->transComplete();

        return redirect()->to('/admin/activities')->with('success', 'Activite modifiee.');
    }

    public function delete(int $id)
    {
        db_connect()->table('ActiviteSportive')->where('id_activite', $id)->delete();

        return redirect()->to('/admin/activities')->with('success', 'Activite supprimee.');
    }

    private function payload(): array
    {
        return [
            'activite' => [
                'libelle' => trim((string) $this->request->getPost('nom')),
                'description' => trim((string) $this->request->getPost('description')),
                'duree_minutes' => (int) $this->request->getPost('duree_minutes'),
                'objectif' => (string) $this->request->getPost('objectif'),
                'intensite' => (string) $this->request->getPost('intensite'),
            ],
            'variation_poids' => (float) $this->request->getPost('variation_poids'),
            'nb_jours' => (int) $this->request->getPost('nb_jours'),
        ];
    }

    private function findActivity(int $id): ?array
    {
        return db_connect()->table('ActiviteSportive a')
            ->select('a.*, da.variation_poids, da.nb_jours')
            ->join('DureeActivite da', 'da.id_activite = a.id_activite', 'left')
            ->where('a.id_activite', $id)
            ->get()
            ->getRowArray() ?: null;
    }
}
