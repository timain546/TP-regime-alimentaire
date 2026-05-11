<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #2c3e50, #3498db);
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #3498db;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .footer-link {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .footer-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        #message {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Connexion</h1>
        <form id="MyForm">
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" placeholder="votre@email.com" value="miora@test.mg" required>
            </div>
            
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <div style="position:relative;display:flex;align-items:center;">
                    <input type="password" id="mdp" placeholder="••••••••" value="client123" required style="flex:1;padding-right:44px;">
                    <button type="button" id="toggle-mdp" aria-label="Afficher/Masquer le mot de passe" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);width:32px;height:32px;border-radius:50%;background:#ecf0f1;border:1px solid #bbb;display:flex;align-items:center;justify-content:center;color:#2980b9;cursor:pointer;font-size:1.1rem;box-shadow:0 1px 3px rgba(0,0,0,0.07);transition:background 0.2s;">
                        <span id="toggle-mdp-icon" style="pointer-events:none;">👁️</span>
                    </button>
                </div>
            </div>

            <button type="submit">Se connecter</button>
        </form>

        <div id="message"></div>

        <p class="footer-link">
            Pas encore de compte ? <a href="/client/form">S'inscrire</a>
        </p>
        <p class="footer-link">
            <a href="/admin/login" style="color:#2980b9;font-weight:bold;">Login Admin</a>
        </p>
    </div>

    <script>
        // Bouton voir/masquer mot de passe
        const mdpInput = document.getElementById('mdp');
        const toggleMdpBtn = document.getElementById('toggle-mdp');
        const toggleMdpIcon = document.getElementById('toggle-mdp-icon');
        if (toggleMdpBtn) {
            toggleMdpBtn.addEventListener('click', function() {
                if (mdpInput.type === 'password') {
                    mdpInput.type = 'text';
                    toggleMdpIcon.textContent = '🙈';
                } else {
                    mdpInput.type = 'password';
                    toggleMdpIcon.textContent = '👁️';
                }
            });
        }
        const formulaire = document.getElementById("MyForm");
        formulaire.addEventListener("submit", function(e) {
            e.preventDefault();

            const xhr = new XMLHttpRequest();
            const donnees = {
                "email": document.getElementById("email").value,
                "mdp": document.getElementById("mdp").value
            };

            xhr.open("POST", "http://localhost:8080/api/auth/login", true);
            xhr.setRequestHeader("Content-Type", "application/json");

            document.getElementById("message").style.color = "#3498db";
            document.getElementById("message").innerText = "Connexion en cours...";

            xhr.onreadystatechange = function() {
                if(xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if(response.succes == true) {
                        document.getElementById("message").style.color = "#27ae60";
                        document.getElementById("message").innerText = "Connexion réussie !";
                        window.location.href = response.redirect;
                    } else {
                        document.getElementById("message").style.color = "#e74c3c";
                        document.getElementById("message").innerText = `Erreur: ${response.message}`;
                    }
                }
            };
            xhr.send(JSON.stringify(donnees));
        });
    </script>
</body>
</html>