<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Votre solde actuel : <?= $solde?></p>
    
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
            
            messageDiv.textContent = 'Envoi du code...';
            messageDiv.style.color = '#3498db';
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/porte_monnaie/code', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            
            xhr.send(JSON.stringify({ code: code }));
        });
    </script>
</body>
</html>