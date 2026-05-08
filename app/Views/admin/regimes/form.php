<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php $isEdit = ! empty($regime); ?>
<h1><?= esc($title) ?></h1>
<form class="panel" method="post" action="<?= $isEdit ? site_url('admin/regimes/update/' . $regime['id']) : site_url('admin/regimes') ?>">
    <label>Nom</label><input name="nom" value="<?= old('nom', $regime['nom'] ?? '') ?>" required>
    <label>Description</label><textarea name="description"><?= old('description', $regime['description'] ?? '') ?></textarea>
    <div class="grid">
        <div><label>Objectif</label><select name="objectif">
            <?php foreach (['augmenter','reduire','imc_ideal'] as $o): ?><option value="<?= $o ?>" <?= old('objectif', $regime['objectif'] ?? '') === $o ? 'selected' : '' ?>><?= $o ?></option><?php endforeach ?>
        </select></div>
        <div><label>Variation poids</label><input type="number" step="0.01" name="variation_poids" value="<?= old('variation_poids', $regime['variation_poids'] ?? '') ?>" required></div>
        <div><label>Duree jours</label><input type="number" name="duree_jours" value="<?= old('duree_jours', $regime['duree_jours'] ?? '') ?>" required></div>
        <div><label>Prix</label><input type="number" step="0.01" name="prix" value="<?= old('prix', $regime['prix'] ?? '') ?>" required></div>
    </div>
    <div class="grid">
        <div><label>% viande</label><input type="number" step="0.01" name="pourcentage_viande" value="<?= old('pourcentage_viande', $regime['pourcentage_viande'] ?? '') ?>" required></div>
        <div><label>% poisson</label><input type="number" step="0.01" name="pourcentage_poisson" value="<?= old('pourcentage_poisson', $regime['pourcentage_poisson'] ?? '') ?>" required></div>
        <div><label>% volaille</label><input type="number" step="0.01" name="pourcentage_volaille" value="<?= old('pourcentage_volaille', $regime['pourcentage_volaille'] ?? '') ?>" required></div>
    </div>
    <button>Enregistrer</button>
</form>
<?= $this->endSection() ?>
