<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat de la suggestion</title>
    <link rel="stylesheet" href="<?= base_url('css/suggestion_resultat.css') ?>">
</head>
<body>
    <div class="page">
        <section class="hero">
            <div class="hero-top">
                <div>
                    <h1>Suggestion personnalisée</h1>
                </div>
                <div class="badge">
                    <?= $gold_actif ? 'Membre Gold activé' : 'Version standard' ?>
                </div>
            </div>

            <div class="grid-stats">
                <div class="stat-card">
                    <span class="stat-label">Objectif</span>
                    <div class="stat-value"><?= esc(ucfirst($objectif)) ?></div>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Variation cible</span>
                    <div class="stat-value"><?= number_format($poids_cible_abs, 1, ',', ' ') ?> kg</div>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Durée estimée</span>
                    <div class="stat-value"><?= number_format($estimation_jours, 0, ',', ' ') ?> jours</div>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Prix estimé</span>
                    <div class="stat-value"><?= number_format($prix_final_estime, 0, ',', ' ') ?> Ar</div>
                </div>
            </div>
        </section>

        <section class="layout">
            <div class="panel">
                <h2>Régime suggéré</h2>
                <?php if (!empty($combinaison['regimes'])) : ?>
                    <div class="list-block">
                        <?php foreach ($combinaison['regimes'] as $regime) : ?>
                            <article class="item-card">
                                <div class="item-head">
                                    <div>
                                        <div class="item-title"><?= esc($regime['libelle'] ?? 'Régime sans nom') ?></div>
                                        <div class="item-meta"><?= esc($regime['description'] ?? 'Aucune description disponible') ?></div>
                                    </div>
                                    <span class="pill <?= ($regime['objectif'] ?? '') === 'augmenter' ? 'success' : (($regime['objectif'] ?? '') === 'reduire' ? 'warning' : '') ?>">
                                        <?= esc(ucfirst($regime['objectif'] ?? 'imc_ideal')) ?>
                                    </span>
                                </div>

                                <div class="details-grid">
                                    <div class="detail">
                                        <span class="label">Id régime</span>
                                        <span class="value"><?= esc((string)($regime['id_regime'] ?? 'N/A')) ?></span>
                                    </div>
                                    <div class="detail">
                                        <span class="label">Prix final</span>
                                        <span class="value">Calculé au PDF</span>
                                    </div>
                                </div>

                                <?php
                                    $idRegime = (int)($regime['id_regime'] ?? 0);
                                    $composition = ($idRegime > 0 && isset($regime_compositions[$idRegime])) ? $regime_compositions[$idRegime] : [];
                                ?>
                                <?php if (!empty($composition)) : ?>
                                    <div class="details-grid" style="margin-top: 12px;">
                                        <div class="detail" style="grid-column: 1 / -1;">
                                            <span class="label">Composition</span>
                                            <span class="value">
                                                <?php foreach ($composition as $index => $ligne) : ?>
                                                    <?= esc($ligne['nom_aliment'] ?? 'Aliment') ?>: <?= number_format((float)($ligne['pourcentage'] ?? 0), 0, ',', ' ') ?>%<?= $index < count($composition) - 1 ? ' · ' : '' ?>
                                                <?php endforeach; ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="empty-state">Aucun régime n'a été retenu par l'algorithme pour le moment.</div>
                <?php endif; ?>
            </div>

            <div class="panel">
                <h2>Activité sportive recommandée</h2>
                <?php if (!empty($combinaison['activites'])) : ?>
                    <div class="list-block">
                        <?php foreach ($combinaison['activites'] as $activite) : ?>
                            <article class="item-card">
                                <div class="item-head">
                                    <div>
                                        <div class="item-title"><?= esc($activite['libelle'] ?? 'Activité sans nom') ?></div>
                                        <div class="item-meta"><?= esc($activite['description'] ?? 'Aucune description disponible') ?></div>
                                    </div>
                                    <span class="pill">
                                        <?= esc(ucfirst($activite['intensite'] ?? 'moyenne')) ?>
                                    </span>
                                </div>

                                <div class="details-grid">
                                    <div class="detail">
                                        <span class="label">Durée</span>
                                        <span class="value"><?= esc((string)($activite['duree_minutes'] ?? '30')) ?> min</span>
                                    </div>
                                    <div class="detail">
                                        <span class="label">Objectif</span>
                                        <span class="value"><?= esc(ucfirst($activite['objectif'] ?? 'imc_ideal')) ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="empty-state">Aucune activité n'a été associée à cette suggestion.</div>
                <?php endif; ?>

                <div class="summary" style="margin-top: 18px;">
                    <div class="summary-item">
                        <strong>Logique de suggestion</strong>
                        <span>
                            Régime choisi selon l'objectif <?= esc($objectif) ?> et la variation de poids visée.
                            L'activité est associée pour soutenir la progression et la cohérence du programme.
                        </span>
                    </div>
                    <div class="summary-item">
                        <strong>Prix final</strong>
                        <span>
                            Base estimée à <?= number_format($prix_base_estime, 0, ',', ' ') ?> Ar.
                            <?php if ($gold_actif) : ?>
                                La remise Gold de <?= (int) $remise_gold ?>% est appliquée automatiquement.
                            <?php else : ?>
                                Connectez-vous en Gold pour bénéficier de la remise automatique de 15%.
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="summary-item">
                        <strong>Export PDF</strong>
                        <span>
                            Le bouton ci-dessous est prêt pour générer un document contenant le profil, le régime, l'activité et le prix.
                        </span>
                    </div>
                </div>

                <div class="chips">
                    <span class="chip <?= $gold_actif ? 'gold' : '' ?>">IMC ciblé</span>
                    <span class="chip">Profil</span>
                    <span class="chip">Régime</span>
                    <span class="chip">Activité</span>
                    <span class="chip">Prix</span>
                </div>

                <div class="actions">
                    <a class="btn btn-primary" href="<?= base_url('suggestion/pdf') ?>">Télécharger en PDF</a>
                    <a class="btn btn-secondary" href="<?= base_url('suggestion') ?>">Modifier ma demande</a>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
