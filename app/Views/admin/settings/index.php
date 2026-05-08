<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<h1>Parametres globaux</h1>
<form class="panel" method="post" action="<?= site_url('admin/settings/update') ?>">
    <?php foreach ($settings as $s): ?>
        <label><?= esc($s['cle']) ?> <span class="muted"><?= esc($s['description']) ?></span></label>
        <input name="settings[<?= esc($s['cle']) ?>]" value="<?= esc($s['valeur']) ?>">
    <?php endforeach ?>
    <button>Enregistrer</button>
</form>
<?= $this->endSection() ?>
