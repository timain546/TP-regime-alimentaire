<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suggestion Regime</title>
</head>
<body>
    <h1>Resultat de suggestion</h1>
    <div class="muted">Date: <?= date('d/m/Y H:i') ?></div>

    <h2>Profil client</h2>
    <table class="grid">
        <tr>
            <th>Nom</th>
            <td><?= esc($client['nom'] ?? 'N/A') ?></td>
            <th>Email</th>
            <td><?= esc($client['email'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Genre</th>
            <td><?= esc($client['genre'] ?? 'N/A') ?></td>
            <th>Gold</th>
            <td><?= !empty($gold_actif) ? 'Oui' : 'Non' ?></td>
        </tr>
        <tr>
            <th>Taille</th>
            <td><?= esc((string)($sante['taille'] ?? 'N/A')) ?> cm</td>
            <th>Poids</th>
            <td><?= esc((string)($sante['poids'] ?? 'N/A')) ?> kg</td>
        </tr>
    </table>

    <h2>Regimes suggeres</h2>
    <table class="grid">
        <thead>
            <tr>
                <th>Libelle</th>
                <th>Description</th>
                <th>Objectif</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($combinaison['regimes'])) : ?>
                <?php foreach ($combinaison['regimes'] as $regime) : ?>
                    <tr>
                        <td><?= esc($regime['libelle'] ?? 'N/A') ?></td>
                        <td><?= esc($regime['description'] ?? 'N/A') ?></td>
                        <td><?= esc($regime['objectif'] ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">Aucun regime retenu.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Activites recommandees</h2>
    <table class="grid">
        <thead>
            <tr>
                <th>Libelle</th>
                <th>Description</th>
                <th>Duree</th>
                <th>Intensite</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($combinaison['activites'])) : ?>
                <?php foreach ($combinaison['activites'] as $activite) : ?>
                    <tr>
                        <td><?= esc($activite['libelle'] ?? 'N/A') ?></td>
                        <td><?= esc($activite['description'] ?? 'N/A') ?></td>
                        <td><?= esc((string)($activite['duree_minutes'] ?? 'N/A')) ?> min</td>
                        <td><?= esc($activite['intensite'] ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">Aucune activite retenue.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Resume prix</h2>
    <div class="summary">
        <p>Objectif: <?= esc($objectif ?? 'N/A') ?></p>
        <p>Variation cible: <?= number_format((float)($poids_cible_abs ?? 0), 1, ',', ' ') ?> kg</p>
        <p>Duree estimee: <?= number_format((float)($estimation_jours ?? 0), 0, ',', ' ') ?> jours</p>
        <p>Prix de base: <?= number_format((float)($prix_base_estime ?? 0), 0, ',', ' ') ?> Ar</p>
        <p>Remise Gold: <?= number_format((float)($remise_gold ?? 0), 0, ',', ' ') ?> %</p>
        <p><strong>Prix final: <?= number_format((float)($prix_final_estime ?? 0), 0, ',', ' ') ?> Ar</strong></p>
    </div>
</body>
</html>
