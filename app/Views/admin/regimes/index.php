<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<h1>Regimes <a class="btn" href="<?= site_url('admin/regimes/create') ?>">Ajouter</a></h1>
<table>
    <tr><th>Nom</th><th>Objectif</th><th>Duree</th><th>Variation</th><th>Prix</th><th>Composition</th><th>Actions</th></tr>
    <?php foreach ($regimes as $r): ?>
        <tr>
            <td><?= esc($r['nom']) ?></td>
            <td><?= esc($r['objectif']) ?></td>
            <td><?= (int) $r['duree_jours'] ?> jours</td>
            <td><?= esc($r['variation_poids']) ?> kg</td>
            <td><?= number_format((float) $r['prix'], 0, ',', ' ') ?> Ar</td>
            <td>V <?= esc($r['pourcentage_viande']) ?>%, P <?= esc($r['pourcentage_poisson']) ?>%, Vol <?= esc($r['pourcentage_volaille']) ?>%</td>
            <td>
                <a class="btn" href="<?= site_url('admin/regimes/edit/' . $r['id']) ?>">Editer</a>
                <form method="post" action="<?= site_url('admin/regimes/delete/' . $r['id']) ?>" style="display:inline"><button class="danger">Supprimer</button></form>
            </td>
        </tr>
    <?php endforeach ?>
</table>
<?= $this->endSection() ?>
