<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Santé</title>
    <style>
        /* Configuration de base (Identique au Dashboard) */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f4f7f6;
        }

        /* Barre latérale (Sidebar) */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: fixed;
            height: 100%;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #3498db;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        /* Contenu principal */
        .main-content {
            margin-left: 250px;
            padding: 40px;
            width: 100%;
        }

        h1, h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        /* Section Informations Personnelles */
        .profile-info {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .info-item {
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .info-item strong {
            color: #7f8c8d;
            width: 100px;
            display: inline-block;
        }

        /* Formulaire de mise à jour */
        .form-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn-submit {
            padding: 10px 20px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background-color: #219150;
        }

        /* Tableau de l'historique */
        .table-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        table th {
            background-color: #f8f9fa;
            color: #7f8c8d;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        table tr:hover {
            background-color: #fcfcfc;
        }

    </style>
</head>
<body>

    <!-- Navigation latérale (Identique au Dashboard) -->
    <nav class="sidebar">
        <h2>Mon App</h2>
        <ul>
            <li><a href="/dashboard">🏠 Accueil</a></li>
            <li><a href="/profil">👤 Profil</a></li>
            <li><a href="/objectif">🎯 Objectif</a></li>
            <li><a href="/porte_monnaie">💰 Porte Monnaie</a></li>
            <li><a href="/logout">🚪 Deconnexion</a></li>
        </ul>
    </nav>

    <main class="main-content">
        <h1>Mon Profil</h1>

        <!-- 1. Informations Personnelles (Statiques) -->
        <section class="profile-info">
            <h2>Informations personnelles</h2>
            <div class="info-item"><strong>Nom :</strong> <?= $client['nom'] ?></div>
            <div class="info-item"><strong>Email :</strong> <?= $client['email'] ?></div>
            <div class="info-item"><strong>Genre :</strong> <?= $client['genre'] ?></div>
        </section>

        <!-- 2. Formulaire pré-rempli (Dernières données) -->
        <section class="form-section">
            <h2>Mettre à jour ma santé</h2>
            <form action="/api/sante/edit" method="post">
                <div class="form-group">
                    <label for="poids">Poids actuel (kg)</label>
                    <input type="number" id="poids" name="poids" step="0.1" value="<?= $poids ?>">
                </div>
                <div class="form-group">
                    <label for="taille">Taille actuelle (cm)</label>
                    <input type="number" id="taille" name="taille" value="<?= $taille ?>">
                </div>
                <button type="submit" class="btn-submit">Enregistrer la pesée</button>
            </form>
            <div id="message"></div>
        </section>

        <!-- 3. Tableau Historique Santé -->
        <section class="table-section">
            <h2>Historique de santé</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Taille (cm)</th>
                        <th>Poids (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i = 0; $i < count($sante); $i++) { ?>
                        <tr>
                            <td><?= $sante[$i]['id_sante'] ?></td>
                            <td><?= $sante[$i]['date'] ?></td>
                            <td><?= $sante[$i]['taille'] ?></td>
                            <td><?= $sante[$i]['poids'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>

</body>
</html>