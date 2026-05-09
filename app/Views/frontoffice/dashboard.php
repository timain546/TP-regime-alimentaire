<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Dashboard</title>
    <style>
        /* Configuration de base */
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

        /* Zone de contenu principal */
        .main-content {
            margin-left: 250px; /* Espace pour la sidebar */
            padding: 40px;
            width: 100%;
        }

        h1 {
            margin-bottom: 30px;
            color: #2c3e50;
        }

        /* Conteneur des cartes (IMC, Poids, Taille) */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card h3 {
            color: #7f8c8d;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2c3e50;
        }

        /* Liens d'accès rapide */
        .quick-links {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .quick-links h3 {
            margin-bottom: 20px;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .badge-gold-mini {
            background: linear-gradient(45deg, #f1c40f, #d4af37);
            color: #fff;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 8px;
            font-weight: bold;
            box-shadow: 0 0 5px rgba(241, 196, 15, 0.5);
            vertical-align: middle;
        }
    </style>
</head>
<body>

    <!-- Navigation latérale -->
    <nav class="sidebar">
        <h2>Mon App</h2>
        <ul>
            <li><a href="/dashboard">🏠 Accueil</a></li>
            <li><a href="/profil">
                👤 Profil
                <?php if(session()->get('client')['is_gold'] == 1): ?>
                    <span class="badge-gold-mini">GOLD</span>
                <?php endif; ?>
            </a></li>
            <li><a href="/suggestion">🎯 Objectif</a></li>
            <li><a href="/porte_monnaie">💰 Porte Monnaie</a></li>
            <li><a href="/gold">👑 Achat gold</a></li>
            <li><a href="/logout">🚪 Deconnexion</a></li>
        </ul>
    </nav>

    <!-- Contenu -->
    <main class="main-content">
        <h1>Tableau de bord</h1>

        <!-- Indicateurs clés -->
        <div class="stats-grid">
            <div class="card">
                <h3>Poids</h3>
                <p><?= $poids ?> kg</p>
            </div>
            <div class="card">
                <h3>Taille</h3>
                <p><?= $taille ?> cm</p>
            </div>
            <div class="card">
                <h3>IMC</h3>
                <p><?= number_format($imc_actuel, 2, ".", "") ?></p>

                <!--Interpretation de l'IMC (insuffisant, normal, surpoids, obèse) -->
                <?php if($imc_actuel < 18.0) { ?>
                    <h3>Insuffisant</h3>
                <?php } else if($imc_actuel >= 18.0 && $imc_actuel < 24.0) { ?>
                    <h3>Normal</h3>
                <?php } else if($imc_actuel >= 24.0 && $imc_actuel < 32.0) { ?>
                    <h3>Surpoids</h3>
                <?php } else { ?>
                    <h3>Obese</h3>
                <?php } ?>

            </div>
        </div>

        <!-- Accès directs -->
        <section class="quick-links">
            <h3>Actions rapides</h3>
            <div class="btn-group">
                <a href="/profil" class="btn">Mettre à jour mon Profil</a>
                <a href="/suggestion" class="btn">Définir un Objectif</a>
                <a href="/porte_monnaie" class="btn">Consulter mon Solde</a>
            </div>
        </section>
    </main>

</body>
</html>