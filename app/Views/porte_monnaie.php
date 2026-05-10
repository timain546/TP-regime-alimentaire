<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Votre solde actuel : <span id="solde-actuel"><?= $solde ?></span></p>
    
    <p>Inserer un code valide pour augmenter votre solde</p>
    <form id="codeForm">
        <input type="text" id="code_recharge" name="code_recharge" placeholder="Ex: REGIM-001" required>
        <button type="submit">Valider le code</button>
    </form>

    <div id="message" style="margin-top: 15px;"></div>

    <script>
        document.getElementById('codeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const code = document.getElementById('code_recharge').value.trim();
            const messageDiv = document.getElementById('message');
            const inputCode = document.getElementById('code_recharge');
            const soldeElement = document.getElementById('solde-actuel');

            if (!code) {
                messageDiv.textContent = 'Veuillez entrer un code.';
                messageDiv.style.color = '#e74c3c';
                return;
            }
            
            messageDiv.textContent = 'Envoi du code...';
            messageDiv.style.color = '#3498db';
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/porte_monnaie/code', true);
            xhr.setRequestHeader('Content-Type', 'application/json');

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
                        messageDiv.textContent = response.message;
                        messageDiv.style.color = '#27ae60';
                        if (typeof response.solde_actuel !== 'undefined' && soldeElement) {
                            soldeElement.textContent = response.solde_actuel;
                        }
                        inputCode.value = '';
                    } else {
                        messageDiv.textContent = response.message || 'Le code est invalide.';
                        messageDiv.style.color = '#e67e22';
                    }
                    return;
                }

                if (xhr.status === 403) {
                    messageDiv.textContent = 'Requête refusée (403).';
                    messageDiv.style.color = '#e74c3c';
                    return;
                }

                messageDiv.textContent = 'Erreur serveur, veuillez réessayer.';
                messageDiv.style.color = '#e74c3c';
            };

            xhr.onerror = function() {
                messageDiv.textContent = 'Erreur réseau, impossible d\'envoyer le code.';
                messageDiv.style.color = '#e74c3c';
            };
            
            xhr.send(JSON.stringify({ code: code }));
        });
    </script>
</body>
</html>