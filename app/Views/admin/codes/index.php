<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<h1>Codes wallet</h1>
<form class="panel" method="post" action="<?= site_url('admin/codes/generate') ?>">
    <div class="grid">
        <div><label>Montant</label><input type="number" step="0.01" name="montant" required></div>
        <div><label>Quantite</label><input type="number" name="quantite" value="1" min="1" required></div>
    </div>
    <button>Generer</button>
</form>
<div class="panel">
    <h3>Liste des codes</h3>
    <table>
        <tr><th>Code</th><th>Montant</th><th>Valide</th><th>Utilise</th><th>User</th><th>Action</th></tr>
        <?php foreach ($codes as $c): ?>
            <tr>
                <td><?= esc($c['code']) ?></td><td><?= number_format((float) $c['valeur'], 0, ',', ' ') ?> Ar</td>
                <td><?= $c['is_valide'] ? '<span class="ok">oui</span>' : '<span class="bad">non</span>' ?></td>
                <td><?= $c['is_utilise'] ? 'oui' : 'non' ?></td><td><?= esc($c['user_nom'] ?? '-') ?></td>
                <td><form method="post" action="<?= site_url('admin/codes/validate/' . $c['id_code_recharge']) ?>"><button><?= $c['is_valide'] ? 'Desactiver' : 'Valider' ?></button></form></td>
            </tr>
        <?php endforeach ?>
    </table>
</div>
<div class="panel">
    <h3>Historique des codes utilises</h3>
    <table><tr><th>Date</th><th>User</th><th>Code</th><th>Montant</th></tr>
    <?php foreach ($history as $h): ?><tr><td><?= esc($h['used_at']) ?></td><td><?= esc($h['user_nom']) ?></td><td><?= esc($h['code']) ?></td><td><?= number_format((float) $h['montant'], 0, ',', ' ') ?> Ar</td></tr><?php endforeach ?>
    </table>
</div>
<?= $this->endSection() ?>
