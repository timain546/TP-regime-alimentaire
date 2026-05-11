<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Back Office') ?> - Regim</title>
    <link rel="stylesheet" href="<?= base_url('css/admin.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header>
    <strong>Regim Back Office</strong>
    <nav>
        <a href="<?= site_url('admin/dashboard') ?>">Dashboard</a>
        <a href="<?= site_url('admin/regimes') ?>">Regimes</a>
        <a href="<?= site_url('admin/activities') ?>">Activites</a>
        <a href="<?= site_url('admin/codes') ?>">Codes</a>
        <a href="<?= site_url('admin/settings') ?>">Parametres</a>
        <a href="<?= site_url('admin/logout') ?>">Deconnexion</a>
    </nav>
</header>
<main>
    <?php if (session('success')): ?><div class="alert success"><?= esc(session('success')) ?></div><?php endif ?>
    <?php if (session('error')): ?><div class="alert error"><?= esc(session('error')) ?></div><?php endif ?>
    <?= $this->renderSection('content') ?>
</main>
</body>
</html>
