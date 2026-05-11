<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = db_connect();

        return view('admin/dashboard', [
            'title' => 'Dashboard',
            'weeklyUsers' => $db->query("
                SELECT YEARWEEK(created_at, 1) AS semaine, COUNT(*) AS total
                FROM Client
                WHERE role = 'client'
                GROUP BY YEARWEEK(created_at, 1)
                ORDER BY semaine
            ")->getResultArray(),
            'objectives' => $db->query("
                SELECT COALESCE(objectif, 'non_defini') AS objectif, COUNT(*) AS total
                FROM Client
                WHERE role = 'client'
                GROUP BY COALESCE(objectif, 'non_defini')
            ")->getResultArray(),
            'topRegimes' => $db->query("
                SELECT
                    r.libelle AS nom,
                    COUNT(DISTINCT pe.id_programme) AS souscriptions,
                    COALESCE(SUM(
                        COALESCE(pe.cout_par_jour, 0) * (GREATEST(COALESCE(pe.jour_fin, 0) - COALESCE(pe.jour_debut, 0) + 1, 0))
                    ), 0) AS total
                FROM RegimeMere r
                LEFT JOIN ProgrammeElement pe
                    ON pe.id_regime = r.id_regime
                    AND pe.type_element = 'regime'
                GROUP BY r.id_regime, r.libelle
                ORDER BY souscriptions DESC, r.libelle ASC
            ")->getResultArray(),
            'wallet' => $db->query("
                SELECT
                    (SELECT COALESCE(SUM(CASE WHEN type = 'credit' THEN montant ELSE -montant END), 0) FROM Transactions) AS total_wallet,
                    (SELECT COUNT(*) FROM CodeRecharge WHERE is_utilise = 1) AS codes_valides,
                    (SELECT COALESCE(SUM(montant), 0) FROM Transactions WHERE type = 'credit') AS total_recharges
                FROM Client
                WHERE role = 'client'
            ")->getRowArray(),
        ]);
    }
}
