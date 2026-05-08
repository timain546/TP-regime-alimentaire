<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Back Office') ?> - Regim</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body{margin:0;font-family:Arial,sans-serif;background:#f5f7fb;color:#1f2937}
        header{background:#111827;color:#fff;padding:14px 24px;display:flex;justify-content:space-between;align-items:center}
        nav a{color:#d1d5db;text-decoration:none;margin-right:16px}
        nav a:hover{color:#fff}
        main{max-width:1120px;margin:24px auto;padding:0 18px}
        .panel{background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:18px;margin-bottom:18px}
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px}
        table{width:100%;border-collapse:collapse;background:#fff}
        th,td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:top}
        th{background:#f9fafb}
        input,select,textarea{width:100%;box-sizing:border-box;padding:9px;border:1px solid #d1d5db;border-radius:6px}
        textarea{min-height:90px}
        label{display:block;font-weight:700;margin:10px 0 5px}
        button,.btn{display:inline-block;border:0;background:#2563eb;color:#fff;padding:9px 12px;border-radius:6px;text-decoration:none;cursor:pointer}
        .danger{background:#dc2626}.muted{color:#6b7280}.ok{color:#047857}.bad{color:#b91c1c}
        .alert{padding:10px 12px;border-radius:6px;margin-bottom:14px}.success{background:#dcfce7}.error{background:#fee2e2}
    </style>
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
