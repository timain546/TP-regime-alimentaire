<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Regimes extends BaseController
{
    public function index()
    {
        return view('admin/regimes/index', [
            'title' => 'Regimes',
            'regimes' => $this->regimeQuery()->orderBy('rm.id_regime', 'DESC')->get()->getResultArray(),
        ]);
    }

    public function create()
    {
        return view('admin/regimes/form', ['title' => 'Ajouter un regime', 'regime' => null]);
    }

    public function store()
    {
        $data = $this->payload();
        if ($error = $this->compositionError($data)) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $db = db_connect();
        $db->transStart();
        $db->table('RegimeMere')->insert([
            'libelle' => $data['nom'],
            'description' => $data['description'],
            'objectif' => $data['objectif'],
        ]);
        $id = (int) $db->insertID();
        $this->saveRegimeDetails($id, $data);
        $db->transComplete();

        return redirect()->to('/admin/regimes')->with('success', 'Regime ajoute.');
    }

    public function edit(int $id)
    {
        $regime = $this->findRegime($id);
        if (! $regime) {
            return redirect()->to('/admin/regimes')->with('error', 'Regime introuvable.');
        }

        return view('admin/regimes/form', ['title' => 'Modifier un regime', 'regime' => $regime]);
    }

    public function update(int $id)
    {
        $data = $this->payload();
        if ($error = $this->compositionError($data)) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $db = db_connect();
        $db->transStart();
        $db->table('RegimeMere')->where('id_regime', $id)->update([
            'libelle' => $data['nom'],
            'description' => $data['description'],
            'objectif' => $data['objectif'],
        ]);
        $db->table('DureeRegime')->where('id_regime', $id)->delete();
        $db->table('PrixRegime')->where('id_regime', $id)->delete();
        $db->table('RegimeFille')->where('id_regime', $id)->delete();
        $this->saveRegimeDetails($id, $data);
        $db->transComplete();

        return redirect()->to('/admin/regimes')->with('success', 'Regime modifie.');
    }

    public function delete(int $id)
    {
        db_connect()->table('RegimeMere')->where('id_regime', $id)->delete();

        return redirect()->to('/admin/regimes')->with('success', 'Regime supprime.');
    }

    private function regimeQuery()
    {
        return db_connect()->table('RegimeMere rm')
            ->select("
                rm.id_regime AS id,
                rm.libelle AS nom,
                rm.description,
                rm.objectif,
                COALESCE(dr.variation_poids, 0) AS variation_poids,
                COALESCE(dr.nb_jours, 0) AS duree_jours,
                COALESCE(pr.prix, 0) AS prix,
                COALESCE(SUM(CASE WHEN a.nom = 'Viande' THEN rf.pourcentage ELSE 0 END), 0) AS pourcentage_viande,
                COALESCE(SUM(CASE WHEN a.nom = 'Poisson' THEN rf.pourcentage ELSE 0 END), 0) AS pourcentage_poisson,
                COALESCE(SUM(CASE WHEN a.nom = 'Volaille' THEN rf.pourcentage ELSE 0 END), 0) AS pourcentage_volaille
            ")
            ->join('DureeRegime dr', 'dr.id_regime = rm.id_regime', 'left')
            ->join('PrixRegime pr', 'pr.id_regime = rm.id_regime AND pr.duree_jours = dr.nb_jours', 'left')
            ->join('RegimeFille rf', 'rf.id_regime = rm.id_regime', 'left')
            ->join('Aliment a', 'a.id_aliment = rf.id_aliment', 'left')
            ->groupBy([
                'rm.id_regime',
                'rm.libelle',
                'rm.description',
                'rm.objectif',
                'dr.variation_poids',
                'dr.nb_jours',
                'pr.prix',
            ]);
    }

    private function findRegime(int $id): ?array
    {
        return $this->regimeQuery()->where('rm.id_regime', $id)->get()->getRowArray() ?: null;
    }

    private function saveRegimeDetails(int $id, array $data): void
    {
        $db = db_connect();
        $db->table('DureeRegime')->insert([
            'id_regime' => $id,
            'variation_poids' => $data['variation_poids'],
            'nb_jours' => $data['duree_jours'],
        ]);
        $db->table('PrixRegime')->insert([
            'id_regime' => $id,
            'duree_jours' => $data['duree_jours'],
            'prix' => $data['prix'],
        ]);

        foreach (['Viande' => 'pourcentage_viande', 'Poisson' => 'pourcentage_poisson', 'Volaille' => 'pourcentage_volaille'] as $aliment => $field) {
            $alimentRow = $db->table('Aliment')->where('nom', $aliment)->get()->getRowArray();
            if (! $alimentRow) {
                $db->table('Aliment')->insert(['nom' => $aliment]);
                $alimentRow = ['id_aliment' => $db->insertID()];
            }

            $db->table('RegimeFille')->insert([
                'id_regime' => $id,
                'id_aliment' => $alimentRow['id_aliment'],
                'pourcentage' => $data[$field],
            ]);
        }
    }

    private function payload(): array
    {
        return [
            'nom' => trim((string) $this->request->getPost('nom')),
            'description' => trim((string) $this->request->getPost('description')),
            'objectif' => (string) $this->request->getPost('objectif'),
            'variation_poids' => (float) $this->request->getPost('variation_poids'),
            'duree_jours' => (int) $this->request->getPost('duree_jours'),
            'prix' => (float) $this->request->getPost('prix'),
            'pourcentage_viande' => (float) $this->request->getPost('pourcentage_viande'),
            'pourcentage_poisson' => (float) $this->request->getPost('pourcentage_poisson'),
            'pourcentage_volaille' => (float) $this->request->getPost('pourcentage_volaille'),
        ];
    }

    private function compositionError(array $data): ?string
    {
        if ($data['nom'] === '' || $data['duree_jours'] <= 0 || $data['prix'] <= 0) {
            return 'Nom, duree et prix sont obligatoires.';
        }

        $total = $data['pourcentage_viande'] + $data['pourcentage_poisson'] + $data['pourcentage_volaille'];
        if (round($total, 2) !== 100.0) {
            return 'La somme viande + poisson + volaille doit etre egale a 100%.';
        }

        return null;
    }
}
