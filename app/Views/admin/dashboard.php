<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<h1>Dashboard</h1>
<div class="grid">
    <div class="panel"><strong>Total wallet</strong><h2><?= number_format((float) $wallet['total_wallet'], 0, ',', ' ') ?> Ar</h2></div>
    <div class="panel"><strong>Codes valides utilises</strong><h2><?= (int) $wallet['codes_valides'] ?></h2></div>
    <div class="panel"><strong>Total recharges</strong><h2><?= number_format((float) $wallet['total_recharges'], 0, ',', ' ') ?> Ar</h2></div>
</div>
<div class="grid">
    <div class="panel"><h3>Inscriptions par semaine</h3><canvas id="weekly"></canvas></div>
    <div class="panel"><h3>Objectifs</h3><canvas id="objectives"></canvas></div>
</div>
<div class="panel">
    <h3>Regimes les plus souscrits</h3>
    <table>
        <tr><th>Regime</th><th>Souscriptions</th><th>Total vendu</th></tr>
        <?php foreach ($topRegimes as $row): ?>
            <tr><td><?= esc($row['nom']) ?></td><td><?= (int) $row['souscriptions'] ?></td><td><?= number_format((float) $row['total'], 0, ',', ' ') ?> Ar</td></tr>
        <?php endforeach ?>
    </table>
</div>
<script>
const weekly = <?= json_encode($weeklyUsers) ?>;
const objectives = <?= json_encode($objectives) ?>;
new Chart(document.getElementById('weekly'), {
    type: 'bar',
    data: {labels: weekly.map(i => i.semaine), datasets: [{label: 'Inscriptions', data: weekly.map(i => i.total), backgroundColor: '#2563eb'}]}
});
new Chart(document.getElementById('objectives'), {
    type: 'pie',
    data: {labels: objectives.map(i => i.objectif), datasets: [{data: objectives.map(i => i.total), backgroundColor: ['#16a34a', '#dc2626', '#f59e0b', '#6b7280']}]}
});
</script>
<?= $this->endSection() ?>
