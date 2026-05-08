<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<h1>Activites sportives <a class="btn" href="<?= site_url('admin/activities/create') ?>">Ajouter</a></h1>
<table>
    <tr><th>Nom</th><th>Objectif</th><th>Duree</th><th>Intensite</th><th>Actions</th></tr>
    <?php foreach ($activities as $a): ?>
        <tr>
            <td><?= esc($a['libelle']) ?></td><td><?= esc($a['objectif']) ?></td><td><?= (int) $a['duree_minutes'] ?> min</td><td><?= esc($a['intensite']) ?></td>
            <td><a class="btn" href="<?= site_url('admin/activities/edit/' . $a['id_activite']) ?>">Editer</a>
            <form method="post" action="<?= site_url('admin/activities/delete/' . $a['id_activite']) ?>" style="display:inline"><button class="danger">Supprimer</button></form></td>
        </tr>
    <?php endforeach ?>
</table>
<?= $this->endSection() ?>
