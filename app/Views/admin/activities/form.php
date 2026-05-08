<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php $isEdit = ! empty($activity); ?>
<h1><?= esc($title) ?></h1>
<form class="panel" method="post" action="<?= $isEdit ? site_url('admin/activities/update/' . $activity['id_activite']) : site_url('admin/activities') ?>">
    <label>Nom</label><input name="nom" value="<?= old('nom', $activity['libelle'] ?? '') ?>" required>
    <label>Description</label><textarea name="description"><?= old('description', $activity['description'] ?? '') ?></textarea>
    <div class="grid">
        <div><label>Duree minutes</label><input type="number" name="duree_minutes" value="<?= old('duree_minutes', $activity['duree_minutes'] ?? '') ?>" required></div>
        <div><label>Objectif</label><select name="objectif"><?php foreach (['augmenter','reduire','imc_ideal'] as $o): ?><option value="<?= $o ?>" <?= old('objectif', $activity['objectif'] ?? '') === $o ? 'selected' : '' ?>><?= $o ?></option><?php endforeach ?></select></div>
        <div><label>Intensite</label><select name="intensite"><?php foreach (['faible','moyenne','forte'] as $i): ?><option value="<?= $i ?>" <?= old('intensite', $activity['intensite'] ?? '') === $i ? 'selected' : '' ?>><?= $i ?></option><?php endforeach ?></select></div>
        <div><label>Variation poids</label><input type="number" step="0.01" name="variation_poids" value="<?= old('variation_poids', $activity['variation_poids'] ?? 0) ?>" required></div>
        <div><label>Nombre de jours</label><input type="number" name="nb_jours" value="<?= old('nb_jours', $activity['nb_jours'] ?? 30) ?>" required></div>
    </div>
    <button>Enregistrer</button>
</form>
<?= $this->endSection() ?>
