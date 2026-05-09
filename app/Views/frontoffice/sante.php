<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations Santé</title>
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
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        p.subtitle {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #27ae60; /* Vert pour la santé/validation */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #219150;
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
        <h1>Bilan Santé</h1>
        <p class="subtitle">Complétez vos informations pour calculer votre IMC</p>
        
        <form id="MyForm">
            <div class="form-group">
                <label for="taille">Taille (cm)</label>
                <input type="number" id="taille" placeholder="Ex: 175" required>
            </div>
            
            <div class="form-group">
                <label for="poids">Poids (kg)</label>
                <input type="number" id="poids" step="0.1" placeholder="Ex: 70" required>
            </div>

            <button type="submit">Valider mon profil</button>
        </form>
        <div id="message"></div>
    </div>

    <script>
        const formulaire = document.getElementById("MyForm");
        formulaire.addEventListener("submit", function(e) {
            e.preventDefault();
            const xhr = new XMLHttpRequest();
            const donnees = {
                "taille": document.getElementById("taille").value,
                "poids": document.getElementById("poids").value
            };

            xhr.open("POST", "http://localhost:8080/api/sante/create", true);
            xhr.setRequestHeader("Content-Type", "application/json");

            xhr.onreadystatechange = function() {
                if(xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if(response.succes == true) {
                        document.getElementById("message").style.color = "#27ae60";
                        document.getElementById("message").innerText = "Données enregistrées !";
                        window.location.href = response.redirect;
                    } else {
                        document.getElementById("message").style.color = "#e74c3c";
                        document.getElementById("message").innerText = `Erreur: ${response.message}`;
                    }
                }
            }
            xhr.send(JSON.stringify(donnees));
        });
    </script>
</body>
</html>