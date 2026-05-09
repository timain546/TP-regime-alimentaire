<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devenir Membre Gold</title>
    <style>
        /* --- CONFIGURATION DE BASE --- */
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

        /* --- SIDEBAR --- */
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

        /* --- CONTENU PRINCIPAL --- */
        .main-content {
            margin-left: 250px;
            padding: 60px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .purchase-container {
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .gold-header {
            background: linear-gradient(135deg, #f1c40f 0%, #f39c12 100%);
            padding: 40px;
            text-align: center;
            color: white;
        }

        .gold-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .purchase-body {
            padding: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .benefits h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .benefit-list {
            list-style: none;
        }

        .benefit-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            color: #555;
        }

        .benefit-list li::before {
            content: "✔";
            background: #f1c40f;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 15px;
            font-weight: bold;
        }

        /* --- ZONE DE PAIEMENT --- */
        .payment-box {
            background: #f9fbfb;
            padding: 30px;
            border-radius: 15px;
            border: 2px dashed #d4af37;
            text-align: center;
        }

        .price-tag {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .wallet-status {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            border: 1px solid #eee;
        }

        .btn-buy-gold {
            display: block;
            background: linear-gradient(90deg, #f1c40f, #d4af37);
            color: white;
            text-decoration: none;
            padding: 15px;
            border-radius: 10px;
            font-weight: bold;
            transition: transform 0.2s;
        }

        .btn-buy-gold:hover {
            transform: translateY(-2px);
        }

        /* --- DESIGN MEMBRE DÉJÀ GOLD --- */
        .gold-status-card {
            background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
            border: 2px solid #d4af37;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            color: white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .badge-gold {
            display: inline-block;
            background: #f1c40f;
            color: #2c3e50;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin: 10px 0;
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

    <nav class="sidebar">
        <h2>Mon App</h2>
        <ul>
            <li><a href="<?= base_url('dashboard') ?>">🏠 Accueil</a></li>
            <li><a href="<?= base_url('profil') ?>">
                👤 Profil
                <?php if(session()->get('client')['is_gold'] == 1): ?>
                    <span class="badge-gold-mini">GOLD</span>
                <?php endif; ?>
            </a></li>
            <li><a href="<?= base_url('suggestion') ?>">🎯 Objectif</a></li>
            <li><a href="<?= base_url('porte_monnaie') ?>">💰 Porte Monnaie</a></li>
            <li><a href="<?= base_url('gold') ?>">👑 Achat gold</a></li>
            <li><a href="<?= base_url('logout') ?>">🚪 Deconnexion</a></li>
        </ul>
    </nav>

    <main class="main-content">
        <div class="purchase-container">
            <div class="gold-header">
                <h1>REJOIGNEZ L'ÉLITE</h1>
                <p>Débloquez tout le potentiel de votre suivi santé</p>
            </div>

            <div class="purchase-body">
                <div class="benefits">
                    <h2>Pourquoi devenir Gold ?</h2>
                    <ul class="benefit-list">
                        <li>Badge de profil exclusif 👑</li>
                        <li>Remise de 15% sur les régimes alimentaires</li>
                        <li>Accès prioritaire au support</li>
                    </ul>
                </div>

                <!-- TEST DU STATUT GOLD -->
                <?php if(session()->get('client')['is_gold'] == 0): ?>
                    <div class="payment-box">
                        <div class="price-tag">10 000 Ar</div>
                        <div class="price-sub">Paiement unique</div>

                        <div class="wallet-status">
                            <span style="font-size: 0.8rem; color: #7f8c8d; display: block;">Solde disponible :</span>
                            <strong style="font-size: 1.2rem; color: #2c3e50;"><?= number_format($solde_compte, 0, "", " ") ?> Ar</strong>
                        </div>

                        <!-- Lien vers ton contrôleur pour traiter l'achat -->
                        <button type="button" id="btn-confirmer-achat" class="btn-buy-gold">
                            CONFIRMER L'ACHAT
                        </button>

                        <div id="message-retour" style="margin-top: 15px; font-weight: bold;"></div>

                        <p style="margin-top: 15px; font-size: 0.8rem; color: #95a5a6;">
                            Montant déduit immédiatement du porte-monnaie.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="gold-status-card">
                        <div style="font-size: 3rem;">👑</div>
                        <h3>Vous êtes Membre Gold</h3>
                        <div class="badge-gold">PRIVILÈGE ACTIF</div>
                        <p style="font-size: 0.9rem; color: #bdc3c7;">Merci de votre soutien ! Profitez de vos avantages exclusifs.</p>
                        <br>
                        <p style="font-size: 0.9rem; color: #bdc3c7;">Il vous reste <?= number_format($solde_compte, 0, "", " ") ?> Ar de solde</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('btn-confirmer-achat').addEventListener('click', function() {
        const btn = this;
        const messageDiv = document.getElementById('message-retour');
        
        btn.disabled = true;
        btn.innerText = "Traitement en cours...";

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "<?= base_url('gold/confirmer') ?>", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    
                    if (response.success) {
                        messageDiv.style.color = "#27ae60";
                        messageDiv.innerHTML = "Félicitations ! Vous êtes Gold. Redirection...";
                        setTimeout(() => { window.location.href = "<?= base_url('dashboard') ?>"; }, 2000);
                    } else {
                        messageDiv.style.color = "#e74c3c";
                        messageDiv.innerHTML = response.message;
                        btn.disabled = false;
                        btn.innerText = "CONFIRMER L'ACHAT";
                    }
                } else {
                    alert("Erreur serveur lors de la transaction.");
                    btn.disabled = false;
                }
            }
        };

        xhr.send(JSON.stringify({ action: 'buy_gold' }));
    });
    </script>

</body>
</html>