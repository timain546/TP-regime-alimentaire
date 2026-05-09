<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
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

        #message {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Inscription</h1>
        <form id="MyForm">
            <div class="form-group">
                <label for="nom">Nom complet</label>
                <input type="text" id="nom" placeholder="Ex: Jean Dupont" required>
            </div>
            
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" placeholder="nom@exemple.com" required>
            </div>

            <div class="form-group">
                <label for="genre">Genre</label>
                <select id="genre">
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                </select>
            </div>

            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" placeholder="••••••••" required>
            </div>

            <button type="submit">Créer mon compte</button>
        </form>
        <div id="message"></div>
    </div>

    <script>
        const formulaire = document.getElementById("MyForm");
        formulaire.addEventListener("submit", function(e) {
            e.preventDefault();
            const xhr = new XMLHttpRequest();
            const donnees = {
                "nom": document.getElementById("nom").value,
                "email": document.getElementById("email").value,
                "genre": document.getElementById("genre").value,
                "mdp": document.getElementById("mdp").value
            };

            xhr.open("POST", "http://localhost:8080/api/client/create", true);
            xhr.setRequestHeader("Content-Type", "application/json");

            xhr.onreadystatechange = function() {
                if(xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if(response.succes == true) {
                        document.getElementById("message").style.color = "#27ae60";
                        document.getElementById("message").innerText = "Inscription réussie ! Redirection...";
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 1500);
                    } else {
                        document.getElementById("message").style.color = "#e74c3c";
                        document.getElementById("message").innerText = `Erreur: ${response.errors}`;
                    }
                }
            }
            xhr.send(JSON.stringify(donnees));
        });
    </script>
</body>
</html>