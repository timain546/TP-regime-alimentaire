<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Porte-monnaie</title>
    <link rel="stylesheet" href="<?= base_url('css/porte_monnaie.css') ?>">
</head>
<body>
    <div class="page">
        <section class="hero">
            <h1>Porte-monnaie</h1>
            <p>Rechargez votre solde en entrant un code valide.</p>
        </section>

        <section class="panel">
            <div class="balance">
                <div class="label">Solde actuel</div>
                <div class="value"><span id="solde-actuel"><?= esc((string)$solde) ?></span></div>
            </div>

            <form id="codeForm" class="form">
                <div class="field">
                    <label for="code_recharge">Code de recharge</label>
                    <input type="text" id="code_recharge" name="code_recharge" placeholder="Ex: REGIM-001" required>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Valider le code</button>
                    <a class="btn btn-secondary" href="<?= base_url('dashboard') ?>">Retour</a>
                </div>
            </form>

            <div id="message" class="message" style="display:none;"></div>
        </section>
    </div>

    <script>
        document.getElementById('codeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const code = document.getElementById('code_recharge').value.trim();
            const messageDiv = document.getElementById('message');
            const inputCode = document.getElementById('code_recharge');
            const soldeElement = document.getElementById('solde-actuel');

            function setMessage(text, kind) {
                messageDiv.style.display = 'block';
                messageDiv.textContent = text;
                messageDiv.className = 'message ' + (kind || '');
            }

            if (!code) {
                setMessage('Veuillez entrer un code.', 'error');
                return;
            }
            
            setMessage('Envoi du code...', '');
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?= base_url('porte_monnaie/code') ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            // Nécessaire pour que CodeIgniter détecte la requête comme AJAX (request->isAJAX()).
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onreadystatechange = function() {
                if (xhr.readyState !== 4) {
                    return;
                }

                let response = null;
                try {
                    response = JSON.parse(xhr.responseText);
                } catch (error) {
                    response = null;
                }

                if (xhr.status === 200 && response) {
                    if (response.success) {
                        setMessage(response.message, 'success');
                        if (typeof response.solde_actuel !== 'undefined' && soldeElement) {
                            soldeElement.textContent = response.solde_actuel;
                        }
                        inputCode.value = '';
                    } else {
                        setMessage(response.message || 'Le code est invalide.', 'warning');
                    }
                    return;
                }

                if (xhr.status === 403) {
                    setMessage('Requête refusée (403).', 'error');
                    return;
                }

                setMessage('Erreur serveur, veuillez réessayer.', 'error');
            };

            xhr.onerror = function() {
                setMessage('Erreur réseau, impossible d\'envoyer le code.', 'error');
            };
            
            xhr.send(JSON.stringify({ code: code }));
        });
    </script>
</body>
</html>